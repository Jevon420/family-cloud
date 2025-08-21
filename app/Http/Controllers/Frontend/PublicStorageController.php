<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Photo;
use App\Models\Gallery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PublicStorageController extends Controller
{
    /**
     * Generate a signed URL for Wasabi storage files
     * This is a public endpoint with basic security measures
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function generateSignedUrl(Request $request)
    {
        $path = $request->input('path');
        $type = $request->input('type', 'short');

        // Basic validation
        if (empty($path)) {
            return response()->json(['error' => 'Path is required'], 400);
        }

        // Rate limiting and security check
        $clientIp = $request->ip();
        $cacheKey = "signed_url_requests_{$clientIp}";
        $requestCount = Cache::get($cacheKey, 0);

        if ($requestCount > 100) { // Max 100 requests per minute per IP
            return response()->json(['error' => 'Rate limit exceeded'], 429);
        }

        Cache::put($cacheKey, $requestCount + 1, now()->addMinute());

        // Additional security: Only allow paths that look like valid family cloud paths
        if (!$this->isValidPath($path)) {
            return response()->json(['error' => 'Invalid path'], 403);
        }

        try {
            // Get expiration time from site settings
            $settingsService = app(\App\Services\SettingsService::class);
            $longExpiration = $settingsService->getSiteSetting('signed_url_long_expiration', 525600);
            $shortExpiration = $settingsService->getSiteSetting('signed_url_short_expiration', 15);

            // AWS/Wasabi maximum allowed expiration is 7 days (10080 minutes)
            $maxAllowedExpiration = 10080; // 7 days in minutes

            $expiration = $type === 'long'
                ? min($longExpiration, $maxAllowedExpiration)
                : min($shortExpiration, $maxAllowedExpiration);

            // Check if file exists in Wasabi
            $wasabiDisk = Storage::disk('wasabi');

            if (!$wasabiDisk->exists($path)) {
                // Try to find the file in alternative locations
                $path = $this->findAlternativePath($path);

                if (!$path || !$wasabiDisk->exists($path)) {
                    return response()->json(['error' => 'File not found'], 404);
                }
            }

            // Generate the signed URL
            $url = $wasabiDisk->temporaryUrl($path, now()->addMinutes($expiration));

            // Return JSON response for AJAX calls
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['url' => $url]);
            }

            // Return redirect for direct browser access
            return redirect()->to($url);

        } catch (\Exception $e) {
            \Log::error('Error generating signed URL: ' . $e->getMessage(), [
                'path' => $path,
                'type' => $type,
                'ip' => $clientIp
            ]);

            return response()->json(['error' => 'Failed to generate signed URL'], 500);
        }
    }

    /**
     * Check if the path is a valid family cloud path
     *
     * @param string $path
     * @return bool
     */
    private function isValidPath($path)
    {
        // Only allow paths that start with familycloud/ or are known valid patterns
        $validPrefixes = [
            'familycloud/family/galleries/',
            'familycloud/family/photos/',
            'familycloud/family/files/',
            'familycloud/user/',
            'photos/', // Legacy paths
            'galleries/', // Legacy paths
            'files/', // Legacy paths
        ];

        foreach ($validPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Try to find the file in alternative locations
     *
     * @param string $path
     * @return string|null
     */
    private function findAlternativePath($path)
    {
        // First check if this is a photo path and try to find the correct gallery
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
                return $newPath;
            }
        }
        // Check if this is a gallery cover image
        else if (str_contains($path, 'cover-image/')) {
            $filename = basename($path);

            // Try to find the gallery this cover belongs to
            $gallery = Gallery::where('cover_image', 'like', "%{$filename}")->first();

            if ($gallery) {
                $newPath = "familycloud/family/galleries/{$gallery->slug}/cover-image/{$filename}";
                $gallery->cover_image = $newPath;
                $gallery->save();
                return $newPath;
            }
        }

        return null;
    }
}
