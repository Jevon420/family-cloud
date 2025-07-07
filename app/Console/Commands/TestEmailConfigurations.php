<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailConfiguration;
use App\Mail\CustomEmail;
use Illuminate\Support\Facades\Mail;

class TestEmailConfigurations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-configurations {--to=jevon_redhead@yahoo.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test all email configurations by sending test emails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $testEmail = $this->option('to');

        $this->info("Testing email configurations by sending test emails to: {$testEmail}");
        $this->line('');

        // Get all active email configurations that can send emails
        $configurations = EmailConfiguration::active()->outgoing()->get();

        if ($configurations->count() === 0) {
            $this->error('No active outgoing email configurations found.');

            // Let's create a default configuration from .env
            $this->info('Creating default email configuration from .env settings...');

            $config = EmailConfiguration::create([
                'name' => 'Default Configuration',
                'email' => env('MAIL_FROM_ADDRESS'),
                'password' => env('MAIL_PASSWORD'),
                'type' => 'outgoing',
                'is_default' => true,
                'is_active' => true,
                'smtp_host' => env('MAIL_HOST'),
                'smtp_port' => env('MAIL_PORT'),
                'smtp_encryption' => env('MAIL_ENCRYPTION'),
                'smtp_username' => env('MAIL_USERNAME'),
                'from_name' => env('MAIL_FROM_NAME'),
                'created_by' => 1, // Assuming admin user ID is 1
            ]);

            $configurations = collect([$config]);
            $this->info('Default configuration created successfully.');
        }

        foreach ($configurations as $config) {
            $this->info("Testing configuration: {$config->name} ({$config->email})");

            try {
                // Configure mail settings dynamically
                $smtpConfig = $config->getSmtpConfig();

                // Set mailer configuration
                config([
                    'mail.mailers.smtp.host' => $smtpConfig['host'],
                    'mail.mailers.smtp.port' => $smtpConfig['port'],
                    'mail.mailers.smtp.encryption' => $smtpConfig['encryption'],
                    'mail.mailers.smtp.username' => $smtpConfig['username'],
                    'mail.mailers.smtp.password' => $smtpConfig['password'],
                ]);

                // Create test email
                $subject = "Test Email from {$config->name}";
                $body = "<h2>Test Email</h2><p>This is a test email sent from the {$config->name} configuration.</p><p>If you received this email, the configuration is working correctly.</p><p>Sent at: " . now()->format('Y-m-d H:i:s') . "</p>";

                $mailable = new CustomEmail(
                    $subject,
                    $body,
                    $config->email,
                    $config->from_name
                );

                // Send test email
                Mail::to($testEmail)->send($mailable);

                $this->info("✓ Test email sent successfully from {$config->name}");

            } catch (\Exception $e) {
                $this->error("✗ Failed to send test email from {$config->name}: " . $e->getMessage());
            }

            $this->line('');
        }

        $this->info('Email configuration testing completed.');

        return 0;
    }
}
