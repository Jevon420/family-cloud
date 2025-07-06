<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\SystemConfiguration;
use App\Models\SecuritySetting;
use App\Models\UserSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class ComprehensiveSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $user = auth()->user();
        
        // Get settings based on user role
        $siteSettings = SiteSetting::accessibleBy($user)->get()->groupBy('group');
        $systemConfigurations = SystemConfiguration::accessibleBy($user)->get()->groupBy('group');
        $securitySettings = SecuritySetting::accessibleBy($user)->get()->groupBy('group');
        
        // User settings
        $userSettings = $user->settings()->get()->groupBy('group');
        
        // System information
        $systemInfo = $this->getSystemInformation();
        
        // Cache information
        $cacheInfo = $this->getCacheInformation();
        
        // Log information
        $logInfo = $this->getLogInformation();
        
        return view('admin.settings.comprehensive', compact(
            'siteSettings',
            'systemConfigurations', 
            'securitySettings',
            'userSettings',
            'systemInfo',
            'cacheInfo',
            'logInfo'
        ));
    }

    public function updateSiteSettings(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.type' => 'required|string|in:string,boolean,number,json',
        ]);

        foreach ($validated['settings'] as $settingData) {
            $setting = SiteSetting::where('key', $settingData['key'])->first();
            
            if (!$setting || !$user->can('update', $setting)) {
                continue;
            }

            $value = $settingData['value'];
            
            // Handle different types
            if ($settingData['type'] === 'boolean') {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif ($settingData['type'] === 'number') {
                $value = is_numeric($value) ? (float)$value : 0;
            } elseif ($settingData['type'] === 'json') {
                $value = is_array($value) ? json_encode($value) : $value;
            }

            SiteSetting::setValue($settingData['key'], $value, $settingData['type']);
        }

        return response()->json(['success' => true, 'message' => 'Site settings updated successfully.']);
    }

    public function updateSystemConfigurations(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.type' => 'required|string|in:string,boolean,number,json,password',
        ]);

        $requiresRestart = false;

        foreach ($validated['settings'] as $settingData) {
            $setting = SystemConfiguration::where('key', $settingData['key'])->first();
            
            if (!$setting || !$user->can('update', $setting)) {
                continue;
            }

            $value = $settingData['value'];
            
            // Handle different types
            if ($settingData['type'] === 'boolean') {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif ($settingData['type'] === 'number') {
                $value = is_numeric($value) ? (float)$value : 0;
            } elseif ($settingData['type'] === 'json') {
                $value = is_array($value) ? json_encode($value) : $value;
            }

            SystemConfiguration::setValue($settingData['key'], $value, $settingData['type']);
            
            if ($setting->requires_restart) {
                $requiresRestart = true;
            }
        }

        $message = 'System configurations updated successfully.';
        if ($requiresRestart) {
            $message .= ' Some changes may require application restart to take effect.';
        }

        return response()->json(['success' => true, 'message' => $message, 'requires_restart' => $requiresRestart]);
    }

    public function updateSecuritySettings(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.type' => 'required|string|in:string,boolean,number,json',
        ]);

        foreach ($validated['settings'] as $settingData) {
            $setting = SecuritySetting::where('key', $settingData['key'])->first();
            
            if (!$setting || !$user->can('update', $setting)) {
                continue;
            }

            $value = $settingData['value'];
            
            // Handle different types
            if ($settingData['type'] === 'boolean') {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif ($settingData['type'] === 'number') {
                $value = is_numeric($value) ? (float)$value : 0;
            } elseif ($settingData['type'] === 'json') {
                $value = is_array($value) ? json_encode($value) : $value;
            }

            SecuritySetting::setValue($settingData['key'], $value, $settingData['type']);
        }

        return response()->json(['success' => true, 'message' => 'Security settings updated successfully.']);
    }

    public function clearCache(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasAnyRole(['Developer', 'Global Admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $cacheType = $request->input('type', 'all');
        
        try {
            switch ($cacheType) {
                case 'config':
                    Artisan::call('config:clear');
                    $message = 'Configuration cache cleared.';
                    break;
                case 'route':
                    Artisan::call('route:clear');
                    $message = 'Route cache cleared.';
                    break;
                case 'view':
                    Artisan::call('view:clear');
                    $message = 'View cache cleared.';
                    break;
                case 'application':
                    Cache::flush();
                    $message = 'Application cache cleared.';
                    break;
                case 'all':
                default:
                    Artisan::call('cache:clear');
                    Artisan::call('config:clear');
                    Artisan::call('route:clear');
                    Artisan::call('view:clear');
                    $message = 'All caches cleared successfully.';
                    break;
            }

            Log::info('Cache cleared', [
                'type' => $cacheType,
                'user_id' => $user->id,
                'user_role' => $user->getRoleNames()->first()
            ]);

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            Log::error('Cache clear failed', [
                'type' => $cacheType,
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);

            return response()->json(['error' => 'Failed to clear cache: ' . $e->getMessage()], 500);
        }
    }

    public function viewLogs(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasAnyRole(['Developer', 'Global Admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $logType = $request->input('type', 'laravel');
        $lines = $request->input('lines', 50);
        
        try {
            $logPath = storage_path('logs');
            $logs = [];
            
            switch ($logType) {
                case 'laravel':
                    $logFile = $logPath . '/laravel.log';
                    break;
                case 'error':
                    $logFile = $logPath . '/error.log';
                    break;
                default:
                    $logFile = $logPath . '/laravel.log';
                    break;
            }

            if (file_exists($logFile)) {
                $logContent = file($logFile);
                $logs = array_slice($logContent, -$lines);
            }

            return response()->json([
                'success' => true,
                'logs' => $logs,
                'type' => $logType,
                'file_exists' => file_exists($logFile)
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to read logs: ' . $e->getMessage()], 500);
        }
    }

    public function downloadLogs(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('Developer')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $logType = $request->input('type', 'laravel');
        $logPath = storage_path('logs');
        
        switch ($logType) {
            case 'laravel':
                $logFile = $logPath . '/laravel.log';
                break;
            case 'error':
                $logFile = $logPath . '/error.log';
                break;
            default:
                $logFile = $logPath . '/laravel.log';
                break;
        }

        if (!file_exists($logFile)) {
            return response()->json(['error' => 'Log file not found'], 404);
        }

        return response()->download($logFile, $logType . '_log_' . date('Y-m-d_H-i-s') . '.log');
    }

    private function getSystemInformation()
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => $this->getDatabaseVersion(),
            'storage_usage' => $this->getStorageUsage(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];
    }

    private function getCacheInformation()
    {
        return [
            'driver' => config('cache.default'),
            'config_cached' => file_exists(base_path('bootstrap/cache/config.php')),
            'routes_cached' => file_exists(base_path('bootstrap/cache/routes-v7.php')),
            'views_cached' => count(glob(storage_path('framework/views/*.php'))) > 0,
        ];
    }

    private function getLogInformation()
    {
        $logPath = storage_path('logs');
        $logs = [];
        
        if (is_dir($logPath)) {
            $logFiles = glob($logPath . '/*.log');
            foreach ($logFiles as $file) {
                $logs[] = [
                    'name' => basename($file),
                    'size' => filesize($file),
                    'modified' => filemtime($file),
                    'formatted_size' => $this->formatBytes(filesize($file))
                ];
            }
        }
        
        return $logs;
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
