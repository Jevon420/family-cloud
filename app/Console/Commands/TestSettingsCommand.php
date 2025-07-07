<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SettingsService;
use App\Models\SiteSetting;
use App\Models\SystemConfiguration;
use App\Models\SecuritySetting;

class TestSettingsCommand extends Command
{
    protected $signature = 'settings:test';
    protected $description = 'Test the comprehensive settings system';

    public function handle(SettingsService $settings)
    {
        $this->info('🔧 Testing Comprehensive Settings System');
        $this->newLine();

        // Test Site Settings
        $this->info('📊 Site Settings:');
        $siteName = $settings->getSiteSetting('site_name');
        $this->line("  Site Name: {$siteName}");

        $maxUpload = $settings->getMaxFileUploadSize();
        $this->line("  Max Upload Size: {$maxUpload} MB");

        $allowedTypes = $settings->getAllowedFileTypes();
        $this->line("  Allowed File Types: " . implode(', ', array_slice($allowedTypes, 0, 5)) . '...');

        $isMaintenanceMode = $settings->isMaintenanceMode();
        $this->line("  Maintenance Mode: " . ($isMaintenanceMode ? 'ON' : 'OFF'));
        $this->newLine();

        // Test System Configurations
        $this->info('⚙️ System Configurations:');
        $cacheDriver = $settings->getSystemConfiguration('cache_driver');
        $this->line("  Cache Driver: {$cacheDriver}");

        $cacheTtl = $settings->getSystemConfiguration('cache_ttl');
        $this->line("  Cache TTL: {$cacheTtl} seconds");

        $mailDriver = $settings->getSystemConfiguration('mail_driver');
        $this->line("  Mail Driver: {$mailDriver}");
        $this->newLine();

        // Test Security Settings
        $this->info('🔒 Security Settings:');
        $sessionLifetime = $settings->getSecuritySetting('session_lifetime');
        $this->line("  Session Lifetime: {$sessionLifetime} minutes");

        $passwordMinLength = $settings->getSecuritySetting('password_min_length');
        $this->line("  Min Password Length: {$passwordMinLength} characters");

        $maxLoginAttempts = $settings->getSecuritySetting('max_login_attempts');
        $this->line("  Max Login Attempts: {$maxLoginAttempts}");
        $this->newLine();

        // Test counts
        $siteCount = SiteSetting::count();
        $systemCount = SystemConfiguration::count();
        $securityCount = SecuritySetting::count();

        $this->info('📈 Database Counts:');
        $this->line("  Site Settings: {$siteCount}");
        $this->line("  System Configurations: {$systemCount}");
        $this->line("  Security Settings: {$securityCount}");
        $this->newLine();

        // Test setting a value and reading it back
        $this->info('🧪 Testing Read/Write:');
        $testKey = 'test_setting_' . time();
        $testValue = 'test_value_' . rand(1000, 9999);

        $settings->setSiteSetting($testKey, $testValue);
        $retrievedValue = $settings->getSiteSetting($testKey);

        if ($retrievedValue === $testValue) {
            $this->line("  ✅ Read/Write test PASSED");
        } else {
            $this->line("  ❌ Read/Write test FAILED");
        }

        // Clean up test setting
        SiteSetting::where('key', $testKey)->delete();
        $this->newLine();

        $this->info('✅ Settings system test completed successfully!');
    }
}
