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
        $folders = Folder::shared()
            ->whereNull('parent_id')
            ->orderBy('name')
            ->paginate(20);

        return view('shared.folders.index', compact('folders'));
    }

    public function show($id)
    {
        $folder = Folder::where('id', $id)
            ->shared()
            ->firstOrFail();

        $subfolders = $folder->children()
            ->shared()
            ->orderBy('name')
            ->get();

        $files = $folder->files()
            ->shared()
            ->orderBy('name')
            ->get();

        return view('shared.folders.show', compact('folder', 'subfolders', 'files'));
    }
}
