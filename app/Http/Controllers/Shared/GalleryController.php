<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Photo;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $galleries = Gallery::where('is_shared', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('shared.galleries.index', compact('galleries'));
    }

    public function show($slug)
    {
        $gallery = Gallery::where('slug', $slug)
            ->where('is_shared', true)
            ->firstOrFail();

        $photos = $gallery->photos()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('shared.galleries.show', compact('gallery', 'photos'));
    }
}
