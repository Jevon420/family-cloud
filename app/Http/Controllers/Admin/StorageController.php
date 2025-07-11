<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StorageManagementService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\SiteSetting;
use App\Models\File;
use App\Models\Photo;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    protected $storageService;

    public function __construct(StorageManagementService $storageService)
    {
        $this->storageService = $storageService;
    }

    public function index()
    {
        $statistics = $this->getStorageStatistics();
        $settings = $this->getWasabiSettings();

        return view('admin.storage.index', compact('statistics', 'settings'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'wasabi_total_storage_gb' => 'required|numeric|min:1',
            'wasabi_allocation_percentage' => 'required|numeric|min:1|max:100',
            'signed_url_short_expiration' => 'required|integer|min:1',
            'signed_url_long_expiration_years' => 'required|integer|min:1',
        ]);

        // Convert years to minutes for long-term expiration
        $longExpirationMinutes = $validatedData['signed_url_long_expiration_years'] * 525600;

        // Update Wasabi settings
        SiteSetting::updateOrCreate(['key' => 'wasabi_total_storage_gb'], ['value' => $validatedData['wasabi_total_storage_gb']]);
        SiteSetting::updateOrCreate(['key' => 'wasabi_allocation_percentage'], ['value' => $validatedData['wasabi_allocation_percentage']]);
        SiteSetting::updateOrCreate(['key' => 'signed_url_short_expiration'], ['value' => $validatedData['signed_url_short_expiration']]);
        SiteSetting::updateOrCreate(['key' => 'signed_url_long_expiration'], ['value' => $longExpirationMinutes]);

        // Recalculate user quotas based on new settings
        $this->recalculateWasabiUserQuotas();

        return redirect()->route('admin.storage.index')->with('success', 'Wasabi storage settings updated successfully.');
    }

    public function recalculate(Request $request)
    {
        $this->recalculateWasabiUserQuotas();
        return redirect()->route('admin.storage.index')->with('success', 'User quotas recalculated successfully.');
    }

    public function generateSignedUrl(Request $request)
    {
        $validatedData = $request->validate([
            'path' => 'required|string',
            'type' => 'required|in:short,long',
        ]);

        $expiration = $validatedData['type'] === 'long'
            ? SiteSetting::where('key', 'signed_url_long_expiration')->value('value') ?? 525600
            : SiteSetting::where('key', 'signed_url_short_expiration')->value('value') ?? 5;

        $maxExpirationMinutes = 10080; // Maximum expiration time for AWS Signature Version 4
        $expiration = min($expiration, $maxExpirationMinutes); // Ensure expiration does not exceed the limit

        $signedUrl = Storage::disk('wasabi')->temporaryUrl(
            $validatedData['path'],
            now()->addMinutes($expiration)
        );

        if ($request->wantsJson()) {
            return response()->json(['url' => $signedUrl]);
        }

        // Redirect to the signed URL for direct image display
        return redirect($signedUrl);
    }

    private function getStorageStatistics()
    {
        // Local hosting storage statistics (site data, profile images, etc.)
        $localStorageUsed = $this->calculateLocalStorageUsed();
        $localStorageAvailable = $this->getAvailableLocalStorage();

        // Wasabi storage statistics (user files, photos, galleries)
        $wasabiTotalGb = SiteSetting::where('key', 'wasabi_total_storage_gb')->value('value') ?? 1000;
        $wasabiAllocationPercentage = SiteSetting::where('key', 'wasabi_allocation_percentage')->value('value') ?? 80;
        $wasabiAllocatedGb = ($wasabiTotalGb * $wasabiAllocationPercentage) / 100;
        $wasabiUsedGb = $this->calculateWasabiStorageUsed();
        $wasabiAvailableGb = $wasabiAllocatedGb - $wasabiUsedGb;

        $userCount = User::count();
        $perUserQuotaGb = $userCount > 0 ? $wasabiAllocatedGb / $userCount : 0;

        return [
            // Local hosting storage
            'local_storage_used_gb' => $localStorageUsed,
            'local_storage_available_gb' => $localStorageAvailable,
            'local_files_count' => $this->getLocalFilesCount(),
            'local_profile_images_count' => UserProfile::whereNotNull('avatar')->count(),

            // Wasabi storage
            'wasabi_total_storage_gb' => $wasabiTotalGb,
            'wasabi_allocation_percentage' => $wasabiAllocationPercentage,
            'wasabi_allocated_gb' => $wasabiAllocatedGb,
            'wasabi_used_gb' => $wasabiUsedGb,
            'wasabi_available_gb' => $wasabiAvailableGb,
            'wasabi_usage_percentage' => $wasabiAllocatedGb > 0 ? ($wasabiUsedGb / $wasabiAllocatedGb) * 100 : 0,

            // User statistics
            'user_count' => $userCount,
            'per_user_quota_gb' => $perUserQuotaGb,
            'user_files_count' => File::count(),
            'user_photos_count' => Photo::count(),
            'user_galleries_count' => Gallery::count(),
        ];
    }

    private function getWasabiSettings()
    {
        return [
            'signed_url_short_expiration' => SiteSetting::where('key', 'signed_url_short_expiration')->value('value') ?? 5,
            'signed_url_long_expiration' => SiteSetting::where('key', 'signed_url_long_expiration')->value('value') ?? 525600,
        ];
    }

    private function calculateLocalStorageUsed()
    {
        // Calculate storage used by site assets, logs, cache, profile images, etc.
        $localStorageUsed = 0;

        // Site assets and public files
        $publicPath = public_path();
        $localStorageUsed += $this->getDirectorySize($publicPath) / (1024 * 1024 * 1024); // Convert to GB

        // Storage directory (excluding user uploads)
        $storagePath = storage_path();
        $localStorageUsed += $this->getDirectorySize($storagePath) / (1024 * 1024 * 1024); // Convert to GB

        return round($localStorageUsed, 2);
    }

    private function getAvailableLocalStorage()
    {
        // Get available disk space on the server
        $bytes = disk_free_space('/');
        return round($bytes / (1024 * 1024 * 1024), 2); // Convert to GB
    }

    private function getLocalFilesCount()
    {
        // Count site-related files (not user uploads)
        $publicFiles = $this->countFilesInDirectory(public_path());
        $storageFiles = $this->countFilesInDirectory(storage_path());
        return $publicFiles + $storageFiles;
    }

    private function calculateWasabiStorageUsed()
    {
        // Calculate storage used by user files, photos, galleries on Wasabi
        $userFilesSize = File::sum('file_size') ?? 0;
        $userPhotosSize = Photo::sum('file_size') ?? 0;

        return round(($userFilesSize + $userPhotosSize) / (1024 * 1024 * 1024), 2); // Convert to GB
    }

    private function recalculateWasabiUserQuotas()
    {
        $wasabiTotalGb = SiteSetting::where('key', 'wasabi_total_storage_gb')->value('value') ?? 1000;
        $wasabiAllocationPercentage = SiteSetting::where('key', 'wasabi_allocation_percentage')->value('value') ?? 80;
        $wasabiAllocatedGb = ($wasabiTotalGb * $wasabiAllocationPercentage) / 100;

        $userCount = User::count();
        $perUserQuotaGb = $userCount > 0 ? $wasabiAllocatedGb / $userCount : 0;

        // Update all users' storage quotas equally (admin can adjust individual users later)
        User::chunk(100, function ($users) use ($perUserQuotaGb) {
            foreach ($users as $user) {
                $user->storage_quota_gb = $perUserQuotaGb;
                $user->save();
            }
        });

        // Recalculate actual usage for all users
        $this->storageService->updateAllUserQuotas();
    }

    private function getDirectorySize($directory)
    {
        $size = 0;
        if (is_dir($directory)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($files as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }
        }
        return $size;
    }

    private function countFilesInDirectory($directory)
    {
        $count = 0;
        if (is_dir($directory)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($files as $file) {
                if ($file->isFile()) {
                    $count++;
                }
            }
        }
        return $count;
    }
}
