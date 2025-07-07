<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\UserSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

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
        $roles = Role::all();
        $totalUsers = User::count();

        return view('admin.settings.index', compact('siteSettings', 'userSettings', 'roles', 'totalUsers'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'site_email' => 'nullable|email|max:255',
            'maintenance_mode' => 'nullable|boolean',
            'registration_enabled' => 'nullable|boolean',
            'max_file_upload_size' => 'nullable|integer|min:1',
            'max_storage_per_user' => 'nullable|integer|min:1',
            'backup_enabled' => 'nullable|boolean',
            'email_notifications' => 'nullable|boolean',
            'theme' => 'nullable|string|in:light,dark',
            'notifications_enabled' => 'nullable|boolean',
            'timezone' => 'nullable|string',
        ]);

        // Update site settings
        $siteSettings = [
            'site_name', 'site_description', 'site_email', 'maintenance_mode',
            'registration_enabled', 'max_file_upload_size', 'max_storage_per_user',
            'backup_enabled', 'email_notifications', 'timezone'
        ];

        foreach ($siteSettings as $setting) {
            if ($request->has($setting)) {
                $value = $request->input($setting);
                if (in_array($setting, ['maintenance_mode', 'registration_enabled', 'backup_enabled', 'email_notifications'])) {
                    $value = $request->boolean($setting);
                }

                SiteSetting::updateOrCreate(
                    ['key' => $setting],
                    ['value' => $value, 'updated_by' => auth()->id()]
                );
            }
        }

        // Update user settings
        $userSettings = ['theme', 'notifications_enabled'];

        foreach ($userSettings as $setting) {
            if ($request->has($setting)) {
                $value = $request->input($setting);
                if ($setting === 'notifications_enabled') {
                    $value = $request->boolean($setting);
                }

                auth()->user()->settings()->updateOrCreate(
                    ['key' => $setting],
                    ['value' => $value, 'updated_by' => auth()->id()]
                );
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    public function users(Request $request)
    {
        // Check if user came from email notification
        if ($request->has('from_email')) {
            session()->flash('info', 'You have pending user registration requests that require your attention.');
        }

        $users = User::with('roles')->where('status', '!=', 'pending')->paginate(20);
        $pendingUsers = User::where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $roles = Role::all();
        $totalUsers = User::where('status', '!=', 'pending')->count();
        $totalPendingUsers = User::where('status', 'pending')->count();

        return view('admin.settings.users', compact('users', 'pendingUsers', 'roles', 'totalUsers', 'totalPendingUsers'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.settings.users')
            ->with('success', "User {$user->name}'s role updated to {$request->role}.");
    }

    public function system()
    {
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => $this->getDatabaseVersion(),
            'storage_usage' => $this->getStorageUsage(),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'mail_driver' => config('mail.default'),
        ];

        return view('admin.settings.system', compact('systemInfo'));
    }

    private function getDatabaseVersion()
    {
        try {
            return \DB::select('SELECT VERSION() as version')[0]->version ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getStorageUsage()
    {
        $storagePath = storage_path('app');
        $totalSize = 0;

        if (is_dir($storagePath)) {
            try {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($storagePath, \RecursiveDirectoryIterator::SKIP_DOTS)
                );

                foreach ($iterator as $file) {
                    $totalSize += $file->getSize();
                }
            } catch (\Exception $e) {
                $totalSize = 0;
            }
        }

        return [
            'used' => $totalSize,
            'formatted' => $this->formatBytes($totalSize)
        ];
    }

    private function formatBytes($size, $precision = 2)
    {
        if ($size == 0) return '0 B';

        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}
