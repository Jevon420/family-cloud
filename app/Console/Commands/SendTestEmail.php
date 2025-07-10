<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Models\EmailConfiguration;

class SendTestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify mail configuration';

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
        $email = $this->argument('email');
        $this->info("Sending test email to {$email}...");

        try {
            // Send email from all email configurations
            $emailConfigs = EmailConfiguration::all();

            foreach ($emailConfigs as $config) {
                Mail::raw('This is a test email from Family Cloud to verify mail configuration is working.', function (Message $message) use ($config) {
                    $message->from($config->email)
                        ->to('jevon_redhead@yahoo.com')
                        ->subject('Family Cloud - Mail Configuration Test');
                });

                $this->info("Test email sent from {$config->email} to jevon_redhead@yahoo.com successfully!");
            }

            // Send email from the default mail from address
            $defaultFromAddress = config('mail.from.address');
            Mail::raw('This is a test email from Family Cloud to verify mail configuration is working.', function (Message $message) use ($defaultFromAddress) {
                $message->from($defaultFromAddress)
                    ->to('jevon_redhead@yahoo.com')
                    ->subject('Family Cloud - Mail Configuration Test');
            });

            $this->info("Test email sent from default mail from address ({$defaultFromAddress}) to jevon_redhead@yahoo.com successfully!");

            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send test email!');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
