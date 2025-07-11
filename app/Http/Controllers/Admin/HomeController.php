<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Photo;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_photos' => Photo::count(),
            'total_files' => File::count(),
            'storage_used' => $this->formatBytes($this->getDirectorySize(storage_path('app'))),
            'storage_available' => $this->formatBytes(disk_free_space(storage_path('app'))),
            'database_connection' => $this->checkDatabaseConnection(),
            'cache_system' => $this->checkCacheSystem(),
            'queue_workers' => $this->checkQueueWorkers(),
        ];

        $roleStats = User::selectRaw('roles.name as role, count(*) as count')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->groupBy('roles.name')
            ->pluck('count', 'role')
            ->toArray();

        // Ensure all roles are represented
        $allRoles = ['admin', 'developer', 'family'];
        foreach ($allRoles as $role) {
            if (!isset($roleStats[$role])) {
                $roleStats[$role] = 0;
            }
        }

        // Get pending users count for the alert
        $pendingUsersCount = User::where('status', 'pending')->count();

        return view('admin.home', compact('stats', 'roleStats', 'pendingUsersCount'));
    }

    public function refreshStats()
    {
        $stats = [
            'total_users' => User::count(),
            'total_photos' => Photo::count(),
            'total_files' => File::count(),
            'storage_used' => $this->formatBytes($this->getDirectorySize(storage_path('app'))),
            'storage_available' => $this->formatBytes(disk_free_space(storage_path('app'))),
            'database_connection' => $this->checkDatabaseConnection(),
            'cache_system' => $this->checkCacheSystem(),
            'queue_workers' => $this->checkQueueWorkers(),
        ];

        return response()->json($stats);
    }

    private function getDirectorySize($path)
    {
        $size = 0;
        if (is_dir($path)) {
            try {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
                );
                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        $size += $file->getSize();
                    }
                }
            } catch (\Exception $e) {
                // Handle any file system errors gracefully
                $size = 0;
            }
        }
        return $size;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        if ($bytes == 0) return '0 B';

        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $base = log($bytes, 1024);

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $units[floor($base)];
    }

    private function checkDatabaseConnection()
    {
        try {
            return \DB::connection()->getPdo() ? 'Active' : 'Inactive';
        } catch (\Exception $e) {
            return 'Inactive';
        }
    }

    private function checkCacheSystem()
    {
        try {
            return \Cache::store('file')->get('key') !== null ? 'Running' : 'Stopped';
        } catch (\Exception $e) {
            return 'Stopped';
        }
    }

    private function checkQueueWorkers()
    {
        try {
            return \Queue::size() > 0 ? 'Operational' : 'Idle';
        } catch (\Exception $e) {
            return 'Idle';
        }
    }
}
