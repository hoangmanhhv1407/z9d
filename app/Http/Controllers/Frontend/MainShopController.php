<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use App\Models\CategoryProduct;
use App\Models\TransactionHistory;
use App\Models\Tbl_Cash_Inven;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\ShopSecurityTrait;
use Illuminate\Support\Facades\Storage;
use App\Models\TimeSetting;

class MainShopController extends Controller
{
    use ShopSecurityTrait;
    protected $product;
    protected $categoryProduct;

    public function __construct(Product $product, CategoryProduct $categoryProduct)
    {
        $this->product = $product;
        $this->categoryProduct = $categoryProduct;
    }

    private function validatePurchaseInput($request)
    {
        return Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:255',
        ], [
            'product_id.required' => 'Vui lòng chọn vật phẩm',
            'product_id.exists' => 'Vật phẩm không tồn tại',
            'quantity.required' => 'Vui lòng nhập số lượng',
            'quantity.integer' => 'Số lượng phải là số nguyên',
            'quantity.min' => 'Số lượng tối thiểu là 1',
            'quantity.max' => 'Số lượng tối đa là 255',
        ]);
    }

    private function logSuspiciousActivity($user, $type, $data)
    {
        Log::warning('Suspicious activity detected', [
            'user_id' => $user->id,
            'username' => $user->userid,
            'type' => $type,
            'data' => $data,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    private function getRemainingTurns($product, $user)
    {
        if (!$product->turn) {
            return null;
        }

        $date = Carbon::now();
        $start = $date->copy()->startOfWeek();
        $end = $date->copy()->endOfWeek();

        $purchasedCount = TransactionHistory::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->whereBetween('created_at', [$start, $end])
            ->sum('qty');

        return max(0, $product->turn - $purchasedCount);
    }

    public function getShopItems(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $categories = $this->categoryProduct
                ->where('cpr_active', 1)
                ->with(['product' => function ($query) {
                    $query->where('prd_status', 1)
                          ->orderBy('created_at', 'desc'); // Sắp xếp sản phẩm theo thời gian tạo mới nhất
                }])
                ->get()
                ->map(function($category) use ($user) {
                    return [
                        'id' => $category->id,
                        'name' => $category->cpr_name,
                        'products' => $category->product->map(function($item) use ($user) {
                            return [
                                'id' => $item->id,
                                'name' => $item->prd_name,
                                'price' => $item->coin,
                                'description' => $item->prd_description,
                                'image' => Storage::url('products/' . $item->prd_thunbar),
                                'code' => $item->prd_code,
                                'limit' => $item->turn,
                                'remaining_turns' => $this->getRemainingTurns($item, $user),
                                'hot' => $item->prd_hot,
                                'created_at' => $item->created_at // Thêm trường created_at để client có thể hiển thị thời gian
                            ];
                        })
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getShopItems: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách vật phẩm'
            ], 500);
        }
    }

    public function buyItem(Request $request)
    {
        try {
            // 1. Validate đầu vào cơ bản
            $validator = $this->validatePurchaseInput($request);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // 2. Lấy thông tin user và kiểm tra đăng nhập
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $user = User::lockForUpdate()->find($user->id);

            // 3. Lấy và kiểm tra thông tin sản phẩm
            $productId = $request->product_id;
            $quantity = intval($request->quantity);
            
            $product = Product::lockForUpdate()->find($productId);
            if (!$product || $product->prd_status !== 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vật phẩm không tồn tại hoặc không khả dụng'
                ], 404);
            }

            // 4. Kiểm tra bảo mật cơ bản
            $securityViolations = $this->validateProductSecurity($product, $user, $quantity);
            if (!empty($securityViolations)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giao dịch không hợp lệ',
                ], 403);
            }

            // 5. Tính toán và kiểm tra chi phí
            $totalCost = $product->coin * $quantity;
            if ($user->coin < $totalCost) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không đủ xu để mua vật phẩm'
                ], 400);
            }

            DB::beginTransaction();
            try {
                // 6. Cập nhật số dư người dùng
                $user->coin -= $totalCost;
                $user->accumulate += $totalCost / 10;
                $user->tongtieucoin += $totalCost;
                
                // Kiểm tra nếu sự kiện tiêu xu đang diễn ra
                $timeSetting = TimeSetting::find(3); // Lấy cài đặt sự kiện tiêu xu
                if ($timeSetting && $timeSetting->status == 1) {
                    $now = Carbon::now();
                    $start = Carbon::parse($timeSetting->day_start);
                    $end = Carbon::parse($timeSetting->day_end);

                    if ($now->between($start, $end)) {
                        $user->spent_coin += $totalCost;
                    }
                }
                $user->save();

                // 7. Thêm vào inventory với khóa chống race condition
                $maxOrderIdx = Tbl_Cash_Inven::max('order_idx') ?? 0;
                $inventoryData = [];
                
                for ($i = 0; $i < $quantity; $i++) {
                    $inventoryData[] = [
                        'order_idx' => $maxOrderIdx + $i + 1,
                        'item_code' => $product->prd_code,
                        'item_user_id' => $user->userid,
                        'item_server_code' => 0,
                        'item_present' => 0,
                        'order_input_date' => Carbon::now()
                    ];
                }

                Tbl_Cash_Inven::insert($inventoryData);

                // 8. Lưu lịch sử giao dịch với mã hóa thông tin
                $transaction = TransactionHistory::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'coin' => $totalCost,
                    'qty' => $quantity,
                    'remaining_amount' => $user->coin,
                    'type' => 2,
                    'transaction_hash' => hash('sha256', "{$user->id}_{$product->id}_{$quantity}_{$totalCost}_{$user->coin}_{time()}"),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Mua vật phẩm thành công',
                    'data' => [
                        'remaining_coin' => $user->coin,
                        'accumulate' => $user->accumulate,
                        'tongtieucoin' => $user->tongtieucoin,
                        'transaction_id' => $transaction->id,
                        'quantity' => $quantity,
                        'remaining_turns' => $this->getRemainingTurns($product, $user),
                        'verification_hash' => hash('sha256', "{$transaction->id}_{$user->coin}_{time()}")
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Transaction error in buyItem:', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra trong quá trình xử lý giao dịch'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error in buyItem:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi mua vật phẩm'
            ], 500);
        }
    }

    public function getItemsByCategory($categoryId)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $items = $this->product
                ->where('prd_category_product_id', $categoryId)
                ->where('prd_status', 1)
                ->orderBy('created_at', 'desc') // Sắp xếp theo thời gian tạo mới nhất
                ->get()
                ->map(function($item) use ($user) {
                    return [
                        'id' => $item->id,
                        'name' => $item->prd_name,
                        'price' => $item->coin,
                        'description' => $item->prd_description,
                        'image' => $item->prd_thunbar,
                        'code' => $item->prd_code,
                        'limit' => $item->turn,
                        'remaining_turns' => $this->getRemainingTurns($item, $user),
                        'hot' => $item->prd_hot,
                        'created_at' => $item->created_at 
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $items
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getItemsByCategory: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách vật phẩm'
            ], 500);
        }
    }

    public function buyWithAccumulate(Request $request)
    {
        try {
            // 1. Validate request
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|integer|exists:products,id'
            ], [
                'product_id.required' => 'Vui lòng chọn vật phẩm',
                'product_id.exists' => 'Vật phẩm không tồn tại'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // 2. Lock user record
            $user = User::lockForUpdate()->find($user->id);

            $productId = $request->product_id;
            $product = $this->product->where('id', $productId)->first();

            // 3. Kiểm tra vật phẩm có tồn tại và cho phép mua bằng điểm tích lũy
            if (!$product || $product->accumulate_status == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không được bày bán trên shop tích luỹ'
                ], 403);
            }

            // 4. Kiểm tra điểm tích lũy đủ không
            $price = $product->accu;
            if ($user->accumulate < $price) {
                $this->logSuspiciousActivity($user, 'insufficient_accumulate', [
                    'required' => $price,
                    'current' => $user->accumulate
                ]);
                
                return response()->json([
                    'success' => false, 
                    'message' => 'Số điểm trong tài khoản không đủ để mua sản phẩm này'
                ], 403);
            }

            DB::beginTransaction();
            try {
                // 5. Trừ điểm tích lũy
                $user->accumulate = $user->accumulate - $price;
                
                // 6. Thêm vào inventory
                $maxOrderIdx = DB::connection('cuuamsql')
                    ->table('Tbl_Cash_Inven')
                    ->max('order_idx') ?? 0;

                DB::connection('cuuamsql')
                    ->table('Tbl_Cash_Inven')
                    ->insert([
                        'order_idx' => $maxOrderIdx + 1,
                        'item_code' => $product->prd_code,
                        'item_user_id' => $user->userid,
                        'item_server_code' => 0,
                        'item_present' => 0,
                        'order_input_date' => Carbon::now()
                    ]);

                // 7. Lưu lịch sử giao dịch với type=4 cho giao dịch bằng điểm tích lũy
                $transaction = TransactionHistory::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'coin' => $price, // Lưu số điểm tích lũy đã dùng
                    'qty' => 1,
                    'remaining_amount' => $user->accumulate,
                    'type' => 4, // Type 4 cho giao dịch bằng điểm tích lũy
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                // 8. Cập nhật tổng tiêu coin
                $user->tongtieucoin += $price;
                $user->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Mua vật phẩm thành công',
                    'data' => [
                        'remaining_coin' => $user->coin,
                        'remaining_accumulate' => $user->accumulate,
                        'tongtieucoin' => $user->tongtieucoin,
                        'transaction_id' => $transaction->id
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error in buyWithAccumulate: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra trong quá trình mua vật phẩm'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error in buyWithAccumulate: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý yêu cầu'
            ], 500);
        }
    }

    public function getAccumulateItems()
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $categories = $this->categoryProduct
                ->where('cpr_active', 1)
                ->with(['product' => function ($query) {
                    $query->where('prd_status', 1)
                          ->where('accumulate_status', 1)
                          ->orderBy('created_at', 'desc'); // Sắp xếp theo thời gian tạo mới nhất
                }])
                ->get()
                ->map(function($category) use ($user) {
                    return [
                        'id' => $category->id,
                        'name' => $category->cpr_name,
                        'products' => $category->product->map(function($item) use ($user) {
                            return [
                                'id' => $item->id,
                                'name' => $item->prd_name,
                                'accumulate_price' => $item->accu,
                                'description' => $item->prd_description,
                                'image' => Storage::url('products/' . $item->prd_thunbar),
                                'code' => $item->prd_code,
                                'created_at' => $item->created_at
                            ];
                        })->filter()
                    ];
                })
                ->filter(function($category) {
                    return $category['products']->isNotEmpty();
                });

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getAccumulateItems: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách vật phẩm'
            ], 500);
        }
    }
}