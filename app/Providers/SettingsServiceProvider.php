<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Apply settings that need to be set early in the application lifecycle
        $this->applyEarlySettings();

        // Share settings with views via view composer
        $this->shareSettingsWithViews();
    }

    protected function applyEarlySettings()
    {
        if (app()->runningInConsole()) {
            return; // Skip for console commands
        }

        try {
            $settings = app(SettingsService::class);

            // Apply timezone
            $timezone = $settings->getSiteSetting('timezone', 'UTC');
            Config::set('app.timezone', $timezone);

            // Apply app name
            $siteName = $settings->getSiteSetting('site_name', 'Family Cloud');
            Config::set('app.name', $siteName);

        } catch (\Exception $e) {
            // Gracefully handle errors (e.g., database not available)
            \Log::warning('Could not apply early settings: ' . $e->getMessage());
        }
    }

    protected function shareSettingsWithViews()
    {
        // Use view composer to share settings with all views
        View::composer('*', function ($view) {
            try {
                $settings = app(SettingsService::class);

                $view->with([
                    'siteName' => $settings->getSiteSetting('site_name', 'Family Cloud'),
                    'siteDescription' => $settings->getSiteSetting('site_description', ''),
                    'siteLogo' => $settings->getSiteSetting('site_logo', 'storage/logos/family-cloud-logo.png'),
                    'maxFileUploadSize' => $settings->getMaxFileUploadSize(),
                    'allowedFileTypes' => $settings->getAllowedFileTypes(),
                    'registrationEnabled' => $settings->isRegistrationEnabled(),
                    'isMaintenanceMode' => $settings->isMaintenanceMode(),
                ]);

            } catch (\Exception $e) {
                // Provide fallback values
                $view->with([
                    'siteName' => 'Family Cloud',
                    'siteDescription' => '',
                    'siteLogo' => 'storage/logos/family-cloud-logo.png',
                    'maxFileUploadSize' => 50,
                    'allowedFileTypes' => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
                    'registrationEnabled' => true,
                    'isMaintenanceMode' => false,
                ]);
            }
        });
    }
}
