<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'about')->first();

        if (!$page) {
            $page = new Page();
        }

        $teamMembers = DB::table('our_team')->get();

        return view('about.index', compact('page', 'teamMembers'));
    }
}
