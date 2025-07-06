<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Folder;

class FolderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $folders = Folder::where('is_shared', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->paginate(20);

        return view('shared.folders.index', compact('folders'));
    }

    public function show($id)
    {
        $folder = Folder::where('id', $id)
            ->where('is_shared', true)
            ->firstOrFail();

        $subfolders = $folder->children()
            ->where('is_shared', true)
            ->orderBy('name')
            ->get();

        $files = $folder->files()
            ->where('is_shared', true)
            ->orderBy('name')
            ->get();

        return view('shared.folders.show', compact('folder', 'subfolders', 'files'));
    }
}
