<?php

namespace App\Http\Controllers\Public;

use App\Models\Page;
use Illuminate\Http\Request;

class CookiePolicyController extends Controller
{
    public function show()
    {
        $page = Page::where('slug', 'cookie-policy')->firstOrFail();
        return view('pages.cookie-policy', compact('page'));
    }
}
