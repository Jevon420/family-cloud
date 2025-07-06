<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;

class HomeController extends Controller
{
    public function index()
    {
        $page = Page::with(['contents', 'images'])->where('slug', 'home')->firstOrFail();

        return view('public.home', compact('page'));
    }
}
