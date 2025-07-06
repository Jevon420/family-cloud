<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\Page;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page = Page::with(['contents', 'images'])->where('slug', 'family-home')->first();

        // Create a default page object if none exists
        if (!$page) {
            $page = new Page();
            $page->title = 'Family Dashboard';
            $page->slug = 'family-home';
        }

        return view('family.home', compact('page'));
    }
}
