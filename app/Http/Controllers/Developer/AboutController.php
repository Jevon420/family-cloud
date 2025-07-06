<?php

namespace App\Http\Controllers\Developer;

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
        $page = Page::with(['contents', 'images'])->where('slug', 'developer-about')->first();

        return view('developer.about.index', compact('page'));
    }
}
