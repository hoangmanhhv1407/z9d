<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\Tbl_Cash_Inven;
use App\Models\TransactionHistory;
use App\User;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\CartManager;
use App\Models\TimeSetting;

class ProductController extends Controller
{
    protected $product;
    protected $categoryProduct;
    protected $cart;

    public function __construct(Product $product, CategoryProduct $categoryProduct, CartManager $cartManager)
    {
        $this->product = $product;
        $this->categoryProduct = $categoryProduct;
        $this->cart = $cartManager;
    }

    public function search(Request $request)
    {
        $showproduct = Product::where('prd_name', 'like', '%' . $request->name . '%')
            ->with([
                'categoryProduct' => function ($query) {
                    $query->where('cpr_active', 1);
                },
            ])
            ->orderBy('id', 'desc')
            ->paginate(10);
        $dataView = ['showproduct' => $showproduct, 'query' => $request->query()];
        return view('frontend.search', $dataView);
    }

    public function productDetail($id)
    {
        $productDetail = $this->product->where('id', $id)->first();
        $productHot = $this->product
            ->where('prd_status', 1)
            ->where('prd_hot', 1)
            ->get();
        $productList = $this->product
            ->where('prd_status', 1)
            ->where('prd_category_product_id', $productDetail->prd_category_product_id)
            ->limit(6)
            ->get();
        return view('frontend.product_detail', compact('productDetail', 'productHot', 'productList'));
    }

    public function productCate()
    {
        $auth = Auth::guard('web')->user();
        $postList = CategoryProduct::with([
            'product' => function ($query) {
                $query->where('prd_status', 1);
            },
        ])->get();
        $accumulateList = Product::where('accumulate_status', 1)->get();
        $cart = $this->cart->getContent();
        if ($auth) {
            $user = User::where('id', $auth->id)->first();
            return view('frontend.productList', compact('user', 'postList', 'cart', 'accumulateList'));
        }
        return view('frontend.productList', compact('postList', 'cart', 'accumulateList'));
    }

    public function addCart(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        $product = $this->product->where('id', $id)->first();
        $date = Carbon::now();
        $start = $date->copy()->startOfWeek();
        $end = $date->copy()->endOfWeek();
        $shoppingDay = 0;
        if ($user) {
            $shoppingDay = TransactionHistory::where('user_id', $user->id)
                ->where('product_id', $id)
                ->where('created_at', '>', $start)
                ->where('created_at', '<', $end)
                ->sum('qty');
        }
        if ($product->turn) {
            if ((int) $product->turn <= (int) $shoppingDay) {
                return redirect()
                    ->back()
                    ->with(['alert' => 'warning', 'message' => 'Sản phẩm đã hết lượt mua trong tuần']);
            }
            $cart = $this->cart->getContent();
            $qtyCart = 0;
            foreach ($cart as $item) {
                if ((int) $item->id === (int) $id) {
                    $qtyCart = $item->quantity;
                }
            }
            if ($product->turn - (int) $shoppingDay - (int) $qtyCart == 0) {
                return redirect()
                    ->back()
                    ->with(['alert' => 'warning', 'message' => 'Sản phẩm đã hết lượt mua trong tuần']);
            }
        }
        $this->cart->add([
            'id' => $id,
            'name' => $product->prd_name,
            'price' => $product->coin,
            'quantity' => 1,
            'attributes' => [
                'image' => $product->prd_thunbar,
                'prd_code' => $product->prd_code
            ]
        ]);
        return redirect()
            ->back()
            ->with(['alert' => 'success', 'message' => 'Sản phẩm đã được thêm vào giỏ hàng']);
    }

