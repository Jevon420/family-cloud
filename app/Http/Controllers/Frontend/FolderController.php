<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        $folders = Folder::where('is_public', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('public.folders.partials.folder-list', compact('folders'))->render(),
                'hasMorePages' => $folders->hasMorePages()
            ]);
        }

        return view('public.folders.index', compact('folders'));
    }

    public function show(Request $request, $id)
    {
        $folder = Folder::where('id', $id)
            ->where('is_public', true)
            ->firstOrFail();

        $subfolders = $folder->children()
            ->where('is_public', true)
            ->orderBy('name')
            ->get();

        $files = $folder->files()
            ->where('is_public', true)
            ->orderBy('name')
            ->paginate(20);

        if ($request->ajax() && $request->has('files_only')) {
            return response()->json([
                'html' => view('public.folders.partials.file-list', compact('files', 'folder'))->render(),
                'hasMorePages' => $files->hasMorePages()
            ]);
        }

        return view('public.folders.show', compact('folder', 'subfolders', 'files'));
    }
}
