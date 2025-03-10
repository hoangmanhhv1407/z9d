<?php 

namespace App\Http\Controllers\Admin;

use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        $settingOptions = Settings::select('option_key', 'option_value')
            ->get()
            ->pluck('option_value', 'option_key');

        return view('admin.settings.index', compact('settingOptions'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $setting = Settings::where('option_key', $key)->first();
            $setting->option_value = $value;
            $setting->save();
        }

        return redirect()->back()->with('success', 'The update was successful!');
    }
}
