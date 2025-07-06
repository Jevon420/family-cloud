<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Photo;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $photos = Photo::where('is_shared', true)
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        return view('shared.photos.index', compact('photos'));
    }

    public function show($id)
    {
        $photo = Photo::where('id', $id)
            ->where('is_shared', true)
            ->firstOrFail();

        return view('shared.photos.show', compact('photo'));
    }
}
