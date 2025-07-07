<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SettingsService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

class ApplyGlobalSettings
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function handle(Request $request, Closure $next)
    {
        // Apply maintenance mode
        if ($this->settingsService->isMaintenanceMode() && !(auth()->check() && auth()->user()->hasAnyRole(['Developer', 'Global Admin']))) {
            return response()->view('maintenance', [], 503);
        }

        // Share common settings with all views
        $this->shareSettingsWithViews();

        // Apply dynamic configurations
        $this->applyDynamicConfigurations();

        return $next($request);
    }

    protected function shareSettingsWithViews()
    {
        // Share site settings with all views
        View::share('siteName', $this->settingsService->getSiteSetting('site_name', 'Family Cloud'));
        View::share('siteDescription', $this->settingsService->getSiteSetting('site_description', ''));
        View::share('siteLogo', $this->settingsService->getSiteSetting('site_logo', 'storage/logos/family-cloud-logo.png'));
        View::share('timezone', $this->settingsService->getSiteSetting('timezone', 'UTC'));

        // Share file upload settings
        View::share('maxFileUploadSize', $this->settingsService->getMaxFileUploadSize());
        View::share('maxStoragePerUser', $this->settingsService->getMaxStoragePerUser());
        View::share('allowedFileTypes', $this->settingsService->getAllowedFileTypes());

        // Share registration setting
        View::share('registrationEnabled', $this->settingsService->isRegistrationEnabled());

        // Share all site settings as a collection for easy access
        View::share('globalSettings', $this->settingsService->getAllSiteSettings());
    }

    protected function applyDynamicConfigurations()
    {
        // Apply timezone
        $timezone = $this->settingsService->getSiteSetting('timezone', 'UTC');
        Config::set('app.timezone', $timezone);
        date_default_timezone_set($timezone);

        // Apply mail settings
        $this->applyMailSettings();

        // Apply cache settings
        $this->applyCacheSettings();

        // Apply session settings
        $this->applySessionSettings();
    }

    protected function applyMailSettings()
    {
        $mailDriver = $this->settingsService->getSystemConfiguration('mail_driver');
        $mailHost = $this->settingsService->getSystemConfiguration('mail_host');
        $mailPort = $this->settingsService->getSystemConfiguration('mail_port');
        $mailUsername = $this->settingsService->getSystemConfiguration('mail_username');
        $mailPassword = $this->settingsService->getSystemConfiguration('mail_password');

        if ($mailDriver) {
            Config::set('mail.default', $mailDriver);
            Config::set('mail.mailers.smtp.host', $mailHost);
            Config::set('mail.mailers.smtp.port', $mailPort);
            Config::set('mail.mailers.smtp.username', $mailUsername);
            Config::set('mail.mailers.smtp.password', $mailPassword);
        }
    }

    protected function applyCacheSettings()
    {
        $cacheDriver = $this->settingsService->getSystemConfiguration('cache_driver');
        if ($cacheDriver) {
            Config::set('cache.default', $cacheDriver);
        }
    }

    protected function applySessionSettings()
    {
        $sessionLifetime = $this->settingsService->getSecuritySetting('session_lifetime', 120);
        Config::set('session.lifetime', $sessionLifetime);
    }
}
