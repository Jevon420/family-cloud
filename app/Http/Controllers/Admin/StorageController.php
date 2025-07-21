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

    /**
     * Get a signed URL for a file in Wasabi storage, checking alternative paths if needed
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function generateSignedUrl(Request $request)
    {
        $path = $request->input('path');
        $type = $request->input('type', 'short');

        // Set expiration time based on type
        $expiration = $type === 'long' ? 24 * 60 : 15;

        // Check if file exists in Wasabi
        $wasabiDisk = Storage::disk('wasabi');

        if (!$wasabiDisk->exists($path)) {
            // Try to find the file in alternative locations
            // 1. First check if this is a photo path and try to find the correct gallery
            if (str_contains($path, 'photos/')) {
                $filename = basename($path);

                // Check if we can find a photo with this filename or path
                $photo = Photo::where(function($query) use ($filename, $path) {
                    $query->where('file_path', 'like', "%{$filename}")
                          ->orWhere('file_path', $path)
                          ->orWhere('thumbnail_path', $path);
                })->first();

                if ($photo && $photo->gallery) {
                    $gallerySlug = $photo->gallery->slug;

                    // Check if this is a thumbnail path
                    if (str_contains($path, 'thumbnails/')) {
                        $newPath = "familycloud/family/galleries/{$gallerySlug}/photos/thumbnails/{$filename}";
                        $photo->thumbnail_path = $newPath;
                    } else {
                        $newPath = "familycloud/family/galleries/{$gallerySlug}/photos/{$filename}";
                        $photo->file_path = $newPath;
                    }

                    $photo->save();
                    $path = $newPath;
                }
            }
            // 2. Check if this is a gallery cover image
            else if (str_contains($path, 'cover-image/')) {
                $filename = basename($path);

                // Try to find the gallery this cover belongs to
                $gallery = Gallery::where('cover_image', 'like', "%{$filename}")->first();

                if ($gallery) {
                    $newPath = "familycloud/family/galleries/{$gallery->slug}/cover-image/{$filename}";
                    $gallery->cover_image = $newPath;
                    $gallery->save();
                    $path = $newPath;
                }
            }

            // If still not found, return a placeholder
            if (!$wasabiDisk->exists($path)) {
                return redirect()->to('/images/placeholder.php');
            }
        }

        // Generate the signed URL
        $url = $wasabiDisk->temporaryUrl($path, now()->addMinutes($expiration));

        return redirect()->to($url);
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

        User::chunk(100, function ($users) use ($perUserQuotaGb) {
            foreach ($users as $user) {
                // Calculate storage used by user
                $userFilesSize = $user->files()->sum('file_size') ?? 0;
                $userPhotosSize = $user->photos()->sum('file_size') ?? 0;
                $userFoldersSize = $user->folders()->sum('folder_size') ?? 0;
                $userThumbnailsSize = $user->photos()->sum('thumbnail_size') ?? 0;
                $userGalleryCoverSize = $user->galleries()->sum('cover_image_size') ?? 0;

                $totalUsedSizeGb = round(($userFilesSize + $userPhotosSize + $userFoldersSize + $userThumbnailsSize + $userGalleryCoverSize) / (1024 * 1024 * 1024), 2);

                // Update user's storage quota and usage
                $user->update([
                    'storage_quota_gb' => $perUserQuotaGb,
                    'storage_used_gb' => $totalUsedSizeGb,
                ]);
            }
        });
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

    /**
     * Creates and saves a photo record in the database with the correct path structure
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fixAllPhotosPaths()
    {
        $photos = Photo::with('gallery')->get();
        $fixed = 0;

        foreach ($photos as $photo) {
            if (!$photo->gallery) {
                continue;
            }

            $gallerySlug = $photo->gallery->slug;
            $filePathChanged = false;
            $thumbnailPathChanged = false;

            // Fix file path
            if ($photo->file_path) {
                $filename = basename($photo->file_path);
                $newFilePath = "familycloud/family/galleries/{$gallerySlug}/photos/{$filename}";

                if ($photo->file_path !== $newFilePath) {
                    $photo->file_path = $newFilePath;
                    $filePathChanged = true;
                }
            }

            // Fix thumbnail path
            if ($photo->thumbnail_path) {
                $thumbnailFilename = basename($photo->thumbnail_path);
                $newThumbnailPath = "familycloud/family/galleries/{$gallerySlug}/photos/thumbnails/{$thumbnailFilename}";

                if ($photo->thumbnail_path !== $newThumbnailPath) {
                    $photo->thumbnail_path = $newThumbnailPath;
                    $thumbnailPathChanged = true;
                }
            } else if ($photo->file_path) {
                // If no thumbnail path, set it to the same as file path
                $photo->thumbnail_path = $photo->file_path;
                $thumbnailPathChanged = true;
            }

            if ($filePathChanged || $thumbnailPathChanged) {
                $photo->save();
                $fixed++;
            }
        }

        // Fix galleries cover images
        $galleries = Gallery::all();
        $fixedGalleries = 0;

        foreach ($galleries as $gallery) {
            if ($gallery->cover_image) {
                $filename = basename($gallery->cover_image);
                $newCoverPath = "familycloud/family/galleries/{$gallery->slug}/cover-image/{$filename}";

                if ($gallery->cover_image !== $newCoverPath) {
                    $gallery->cover_image = $newCoverPath;
                    $gallery->save();
                    $fixedGalleries++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'photos_fixed' => $fixed,
            'galleries_fixed' => $fixedGalleries,
            'total_photos' => $photos->count(),
            'total_galleries' => $galleries->count()
        ]);
    }

    public function updateUserStorageUsage(Request $request)
    {
        $users = User::all();
        $updatedUsers = 0;

        foreach ($users as $user) {
            // Calculate storage used by user from database
            $userFilesSize = $user->files()->sum('file_size') ?? 0;
            $userPhotosSize = $user->photos()->sum('file_size') ?? 0;
            $userFoldersSize = $user->folders()->sum('folder_size') ?? 0;
            $userThumbnailsSize = $user->photos()->sum('thumbnail_size') ?? 0;
            $userGalleryCoverSize = $user->galleries()->sum('cover_image_size') ?? 0;

            // Calculate actual storage used in Wasabi
            $wasabiDisk = Storage::disk('wasabi');
            $actualStorageUsed = 0;

            foreach ($user->files as $file) {
                if ($wasabiDisk->exists($file->file_path)) {
                    $actualStorageUsed += $wasabiDisk->size($file->file_path);
                }
            }

            foreach ($user->photos as $photo) {
                if ($wasabiDisk->exists($photo->file_path)) {
                    $actualStorageUsed += $wasabiDisk->size($photo->file_path);
                }
                if ($wasabiDisk->exists($photo->thumbnail_path)) {
                    $actualStorageUsed += $wasabiDisk->size($photo->thumbnail_path);
                }
            }

            foreach ($user->galleries as $gallery) {
                if ($wasabiDisk->exists($gallery->cover_image)) {
                    $actualStorageUsed += $wasabiDisk->size($gallery->cover_image);
                }
            }

            // Convert to GB and update storage_used_gb
            $totalUsedSizeGb = round(($userFilesSize + $userPhotosSize + $userFoldersSize + $userThumbnailsSize + $userGalleryCoverSize + $actualStorageUsed) / (1024 * 1024 * 1024), 2);
            $user->update(['storage_used_gb' => $totalUsedSizeGb]);
            $updatedUsers++;
        }

        return response()->json(['success' => true, 'updated_users' => $updatedUsers]);
    }
}
