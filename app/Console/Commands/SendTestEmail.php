<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

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
            Mail::raw('This is a test email from Family Cloud to verify mail configuration is working.', function (Message $message) use ($email) {
                $message->to($email)
                    ->subject('Family Cloud - Mail Configuration Test');
            });

            $this->info('Test email sent successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send test email!');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
