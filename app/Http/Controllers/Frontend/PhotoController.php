<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\SharedMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $photos = Photo::where('visibility', 'public')
            ->with('visibility')
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        // Add signed URLs for each photo
        $photos->getCollection()->transform(function ($photo) {
            $photo->signed_url = route('admin.storage.signedUrl', ['path' => $photo->file_path, 'type' => 'long']);
            $photo->signed_thumbnail_url = route('admin.storage.signedUrl', ['path' => $photo->thumbnail_path ?? $photo->file_path, 'type' => 'long']);
            return $photo;
        });

        $sharedPhotos = collect();
        if (auth()->check()) {
            $sharedPhotos = Photo::sharedWithUser(auth()->id())
                ->with(['visibility', 'sharedMedia' => function($query) {
                    $query->where('shared_with', auth()->id());
                }])
                ->orderBy('created_at', 'desc')
                ->paginate(24);

            // Add signed URLs for each shared photo
            $sharedPhotos->getCollection()->transform(function ($photo) {
                $photo->signed_url = route('admin.storage.signedUrl', ['path' => $photo->file_path, 'type' => 'long']);
                $photo->signed_thumbnail_url = route('admin.storage.signedUrl', ['path' => $photo->thumbnail_path ?? $photo->file_path, 'type' => 'long']);
                return $photo;
            });
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('public.photos.partials.photo-grid', compact('photos', 'sharedPhotos'))->render(),
                'hasMorePages' => $photos->hasMorePages() || $sharedPhotos->hasMorePages()
            ]);
        }

        return view('public.photos.index', compact('photos', 'sharedPhotos'));
    }

    public function show(Request $request, $id)
    {
        $photo = Photo::where('id', $id)
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

        // Get next and previous photos
        $nextPhoto = Photo::public()
            ->where('created_at', '<', $photo->created_at)
            ->orderBy('created_at', 'desc')
            ->first();

        $prevPhoto = Photo::public()
            ->where('created_at', '>', $photo->created_at)
            ->orderBy('created_at', 'asc')
            ->first();

        // Add signed URLs for Wasabi storage
        $photo->signed_url = route('admin.storage.signedUrl', ['path' => $photo->file_path, 'type' => 'long']);
        $photo->signed_thumbnail_url = route('admin.storage.signedUrl', ['path' => $photo->thumbnail_path ?? $photo->file_path, 'type' => 'long']);

        if ($nextPhoto) {
            $nextPhoto->signed_url = route('admin.storage.signedUrl', ['path' => $nextPhoto->file_path, 'type' => 'long']);
            $nextPhoto->signed_thumbnail_url = route('admin.storage.signedUrl', ['path' => $nextPhoto->thumbnail_path ?? $nextPhoto->file_path, 'type' => 'long']);
        }

        if ($prevPhoto) {
            $prevPhoto->signed_url = route('admin.storage.signedUrl', ['path' => $prevPhoto->file_path, 'type' => 'long']);
            $prevPhoto->signed_thumbnail_url = route('admin.storage.signedUrl', ['path' => $prevPhoto->thumbnail_path ?? $prevPhoto->file_path, 'type' => 'long']);
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('public.photos.partials.photo-detail', compact('photo', 'nextPhoto', 'prevPhoto'))->render(),
                'id' => $photo->id,
                'name' => $photo->name,
                'nextId' => $nextPhoto ? $nextPhoto->id : null,
                'prevId' => $prevPhoto ? $prevPhoto->id : null
            ]);
        }

        return view('public.photos.show', compact('photo', 'nextPhoto', 'prevPhoto'));
    }
}
