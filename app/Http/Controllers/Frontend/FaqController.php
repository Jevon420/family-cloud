<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class FaqController extends Controller
{
    public function index()
    {
        $page = Page::with(['contents', 'images'])->where('slug', 'faq')->firstOrFail();

        return view('public.faq', compact('page'));
    }
}
