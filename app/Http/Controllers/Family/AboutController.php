<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\Page;

class AboutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page = Page::with(['contents', 'images'])->where('slug', 'family-about')->first();

        return view('family.about.index', compact('page'));
    }
}
