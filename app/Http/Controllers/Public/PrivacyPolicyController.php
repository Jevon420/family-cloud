<?php

namespace App\Http\Controllers\Public;

use App\Models\Page;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function show()
    {
        $page = Page::where('slug', 'privacy-policy')->firstOrFail();
        return view('pages.privacy-policy', compact('page'));
    }
}
