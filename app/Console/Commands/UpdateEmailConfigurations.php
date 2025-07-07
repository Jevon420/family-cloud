<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailConfiguration;
use Exception;

class UpdateEmailConfigurations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:update-configurations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy SMTP settings from support email to other email configurations';

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
        $this->info('Updating email configurations...');
        $this->line('');

        // Find the support email configuration (assumed to be working)
        $supportEmail = EmailConfiguration::where('email', 'support@jevonredhead.com')->first();

        if (!$supportEmail) {
            $this->error('Support email configuration not found.');
            return 1;
        }

        $this->info("Found support email configuration: {$supportEmail->name}");
        $this->line('');

        // Get the SMTP settings from the support email
        $smtpHost = $supportEmail->smtp_host;
        $smtpPort = $supportEmail->smtp_port;
        $smtpEncryption = $supportEmail->smtp_encryption;

        // Find the other email configurations to update
        $otherEmails = EmailConfiguration::where('email', '!=', 'support@jevonredhead.com')
            ->whereIn('email', [
                'updates@jevonredhead.com',
                'contact@jevonredhead.com',
                'no-reply@jevonredhead.com'
            ])
            ->get();

        if ($otherEmails->isEmpty()) {
            $this->error('No other email configurations found to update.');
            return 1;
        }

        $this->info("Found " . $otherEmails->count() . " email configuration(s) to update.");

        // Update each email configuration with the SMTP settings from the support email
        foreach ($otherEmails as $email) {
            $this->info("Updating: {$email->email} ({$email->name})");

            // Copy the SMTP settings
            $email->smtp_host = $smtpHost;
            $email->smtp_port = $smtpPort;
            $email->smtp_encryption = $smtpEncryption;

            // If the password wasn't set, copy it from the support email
            if (empty($email->password)) {
                $email->password = $supportEmail->password;
                $this->line('- Copied password from support email');
            }

            // Make sure the email is active and can send emails
            $email->is_active = true;
            $email->type = 'outgoing'; // or 'both' if you want to receive emails too

            try {
                $email->save();
                $this->info("✓ Successfully updated {$email->email}");
            } catch (Exception $e) {
                $this->error("✗ Failed to update {$email->email}: " . $e->getMessage());
            }

            $this->line('');
        }

        $this->info('Email configuration update completed.');
        $this->line('');
        $this->info('Now run the email:verify-accounts command to test the updated configurations.');

        return 0;
    }
}
