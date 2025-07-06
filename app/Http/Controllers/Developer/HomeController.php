<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\User;
use App\Models\Gallery;
use App\Models\Photo;
use App\Models\File;
use App\Models\Folder;
use App\Models\LoginActivity;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page = Page::with(['contents', 'images'])->where('slug', 'developer-home')->first();

        // Create a default page object if none exists
        if (!$page) {
            $page = new Page();
            $page->title = 'Developer Dashboard';
            $page->slug = 'developer-home';
            // We'll handle the getContent method call in the view
        }

        $stats = [
            'total_users' => User::count(),
            'total_galleries' => Gallery::count(),
            'total_photos' => Photo::count(),
            'total_files' => File::count(),
            'total_folders' => Folder::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_galleries' => Gallery::latest()->take(5)->get(),
            'recent_logins' => LoginActivity::with('user')->latest()->take(10)->get(),
        ];

        return view('developer.home', compact('page', 'stats'));
    }
}
