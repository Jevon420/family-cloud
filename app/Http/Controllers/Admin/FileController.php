<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Services\StorageManagementService;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin|Developer']);
    }

    /**
     * Display a listing of the files.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = File::with(['user', 'folder']);
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
        $files = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->all());
        return view('admin.files.index', compact('files'));
    }

    /**
     * Show the form for creating a new file.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::all();
        $folders = Folder::all();
        $tags = Tag::all();

        return view('admin.files.create', compact('users', 'folders', 'tags'));
    }

    /**
     * Store a newly created file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'folder_id' => 'nullable|exists:folders,id',
            'visibility' => 'required|in:public,private,shared',
            'file' => 'required|file|max:51200', // 50MB max
            'tags' => 'nullable|array',
        ]);

        $data = $request->except('file', 'tags');
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $folderSlug = $request->folder_id ? Folder::find($request->folder_id)->slug : 'uncategorized';
            $filename = time() . '_' . $file->getClientOriginalName();

            // Use the uploaded file name if no name is provided
            if (empty($data['name'])) {
                $data['name'] = $file->getClientOriginalName();
            }

            $data['slug'] = Str::slug($data['name']);

            // Store file in folder-specific directory
            $path = $file->storeAs("files/{$folderSlug}", $filename, 'public');
            $data['file_path'] = $path;

            // Set mime type and file size
            $data['mime_type'] = $file->getMimeType();
            $data['file_size'] = $file->getSize();

            // Create thumbnail for images
            if (strpos($file->getMimeType(), 'image/') === 0) {
                try {
                    $thumbnail = Image::make($file)->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode();

                    $thumbnailPath = "files/{$folderSlug}/thumbnails/{$filename}";
                    Storage::disk('public')->put($thumbnailPath, $thumbnail);
                    $data['thumbnail_path'] = $thumbnailPath;
                } catch (\Exception $e) {
                    // If thumbnail creation fails, continue without it
                }
            }
        }

        $file = File::create($data);

        // Create a visibility record for the file
        $file->visibility()->create([
            'visibility' => $request->visibility,
            'created_by' => auth()->id(),
        ]);

        if ($request->has('tags')) {
            $file->tags()->attach($request->tags);
        }

        $storageService = app(StorageManagementService::class);
        $storageService->calculateUserStorageUsage($file->user_id);
        $storageService->updateUserStorage($file->user);

        return redirect()->route('admin.files.show', $file)
            ->with('success', 'File uploaded successfully.');
    }

    /**
     * Display the specified file.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\View\View
     */
    public function show(File $file)
    {
        $file->load(['user', 'folder', 'tags', 'creator']);

        return view('admin.files.show', compact('file'));
    }

    /**
     * Show the form for editing the specified file.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\View\View
     */
    public function edit(File $file)
    {
        $users = User::all();
        $folders = Folder::all();
        $tags = Tag::all();
        $selectedTags = $file->tags->pluck('id')->toArray();

        return view('admin.files.edit', compact('file', 'users', 'folders', 'tags', 'selectedTags'));
    }

    /**
     * Update the specified file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, File $file)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'folder_id' => 'nullable|exists:folders,id',
            'visibility' => 'required|in:public,private,shared',
            'file' => 'nullable|file|max:51200', // 50MB max
            'tags' => 'nullable|array',
        ]);

        $data = $request->except('file', 'tags');
        $data['slug'] = Str::slug($request->name);
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('file')) {
            // Delete old files if they exist
            if ($file->file_path) {
                Storage::disk('public')->delete($file->file_path);
            }

            if ($file->thumbnail_path) {
                Storage::disk('public')->delete($file->thumbnail_path);
            }

            $uploadedFile = $request->file('file');
            $folderSlug = $request->folder_id ? Folder::find($request->folder_id)->slug : 'uncategorized';
            $filename = time() . '_' . $uploadedFile->getClientOriginalName();

            // Store file in folder-specific directory
            $path = $uploadedFile->storeAs("files/{$folderSlug}", $filename, 'public');
            $data['file_path'] = $path;

            // Set mime type and file size
            $data['mime_type'] = $uploadedFile->getMimeType();
            $data['file_size'] = $uploadedFile->getSize();

            // Create thumbnail for images
            if (strpos($uploadedFile->getMimeType(), 'image/') === 0) {
                try {
                    $thumbnail = Image::make($uploadedFile)->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode();

                    $thumbnailPath = "files/{$folderSlug}/thumbnails/{$filename}";
                    Storage::disk('public')->put($thumbnailPath, $thumbnail);
                    $data['thumbnail_path'] = $thumbnailPath;
                } catch (\Exception $e) {
                    // If thumbnail creation fails, continue without it
                }
            }
        }

        $file->update($data);

        // Sync tags
        if ($request->has('tags')) {
            $file->tags()->sync($request->tags);
        } else {
            $file->tags()->detach();
        }

        $storageService = app(StorageManagementService::class);
        $storageService->calculateUserStorageUsage($file->user_id);
        $storageService->updateUserStorage($file->user()->first());

        return redirect()->route('admin.files.show', $file)
            ->with('success', 'File updated successfully.');
    }

    /**
     * Download the specified file.
     *
     * @param  \App\Models\File  $file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(File $file)
    {
        // Record download
        $file->downloads()->create([
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'created_by' => auth()->id()
        ]);

        return response()->streamDownload(function () use ($file) {
            echo Storage::disk('public')->get($file->file_path);
        }, $file->name);
    }

    /**
     * Remove the specified file from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(File $file)
    {
        // Set deleted_by
        $file->deleted_by = auth()->id();
        $file->save();

        // Delete the file (soft delete)
        $file->delete();

        $storageService = app(StorageManagementService::class);
        $storageService->calculateUserStorageUsage($file->user_id);
        $storageService->updateUserStorage($file->user()->first());

        return redirect()->route('admin.files.index')
            ->with('success', 'File deleted successfully.');
    }
}
