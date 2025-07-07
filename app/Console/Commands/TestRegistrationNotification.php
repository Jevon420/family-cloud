<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\RegistrationRequestNotification;
use Illuminate\Support\Facades\Notification;

class TestRegistrationNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:registration-notification {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test registration notification email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');

        // Create a fake user for testing with unique email
        $uniqueEmail = 'test-' . time() . '@example.com';
        $testUser = User::create([
            'name' => 'Test Registration User',
            'email' => $uniqueEmail,
            'password' => bcrypt('temp-password'),
            'status' => 'pending',
            'password_change_required' => true
        ]);

        $this->info("Sending test registration notification to {$email}...");
        $this->info("Created test user with ID: {$testUser->id}");

        try {
            Notification::route('mail', $email)
                ->notify(new RegistrationRequestNotification($testUser));

            $this->info('Test registration notification sent successfully!');
            $this->info('You can now test the approval/rejection links in the email.');
            $this->info("Remember to clean up the test user with ID {$testUser->id} after testing.");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send test notification!');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
