<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\WeaponChangeConfig;
use App\Models\WeaponType;
use App\Models\WeaponImage;
use App\Models\WeaponChangeLog;

class ItemChangeController extends Controller
{
    /**
     * Hiển thị trang cấu hình thay đổi vũ khí
     */
    public function index()
    {
        // Lấy cấu hình chung
        $generalConfig = WeaponChangeConfig::first();
        
        // Lấy danh sách loại vũ khí
        $weaponTypes = WeaponType::orderBy('name', 'asc')->get();
        
        // Lấy danh sách hình ảnh vũ khí
        $weaponImages = WeaponImage::orderBy('weapon_id', 'asc')->get();
        
        return view('admin.weapon.index', compact('generalConfig', 'weaponTypes', 'weaponImages'));
    }
    
    /**
     * Cập nhật cấu hình chung
     */
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'change_fee' => 'required|integer|min:0',
            'min_level_required' => 'required|integer|min:1',
            'is_enabled' => 'required|boolean',
            'maintenance_message' => 'nullable|string|max:500'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        
        // Lấy hoặc tạo mới cấu hình
        $config = WeaponChangeConfig::first();
        
        if (!$config) {
            $config = new WeaponChangeConfig();
        }
        
        // Cập nhật cấu hình
        $config->change_fee = $request->change_fee;
        $config->min_level_required = $request->min_level_required;
        $config->is_enabled = $request->is_enabled;
        $config->maintenance_message = $request->maintenance_message;
        $config->save();
        
