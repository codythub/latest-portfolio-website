<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.site-settings.edit', [
            'siteSettings' => SiteSetting::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $siteSettings = SiteSetting::current();

        $validated = $request->validate([
            'hero_background_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'about_background_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'footer_credit_text' => ['required', 'string', 'max:255'],
            'contact_heading' => ['required', 'string', 'max:255'],
            'contact_description' => ['required', 'string', 'max:500'],
            'default_seo_title' => ['required', 'string', 'max:255'],
            'default_meta_description' => ['required', 'string', 'max:500'],
            'favicon' => ['nullable', 'file', 'mimes:ico,png,svg,jpg,jpeg,webp', 'max:1024'],
        ]);

        unset(
            $validated['hero_background_image'],
            $validated['about_background_image'],
            $validated['favicon'],
        );

        foreach ([
            'hero_background_image',
            'about_background_image',
        ] as $field) {
            if ($request->hasFile($field)) {
                $this->replacePublicFile(
                    $siteSettings,
                    $field,
                    $request->file($field)->store('site-settings/backgrounds', 'public')
                );
            }
        }

        if ($request->hasFile('favicon')) {
            $this->replacePublicFile(
                $siteSettings,
                'favicon_path',
                $request->file('favicon')->store('site-settings/favicons', 'public')
            );
        }

        $siteSettings->fill($validated);
        $siteSettings->save();

        return redirect()
            ->route('admin.site-settings.edit')
            ->with('success', 'Site settings saved.');
    }

    private function replacePublicFile(SiteSetting $siteSettings, string $field, string $newPath): void
    {
        $oldPath = $siteSettings->{$field};

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        $siteSettings->{$field} = $newPath;
    }
}
