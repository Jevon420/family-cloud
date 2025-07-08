<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\File;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin|Developer']);
    }

    /**
     * Display a listing of the folders.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Folder::with(['user', 'children', 'files'])->whereNull('parent_id');
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
        $rootFolders = $query->orderBy('name')->paginate(20)->appends($request->all());
        return view('admin.folders.index', compact('rootFolders'));
    }

    /**
     * Show the form for creating a new folder.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::all();
        $parentFolders = Folder::all();
        $tags = Tag::all();

        return view('admin.folders.create', compact('users', 'parentFolders', 'tags'));
    }

    /**
     * Store a newly created folder in storage.
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
            'parent_id' => 'nullable|exists:folders,id',
            'visibility' => 'required|in:public,private,shared',
            'cover_image' => 'nullable|image|max:2048',
            'tags' => 'nullable|array',
        ]);

        $data = $request->except('cover_image', 'tags');
        $data['slug'] = Str::slug($request->name);
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('folder_covers', 'public');
            $data['cover_image'] = $path;
        }

        $folder = Folder::create($data);

        // Create a visibility record for the folder
        $folder->visibility()->create([
            'visibility' => $request->visibility,
            'created_by' => auth()->id(),
        ]);

        if ($request->has('tags')) {
            $folder->tags()->attach($request->tags);
        }

        return redirect()->route('admin.folders.show', $folder)
            ->with('success', 'Folder created successfully.');
    }

    /**
     * Display the specified folder.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\View\View
     */
    public function show(Folder $folder)
    {
        $folder->load(['user', 'parent', 'creator', 'tags']);
        $subfolders = Folder::where('parent_id', $folder->id)->orderBy('name')->get();
        $files = File::where('folder_id', $folder->id)->orderBy('name')->get();

        return view('admin.folders.show', compact('folder', 'subfolders', 'files'));
    }

    /**
     * Show the form for editing the specified folder.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\View\View
     */
    public function edit(Folder $folder)
    {
        $users = User::all();
        $parentFolders = Folder::where('id', '!=', $folder->id)
            ->whereNotIn('id', $this->getChildrenIds($folder))
            ->get();
        $tags = Tag::all();
        $selectedTags = $folder->tags->pluck('id')->toArray();

        return view('admin.folders.edit', compact('folder', 'users', 'parentFolders', 'tags', 'selectedTags'));
    }

    /**
     * Update the specified folder in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'parent_id' => 'nullable|exists:folders,id',
            'visibility' => 'required|in:public,private,shared',
            'cover_image' => 'nullable|image|max:2048',
            'tags' => 'nullable|array',
        ]);

        // Prevent circular references
        if ($request->parent_id) {
            $childrenIds = $this->getChildrenIds($folder);
            if (in_array($request->parent_id, $childrenIds) || $request->parent_id == $folder->id) {
                return redirect()->back()->withErrors(['parent_id' => 'Cannot set a descendant folder as the parent.']);
            }
        }

        $data = $request->except('cover_image', 'tags');
        $data['slug'] = Str::slug($request->name);
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($folder->cover_image) {
                Storage::disk('public')->delete($folder->cover_image);
            }

            $path = $request->file('cover_image')->store('folder_covers', 'public');
            $data['cover_image'] = $path;
        }

        $folder->update($data);

        // Sync tags
        if ($request->has('tags')) {
            $folder->tags()->sync($request->tags);
        } else {
            $folder->tags()->detach();
        }

        return redirect()->route('admin.folders.show', $folder)
            ->with('success', 'Folder updated successfully.');
    }

    /**
     * Remove the specified folder from storage.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Folder $folder)
    {
        // Set deleted_by
        $folder->deleted_by = auth()->id();
        $folder->save();

        // Delete the folder (soft delete)
        $folder->delete();

        // Redirect to parent folder if exists, otherwise to folders index
        if ($folder->parent_id) {
            return redirect()->route('admin.folders.show', $folder->parent_id)
                ->with('success', 'Folder deleted successfully.');
        }

        return redirect()->route('admin.folders.index')
            ->with('success', 'Folder deleted successfully.');
    }

    /**
     * Get all children IDs of a folder (recursive)
     *
     * @param  \App\Models\Folder  $folder
     * @return array
     */
    private function getChildrenIds(Folder $folder)
    {
        $ids = [];

        foreach ($folder->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getChildrenIds($child));
        }

        return $ids;
    }
}
