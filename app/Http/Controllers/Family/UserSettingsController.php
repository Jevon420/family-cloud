<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Get user settings or create defaults
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';
        $simpleDashboard = $this->getUserSetting('simple_dashboard', 'false') === 'true';
        $galleryLayout = $this->getUserSetting('gallery_layout', 'grid');

        return view('family.settings.index', compact(
            'user',
            'theme',
            'darkMode',
            'simpleDashboard',
            'galleryLayout'
        ));
    }

    /**
     * Update the user's settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the incoming request
        $validated = $request->validate([
            'theme' => 'required|string|in:light,blue,green,purple,indigo',
            'dark_mode' => 'boolean',
            'simple_dashboard' => 'boolean',
            'gallery_layout' => 'required|string|in:grid,list,masonry',
        ]);

        // Update or create each setting
        $this->updateUserSetting('theme', $validated['theme']);
        $this->updateUserSetting('dark_mode', $validated['dark_mode'] ? 'true' : 'false');
        $this->updateUserSetting('simple_dashboard', $validated['simple_dashboard'] ? 'true' : 'false');
        $this->updateUserSetting('gallery_layout', $validated['gallery_layout']);

        return redirect()->route('family.settings.index')
            ->with('success', 'Your settings have been updated successfully.');
    }

    /**
     * Get a user setting by key with a default value if not found.
     *
     * @param  string  $key
     * @param  string  $default
     * @return string
     */
    private function getUserSetting($key, $default = '')
    {
        $setting = UserSetting::where('user_id', Auth::id())
            ->where('key', $key)
            ->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Update or create a user setting.
     *
     * @param  string  $key
     * @param  string  $value
     * @return \App\Models\UserSetting
     */
    private function updateUserSetting($key, $value)
    {
        return UserSetting::updateOrCreate(
            ['user_id' => Auth::id(), 'key' => $key],
            [
                'value' => $value,
                'type' => 'string',
                'group' => 'appearance',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]
        );
    }
}
