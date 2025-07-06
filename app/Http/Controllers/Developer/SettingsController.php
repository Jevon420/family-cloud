<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\UserSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $siteSettings = SiteSetting::all()->pluck('value', 'key');
        $userSettings = auth()->user()->settings()->pluck('value', 'key');

        return view('developer.settings.index', compact('siteSettings', 'userSettings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'maintenance_mode' => 'nullable|boolean',
            'registration_enabled' => 'nullable|boolean',
            'max_file_upload_size' => 'nullable|integer|min:1',
            'theme' => 'nullable|string|in:light,dark',
            'notifications_enabled' => 'nullable|boolean',
        ]);

        // Update site settings
        if ($request->has('site_name')) {
            SiteSetting::updateOrCreate(
                ['key' => 'site_name'],
                ['value' => $request->site_name, 'updated_by' => auth()->id()]
            );
        }

        if ($request->has('site_description')) {
            SiteSetting::updateOrCreate(
                ['key' => 'site_description'],
                ['value' => $request->site_description, 'updated_by' => auth()->id()]
            );
        }

        if ($request->has('maintenance_mode')) {
            SiteSetting::updateOrCreate(
                ['key' => 'maintenance_mode'],
                ['value' => $request->boolean('maintenance_mode'), 'updated_by' => auth()->id()]
            );
        }

        if ($request->has('registration_enabled')) {
            SiteSetting::updateOrCreate(
                ['key' => 'registration_enabled'],
                ['value' => $request->boolean('registration_enabled'), 'updated_by' => auth()->id()]
            );
        }

        if ($request->has('max_file_upload_size')) {
            SiteSetting::updateOrCreate(
                ['key' => 'max_file_upload_size'],
                ['value' => $request->max_file_upload_size, 'updated_by' => auth()->id()]
            );
        }

        // Update user settings
        if ($request->has('theme')) {
            auth()->user()->settings()->updateOrCreate(
                ['key' => 'theme'],
                ['value' => $request->theme, 'updated_by' => auth()->id()]
            );
        }

        if ($request->has('notifications_enabled')) {
            auth()->user()->settings()->updateOrCreate(
                ['key' => 'notifications_enabled'],
                ['value' => $request->boolean('notifications_enabled'), 'updated_by' => auth()->id()]
            );
        }

        return redirect()->route('developer.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
