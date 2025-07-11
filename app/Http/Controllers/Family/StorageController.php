<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Photo;
use App\Models\Gallery;
use App\Models\Folder;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userSettings = auth()->user()->settings()->pluck('value', 'key');
        $darkMode = filter_var($userSettings['dark_mode'] ?? false, FILTER_VALIDATE_BOOLEAN);

        // Get comprehensive storage data
        $storageData = $this->getStorageData();

        return view('family.storage.index', compact('storageData', 'darkMode'));
    }

    public function files()
    {
        $userSettings = auth()->user()->settings()->pluck('value', 'key');
        $darkMode = filter_var($userSettings['dark_mode'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $files = auth()->user()->files()
            ->select('id', 'name', 'file_size', 'mime_type', 'created_at')
            ->orderBy('file_size', 'desc')
            ->paginate(20);

        $totalSize = auth()->user()->files()->sum('file_size');
        $fileTypes = auth()->user()->files()
            ->select('mime_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(file_size) as total_size'))
            ->groupBy('mime_type')
            ->orderBy('total_size', 'desc')
            ->get();

        return view('family.storage.files', compact('files', 'totalSize', 'fileTypes', 'darkMode'));
    }

    public function photos()
    {
        $userSettings = auth()->user()->settings()->pluck('value', 'key');
        $darkMode = filter_var($userSettings['dark_mode'] ?? false, FILTER_VALIDATE_BOOLEAN);

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
        $darkMode = filter_var($userSettings['dark_mode'] ?? false, FILTER_VALIDATE_BOOLEAN);

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

        // Wasabi storage calculations (user files, photos, galleries)
        $fileStorage = File::where('user_id', $userId)->sum('file_size') ?? 0;
        $photoStorage = Photo::where('user_id', $userId)->sum('file_size') ?? 0;
        $totalWasabiUsed = $fileStorage + $photoStorage;
        $maxWasabiStorage = auth()->user()->storage_quota_gb ? auth()->user()->storage_quota_gb * 1024 * 1024 * 1024 : 5 * 1024 * 1024 * 1024;

        // Local storage (profile image size - minimal)
        $profileImageSize = 0;
        if (auth()->user()->profile_image && file_exists(public_path('storage/' . auth()->user()->profile_image))) {
            $profileImageSize = filesize(public_path('storage/' . auth()->user()->profile_image));
        }

        // File type breakdown
        $fileTypes = File::where('user_id', $userId)
            ->select('mime_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(file_size) as total_size'))
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
                ->sum('file_size') ?? 0;
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
            ->orderBy('file_size', 'desc')
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

                // Wasabi storage (user content)
                'fileStorage' => $fileStorage,
                'photoStorage' => $photoStorage,
                'wasabiTotalUsed' => $totalWasabiUsed,
                'wasabiMaxStorage' => $maxWasabiStorage,
                'wasabiAvailableStorage' => $maxWasabiStorage - $totalWasabiUsed,
                'wasabiUsagePercentage' => $maxWasabiStorage > 0 ? ($totalWasabiUsed / $maxWasabiStorage) * 100 : 0,

                // Local storage (profile image)
                'localStorageUsed' => $profileImageSize,
                'localStorageUsedFormatted' => $this->formatBytes($profileImageSize),

                // Legacy fields for compatibility
                'totalUsed' => $totalWasabiUsed,
                'maxStorage' => $maxWasabiStorage,
                'availableStorage' => $maxWasabiStorage - $totalWasabiUsed,
                'usagePercentage' => $maxWasabiStorage > 0 ? ($totalWasabiUsed / $maxWasabiStorage) * 100 : 0,
            ],
            'fileTypes' => $fileTypes,
            'photoTypes' => $photoTypes,
            'monthlyUsage' => $monthlyUsage,
            'largestFiles' => $largestFiles,
            'largestPhotos' => $largestPhotos,
        ];
    }

    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }

    public function generateSignedUrl($path, $type = 'short')
    {
        $expirationMinutes = $type === 'short'
            ? config('settings.signed_url_short_expiration', 5)
            : config('settings.signed_url_long_expiration', 525600);

        return Storage::disk(env('WASABI_DISK'))->temporaryUrl($path, now()->addMinutes($expirationMinutes));
    }

    public function refreshSignedUrl(Request $request)
    {
        $path = $request->input('path');
        $type = $request->input('type', 'short');

        if (!$path) {
            return response()->json(['error' => 'Path is required'], 400);
        }

        $signedUrl = $this->generateSignedUrl($path, $type);

        return response()->json(['signed_url' => $signedUrl]);
    }

    public function updateStorageSettings(Request $request)
    {
        $validatedData = $request->validate([
            'signed_url_short_expiration' => 'required|integer|min:1',
            'signed_url_long_expiration_years' => 'required|integer|min:1',
        ]);

        $longExpirationMinutes = $validatedData['signed_url_long_expiration_years'] * 525600;

        // Update settings in the database
        SiteSetting::updateOrCreate(['key' => 'signed_url_short_expiration'], ['value' => $validatedData['signed_url_short_expiration']]);
        SiteSetting::updateOrCreate(['key' => 'signed_url_long_expiration'], ['value' => $longExpirationMinutes]);

        return redirect()->route('admin.storage.index')->with('success', 'Storage settings updated successfully.');
    }
}
