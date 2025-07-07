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
        $photos = $user->photos()->with('gallery')->latest()->paginate(24);

        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';
        $galleryLayout = $this->getUserSetting('gallery_layout', 'grid');

        return view('family.photos.index', compact('photos', 'theme', 'darkMode', 'galleryLayout'));
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
        $this->authorize('view', $photo);

        // Find next and previous photos in the same gallery
        $nextPhoto = Photo::where('gallery_id', $photo->gallery_id)
            ->where('id', '>', $photo->id)
            ->orderBy('id', 'asc')
            ->first();

        $prevPhoto = Photo::where('gallery_id', $photo->gallery_id)
            ->where('id', '<', $photo->id)
            ->orderBy('id', 'desc')
            ->first();

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

        return Storage::disk('public')->download($photo->file_path, $photo->title . '.jpg');
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
