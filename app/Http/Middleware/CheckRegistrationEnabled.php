<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SettingsService;

class CheckRegistrationEnabled
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function handle(Request $request, Closure $next)
    {
        if (!$this->settingsService->isRegistrationEnabled()) {
            return redirect()->route('login')->withErrors([
                'registration' => 'User registration is currently disabled.'
            ]);
        }

        return $next($request);
    }
}
