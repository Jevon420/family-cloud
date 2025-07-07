<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class AboutController extends Controller
{
    public function index()
    {
        $page = Page::with(['contents', 'images'])->where('slug', 'about')->first();

        // Create a default page if none exists
        if (!$page) {
            $page = new Page([
                'name' => 'About',
                'slug' => 'about',
                'meta_description' => 'Learn more about Family Cloud',
                'is_published' => true
            ]);
        }


        return view('about.index', compact('page'));
    }
}
