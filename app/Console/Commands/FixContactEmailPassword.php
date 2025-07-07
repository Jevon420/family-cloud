<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailConfiguration;

class FixContactEmailPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:fix-contact-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the contact email password by copying from support email';

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
        $this->info('Fixing contact email password...');
        $this->line('');

        // Find the support email (which is working)
        $supportEmail = EmailConfiguration::where('email', 'support@jevonredhead.com')->first();

        if (!$supportEmail) {
            $this->error('Support email configuration not found.');
            return 1;
        }

        // Get the support email password
        $supportPassword = $supportEmail->password;

        if (empty($supportPassword)) {
            $this->error('Support email has no password set.');
            return 1;
        }

        // Find the contact email
        $contactEmail = EmailConfiguration::where('email', 'contact@jevonredhead.com')->first();

        if (!$contactEmail) {
            $this->error('Contact email configuration not found.');
            return 1;
        }

        // Update the contact email password
        try {
            $contactEmail->password = $supportPassword;
            $contactEmail->save();

            $this->info('✓ Successfully updated contact email password');
            $this->line('');
            $this->info('Now run the email:verify-accounts command to test the updated configuration.');

            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to update contact email password: ' . $e->getMessage());
            return 1;
        }
    }
}
