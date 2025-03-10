<?php namespace App\Http\Controllers\Admin;
use App\Http\Requests\BlogRequest;
use App\Http\Requests\GiftCodeRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Blog;
use App\Models\CategoryBlog;
use App\Models\CategoryProduct;
use App\Models\GiftCode;
use App\Models\NameGiftCode;
use App\Models\Product;
use App\Models\TimeSetting;
use App\Models\TransactionHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class TimeSettingController extends Controller
{
    public function index(Request $request)
    {
        $setting = TimeSetting::get();
        return view('admin.timeSetting.index', compact('setting'));
    }
    public function edit(Request $request, $id)
    {
        $promotion = TimeSetting::where('id', $id)->first();
        return view('admin.timeSetting.edit', ['promotion' => $promotion]);
    }
    public function update(Request $request, $id)
    {
        $data = $request->except('_token');
        $dataImport = [];
        switch ($id) {
            case 1:
                $dataImport = ['status' => $data['status'], 'number' => $data['number'], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
                break;
            case 2:
                $dataImport = ['status' => $data['status'], 'number' => $data['number'], 'min_level' => $data['min_level'], 'per_week' => $data['per_week'], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
                break;
            case 3:
                if($data['status'] ==2){
                    $test = User::where('char_event','!=','0')->update(['char_event' => '0']);
                }
                if (isset($data['day_loop'])) {
                    $day_start = Carbon::now();
                    $day_end = Carbon::now()->addDays($data['day_loop']);
                    $dataImport = ['status' => $data['status'], 'day_loop' => $data['day_loop'], 'day_start' => $day_start, 'day_end' => $day_end, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
                } else {
                    if (strtotime($data['day_start']) > strtotime($data['day_end'])) {
                        return redirect()
                            ->back()
                            ->with('error', 'Ngày bắt đầu phải nhỏ hơn ngày kết thúc');
                    }
                    $dataImport = ['status' => $data['status'], 'day_loop' => null, 'day_start' => $data['day_start'], 'day_end' => $data['day_end'], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
                }
                break;
            default:
                return response(['error' => 'error'], 500);
        }
        $id = TimeSetting::where('id', $id)->update($dataImport);
        if ($id > 0) {
            return redirect()
                ->route('admin.timeSetting.index')
                ->with('success', ' The update was successful !!! ');
        }
        return redirect()
            ->route('admin.timeSetting.index')
            ->with('danger', ' The update was failed !!! ');
    }

    public function getTimeSettings()
{
    try {
        $settings = TimeSetting::find(1); // Lấy setting đầu tiên chứa số nhân coin
        return response()->json([
            'success' => true,
            'data' => [
                'number' => $settings->number,
                'status' => $settings->status
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy thông tin cấu hình'
        ], 500);
    }
}
}
