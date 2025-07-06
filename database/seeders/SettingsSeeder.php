<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use App\Models\SystemConfiguration;
use App\Models\SecuritySetting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $this->seedSiteSettings();
        $this->seedSystemConfigurations();
        $this->seedSecuritySettings();
    }

    private function seedSiteSettings()
    {
        $siteSettings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Family Cloud',
                'type' => 'string',
                'group' => 'general',
                'description' => 'The name of your website',
                'access_level' => 'global_admin',
                'is_public' => true,
            ],
            [
                'key' => 'site_description',
                'value' => 'A secure family cloud storage system',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Brief description of your website',
                'access_level' => 'global_admin',
                'is_public' => true,
            ],
            [
                'key' => 'site_email',
                'value' => 'admin@familycloud.com',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Primary contact email for the site',
                'access_level' => 'global_admin',
                'is_public' => false,
            ],
            [
                'key' => 'site_logo',
                'value' => 'storage/logos/family-cloud-logo.png',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Path to site logo',
                'access_level' => 'admin',
                'is_public' => true,
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Enable maintenance mode to prevent user access',
                'access_level' => 'global_admin',
                'is_public' => false,
            ],
            [
                'key' => 'registration_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'user_management',
                'description' => 'Allow new user registrations',
                'access_level' => 'global_admin',
                'is_public' => false,
            ],
            [
                'key' => 'max_file_upload_size',
                'value' => '50',
                'type' => 'number',
                'group' => 'storage',
                'description' => 'Maximum file upload size in MB',
                'access_level' => 'global_admin',
                'is_public' => false,
            ],
            [
                'key' => 'max_storage_per_user',
                'value' => '5000',
                'type' => 'number',
                'group' => 'storage',
                'description' => 'Maximum storage per user in MB',
                'access_level' => 'global_admin',
                'is_public' => false,
            ],
            [
                'key' => 'allowed_file_types',
                'value' => 'jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
                'type' => 'string',
                'group' => 'storage',
                'description' => 'Comma-separated list of allowed file extensions',
                'access_level' => 'global_admin',
                'is_public' => false,
            ],
            [
                'key' => 'backup_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'backup',
                'description' => 'Enable automatic backups',
                'access_level' => 'global_admin',
                'is_public' => false,
            ],
            [
                'key' => 'backup_frequency',
                'value' => 'daily',
                'type' => 'string',
                'group' => 'backup',
                'description' => 'Backup frequency (daily, weekly, monthly)',
                'access_level' => 'global_admin',
                'is_public' => false,
            ],
            [
                'key' => 'email_notifications',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'Enable email notifications',
                'access_level' => 'global_admin',
                'is_public' => false,
            ],
            [
                'key' => 'timezone',
                'value' => 'UTC',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default timezone for the application',
                'access_level' => 'global_admin',
                'is_public' => true,
            ],
        ];

        foreach ($siteSettings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    private function seedSystemConfigurations()
    {
        $systemConfigurations = [
            // Cache Settings
            [
                'key' => 'cache_driver',
                'value' => 'file',
                'type' => 'string',
                'group' => 'cache',
                'description' => 'Cache driver (file, redis, memcached)',
                'access_level' => 'developer',
                'requires_restart' => true,
            ],
            [
                'key' => 'cache_ttl',
                'value' => '3600',
                'type' => 'number',
                'group' => 'cache',
                'description' => 'Default cache TTL in seconds',
                'access_level' => 'global_admin',
                'requires_restart' => false,
            ],
            
            // Queue Settings
            [
                'key' => 'queue_driver',
                'value' => 'sync',
                'type' => 'string',
                'group' => 'queue',
                'description' => 'Queue driver (sync, database, redis)',
                'access_level' => 'developer',
                'requires_restart' => true,
            ],
            [
                'key' => 'queue_timeout',
                'value' => '300',
                'type' => 'number',
                'group' => 'queue',
                'description' => 'Queue job timeout in seconds',
                'access_level' => 'global_admin',
                'requires_restart' => false,
            ],
            
            // Mail Settings
            [
                'key' => 'mail_driver',
                'value' => 'smtp',
                'type' => 'string',
                'group' => 'mail',
                'description' => 'Mail driver (smtp, sendmail, mailgun, ses)',
                'access_level' => 'global_admin',
                'requires_restart' => true,
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.mailtrap.io',
                'type' => 'string',
                'group' => 'mail',
                'description' => 'SMTP host address',
                'access_level' => 'global_admin',
                'requires_restart' => true,
            ],
            [
                'key' => 'mail_port',
                'value' => '587',
                'type' => 'number',
                'group' => 'mail',
                'description' => 'SMTP port number',
                'access_level' => 'global_admin',
                'requires_restart' => true,
            ],
            [
                'key' => 'mail_username',
                'value' => '',
                'type' => 'string',
                'group' => 'mail',
                'description' => 'SMTP username',
                'access_level' => 'global_admin',
                'requires_restart' => true,
                'is_sensitive' => true,
            ],
            [
                'key' => 'mail_password',
                'value' => '',
                'type' => 'password',
                'group' => 'mail',
                'description' => 'SMTP password',
                'access_level' => 'global_admin',
                'requires_restart' => true,
                'is_sensitive' => true,
            ],
            
            // Storage Settings
            [
                'key' => 'filesystem_driver',
                'value' => 'local',
                'type' => 'string',
                'group' => 'storage',
                'description' => 'Default filesystem driver',
                'access_level' => 'developer',
                'requires_restart' => true,
            ],
            [
                'key' => 'aws_access_key_id',
                'value' => '',
                'type' => 'string',
                'group' => 'storage',
                'description' => 'AWS Access Key ID for S3 storage',
                'access_level' => 'developer',
                'requires_restart' => true,
                'is_sensitive' => true,
            ],
            [
                'key' => 'aws_secret_access_key',
                'value' => '',
                'type' => 'password',
                'group' => 'storage',
                'description' => 'AWS Secret Access Key for S3 storage',
                'access_level' => 'developer',
                'requires_restart' => true,
                'is_sensitive' => true,
            ],
            
            // Logging Settings
            [
                'key' => 'log_level',
                'value' => 'info',
                'type' => 'string',
                'group' => 'logging',
                'description' => 'Minimum log level (debug, info, warning, error)',
                'access_level' => 'global_admin',
                'requires_restart' => false,
            ],
            [
                'key' => 'log_max_files',
                'value' => '7',
                'type' => 'number',
                'group' => 'logging',
                'description' => 'Maximum number of log files to keep',
                'access_level' => 'global_admin',
                'requires_restart' => false,
            ],
        ];

        foreach ($systemConfigurations as $setting) {
            SystemConfiguration::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    private function seedSecuritySettings()
    {
        $securitySettings = [
            // Authentication Settings
            [
                'key' => 'session_lifetime',
                'value' => '120',
                'type' => 'number',
                'group' => 'authentication',
                'description' => 'Session lifetime in minutes',
                'access_level' => 'global_admin',
                'is_critical' => false,
            ],
            [
                'key' => 'password_min_length',
                'value' => '8',
                'type' => 'number',
                'group' => 'authentication',
                'description' => 'Minimum password length',
                'access_level' => 'global_admin',
                'is_critical' => false,
            ],
            [
                'key' => 'password_require_uppercase',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'authentication',
                'description' => 'Require uppercase letters in passwords',
                'access_level' => 'global_admin',
                'is_critical' => false,
            ],
            [
                'key' => 'password_require_lowercase',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'authentication',
                'description' => 'Require lowercase letters in passwords',
                'access_level' => 'global_admin',
                'is_critical' => false,
            ],
            [
                'key' => 'password_require_numbers',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'authentication',
                'description' => 'Require numbers in passwords',
                'access_level' => 'global_admin',
                'is_critical' => false,
            ],
            [
                'key' => 'password_require_symbols',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'authentication',
                'description' => 'Require special characters in passwords',
                'access_level' => 'global_admin',
                'is_critical' => false,
            ],
            [
                'key' => 'two_factor_enabled',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'authentication',
                'description' => 'Enable two-factor authentication',
                'access_level' => 'global_admin',
                'is_critical' => true,
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'number',
                'group' => 'authentication',
                'description' => 'Maximum login attempts before lockout',
                'access_level' => 'global_admin',
                'is_critical' => false,
            ],
            [
                'key' => 'lockout_duration',
                'value' => '15',
                'type' => 'number',
                'group' => 'authentication',
                'description' => 'Account lockout duration in minutes',
                'access_level' => 'global_admin',
                'is_critical' => false,
            ],
            
            // Authorization Settings
            [
                'key' => 'role_hierarchy_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'authorization',
                'description' => 'Enable role hierarchy (higher roles inherit lower role permissions)',
                'access_level' => 'developer',
                'is_critical' => true,
            ],
            [
                'key' => 'permission_caching_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'authorization',
                'description' => 'Enable permission caching for better performance',
                'access_level' => 'developer',
                'is_critical' => false,
            ],
            
            // Encryption Settings
            [
                'key' => 'app_key',
                'value' => env('APP_KEY', ''),
                'type' => 'password',
                'group' => 'encryption',
                'description' => 'Application encryption key',
                'access_level' => 'developer',
                'is_critical' => true,
            ],
            [
                'key' => 'encrypt_cookies',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'encryption',
                'description' => 'Encrypt cookies for additional security',
                'access_level' => 'global_admin',
                'is_critical' => true,
            ],
            [
                'key' => 'csrf_protection_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Enable CSRF protection',
                'access_level' => 'developer',
                'is_critical' => true,
            ],
            [
                'key' => 'secure_headers_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Enable secure headers (X-Frame-Options, etc.)',
                'access_level' => 'global_admin',
                'is_critical' => true,
            ],
        ];

        foreach ($securitySettings as $setting) {
            SecuritySetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
