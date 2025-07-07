<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class CookiePolicyController extends Controller
{
    public function show()
    {
        $page = Page::where('slug', 'cookie-policy')->firstOrFail();
        return view('cookie-policy.index', compact('page'));
    }
}
