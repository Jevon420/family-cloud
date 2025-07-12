<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's photos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $search = request('search');
        $galleryId = request('gallery');
        $sort = request('sort');
        $sortOrder = request('sort_order', 'asc');

        $photosQuery = $user->photos()->with('gallery')->latest();

        if ($search) {
            $photosQuery->where('name', 'like', "%$search%");
        }

        if ($galleryId) {
            $photosQuery->where('gallery_id', $galleryId);
        }

        if ($sort === 'name') {
            $photosQuery->orderBy('name', $sortOrder);
        } elseif ($sort === 'date') {
            $photosQuery->orderBy('created_at', $sortOrder);
        } elseif ($sort === 'type') {
            $photosQuery->orderBy('mime_type', $sortOrder);
        }

        $photos = $photosQuery->paginate(24);        // Add signed URLs for Wasabi storage
        $photos->getCollection()->transform(function ($photo) {
            // Always get the current gallery slug, even if the path looks correct
            $gallerySlug = $photo->gallery->slug ?? 'unknown';
            $filename = basename($photo->file_path);

            // Ensure file path uses the correct gallery slug
            $photo->file_path = "familycloud/family/galleries/{$gallerySlug}/photos/{$filename}";

            // Similarly fix the thumbnail path
            if ($photo->thumbnail_path) {
                $thumbnailFilename = basename($photo->thumbnail_path);
                $photo->thumbnail_path = "familycloud/family/galleries/{$gallerySlug}/photos/thumbnails/{$thumbnailFilename}";
                $photo->save();
            } else {
                // If no thumbnail path, set it to the same as file path
                $photo->thumbnail_path = $photo->file_path;
                $photo->save();
            }

            // Generate signed URLs
            $photo->signed_url = route('admin.storage.signedUrl', ['path' => $photo->file_path, 'type' => 'long']);
            $photo->signed_thumbnail_url = route('admin.storage.signedUrl', ['path' => $photo->thumbnail_path, 'type' => 'long']);
            return $photo;
        });

        $galleries = $user->galleries()->get();

        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';
        $galleryLayout = $this->getUserSetting('gallery_layout', 'grid');

        if (request()->ajax()) {
            $html = view('family.photos.partials.photos', compact('photos', 'galleryLayout', 'darkMode'))->render();
            return response()->json(['html' => $html]);
        }

        return view('family.photos.index', compact('photos', 'theme', 'darkMode', 'galleryLayout', 'galleries'));
    }

    /**
     * Display the specified photo.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $photo = Photo::with('gallery')->findOrFail($id);

        // Check if the user is authorized to view this photo
        $this->authorize('view', $photo);        // Check if the file path follows the expected pattern
        // Always get the current gallery slug, even if the path looks correct
        $gallerySlug = $photo->gallery->slug ?? 'unknown';
        $filename = basename($photo->file_path);

        // Ensure file path uses the correct gallery slug
        $photo->file_path = "familycloud/family/galleries/{$gallerySlug}/photos/{$filename}";

        // Similarly fix the thumbnail path
        if ($photo->thumbnail_path) {
            $thumbnailFilename = basename($photo->thumbnail_path);
            $photo->thumbnail_path = "familycloud/family/galleries/{$gallerySlug}/photos/thumbnails/{$thumbnailFilename}";
            $photo->save();
        } else {
            // If no thumbnail path, set it to the same as file path
            $photo->thumbnail_path = $photo->file_path;
            $photo->save();
        }

        // Add signed URLs for Wasabi storage
        $photo->signed_url = route('admin.storage.signedUrl', ['path' => $photo->file_path, 'type' => 'long']);
        $photo->signed_thumbnail_url = route('admin.storage.signedUrl', ['path' => $photo->thumbnail_path, 'type' => 'long']);

        // Find next and previous photos in the same gallery
        $nextPhoto = Photo::where('gallery_id', $photo->gallery_id)
            ->where('id', '>', $photo->id)
            ->orderBy('id', 'asc')
            ->first();

        $prevPhoto = Photo::where('gallery_id', $photo->gallery_id)
            ->where('id', '<', $photo->id)
            ->orderBy('id', 'desc')
            ->first();

        // Add signed URLs for next and previous photos
        if ($nextPhoto) {
            $nextPhoto->signed_url = route('admin.storage.signedUrl', ['path' => $nextPhoto->file_path, 'type' => 'long']);
            $nextPhoto->signed_thumbnail_url = route('admin.storage.signedUrl', ['path' => $nextPhoto->thumbnail_path ?? $nextPhoto->file_path, 'type' => 'long']);
        }

        if ($prevPhoto) {
            $prevPhoto->signed_url = route('admin.storage.signedUrl', ['path' => $prevPhoto->file_path, 'type' => 'long']);
            $prevPhoto->signed_thumbnail_url = route('admin.storage.signedUrl', ['path' => $prevPhoto->thumbnail_path ?? $prevPhoto->file_path, 'type' => 'long']);
        }

        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';

        return view('family.photos.show', compact('photo', 'nextPhoto', 'prevPhoto', 'theme', 'darkMode'));
    }

    /**
     * Show the form for editing the specified photo.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $photo = Photo::findOrFail($id);

        // Check if the user is authorized to edit this photo
        $this->authorize('update', $photo);

        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';

        return view('family.photos.edit', compact('photo', 'theme', 'darkMode'));
    }

    /**
     * Update the specified photo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $photo = Photo::findOrFail($id);

        // Check if the user is authorized to update this photo
        $this->authorize('update', $photo);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $photo->title = $validated['title'];
        $photo->description = $validated['description'] ?? null;
        $photo->updated_by = Auth::id();
        $photo->save();

        return redirect()->route('family.photos.show', $photo->id)
            ->with('success', 'Photo updated successfully.');
    }

    /**
     * Download a photo.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($id)
    {
        $photo = Photo::findOrFail($id);

        // Check if the user is authorized to download this photo
        $this->authorize('view', $photo);

        return Storage::disk('wasabi')->download($photo->file_path, $photo->title . '.jpg');
    }

    /**
     * Debug method to check photo paths
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function debugPhotoPaths($id)
    {
        $photo = Photo::with('gallery')->findOrFail($id);

        // Check if the user is authorized to view this photo
        $this->authorize('view', $photo);

        $gallerySlug = $photo->gallery->slug ?? 'unknown';
        $filename = basename($photo->file_path);
        $expectedPath = "familycloud/family/galleries/{$gallerySlug}/photos/{$filename}";

        $wasabiDisk = Storage::disk('wasabi');

        return response()->json([
            'photo_id' => $photo->id,
            'gallery_slug' => $gallerySlug,
            'current_file_path' => $photo->file_path,
            'current_thumbnail_path' => $photo->thumbnail_path,
            'expected_file_path' => $expectedPath,
            'file_exists_in_wasabi' => $wasabiDisk->exists($photo->file_path),
            'thumbnail_exists_in_wasabi' => $photo->thumbnail_path ? $wasabiDisk->exists($photo->thumbnail_path) : false,
            'expected_path_exists_in_wasabi' => $wasabiDisk->exists($expectedPath)
        ]);
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
        $setting = \App\Models\UserSetting::where('user_id', Auth::id())
            ->where('key', $key)
            ->first();

        return $setting ? $setting->value : $default;
    }
}
