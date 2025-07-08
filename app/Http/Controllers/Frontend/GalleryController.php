<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Photo;
use App\Models\SharedMedia;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $galleries = Gallery::where('visibility', 'public')
            ->with('visibility')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $sharedGalleries = collect();
        if (auth()->check()) {
            $sharedGalleries = Gallery::sharedWithUser(auth()->id())
                ->with(['visibility', 'sharedMedia' => function($query) {
                    $query->where('shared_with', auth()->id());
                }])
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('public.galleries.partials.gallery-grid', compact('galleries', 'sharedGalleries'))->render(),
                'hasMorePages' => $galleries->hasMorePages() || $sharedGalleries->hasMorePages()
            ]);
        }

        return view('public.galleries.index', compact('galleries', 'sharedGalleries'));
    }

    public function show(Request $request, $slug)
    {
        $gallery = Gallery::where('slug', $slug)
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

        $photos = $gallery->photos()
            ->whereHas('visibility', function($q) {
                $q->where('visibility', 'public');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('public.galleries.partials.photo-grid', compact('photos', 'gallery'))->render(),
                'hasMorePages' => $photos->hasMorePages()
            ]);
        }

        return view('public.galleries.show', compact('gallery', 'photos'));
    }
}
