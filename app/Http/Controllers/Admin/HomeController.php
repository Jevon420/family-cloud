<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $page = Page::with(['contents', 'images'])->where('slug', 'admin-home')->first();

        return view('admin.home', compact('page'));
    }
}
