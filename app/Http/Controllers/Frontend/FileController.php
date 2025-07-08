<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\SharedMedia;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $files = File::where('visibility', 'public')
            ->with('visibility')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $sharedFiles = collect();
        if (auth()->check()) {
            $sharedFiles = File::sharedWithUser(auth()->id())
                ->with(['visibility', 'sharedMedia' => function($query) {
                    $query->where('shared_with', auth()->id());
                }])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('public.files.partials.file-list', compact('files', 'sharedFiles'))->render(),
                'hasMorePages' => $files->hasMorePages() || $sharedFiles->hasMorePages()
            ]);
        }

        return view('public.files.index', compact('files', 'sharedFiles'));
    }

    public function show($id)
    {
        $file = File::where('id', $id)
            ->where(function($query) {
                $query->whereHas('visibility', function($q) {
                    $q->where('visibility', 'public');
                });

                if (auth()->check()) {
                    $query->orWhere(function($q) {
                        $q->whereHas('sharedMedia', function($sq) {
                            $sq->where('shared_with', auth()->id())
                              ->where(function ($expireQuery) {
                                  $expireQuery->whereNull('expires_at')
                                            ->orWhere('expires_at', '>', now());
                              });
                        });
                    });
                }
            })
            ->with(['visibility', 'sharedMedia'])
            ->firstOrFail();

        return view('public.files.show', compact('file'));
    }

    public function download($id)
    {
        $file = File::where('id', $id)
            ->where(function($query) {
                $query->whereHas('visibility', function($q) {
                    $q->where('visibility', 'public');
                });

                if (auth()->check()) {
                    $query->orWhere(function($q) {
                        $q->whereHas('sharedMedia', function($sq) {
                            $sq->where('shared_with', auth()->id())
                              ->where(function ($expireQuery) {
                                  $expireQuery->whereNull('expires_at')
                                            ->orWhere('expires_at', '>', now());
                              });
                        });
                    });
                }
            })
            ->with(['visibility', 'sharedMedia'])
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
