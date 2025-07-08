<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's folders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $parentId = $request->query('parent_id');

        $query = $user->folders();

        if ($parentId) {
            $query->where('parent_id', $parentId);
            $parentFolder = Folder::findOrFail($parentId);
        } else {
            $query->whereNull('parent_id');
            $parentFolder = null;
        }

        $folders = $query->get();

        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';

        return view('family.folders.index', compact('folders', 'parentFolder', 'theme', 'darkMode'));
    }

    /**
     * Show the form for creating a new folder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $parentId = $request->query('parent_id');
        $folders = Auth::user()->folders()->get();

        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';

        return view('family.folders.create', compact('folders', 'parentId', 'theme', 'darkMode'));
    }

    /**
     * Store a newly created folder in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        $folder = new Folder();
        $folder->user_id = Auth::id();
        $folder->name = $validated['name'];
        $folder->slug = Str::slug($validated['name']) . '-' . Str::random(5);
        $folder->description = $validated['description'] ?? null;
        $folder->parent_id = $validated['parent_id'] ?? null;
        $folder->created_by = Auth::id();
        $folder->updated_by = Auth::id();
        $folder->save();

        // Create a visibility record for the folder (default to private)
        // If this folder has a parent, inherit the parent's visibility
        $visibility = 'private';
        if ($folder->parent_id && $folder->parent->visibility) {
            $visibility = $folder->parent->visibility->visibility;
        }

        $folder->visibility()->create([
            'visibility' => $visibility,
            'created_by' => Auth::id(),
        ]);

        $folderSlug = Str::slug($validated['name']);
        Storage::disk('public')->makeDirectory("files/folder/{$folderSlug}");

        return redirect()->route('family.folders.show', $folder->id)
            ->with('success', 'Folder created successfully.');
    }

    /**
     * Display the specified folder.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $folder = Folder::with(['files', 'subfolders'])->findOrFail($id);

        // Check if the user is authorized to view this folder
        $this->authorize('view', $folder);

        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';

        return view('family.folders.show', compact('folder', 'theme', 'darkMode'));
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
