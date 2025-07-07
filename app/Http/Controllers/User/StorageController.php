<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    /**
     * Display user storage information
     */
    public function index()
    {
        return view('user.storage');
    }
}
