<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    private const TEXT_KEYS = [
        'site_name', 'site_tagline', 'site_description', 'running_text',
        'contact_email', 'contact_phone', 'contact_address',
        'social_facebook', 'social_instagram', 'social_youtube',
        'copyright_text', 'color_primary', 'color_secondary', 'color_accent',
    ];

    private const IMAGE_KEYS = ['logo', 'favicon', 'hero_background'];

    public function edit(): View
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $settings = Setting::pluck('value', 'key');

        return view('admin.settings.edit', ['settings' => $settings]);
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:1000'],
            'running_text' => ['nullable', 'string', 'max:1000'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:500'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_youtube' => ['nullable', 'url', 'max:255'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
            'color_primary' => ['nullable', 'string', 'max:20'],
            'color_secondary' => ['nullable', 'string', 'max:20'],
            'color_accent' => ['nullable', 'string', 'max:20'],
            // Favicon sengaja tidak pakai rule `image` — Laravel tidak selalu
            // mengenali .ico sebagai gambar valid lewat rule tersebut, padahal
            // .ico adalah format favicon paling umum.
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:'.config('sisukat.uploads.logo_max_kb')],
            'favicon' => ['nullable', 'file', 'mimes:ico,png,jpg,jpeg', 'max:'.config('sisukat.uploads.favicon_max_kb')],
            'hero_background' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:'.config('sisukat.uploads.hero_background_max_kb')],
        ]);

        foreach (self::TEXT_KEYS as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $validated[$key] ?? null]);
        }

        foreach (self::IMAGE_KEYS as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $path = $request->file($fileKey)->store('branding', 'public');
                Setting::updateOrCreate(['key' => $fileKey], ['value' => $path]);
            }
        }

        ActivityLogger::log('update', 'Memperbarui pengaturan website');

        return back()->with('status', 'Pengaturan berhasil disimpan.');
    }
}