    public function buyAccumulator(Request $request)
    {
        $user = Auth::guard('web')->user();
        $product_id = (int)$request->product_id;
        $product = $this->product->where('id', $product_id)->first();
        if ($product->accumulate_status == 0) {
            return response(['error' => 'Sản phẩm không được bày bán trên shop tích luỹ'], 403);
        }

        $price = $product->accu;
        if ($user->accumulate < $price) {
            return response(['error' => 'Số điểm trong tài khoản không đủ để mua sản phẩm này'], 403);
        }
        $user->accumulate = $user->accumulate - $price;
        $date = Carbon::now();
        $start = $date->copy()->startOfWeek();
        $end = $date->copy()->endOfWeek();
        Tbl_Cash_Inven::insert(['order_idx' => Tbl_Cash_Inven::max('order_idx') + 1, 'item_code' => (int)$product->prd_code, 'item_user_id' => $user->userid, 'item_server_code' => 0 , 'item_present' => 0, 'order_input_date' => Carbon::now()]);
        TransactionHistory::insert(['product_id' => $product->id, 'user_id' => $user->id, 'coin' => 0, 'qty' => 1, 'remaining_amount' => $user->coin, 'type' => 2]);
        $user->accumulate += $price / 10;
        $user->tongtieucoin += $price;
        $user->save();
        return response(['price' => $price]);
    }

    public function delete($id)
    {
        $this->cart->remove($id);
        return redirect()
            ->back()
            ->with(['alert' => 'success', 'message' => 'Sản phẩm đã được xóa thành công']);
    }

    public function increment($productId)
    {
        $product = $this->product->where('id', $productId)->first();
        $date = Carbon::now();
        $start = $date->copy()->startOfWeek();
        $end = $date->copy()->endOfWeek();
        $shoppingDay = TransactionHistory::where('user_id', Auth::guard('web')->user()->id)
            ->where('product_id', $productId)
            ->where('created_at', '>', $start)
            ->where('created_at', '<', $end)
            ->sum('qty');
        if ($product->turn) {
            if ($product->turn <= (int) $shoppingDay) {
                return redirect()
                    ->back()
                    ->with(['alert' => 'warning', 'message' => 'Sản phẩm đã hết lượt mua trong tuần']);
            }
            $cart = $this->cart->getContent();
            $qtyCart = 0;
            foreach ($cart as $item) {
                if ((int) $item->id === (int) $productId) {
                    $qtyCart = $item->quantity;
                }
            }
            if ($product->turn - (int) $shoppingDay - (int) $qtyCart == 0) {
                return redirect()
                    ->back()
                    ->with(['alert' => 'warning', 'message' => 'Sản phẩm đã hết lượt mua trong tuần']);
            }
        }
        $this->cart->update($productId, [
            'quantity' => 1
        ]);
        return redirect()
            ->back()
            ->with(['alert' => 'success', 'message' => 'Cập nhật thành công']);
    }

    public function decrease($productId)
    {
        $item = $this->cart->get($productId);
        if ($item && $item->quantity > 1) {
            $this->cart->update($productId, [
                'quantity' => -1
            ]);
        }
        return redirect()
            ->back()
            ->with(['alert' => 'success', 'message' => 'Cập nhật thành công']);
    }

    public function order()
    {
        $cart = $this->cart->getContent();
        return view('frontend.order', compact('cart'));
    }

