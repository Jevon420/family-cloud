<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Http\Controllers\Admin\UserApprovalController;
use Illuminate\Http\Request;

class TestUserApproval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:user-approval {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test user approval process';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user_id');

        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return 1;
        }

        $this->info("Testing approval for user: {$user->name} ({$user->email})");
        $this->info("Current status: {$user->status}");

        if ($user->status !== 'pending') {
            $this->error("User is not in pending status. Cannot approve.");
            return 1;
        }

        try {
            // Create a mock authentication for the test
            $adminUser = User::role(['Global Admin', 'Admin', 'Developer'])->first();

            if ($adminUser) {
                \Auth::login($adminUser);
                $this->info("Authenticated as: {$adminUser->name}");
            }

            // Simulate the approval process
            $controller = new UserApprovalController();
            $request = new Request();

            $response = $controller->approve($userId);

            $this->info("Approval process completed successfully!");

            // Refresh user data
            $user->refresh();
            $this->info("User new status: {$user->status}");

            return 0;

        } catch (\Exception $e) {
            $this->error("Approval failed: " . $e->getMessage());
            return 1;
        }
    }
}
