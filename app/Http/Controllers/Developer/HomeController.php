<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Page;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:developer');
    }

    public function index()
    {
        $page = Page::with(['contents', 'images'])->where('slug', 'developer-home')->first();

        return view('developer.home', compact('page'));
    }
}
