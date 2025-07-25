<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin|Developer']);
    }

    /**
     * Display a listing of the galleries.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Gallery::with(['user', 'photos'])
            ->withCount('photos');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    });
            });
        }
        if ($request->filled('visibility')) {
            $query->where('visibility', $request->input('visibility'));
        }
        $galleries = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->all());
        return view('admin.galleries.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new gallery.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $tags = Tag::all();

        return view('admin.galleries.create', compact('users', 'tags'));
    }

    /**
     * Store a newly created gallery in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'visibility' => 'required|in:public,private,shared',
            'cover_image' => 'nullable|mimes:jpeg,png,jpg,gif,webp,bmp,svg,heic|max:10240', // 10MB max, allow .heic
            'tags' => 'nullable|array',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('cover_image')) {
            $gallerySlug = $data['slug'];
            $coverFile = $request->file('cover_image');
            $baseFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
            $coverImagePath = "familycloud/family/galleries/{$gallerySlug}/cover-image/{$baseFilename}.webp";
            try {
                $img = \Intervention\Image\Facades\Image::make($coverFile);
                $img->encode('webp', 90);
                Storage::disk('wasabi')->put($coverImagePath, $img->__toString());
                $data['cover_image'] = $coverImagePath;
            } catch (\Exception $e) {
                try {
                    $coverImagePath = "familycloud/family/galleries/{$gallerySlug}/cover-image/{$baseFilename}.png";
                    $img = \Intervention\Image\Facades\Image::make($coverFile);
                    $img->encode('png', 90);
                    Storage::disk('wasabi')->put($coverImagePath, $img->__toString());
                    $data['cover_image'] = $coverImagePath;
                } catch (\Exception $e2) {
                    return back()->withErrors(['cover_image' => 'Unable to convert cover image to webp or png.']);
                }
            }
        }

        $gallery = Gallery::create($data);

        // Create a visibility record for the gallery
        $gallery->visibility()->create([
            'visibility' => $request->visibility,
            'created_by' => auth()->id(),
        ]);

        if ($request->has('tags')) {
            $gallery->tags()->attach($request->tags);
        }

        return redirect()->route('admin.galleries.show', $gallery)
            ->with('success', 'Gallery created successfully.');
    }

    /**
     * Display the specified gallery.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery)
    {
        $gallery->load(['user', 'photos', 'tags', 'creator']);

        return view('admin.galleries.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified gallery.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery)
    {
        $users = User::all();
        $tags = Tag::all();
        $selectedTags = $gallery->tags->pluck('id')->toArray();

        return view('admin.galleries.edit', compact('gallery', 'users', 'tags', 'selectedTags'));
    }

    /**
     * Update the specified gallery in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'visibility' => 'required|in:public,private,shared',
            'cover_image' => 'nullable|image|max:10240',
            'tags' => 'nullable|array',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($gallery->cover_image) {
                Storage::disk('public')->delete($gallery->cover_image);
            }
            $gallerySlug = $data['slug'];
            $coverFile = $request->file('cover_image');
            $baseFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
            $coverImagePath = "galleries/{$gallerySlug}/cover-image/{$baseFilename}.webp";
            try {
                $img = \Intervention\Image\Facades\Image::make($coverFile);
                $img->encode('webp', 90);
                Storage::disk('wasabi')->put($coverImagePath, $img->__toString());
                $data['cover_image'] = $coverImagePath;
            } catch (\Exception $e) {
                try {
                    $coverImagePath = "galleries/{$gallerySlug}/cover-image/{$baseFilename}.png";
                    $img = \Intervention\Image\Facades\Image::make($coverFile);
                    $img->encode('png', 90);
                    Storage::disk('wasabi')->put($coverImagePath, $img->__toString());
                    $data['cover_image'] = $coverImagePath;
                } catch (\Exception $e2) {
                    return back()->withErrors(['cover_image' => 'Unable to convert cover image to webp or png.']);
                }
            }
        }

        $gallery->update($data);

        // Sync tags
        if ($request->has('tags')) {
            $gallery->tags()->sync($request->tags);
        } else {
            $gallery->tags()->detach();
        }

        return redirect()->route('admin.galleries.show', $gallery)
            ->with('success', 'Gallery updated successfully.');
    }

    /**
     * Remove the specified gallery from storage.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        // Set deleted_by
        $gallery->deleted_by = auth()->id();
        $gallery->save();

        // Delete the gallery (soft delete)
        $gallery->delete();

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery deleted successfully.');
    }
}
