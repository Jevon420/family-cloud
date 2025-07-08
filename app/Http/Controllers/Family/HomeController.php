<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Gallery;
use App\Models\File;
use App\Models\Folder;
use App\Models\Photo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page = Page::with(['contents', 'images'])->where('slug', 'family-home')->first();

        // Create a default page object if none exists
        if (!$page) {
            $page = new Page();
            $page->title = 'Family Dashboard';
            $page->slug = 'family-home';
        }

        $userSettings = auth()->user()->settings()->pluck('value', 'key');
        $simpleDashboard = filter_var($userSettings['simple_dashboard'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $darkMode = filter_var($userSettings['dark_mode'] ?? false, FILTER_VALIDATE_BOOLEAN);

        // Get recent galleries with cover images
        $recentGalleries = auth()->user()->galleries()
            ->withCount('photos')
            ->latest()
            ->take(4)
            ->get();

        // Get recent files
        $recentFiles = auth()->user()->files()
            ->latest()
            ->take(5)
            ->get();

        // Get recent folders
        $recentFolders = auth()->user()->folders()
            ->withCount('files')
            ->latest()
            ->take(5)
            ->get();

        // Calculate storage summary
        $storageSummary = $this->calculateStorageSummary();

        // Get storage breakdown by type
        $storageBreakdown = $this->getStorageBreakdown();

        return view('family.home', compact(
            'page',
            'simpleDashboard',
            'darkMode',
            'recentGalleries',
            'recentFiles',
            'recentFolders',
            'storageSummary',
            'storageBreakdown'
        ));
    }

    private function calculateStorageSummary()
    {
        $userId = auth()->id();

        // Get file storage using actual file sizes
        $fileStorage = File::where('user_id', $userId)
            ->sum('file_size') ?? 0;

        // Get photo storage using actual file sizes
        $photoStorage = Photo::where('user_id', $userId)
            ->sum('file_size') ?? 0;

        $totalUsedStorage = $fileStorage + $photoStorage;

        return [
            'totalFiles' => File::where('user_id', $userId)->count(),
            'totalFolders' => Folder::where('user_id', $userId)->count(),
            'totalPhotos' => Photo::where('user_id', $userId)->count(),
            'totalGalleries' => Gallery::where('user_id', $userId)->count(),
            'usedStorage' => $totalUsedStorage,
            'fileStorage' => $fileStorage,
            'photoStorage' => $photoStorage,
            'maxStorage' => auth()->user()->storage_quota_gb ? auth()->user()->storage_quota_gb * 1024 * 1024 * 1024 : 5 * 1024 * 1024 * 1024, // Default 5GB
        ];
    }

    private function getStorageBreakdown()
    {
        $userId = auth()->id();

        // Get file types breakdown
        $fileTypes = File::where('user_id', $userId)
            ->select('mime_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(file_size) as total_size'))
            ->groupBy('mime_type')
            ->get();

        // Get photo types breakdown
        $photoTypes = Photo::where('user_id', $userId)
            ->select('mime_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(file_size) as total_size'))
            ->groupBy('mime_type')
            ->get();

        return [
            'fileTypes' => $fileTypes,
            'photoTypes' => $photoTypes,
        ];
    }
}