        return redirect()->back()->with('success', 'Cập nhật cấu hình thành công!');
    }
    
    /**
     * Thêm loại vũ khí mới
     */
    public function addType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'min_id' => 'required|integer|min:1',
            'max_id' => 'required|integer|min:1|gte:min_id'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        
        // Kiểm tra xung đột phạm vi ID với các loại vũ khí khác
        $conflictType = WeaponType::where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('min_id', '<=', $request->min_id)
                      ->where('max_id', '>=', $request->min_id);
                })->orWhere(function ($q) use ($request) {
                    $q->where('min_id', '<=', $request->max_id)
                      ->where('max_id', '>=', $request->max_id);
                });
            })
            ->first();
            
        if ($conflictType) {
            return redirect()->back()->with('error', 'Phạm vi ID bị trùng với loại vũ khí khác: ' . $conflictType->name);
        }
        
        // Tạo loại vũ khí mới
        $type = new WeaponType();
        $type->name = $request->name;
        $type->min_id = $request->min_id;
        $type->max_id = $request->max_id;
        $type->save();
        
        return redirect()->back()->with('success', 'Thêm loại vũ khí thành công!');
    }
    
    /**
     * Cập nhật loại vũ khí
     */
    public function updateType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:weapon_types,id',
            'name' => 'required|string|max:100',
            'min_id' => 'required|integer|min:1',
            'max_id' => 'required|integer|min:1|gte:min_id'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        
        // Kiểm tra xung đột phạm vi ID với các loại vũ khí khác
        $conflictType = WeaponType::where('id', '!=', $request->id)
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('min_id', '<=', $request->min_id)
                      ->where('max_id', '>=', $request->min_id);
                })->orWhere(function ($q) use ($request) {
                    $q->where('min_id', '<=', $request->max_id)
                      ->where('max_id', '>=', $request->max_id);
                });
            })
            ->first();
            
        if ($conflictType) {
            return redirect()->back()->with('error', 'Phạm vi ID bị trùng với loại vũ khí khác: ' . $conflictType->name);
        }
        
        // Cập nhật loại vũ khí
        $type = WeaponType::findOrFail($request->id);
        $type->name = $request->name;
        $type->min_id = $request->min_id;
        $type->max_id = $request->max_id;
        $type->save();
        
        return redirect()->back()->with('success', 'Cập nhật loại vũ khí thành công!');
    }
    
    /**
     * Xóa loại vũ khí
     */
    public function deleteType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:weapon_types,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        
        // Kiểm tra xem có hình ảnh nào liên quan đến loại vũ khí này không
        $type = WeaponType::findOrFail($request->id);
        $relatedImages = WeaponImage::whereBetween('weapon_id', [$type->min_id, $type->max_id])->count();
        
        if ($relatedImages > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa: Có ' . $relatedImages . ' hình ảnh liên quan đến loại vũ khí này'
            ]);
        }
        
        // Xóa loại vũ khí
        $type->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Xóa loại vũ khí thành công!'
        ]);
    }
    
    /**
     * Thêm hình ảnh vũ khí mới
     */
    public function addImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'weapon_id' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048' // Tối đa 2MB
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        
        // Kiểm tra xem ID vũ khí có thuộc loại vũ khí nào không
        $weaponType = WeaponType::where(function ($query) use ($request) {
                $query->where('min_id', '<=', $request->weapon_id)
                      ->where('max_id', '>=', $request->weapon_id);
            })
            ->first();
            
        if (!$weaponType) {
            return redirect()->back()->with('error', 'ID vũ khí không thuộc loại vũ khí nào đã được cấu hình');
        }
        
        // Kiểm tra xem đã có hình ảnh cho ID vũ khí này chưa
        $existingImage = WeaponImage::where('weapon_id', $request->weapon_id)->first();
        
        if ($existingImage) {
            return redirect()->back()->with('error', 'Đã tồn tại hình ảnh cho ID vũ khí này');
        }
        
        // Lưu hình ảnh
        $imagePath = $request->file('image')->store('weapon_images', 'public');
        $fileName = basename($imagePath);
        
        // Lưu thông tin vào database
        $weaponImage = new WeaponImage();
        $weaponImage->weapon_id = $request->weapon_id;
        $weaponImage->description = $request->description;
        $weaponImage->image_url = $fileName;
        $weaponImage->save();
        
        return redirect()->back()->with('success', 'Thêm hình ảnh vũ khí thành công!');
    }
    
    /**
     * Cập nhật hình ảnh vũ khí
     */
    public function updateImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:weapon_images,id',
            'weapon_id' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048' // Tối đa 2MB
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        
        // Lấy thông tin hình ảnh hiện tại
        $weaponImage = WeaponImage::findOrFail($request->id);
        
        // Kiểm tra xem ID vũ khí có thuộc loại vũ khí nào không
        $weaponType = WeaponType::where(function ($query) use ($request) {
                $query->where('min_id', '<=', $request->weapon_id)
                      ->where('max_id', '>=', $request->weapon_id);
            })
            ->first();
            
        if (!$weaponType) {
            return redirect()->back()->with('error', 'ID vũ khí không thuộc loại vũ khí nào đã được cấu hình');
        }
        
        // Kiểm tra xem đã có hình ảnh cho ID vũ khí mới này chưa (nếu khác ID cũ)
        if ($weaponImage->weapon_id != $request->weapon_id) {
            $existingImage = WeaponImage::where('weapon_id', $request->weapon_id)
                ->where('id', '!=', $request->id)
                ->first();
                
            if ($existingImage) {
                return redirect()->back()->with('error', 'Đã tồn tại hình ảnh cho ID vũ khí này');
            }
        }
        
        // Nếu có upload hình ảnh mới
        if ($request->hasFile('image')) {
            // Xóa hình ảnh cũ nếu có
            if ($weaponImage->image_url) {
                Storage::disk('public')->delete('weapon_images/' . $weaponImage->image_url);
            }
            
            // Lưu hình ảnh mới
            $imagePath = $request->file('image')->store('weapon_images', 'public');
            $fileName = basename($imagePath);
            $weaponImage->image_url = $fileName;
        }
        
        // Cập nhật thông tin
        $weaponImage->weapon_id = $request->weapon_id;
        $weaponImage->description = $request->description;
        $weaponImage->save();
        
        return redirect()->back()->with('success', 'Cập nhật hình ảnh vũ khí thành công!');
    }
    
    /**
     * Xóa hình ảnh vũ khí
     */
    public function deleteImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:weapon_images,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        
        // Lấy thông tin hình ảnh
        $image = WeaponImage::findOrFail($request->id);
        
        // Xóa file hình ảnh
        if ($image->image_url) {
            Storage::disk('public')->delete('weapon_images/' . $image->image_url);
        }
        
        // Xóa bản ghi
        $image->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Xóa hình ảnh vũ khí thành công!'
        ]);
    }
    
    /**
     * Lấy dữ liệu lịch sử thay đổi vũ khí cho DataTables
     */
    public function getLogs()
    {
        $logs = WeaponChangeLog::select([
            'weapon_change_logs.id',
            'weapon_change_logs.user_id',
            'weapon_change_logs.character_id',
            'weapon_change_logs.character_name',
            'weapon_change_logs.old_weapon_id',
            'weapon_change_logs.new_weapon_id',
            'weapon_change_logs.cost',
            'weapon_change_logs.created_at'
        ]);
        
        return DataTables::of($logs)
            ->addColumn('old_weapon', function ($log) {
                $weaponName = $this->getWeaponName($log->old_weapon_id);
                return "#{$log->old_weapon_id} - {$weaponName}";
            })
            ->addColumn('new_weapon', function ($log) {
                $weaponName = $this->getWeaponName($log->new_weapon_id);
                return "#{$log->new_weapon_id} - {$weaponName}";
            })
            ->editColumn('created_at', function ($log) {
                return $log->created_at->format('d/m/Y H:i:s');
            })
            ->make(true);
    }
    
    /**
     * Lấy tên vũ khí từ ID
     */
    private function getWeaponName($weaponId)
    {
        // Thử lấy từ bảng hình ảnh
        $image = WeaponImage::where('weapon_id', $weaponId)->first();
        if ($image && $image->description) {
            return $image->description;
        }
        
        // Lấy loại vũ khí
        $type = WeaponType::where(function ($query) use ($weaponId) {
                $query->where('min_id', '<=', $weaponId)
                      ->where('max_id', '>=', $weaponId);
            })
            ->first();
            
        if ($type) {
            $weaponIndex = $weaponId - $type->min_id + 1;
            return "{$type->name} Lv{$weaponIndex}";
        }
        
        return "Vũ khí #{$weaponId}";
    }
}