<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's galleries.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $galleries = $user->galleries()->withCount('photos')->latest()->paginate(12);

        // Add signed URLs for gallery cover images
        $galleries->getCollection()->transform(function ($gallery) {
            if ($gallery->cover_image) {
                $gallery->signed_cover_url = route('admin.storage.signedUrl', ['path' => $gallery->cover_image, 'type' => 'long']);
            }
            return $gallery;
        });

        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';
        $galleryLayout = $this->getUserSetting('gallery_layout', 'grid');

        return view('family.galleries.index', compact('galleries', 'theme', 'darkMode', 'galleryLayout'));
    }

    /**
     * Show the form for creating a new gallery.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';

        return view('family.galleries.create', compact('theme', 'darkMode'));
    }

    /**
     * Store a newly created gallery in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $gallery = new Gallery();
        $gallery->user_id = Auth::id();
        $gallery->name = $validated['title'];
        $gallery->slug = Str::slug($validated['title']) . '-' . Str::random(5);
        $gallery->description = $validated['description'] ?? null;

        if ($request->hasFile('cover_image')) {
            $gallerySlug = $gallery->slug;
            $coverImagePath = "familycloud/family/galleries/{$gallerySlug}/cover-image/" . $request->file('cover_image')->getClientOriginalName();
            $request->file('cover_image')->storeAs('', $coverImagePath, 'wasabi');
            $gallery->cover_image = $coverImagePath;
        }

        $gallery->created_by = Auth::id();
        $gallery->updated_by = Auth::id();
        $gallery->save();

        // Create a visibility record for the gallery (default to private)
        $gallery->visibility()->create([
            'visibility' => 'private',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('family.galleries.show', $gallery->slug)
            ->with('success', 'Gallery created successfully.');
    }

    /**
     * Display the specified gallery.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $gallery = Gallery::where('slug', $slug)->firstOrFail();

        // Check if the user is authorized to view this gallery
        $this->authorize('view', $gallery);

        // Add signed URL for gallery cover image
        if ($gallery->cover_image) {
            $gallery->signed_cover_url = route('admin.storage.signedUrl', ['path' => $gallery->cover_image, 'type' => 'long']);
        }

        // Handle search and filter
        $query = $gallery->photos();

        if (request()->has('search')) {
            $search = request('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if (request()->has('sort') && !empty(request('sort'))) {
            $sort = request('sort');
            $sortOrder = request('sort_order', 'asc');
            $query->orderBy($sort, $sortOrder);
        } else {
            $query->latest();
        }

        $photos = $query->paginate(24);        // Add signed URLs for all photos
        $photos->getCollection()->transform(function ($photo) use ($gallery) {
            // Always get the current gallery slug
            $filename = basename($photo->file_path);
            $photo->file_path = "familycloud/family/galleries/{$gallery->slug}/photos/{$filename}";

            // Similarly fix the thumbnail path
            if ($photo->thumbnail_path) {
                $thumbnailFilename = basename($photo->thumbnail_path);
                $photo->thumbnail_path = "familycloud/family/galleries/{$gallery->slug}/photos/thumbnails/{$thumbnailFilename}";
                $photo->save();
            } else {
                // If no thumbnail path, set it to the same as file path
                $photo->thumbnail_path = $photo->file_path;
                $photo->save();
            }

            $photo->signed_url = route('admin.storage.signedUrl', ['path' => $photo->file_path, 'type' => 'long']);
            $photo->signed_thumbnail_url = route('admin.storage.signedUrl', ['path' => $photo->thumbnail_path, 'type' => 'long']);
            return $photo;
        });

        // Handle AJAX request
        if (request()->ajax()) {
            $html = view('family.galleries.partials.photos', compact('photos'))->render();
            return response()->json(['html' => $html]);
        }

        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';
        $galleryLayout = $this->getUserSetting('gallery_layout', 'grid');

        return view('family.galleries.show', compact('gallery', 'photos', 'theme', 'darkMode', 'galleryLayout'));
    }

    /**
     * Upload photos to a gallery.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadPhotos(Request $request, $slug)
    {
        $gallery = Gallery::where('slug', $slug)->firstOrFail();

        // Check if the user is authorized to upload to this gallery
        $this->authorize('update', $gallery);

        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'required|image|max:5120', // 5MB per photo
        ]);

        foreach ($request->file('photos') as $photoFile) {
            $filename = $photoFile->getClientOriginalName();
            $photoPath = "familycloud/family/galleries/{$gallery->slug}/photos/{$filename}";
            $thumbnailPath = "familycloud/family/galleries/{$gallery->slug}/photos/thumbnails/{$filename}";

            // Store original photo in Wasabi
            $photoFile->storeAs('', $photoPath, 'wasabi');

            // Create and store thumbnail in Wasabi
            try {
                $thumbnail = \Intervention\Image\Facades\Image::make($photoFile)
                    ->fit(400, 400, function ($constraint) {
                        $constraint->upsize();
                    })
                    ->encode();

                Storage::disk('wasabi')->put($thumbnailPath, $thumbnail->__toString());
            } catch (\Exception $e) {
                // If thumbnail creation fails, use original as thumbnail
                $thumbnailPath = $photoPath;
            }

            $photo = new Photo();
            $photo->user_id = Auth::id();
            $photo->gallery_id = $gallery->id;
            $photo->slug = Str::slug(pathinfo($filename, PATHINFO_FILENAME), '-') . '-' . Str::random(5);
            $photo->name = pathinfo($filename, PATHINFO_FILENAME);
            $photo->file_path = $photoPath;
            $photo->thumbnail_path = $thumbnailPath;
            $photo->mime_type = $photoFile->getClientMimeType();
            $photo->file_size = $photoFile->getSize();
            $photo->created_by = Auth::id();
            $photo->updated_by = Auth::id();
            $photo->save();

            // Create a visibility record for the photo (inherit from gallery or default to private)
            $galleryVisibility = $gallery->visibility ? $gallery->visibility->visibility : 'private';
            $photo->visibility()->create([
                'visibility' => $galleryVisibility,
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->route('family.galleries.show', $gallery->slug)
            ->with('success', count($request->file('photos')) . ' photos uploaded successfully.');
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
