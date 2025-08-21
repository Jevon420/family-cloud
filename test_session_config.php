<?php

// Test script to check session configuration issues
echo "=== Session Configuration Test ===\n";

// Check environment values
echo "ENV SESSION_LIFETIME: " . env('SESSION_LIFETIME', 'not set') . "\n";
echo "ENV SESSION_DRIVER: " . env('SESSION_DRIVER', 'not set') . "\n";
echo "ENV SESSION_DOMAIN: " . (env('SESSION_DOMAIN') ?? 'null') . "\n";
echo "ENV SESSION_SECURE_COOKIE: " . (env('SESSION_SECURE_COOKIE') ?? 'null') . "\n";

// Check config values
echo "\nConfig session.lifetime: " . config('session.lifetime') . "\n";
echo "Config session.driver: " . config('session.driver') . "\n";
echo "Config session.domain: " . (config('session.domain') ?? 'null') . "\n";
echo "Config session.secure: " . (config('session.secure') ? 'true' : 'false') . "\n";
echo "Config session.path: " . config('session.path') . "\n";
echo "Config session.same_site: " . config('session.same_site') . "\n";

// Check if settings service is overriding values
try {
    $settingsService = app(\App\Services\SettingsService::class);
    echo "\nSettings Service session_lifetime: " . $settingsService->getSecuritySetting('session_lifetime', 120) . "\n";
} catch (Exception $e) {
    echo "\nError accessing SettingsService: " . $e->getMessage() . "\n";
}

echo "\n=== End Test ===\n";
