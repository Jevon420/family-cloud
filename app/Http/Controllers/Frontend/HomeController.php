<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class HomeController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'home')->first();

        if (!$page) {
            $page = new Page();
        }

        return view('public.home', compact('page'));
    }
}
