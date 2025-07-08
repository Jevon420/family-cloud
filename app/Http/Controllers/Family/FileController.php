<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's files.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $folderId = $request->query('folder_id');

        $query = $user->files();

        if ($folderId) {
            $query->where('folder_id', $folderId);
            $currentFolder = Folder::findOrFail($folderId);
        } else {
            $query->whereNull('folder_id');
            $currentFolder = null;
        }

        $files = $query->latest()->paginate(20);
        $folders = $user->folders()->when($folderId, function($query) use ($folderId) {
            return $query->where('parent_id', $folderId);
        }, function($query) {
            return $query->whereNull('parent_id');
        })->get();

        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';

        return view('family.files.index', compact('files', 'folders', 'currentFolder', 'theme', 'darkMode'));
    }

    /**
     * Display the specified file.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $file = File::findOrFail($id);

        // Check if the user is authorized to view this file
        $this->authorize('view', $file);

        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';

        return view('family.files.show', compact('file', 'theme', 'darkMode'));
    }

    /**
     * Show the form for creating a new file upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $folderId = $request->query('folder_id');
        $folders = Auth::user()->folders()->get();

        // User settings for view preferences
        $theme = $this->getUserSetting('theme', 'light');
        $darkMode = $this->getUserSetting('dark_mode', 'false') === 'true';

        return view('family.files.create', compact('folders', 'folderId', 'theme', 'darkMode'));
    }

    /**
     * Store a newly created file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $file = $request->file('file');
        $folderSlug = $validated['folder_id'] ? Folder::find($validated['folder_id'])->slug : 'uncategorized';
        $path = $file->store("files/{$folderSlug}", 'public');

        $newFile = new File();
        $newFile->user_id = Auth::id();
        $newFile->name = $validated['title'];
        $newFile->slug = Str::slug($validated['title']) . '-' . Str::random(5);
        $newFile->description = $validated['description'] ?? null;
        $newFile->folder_id = $validated['folder_id'] ?? null;
        //$newFile->filename = $file->getClientOriginalName();
        $newFile->file_path = $path;
        $newFile->mime_type = $file->getClientMimeType();
        $newFile->file_size = $file->getSize();
        $newFile->created_by = Auth::id();
        $newFile->updated_by = Auth::id();
        $newFile->save();

        // Create a visibility record for the file (default to private)
        // If this file belongs to a folder, inherit the folder's visibility
        $visibility = 'private';
        if ($newFile->folder_id && $newFile->folder->visibility) {
            $visibility = $newFile->folder->visibility->visibility;
        }

        $newFile->visibility()->create([
            'visibility' => $visibility,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('family.files.show', $newFile->id)
            ->with('success', 'File uploaded successfully.');
    }

    /**
     * Download a file.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($id)
    {
        $file = File::findOrFail($id);

        // Check if the user is authorized to download this file
        $this->authorize('view', $file);

        // Log the download
        $file->downloads()->create([
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return Storage::disk('public')->download($file->file_path, $file->filename);
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