    public function payment()
    {
        $cart = $this->cart->getContent();
        $total = $this->cart->getTotal();
        if ($total === 0) {
            return redirect()
                ->back()
                ->with(['alert' => 'warning', 'message' => 'Bạn chưa chọn vật phẩm']);
        }
        $user = User::where('id', Auth::guard('web')->user()->id)->first();
        if ((int) $total > (int) $user->coin) {
            return redirect()
                ->back()
                ->with(['alert' => 'warning', 'message' => 'Bạn không đủ coin']);
        }
        $user->accumulate += $total / 10;
        $user->tongtieucoin += $total;
        $user->save();
        if ((int) $user->coin >= (int) $total) {
            $coinChuaTru = (int) $user->coin;
            $checkUpdate = User::where('id', Auth::guard('web')->user()->id)->update(['coin' => (int) $user->coin - (int) $total]);
            $coinDaTru = User::where('id', Auth::guard('web')->user()->id)->first()->coin;
            if ($checkUpdate === 1 && (int) $coinChuaTru - (int) $coinDaTru == (int) $total) {
                $arr = [];
                foreach ($cart as $item) {
                    $soTienHienTai = User::where('id', Auth::guard('web')->user()->id)->value('coin');
                    array_push($arr, ['product_id' => $item->id, 'user_id' => Auth::guard('web')->user()->id, 'coin' => (int) ($item->quantity * $item->price), 'qty' => $item->quantity, 'remaining_amount' => $soTienHienTai, 'type' => 2]);
                    if ($item->quantity > 1) {
                        for ($x = 1; $x <= $item->quantity; $x++) {
                            Tbl_Cash_Inven::insert(['order_idx' => Tbl_Cash_Inven::max('order_idx') + 1, 'item_code' => $item->attributes['prd_code'], 'item_user_id' => Auth::guard('web')->user()->userid, 'item_server_code' => 0, 'item_present' => 0, 'order_input_date' => Carbon::now()]);
                        }
                    } else {
                        Tbl_Cash_Inven::insert(['order_idx' => Tbl_Cash_Inven::max('order_idx') + 1, 'item_code' => $item->attributes['prd_code'], 'item_user_id' => Auth::guard('web')->user()->userid, 'item_server_code' => 0, 'item_present' => 0, 'order_input_date' => Carbon::now()]);
                    }
                }
                TransactionHistory::insert($arr);
                $this->cart->clear();
                return redirect()
                    ->route('frontend.productCate')
                    ->with(['alert' => 'success', 'message' => 'Thanh toán thành công']);
            } else {
                return redirect()
                    ->back()
                    ->with(['alert' => 'warning', 'message' => 'Giao dịch không thành công']);
            }
        } else {
            return redirect()
                ->back()
                ->with(['alert' => 'warning', 'message' => 'Chúc mừng bạn đã Hack thành công :)']);
        }
    }


    public function quickBuy()
    {
        $user = Auth::guard('web')->user();
        $categories = CategoryProduct::with(['products' => function ($query) {
            $query->where('prd_status', 1);
        }])->get();

        foreach ($categories as $category) {
            foreach ($category->products as $product) {
                $product->remaining_turns = $this->getRemainingTurns($product, $user);
            }
        }

        return view('frontend.quick_buy', compact('categories', 'user'));
    }

    public function buyNow(Request $request)
    {
        $user = Auth::guard('web')->user();
        $product = Product::findOrFail($request->product_id);

        if ($product->turn && $this->getRemainingTurns($product, $user) <= 0) {
            return response()->json(['error' => 'Hết lượt mua trong tuần'], 400);
        }

        if ($user->coin < $product->coin) {
            return response()->json(['error' => 'Không đủ coin'], 400);
        }

        $user->coin -= $product->coin;
        $user->accumulate += $product->coin / 10;
        $user->tongtieucoin += $product->coin;

            // Kiểm tra nếu sự kiện tiêu xu đang diễn ra
    $timeSetting = TimeSetting::find(3); // Lấy cài đặt sự kiện tiêu xu
    if ($timeSetting && $timeSetting->status == 1) {
        $now = Carbon::now();
        $start = Carbon::parse($timeSetting->day_start);
        $end = Carbon::parse($timeSetting->day_end);

        if ($now->between($start, $end)) {
            $user->spent_coin += $product->coin; // Tăng số xu đã tiêu trong thời gian sự kiện
        }
    }
        $user->save();

        Tbl_Cash_Inven::create([
            'order_idx' => Tbl_Cash_Inven::max('order_idx') + 1,
            'item_code' => $product->prd_code,
            'item_user_id' => $user->userid,
            'item_server_code' => 0,
            'item_present' => 0,
            'order_input_date' => Carbon::now()
        ]);

        TransactionHistory::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'coin' => $product->coin,
            'qty' => 1,
            'remaining_amount' => $user->coin,
            'type' => 2
        ]);

        $remainingTurns = $this->getRemainingTurns($product, $user);

        return response()->json([
            'success' => 'Mua hàng thành công',
            'remaining_coin' => $user->coin,
            'remaining_turns' => $remainingTurns
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

        $shoppingDay = TransactionHistory::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->whereBetween('created_at', [$start, $end])
            ->sum('qty');

        return max(0, $product->turn - $shoppingDay);
    }
}