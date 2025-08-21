<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SettingsService;

class TestSessionConfig extends Command
{
    protected $signature = 'test:session-config';
    protected $description = 'Test session configuration settings';

    public function handle()
    {
        $this->info('=== Session Configuration Test ===');

        // Check environment values
        $this->line('ENV SESSION_LIFETIME: ' . env('SESSION_LIFETIME', 'not set'));
        $this->line('ENV SESSION_DRIVER: ' . env('SESSION_DRIVER', 'not set'));
        $this->line('ENV SESSION_DOMAIN: ' . (env('SESSION_DOMAIN') ?? 'null'));
        $this->line('ENV SESSION_SECURE_COOKIE: ' . (env('SESSION_SECURE_COOKIE') ?? 'null'));

        // Check config values
        $this->newLine();
        $this->line('Config session.lifetime: ' . config('session.lifetime'));
        $this->line('Config session.driver: ' . config('session.driver'));
        $this->line('Config session.domain: ' . (config('session.domain') ?? 'null'));
        $this->line('Config session.secure: ' . (config('session.secure') ? 'true' : 'false'));
        $this->line('Config session.path: ' . config('session.path'));
        $this->line('Config session.same_site: ' . config('session.same_site'));

        // Check if settings service is overriding values
        try {
            $settingsService = app(SettingsService::class);
            $this->newLine();
            $this->line('Settings Service session_lifetime: ' . $settingsService->getSecuritySetting('session_lifetime', 120));
        } catch (\Exception $e) {
            $this->error('Error accessing SettingsService: ' . $e->getMessage());
        }

        $this->info('=== End Test ===');
    }
}
