<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class TermsOfServiceController extends Controller
{
    public function show()
    {
        $page = Page::where('slug', 'terms-of-service')->firstOrFail();
        return view('terms-of-service.index', compact('page'));
    }
}
