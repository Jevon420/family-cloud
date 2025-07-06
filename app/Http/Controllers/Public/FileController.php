<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\File;

class FileController extends Controller
{
    public function index()
    {
        $files = File::public()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('public.files.index', compact('files'));
    }

    public function show($id)
    {
        $file = File::where('id', $id)
            ->public()
            ->firstOrFail();

        return view('public.files.show', compact('file'));
    }

    public function download($id)
    {
        $file = File::where('id', $id)
            ->public()
            ->firstOrFail();

        // Log the download
        // You can implement download logging here if needed

        return response()->download(storage_path('app/' . $file->path), $file->name);
    }
}
