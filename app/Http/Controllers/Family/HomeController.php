<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\Page;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:family');
    }

    public function index()
    {
        $page = Page::with(['contents', 'images'])->where('slug', 'family-home')->first();

        return view('family.home', compact('page'));
    }
}
