<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $files = File::public()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('public.files.partials.file-list', compact('files'))->render(),
                'hasMorePages' => $files->hasMorePages()
            ]);
        }

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
        $file->downloads()->create([
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Determine file path (this depends on your storage setup)
        $filePath = storage_path('app/' . $file->file_path);

        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        // Return file download response
        return response()->download($filePath, $file->name);
    }
}
