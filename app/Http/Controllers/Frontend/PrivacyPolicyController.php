<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function show()
    {
        $page = Page::where('slug', 'privacy-policy')->firstOrFail();
        return view('privacy-policy.index', compact('page'));
    }
}
