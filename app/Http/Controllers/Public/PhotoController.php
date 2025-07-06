<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $photos = Photo::where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('public.photos.partials.photo-grid', compact('photos'))->render(),
                'hasMorePages' => $photos->hasMorePages()
            ]);
        }

        return view('public.photos.index', compact('photos'));
    }

    public function show(Request $request, $id)
    {
        $photo = Photo::where('id', $id)
            ->where('is_public', true)
            ->firstOrFail();

        // Get next and previous photos
        $nextPhoto = Photo::where('is_public', true)
            ->where('created_at', '<', $photo->created_at)
            ->orderBy('created_at', 'desc')
            ->first();

        $prevPhoto = Photo::where('is_public', true)
            ->where('created_at', '>', $photo->created_at)
            ->orderBy('created_at', 'asc')
            ->first();

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
