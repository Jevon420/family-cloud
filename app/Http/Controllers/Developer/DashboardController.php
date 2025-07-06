<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\File;
use App\Models\Photo;
use App\Models\Gallery;
use App\Models\Folder;

class DashboardController extends Controller
{
    public function index()
    {
        return view('developer.dashboard', [
            'userCount' => User::count(),
            'fileCount' => File::count(),
            'photoCount' => Photo::count(),
            'galleryCount' => Gallery::count(),
            'folderCount' => Folder::count(),
        ]);
    }
}
