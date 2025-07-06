<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $roles = $user ? $user->roles()->pluck('name') : collect([]);

        return response()->json([
            'user' => $user,
            'roles' => $roles,
            'has_developer_role' => $user ? $user->hasRole('Developer') : false,
            'has_admin_role' => $user ? $user->hasRole('Admin') : false,
            'has_family_role' => $user ? $user->hasRole('Family') : false,
            'auth_check' => Auth::check(),
            'session_id' => session()->getId(),
        ]);
    }
}
