<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Photo;
use App\Models\SharedMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $galleries = Gallery::where('visibility', 'public')
            ->with('visibility')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Add signed URLs for each gallery cover image
        $galleries->getCollection()->transform(function ($gallery) {
            if ($gallery->cover_image) {
                $gallery->signed_cover_url = route('admin.storage.signedUrl', ['path' => $gallery->cover_image, 'type' => 'long']);
            }
            return $gallery;
        });

        $sharedGalleries = collect();
        if (auth()->check()) {
            $sharedGalleries = Gallery::sharedWithUser(auth()->id())
                ->with(['visibility', 'sharedMedia' => function($query) {
                    $query->where('shared_with', auth()->id());
                }])
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            // Add signed URLs for each shared gallery cover image
            $sharedGalleries->getCollection()->transform(function ($gallery) {
                if ($gallery->cover_image) {
                    $gallery->signed_cover_url = route('admin.storage.signedUrl', ['path' => $gallery->cover_image, 'type' => 'long']);
                }
                return $gallery;
            });
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

        // Add signed URL for gallery cover image
        if ($gallery->cover_image) {
            $gallery->signed_cover_url = route('admin.storage.signedUrl', ['path' => $gallery->cover_image, 'type' => 'long']);
        }

        $photos = $gallery->photos()
            ->whereHas('visibility', function($q) {
                $q->where('visibility', 'public');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Add signed URLs for photos
        $photos->getCollection()->transform(function ($photo) {
            $photo->signed_url = route('admin.storage.signedUrl', ['path' => $photo->file_path, 'type' => 'long']);
            $photo->signed_thumbnail_url = route('admin.storage.signedUrl', ['path' => $photo->thumbnail_path ?? $photo->file_path, 'type' => 'long']);
            return $photo;
        });

        if ($request->ajax()) {
            return response()->json([
                'html' => view('public.galleries.partials.photo-grid', compact('photos', 'gallery'))->render(),
                'hasMorePages' => $photos->hasMorePages()
            ]);
        }

        return view('public.galleries.show', compact('gallery', 'photos'));
    }
}
