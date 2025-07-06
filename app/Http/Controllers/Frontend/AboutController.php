<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class AboutController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'about')->first();

        if (!$page) {
            $page = new Page();
        }

        return view('about.index', compact('page'));
    }
}
