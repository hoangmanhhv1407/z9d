<?php namespace App\Http\Controllers\Admin;
use App\Http\Requests\ProductRequest;
use App\Models\CategoryProduct;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
class ProductController extends Controller
{
public function index(Request $request)
{
    $showproduct = Product::where('prd_name', 'like', '%' . $request->name . '%')
        ->where('prd_category_product_id', 'like', '%' . $request->category_product . '%')
        ->with([
            'categoryProduct' => function ($query) {
                $query->where('cpr_active', 1);
            },
        ])
        ->orderBy('id', 'desc')
        ->take(1000)  // Giới hạn số lượng sản phẩm tối đa là 1000
        ->get();      // Sử dụng get() thay vì paginate()
    
    $category = CategoryProduct::where('cpr_active', 1)->get();
    $dataView = ['showproduct' => $showproduct, 'category' => $category, 'query' => $request->query()];
    return view('admin.product.index', $dataView);
}

    public function add()
    {
        $category = CategoryProduct::where('cpr_active', 1)->get();
        return view('admin.product.add', compact('category'));
    }

    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('prd_thunbar')) {
                $file = $request->file('prd_thunbar');
                $filename = time() . '_' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('products', $file, $filename);
                $data['prd_thunbar'] = $filename;
            }

            $data['prd_slug'] = str_slug($request->prd_name);
            $data['created_at'] = $data['updated_at'] = Carbon::now();

            Product::create($data);

            return redirect()
                ->route('admin.product.index')
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Failed to create product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $category = CategoryProduct::where('cpr_active', 1)->get();
            $product = Product::findOrFail($id);
            return view('admin.product.edit', compact('product', 'category'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.product.index')
                ->with('danger', 'Product not found!');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $data = $request->all();

            if ($request->hasFile('prd_thunbar')) {
                // Delete old image if exists
                if ($product->prd_thunbar) {
                    Storage::disk('public')->delete('products/' . $product->prd_thunbar);
                }

                // Upload new image
                $file = $request->file('prd_thunbar');
                $filename = time() . '_' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('products', $file, $filename);
                $data['prd_thunbar'] = $filename;
            }

            $data['prd_slug'] = str_slug($request->prd_name);
            $data['updated_at'] = Carbon::now();

            $product->update($data);

            return redirect()
                ->route('admin.product.index')
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Failed to update product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Delete associated image if exists
            if ($product->prd_thunbar) {
                Storage::disk('public')->delete('products/' . $product->prd_thunbar);
            }

            $product->delete();

            return redirect()
                ->back()
                ->with('success', 'Product deleted successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Failed to delete product: ' . $e->getMessage());
        }
    }
    /**
     * Cập nhật trạng thái hiển thị của sản phẩm (prd_status)
     */
    public function status($id, $status)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Chuyển đổi trạng thái
            $newStatus = $status == 1 ? 0 : 1;
            
            $product->update([
                'prd_status' => $newStatus
            ]);
            
            // Cập nhật cache nếu cần
            Cache::forget('shop_items_' . auth()->id());
            
            return redirect()
                ->back()
                ->with('success', 'Cập nhật trạng thái thành công!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Cập nhật trạng thái thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật trạng thái kích hoạt của sản phẩm (prd_active)
     */
    public function active($id, $active)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Chuyển đổi trạng thái active
            $newActive = $active == 1 ? 0 : 1;
            
            $product->update([
                'prd_active' => $newActive
            ]);
            
            // Cập nhật cache nếu cần
            Cache::forget('shop_items_' . auth()->id());
            
            return redirect()
                ->back()
                ->with('success', 'Cập nhật trạng thái kích hoạt thành công!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Cập nhật trạng thái kích hoạt thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật trạng thái nổi bật của sản phẩm (prd_hot)
     */
    public function hot($id, $hot)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Chuyển đổi trạng thái hot
            $newHotStatus = $hot == 1 ? 0 : 1;
            
            $product->update([
                'prd_hot' => $newHotStatus
            ]);
            
            // Cập nhật cache nếu cần
            Cache::forget('shop_items_' . auth()->id());
            
            return redirect()
                ->back()
                ->with('success', 'Cập nhật trạng thái nổi bật thành công!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Cập nhật trạng thái nổi bật thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật trạng thái lucky của sản phẩm
     */
    public function luckyStatus($id, $status)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Chuyển đổi trạng thái lucky 
            // Từ 1 -> 2 hoặc từ 2 -> 1
            $newStatus = $status == 1 ? 2 : 1;
            
            $product->update([
                'luckyStatus' => $newStatus
            ]);
            
            return redirect()
                ->back()
                ->with('success', 'Cập nhật trạng thái quay thưởng thành công!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Cập nhật trạng thái quay thưởng thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật trạng thái tích lũy của sản phẩm
     */
    public function accumulateStatus($id, $status)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Chuyển đổi trạng thái tích lũy
            // Từ 1 -> 2 hoặc từ 2 -> 1
            $newStatus = $status == 1 ? 2 : 1;
            
            $product->update([
                'accumulate_status' => $newStatus
            ]);
            
            // Cập nhật cache nếu cần
            Cache::forget('accumulate_items_' . auth()->id());
            
            return redirect()
                ->back()
                ->with('success', 'Cập nhật trạng thái tích lũy thành công!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Cập nhật trạng thái tích lũy thất bại: ' . $e->getMessage());
        }
    }

    public function dailyGiftStatus($id, $status)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Chuyển đổi trạng thái
            $newStatus = $status == 1 ? 0 : 1;
            
            $product->update([
                'daily_gift_status' => $newStatus
            ]);
            
            Cache::forget('daily_gifts_' . auth()->id());
            
            return redirect()
                ->back()
                ->with('success', 'Cập nhật trạng thái quà hàng ngày thành công!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Cập nhật thất bại: ' . $e->getMessage());
        }
    }

    public function dailyGiftType($id, $type)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Chuyển đổi loại quà thường/VIP
            $newType = $type == 1 ? 0 : 1;
            
            $product->update([
                'daily_gift_type' => $newType
            ]);
            
            Cache::forget('daily_gifts_' . auth()->id());
            
            return redirect()
                ->back()
                ->with('success', 'Cập nhật loại quà hàng ngày thành công!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Cập nhật thất bại: ' . $e->getMessage());
        }
    }
}
