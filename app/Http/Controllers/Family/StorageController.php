<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Photo;
use App\Models\Gallery;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userSettings = auth()->user()->settings()->pluck('value', 'key');
        $darkMode = $userSettings['dark_mode'] ?? false;

        // Get comprehensive storage data
        $storageData = $this->getStorageData();
        
        return view('family.storage.index', compact('storageData', 'darkMode'));
    }

    public function files()
    {
        $userSettings = auth()->user()->settings()->pluck('value', 'key');
        $darkMode = $userSettings['dark_mode'] ?? false;

        $files = auth()->user()->files()
            ->select('id', 'name', 'size', 'mime_type', 'created_at')
            ->orderBy('size', 'desc')
            ->paginate(20);

        $totalSize = auth()->user()->files()->sum('size');
        $fileTypes = auth()->user()->files()
            ->select('mime_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(size) as total_size'))
            ->groupBy('mime_type')
            ->orderBy('total_size', 'desc')
            ->get();

        return view('family.storage.files', compact('files', 'totalSize', 'fileTypes', 'darkMode'));
    }

    public function photos()
    {
        $userSettings = auth()->user()->settings()->pluck('value', 'key');
        $darkMode = $userSettings['dark_mode'] ?? false;

        $photos = auth()->user()->photos()
            ->select('id', 'name', 'file_size', 'mime_type', 'file_path', 'created_at')
            ->orderBy('file_size', 'desc')
            ->paginate(20);

        $totalSize = auth()->user()->photos()->sum('file_size');
        $photoTypes = auth()->user()->photos()
            ->select('mime_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(file_size) as total_size'))
            ->groupBy('mime_type')
            ->orderBy('total_size', 'desc')
            ->get();

        return view('family.storage.photos', compact('photos', 'totalSize', 'photoTypes', 'darkMode'));
    }

    public function galleries()
    {
        $userSettings = auth()->user()->settings()->pluck('value', 'key');
        $darkMode = $userSettings['dark_mode'] ?? false;

        $galleries = auth()->user()->galleries()
            ->withCount('photos')
            ->with(['photos' => function($query) {
                $query->select('gallery_id', DB::raw('SUM(file_size) as total_size'))
                    ->groupBy('gallery_id');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('family.storage.galleries', compact('galleries', 'darkMode'));
    }

    private function getStorageData()
    {
        $userId = auth()->id();
        
        // Basic counts
        $totalFiles = File::where('user_id', $userId)->count();
        $totalFolders = Folder::where('user_id', $userId)->count();
        $totalPhotos = Photo::where('user_id', $userId)->count();
        $totalGalleries = Gallery::where('user_id', $userId)->count();

        // Storage calculations
        $fileStorage = File::where('user_id', $userId)->sum('size') ?? 0;
        $photoStorage = Photo::where('user_id', $userId)->sum('file_size') ?? 0;
        $totalUsed = $fileStorage + $photoStorage;
        $maxStorage = auth()->user()->storage_quota_gb ? auth()->user()->storage_quota_gb * 1024 * 1024 * 1024 : 5 * 1024 * 1024 * 1024;

        // File type breakdown
        $fileTypes = File::where('user_id', $userId)
            ->select('mime_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(size) as total_size'))
            ->groupBy('mime_type')
            ->orderBy('total_size', 'desc')
            ->get();

        // Photo type breakdown
        $photoTypes = Photo::where('user_id', $userId)
            ->select('mime_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(file_size) as total_size'))
            ->groupBy('mime_type')
            ->orderBy('total_size', 'desc')
            ->get();

        // Monthly usage
        $monthlyUsage = collect([]);
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $files = File::where('user_id', $userId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('size') ?? 0;
            $photos = Photo::where('user_id', $userId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('file_size') ?? 0;
            
            $monthlyUsage->push([
                'month' => $date->format('M Y'),
                'files' => $files,
                'photos' => $photos,
                'total' => $files + $photos
            ]);
        }

        // Largest files
        $largestFiles = File::where('user_id', $userId)
            ->orderBy('size', 'desc')
            ->take(10)
            ->get();

        // Largest photos
        $largestPhotos = Photo::where('user_id', $userId)
            ->orderBy('file_size', 'desc')
            ->take(10)
            ->get();

        return [
            'summary' => [
                'totalFiles' => $totalFiles,
                'totalFolders' => $totalFolders,
                'totalPhotos' => $totalPhotos,
                'totalGalleries' => $totalGalleries,
                'fileStorage' => $fileStorage,
                'photoStorage' => $photoStorage,
                'totalUsed' => $totalUsed,
                'maxStorage' => $maxStorage,
                'availableStorage' => $maxStorage - $totalUsed,
                'usagePercentage' => $maxStorage > 0 ? ($totalUsed / $maxStorage) * 100 : 0,
            ],
            'fileTypes' => $fileTypes,
            'photoTypes' => $photoTypes,
            'monthlyUsage' => $monthlyUsage,
            'largestFiles' => $largestFiles,
            'largestPhotos' => $largestPhotos,
        ];
    }
}
