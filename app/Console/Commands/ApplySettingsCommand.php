<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Artisan;

class ApplySettingsCommand extends Command
{
    protected $signature = 'settings:apply {--cache-clear : Clear all caches after applying settings}';
    protected $description = 'Apply settings changes throughout the application';

    public function handle(SettingsService $settings)
    {
        $this->info('🔧 Applying settings changes...');

        // Clear settings cache
        $settings->clearAllSettingsCache();
        $this->line('✅ Settings cache cleared');

        // Clear other caches if requested
        if ($this->option('cache-clear')) {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            $this->line('✅ All caches cleared');
        }

        // Test critical settings
        $this->info('🧪 Testing critical settings...');

        $siteName = $settings->getSiteSetting('site_name');
        $this->line("  Site Name: {$siteName}");

        $maintenanceMode = $settings->isMaintenanceMode() ? 'ON' : 'OFF';
        $this->line("  Maintenance Mode: {$maintenanceMode}");

        $registrationEnabled = $settings->isRegistrationEnabled() ? 'ENABLED' : 'DISABLED';
        $this->line("  Registration: {$registrationEnabled}");

        $maxUpload = $settings->getMaxFileUploadSize();
        $this->line("  Max Upload Size: {$maxUpload} MB");

        $this->info('✅ Settings applied successfully!');

        if ($settings->isMaintenanceMode()) {
            $this->warn('⚠️  Maintenance mode is ACTIVE - non-admin users will see maintenance page');
        }

        return 0;
    }
}
