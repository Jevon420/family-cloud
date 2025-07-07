<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSettingsAccess
{
    public function handle(Request $request, Closure $next, $accessLevel = null)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Developers have access to everything
        if ($user->hasRole('Developer')) {
            return $next($request);
        }

        // Global Admins have access to most settings
        if ($user->hasRole('Global Admin')) {
            // Restrict access to developer-only settings
            if ($accessLevel === 'developer') {
                abort(403, 'Unauthorized access to developer settings.');
            }
            return $next($request);
        }

        // Regular Admins have limited access
        if ($user->hasRole('Admin')) {
            // Restrict access to global admin and developer settings
            if (in_array($accessLevel, ['developer', 'global_admin'])) {
                abort(403, 'Unauthorized access to administrative settings.');
            }
            return $next($request);
        }

        // No access for other roles
        abort(403, 'Unauthorized access to settings.');
    }
}
