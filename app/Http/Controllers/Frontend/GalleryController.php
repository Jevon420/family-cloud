<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Photo;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $galleries = Gallery::public()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('public.galleries.partials.gallery-grid', compact('galleries'))->render(),
                'hasMorePages' => $galleries->hasMorePages()
            ]);
        }

        return view('public.galleries.index', compact('galleries'));
    }

    public function show(Request $request, $slug)
    {
        $gallery = Gallery::where('slug', $slug)
            ->public()
            ->firstOrFail();

        $photos = $gallery->photos()
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
