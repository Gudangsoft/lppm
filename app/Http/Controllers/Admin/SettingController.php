<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $languages = Language::where('is_active', true)->get();

        return view('admin.settings.index', compact('settings', 'languages'));
    }

    public function update(Request $request)
    {
        // Handle file uploads
        foreach (['site_logo', 'site_favicon'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                // Delete old file
                $oldSetting = Setting::where('key', $fileKey)->first();
                if ($oldSetting && $oldSetting->value) {
                    Storage::disk('public')->delete($oldSetting->value);
                }
                
                $path = $request->file($fileKey)->store('settings', 'public');
                Setting::updateOrCreate(
                    ['key' => $fileKey],
                    ['value' => $path]
                );
            }
        }
        
        // Handle text settings
        if ($request->has('settings')) {
            foreach ($request->settings as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }
        
        // Clear cache
        cache()->forget('site_settings');

        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
