<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // Redirect based on user role
        if (Auth::user()->hasRole('admin')) {
            return redirect()->intended(route('admin.home'));
        } elseif (Auth::user()->hasRole('developer')) {
            return redirect()->intended(route('developer.home'));
        } elseif (Auth::user()->hasRole('family')) {
            return redirect()->intended(route('family.home'));
        }

        return redirect()->intended(config('fortify.home'));
    }
}
