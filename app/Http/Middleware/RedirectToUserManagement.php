<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RedirectToUserManagement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if user is admin/developer and there are pending registrations
            if ($user->hasAnyRole(['Global Admin', 'Developer', 'Admin'])) {
                $pendingCount = User::where('status', 'pending')->count();

                if ($pendingCount > 0 && session('redirect_to_user_management')) {
                    session()->forget('redirect_to_user_management');
                    return redirect()->route('admin.settings.users')
                        ->with('info', "You have {$pendingCount} pending user registration(s) that require attention.");
                }
            }
        }

        return $next($request);
    }
}
