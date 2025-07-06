<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Folder;

class FolderController extends Controller
{
    public function index()
    {
        $folders = Folder::public()
            ->whereNull('parent_id')
            ->orderBy('name')
            ->paginate(20);

        return view('public.folders.index', compact('folders'));
    }

    public function show($id)
    {
        $folder = Folder::where('id', $id)
            ->public()
            ->firstOrFail();

        $subfolders = $folder->children()
            ->public()
            ->orderBy('name')
            ->get();

        $files = $folder->files()
            ->public()
            ->orderBy('name')
            ->get();

        return view('public.folders.show', compact('folder', 'subfolders', 'files'));
    }
}
