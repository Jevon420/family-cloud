<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\File;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $files = File::where('is_shared', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('shared.files.index', compact('files'));
    }

    public function show($id)
    {
        $file = File::where('id', $id)
            ->where('is_shared', true)
            ->firstOrFail();

        return view('shared.files.show', compact('file'));
    }

    public function download($id)
    {
        $file = File::where('id', $id)
            ->where('is_shared', true)
            ->firstOrFail();

        // Log the download
        // You can implement download logging here if needed

        return response()->download(storage_path('app/' . $file->path), $file->name);
    }
}
