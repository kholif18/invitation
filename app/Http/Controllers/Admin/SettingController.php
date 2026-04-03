<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        // Handle checkbox
        $data['maintenance_mode'] = $request->has('maintenance_mode') ? 1 : 0;

        // ================= LOGO =================
        if ($request->hasFile('site_logo')) {

            // Ambil file lama
            $oldLogo = setting('site_logo');

            // Hapus jika ada
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Upload baru
            $path = $request->file('site_logo')->store('settings', 'public');

            Setting::set('site_logo', $path);
        }

        // ================= FAVICON =================
        if ($request->hasFile('favicon')) {

            $oldFavicon = setting('favicon');

            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }

            $path = $request->file('favicon')->store('settings', 'public');

            Setting::set('favicon', $path);
        }

        // ================= SAVE OTHER =================
        foreach ($data as $key => $value) {
            if (!in_array($key, ['site_logo', 'favicon'])) {
                Setting::set($key, $value);
            }
        }

        return back()->with('success', 'Settings updated!');
    }
}
