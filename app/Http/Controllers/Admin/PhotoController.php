<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Gallery;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Imagick;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin|Developer']);
    }

    /**
     * Display a listing of the photos.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Photo::with(['user', 'gallery']);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%") ;
                  });
            });
        }
        if ($request->filled('visibility')) {
            $query->where('visibility', $request->input('visibility'));
        }
        $photos = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->all());
        return view('admin.photos.index', compact('photos'));
    }

    /**
     * Show the form for creating a new photo.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::all();
        $galleries = Gallery::all();
        $tags = Tag::all();

        return view('admin.photos.create', compact('users', 'galleries', 'tags'));
    }

    /**
     * Store a newly created photo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'gallery_id' => 'required|exists:galleries,id',
            'visibility' => 'required|in:public,private,shared',
            'photo' => 'required|mimes:jpeg,png,jpg,gif,webp,bmp,svg,heic|max:10240', // 10MB max, allow .heic
            'tags' => 'nullable|array',
        ]);

        $data = $request->except('photo', 'tags');
        $data['slug'] = Str::slug($request->name);
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $gallerySlug = Gallery::find($request->gallery_id)->slug;
            $baseFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = time() . '_' . Str::slug($baseFilename);
            $extension = strtolower($image->getClientOriginalExtension() ?? '');
            $photoPath = "familycloud/family/galleries/{$gallerySlug}/photos/{$filename}.webp";

            // Try to convert to webp, fallback to png, else error
            try {
                // Special handling for heic: check if supported
                if ($extension === 'heic') {
                    $supported = false;
                    if (extension_loaded('imagick')) {
                        $imagick = new Imagick();
                        $formats = array_map('strtolower', $imagick->queryFormats());
                        $supported = in_array('heic', $formats);
                    }
                    if (!$supported) {
                        throw new \Exception('HEIC images are not supported by your server. Please install Imagick with HEIC support (libheif).');
                    }
                }
                $img = Image::make($image);
                $img->encode('webp', 90);
                Storage::disk('wasabi')->put($photoPath, $img->__toString());
                $data['file_path'] = $photoPath;
                $data['mime_type'] = 'image/webp';
                $data['file_size'] = strlen($img->__toString());
            } catch (\Exception $e) {
                try {
                    $photoPath = "familycloud/family/galleries/{$gallerySlug}/photos/{$filename}.png";
                    $img = Image::make($image);
                    $img->encode('png', 90);
                    Storage::disk('wasabi')->put($photoPath, $img->__toString());
                    $data['file_path'] = $photoPath;
                    $data['mime_type'] = 'image/png';
                    $data['file_size'] = strlen($img->__toString());
                } catch (\Exception $e2) {
                    $errorMsg = 'Unable to convert file to webp or png.';
                    if ($extension === 'heic') {
                        $errorMsg .= ' Your server may not support HEIC images. Please install Imagick with HEIC/libheif support.';
                    }
                    $errorMsg .= ' [Error: ' . $e->getMessage() . ']';
                    return back()->withErrors(['photo' => $errorMsg]);
                }
            }

            // Create and store thumbnail in Wasabi
            try {
                $thumbnail = Image::make($image)
                    ->fit(300, 300, function ($constraint) {
                        $constraint->upsize();
                    })
                    ->encode('webp', 90);
                $thumbnailPath = "familycloud/family/galleries/{$gallerySlug}/photos/thumbnails/{$filename}.webp";
                Storage::disk('wasabi')->put($thumbnailPath, $thumbnail->__toString());
                Storage::disk('public')->put($thumbnailPath, $thumbnail);
                $data['thumbnail_path'] = $thumbnailPath;
            } catch (\Exception $e) {
                try {
                    $thumbnail = Image::make($image)
                        ->fit(300, 300, function ($constraint) {
                            $constraint->upsize();
                        })
                        ->encode('png', 90);
                    $thumbnailPath = "familycloud/family/galleries/{$gallerySlug}/photos/thumbnails/{$filename}.png";
                    Storage::disk('wasabi')->put($thumbnailPath, $thumbnail->__toString());
                    Storage::disk('public')->put($thumbnailPath, $thumbnail);
                    $data['thumbnail_path'] = $thumbnailPath;
                } catch (\Exception $e2) {
                    return back()->withErrors(['photo' => 'Unable to convert thumbnail to webp or png.']);
                }
            }
        }

        $photo = Photo::create($data);

        // Create a visibility record for the photo
        $photo->visibility()->create([
            'visibility' => $request->visibility,
            'created_by' => auth()->id(),
        ]);

        if ($request->has('tags')) {
            $photo->tags()->attach($request->tags);
        }

        return redirect()->route('admin.photos.show', $photo)
            ->with('success', 'Photo uploaded successfully.');
    }

    /**
     * Display the specified photo.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\View\View
     */
    public function show(Photo $photo)
    {
        $photo->load(['user', 'gallery', 'tags', 'creator']);

        return view('admin.photos.show', compact('photo'));
    }

    /**
     * Show the form for editing the specified photo.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\View\View
     */
    public function edit(Photo $photo)
    {
        $users = User::all();
        $galleries = Gallery::all();
        $tags = Tag::all();
        $selectedTags = $photo->tags->pluck('id')->toArray();

        return view('admin.photos.edit', compact('photo', 'users', 'galleries', 'tags', 'selectedTags'));
    }

    /**
     * Update the specified photo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Photo $photo)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'gallery_id' => 'required|exists:galleries,id',
            'visibility' => 'required|in:public,private,shared',
            'photo' => 'nullable|mimes:jpeg,png,jpg,gif,webp,bmp,svg,heic|max:10240', // 10MB max, allow .heic
            'tags' => 'nullable|array',
        ]);

        $data = $request->except('photo', 'tags');
        $data['slug'] = Str::slug($request->name);
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('photo')) {
            // Delete old files if they exist
            if ($photo->file_path) {
                Storage::disk('public')->delete($photo->file_path);
            }

            if ($photo->thumbnail_path) {
                Storage::disk('public')->delete($photo->thumbnail_path);
            }

            $image = $request->file('photo');
            $gallerySlug = Gallery::find($request->gallery_id)->slug;
            $filename = time() . '_' . $image->getClientOriginalName();

            // Store original image in gallery-specific folder
            $path = $image->storeAs("familycloud/family/galleries/{$gallerySlug}/photos", $filename, 'public');
            $data['file_path'] = $path;

            // Create and store thumbnail in gallery-specific folder
            $thumbnail = Image::make($image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode();

            $thumbnailPath = "familycloud/family/galleries/{$gallerySlug}/photos/thumbnails/{$filename}";
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            $data['thumbnail_path'] = $thumbnailPath;

            // Set mime type and file size
            $data['mime_type'] = $image->getMimeType();
            $data['file_size'] = $image->getSize();
        }

        $photo->update($data);

        // Sync tags
        if ($request->has('tags')) {
            $photo->tags()->sync($request->tags);
        } else {
            $photo->tags()->detach();
        }

        return redirect()->route('admin.photos.show', $photo)
            ->with('success', 'Photo updated successfully.');
    }

    /**
     * Remove the specified photo from storage.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Photo $photo)
    {
        // Set deleted_by
        $photo->deleted_by = auth()->id();
        $photo->save();

        // Delete the photo (soft delete)
        $photo->delete();

        return redirect()->route('admin.photos.index')
            ->with('success', 'Photo deleted successfully.');
    }
}
