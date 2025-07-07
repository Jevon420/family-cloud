<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailConfiguration;
use Exception;
use Swift_SmtpTransport;
use Swift_Mailer;

class VerifyEmailAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:verify-accounts
                            {--connection-only : Only test the connection without sending actual emails}
                            {--recipient= : Specify a test recipient email address}
                            {--debug : Show detailed debugging information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configurations from database by sending test emails from each configured account';

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
        $connectionOnly = $this->option('connection-only');
        $debug = $this->option('debug');
        $results = []; // Initialize results array for summary

        if ($connectionOnly) {
            $this->info('Testing email configuration connections only (no emails will be sent)...');
        } else {
            $this->info('Testing email configurations from database and sending test emails...');
        }
        $this->line('');

        // Get all active email configurations that can send emails
        $emailConfigs = EmailConfiguration::active()
            ->whereIn('type', ['outgoing', 'both'])
            ->get();

        if ($emailConfigs->isEmpty()) {
            $this->error('No active email configurations found in the database.');
            return 1;
        }

        $this->info("Found {$emailConfigs->count()} active email configuration(s) to test.");
        $this->line('');

        // Get recipient from option or ask for it if we're sending actual emails
        $testRecipient = $this->option('recipient');
        if (!$connectionOnly && !$testRecipient) {
            $testRecipient = $this->ask('Enter test recipient email address', 'test@example.com');
        }

        foreach ($emailConfigs as $config) {
            $this->info("Testing: {$config->email} ({$config->name})");

            try {
                // Get SMTP configuration for this email
                $smtpConfig = $config->getSmtpConfig();

                if ($debug) {
                    $this->line("  Debug: SMTP Host = {$smtpConfig['host']}");
                    $this->line("  Debug: SMTP Port = {$smtpConfig['port']}");
                    $this->line("  Debug: SMTP Encryption = {$smtpConfig['encryption']}");
                    $this->line("  Debug: SMTP Username = {$smtpConfig['username']}");
                    $this->line("  Debug: From Address = {$smtpConfig['from']['address']}");
                    $this->line("  Debug: From Name = {$smtpConfig['from']['name']}");
                }

                // Configure mail settings dynamically
                config([
                    'mail.mailers.smtp.host' => $smtpConfig['host'],
                    'mail.mailers.smtp.port' => $smtpConfig['port'],
                    'mail.mailers.smtp.encryption' => $smtpConfig['encryption'],
                    'mail.mailers.smtp.username' => $smtpConfig['username'],
                    'mail.mailers.smtp.password' => $smtpConfig['password'],
                    'mail.from.address' => $smtpConfig['from']['address'],
                    'mail.from.name' => $smtpConfig['from']['name'],
                ]);

                if ($connectionOnly) {
                    // Only test the connection without sending an email
                    $transport = new Swift_SmtpTransport(
                        $smtpConfig['host'],
                        $smtpConfig['port'],
                        $smtpConfig['encryption']
                    );

                    $transport->setUsername($smtpConfig['username']);
                    $transport->setPassword($smtpConfig['password']);

                    // Try to connect                    $mailer = new Swift_Mailer($transport);
                    $mailer->getTransport()->start();

                    $this->info("✓ {$config->email} - Connection successful");
                    $results[$config->email] = [
                        'status' => 'Success',
                        'notes' => 'Connection successful'
                    ];
                } else {
                    // Create and send a test email
                    $mailable = new \App\Mail\CustomEmail(
                        'Email Configuration Test',
                        '<p>This is a test email from the email configuration system.</p>
                        <p>From Email: ' . $config->email . '</p>
                        <p>Configuration: ' . $config->name . '</p>
                        <p>Time: ' . now() . '</p>',
                        $config->email,
                        $config->from_name ?: $config->name
                    );

                    $this->line("  Sending to: {$testRecipient}");

                    // Try directly with Swift_Mailer for better error control
                    $message = (new \Swift_Message('Email Configuration Test'))
                        ->setFrom([$config->email => $config->from_name ?: $config->name])
                        ->setTo([$testRecipient])
                        ->setBody('<p>This is a test email from the email configuration system.</p>
                        <p>From Email: ' . $config->email . '</p>
                        <p>Configuration: ' . $config->name . '</p>
                        <p>Time: ' . now() . '</p>', 'text/html');

                    $transport = new Swift_SmtpTransport(
                        $smtpConfig['host'],
                        $smtpConfig['port'],
                        $smtpConfig['encryption']
                    );

                    $transport->setUsername($smtpConfig['username']);
                    $transport->setPassword($smtpConfig['password']);

                    $mailer = new Swift_Mailer($transport);
                    $result = $mailer->send($message);
                    if ($result) {
                        $this->info("✓ {$config->email} - Test email sent successfully");
                        $results[$config->email] = [
                            'status' => 'Success',
                            'notes' => 'Email sent successfully'
                        ];
                    } else {
                        $this->error("✗ {$config->email} - Failed to send email");
                        $results[$config->email] = [
                            'status' => 'Failed',
                            'notes' => 'Failed to send email'
                        ];
                    }
                }

            } catch (Exception $e) {
                $errorMessage = $e->getMessage();

                if (strpos($errorMessage, 'authentication failed') !== false ||
                    strpos($errorMessage, 'Invalid credentials') !== false) {
                    $this->error("✗ {$config->email} - Authentication failed (check username/password)");
                    $this->line("  Error details: " . $errorMessage);
                    $results[$config->email] = [
                        'status' => 'Failed',
                        'notes' => 'Authentication failed (check username/password)'
                    ];
                } else if (strpos($errorMessage, 'Connection could not be established') !== false) {
                    $this->error("✗ {$config->email} - Connection failed (check SMTP settings)");
                    $this->line("  Error details: " . $errorMessage);
                    $results[$config->email] = [
                        'status' => 'Failed',
                        'notes' => 'Connection failed (check SMTP settings)'
                    ];
                } else if (strpos($errorMessage, 'no valid recipients') !== false) {
                    $this->error("✗ {$config->email} - No valid recipients");
                    $this->line("  Error details: " . $errorMessage);
                    $results[$config->email] = [
                        'status' => 'Failed',
                        'notes' => 'No valid recipients'
                    ];
                } else {
                    $this->error("✗ {$config->email} - Error: " . $errorMessage);
                    $results[$config->email] = [
                        'status' => 'Failed',
                        'notes' => 'Error: ' . substr($errorMessage, 0, 100) . (strlen($errorMessage) > 100 ? '...' : '')
                    ];
                }
            }

            $this->line('');
        }

        if ($connectionOnly) {
            $this->info('Email configuration connection testing completed.');
        } else {
            $this->info('Email configuration testing completed.');
            $this->line('');
            $this->info('Check the recipient inbox for test emails from successful configurations.');
        }

        // Summary of results
        $this->line('');
        $this->info('Email Testing Summary:');
        $this->line('');

        $headers = ['Email', 'Status', 'Notes'];
        $rows = [];

        foreach ($emailConfigs as $config) {
            $status = isset($results[$config->email]) ? $results[$config->email]['status'] : 'Not Tested';
            $notes = isset($results[$config->email]) ? $results[$config->email]['notes'] : '';

            $rows[] = [
                'email' => $config->email,
                'status' => $status,
                'notes' => $notes
            ];
        }

        $this->table($headers, $rows);

        return 0;
    }
}
