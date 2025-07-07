<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckPendingUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:pending-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check pending user registrations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pendingUsers = User::where('status', 'pending')->get();

        if ($pendingUsers->count() === 0) {
            $this->info('No pending user registrations found.');
            return 0;
        }

        $this->info("Found {$pendingUsers->count()} pending user registration(s):");
        $this->line('');

        $headers = ['ID', 'Name', 'Email', 'Registration Date'];
        $rows = [];

        foreach ($pendingUsers as $user) {
            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->created_at->format('Y-m-d H:i:s')
            ];
        }

        $this->table($headers, $rows);

        $this->line('');
        $this->info('Use the following commands to approve/reject users:');
        $this->line('php artisan test:user-approval {user_id}');
        $this->line('');
        $this->info('Or access the admin panel at: http://127.0.0.1:8000/admin/settings/users');

        return 0;
    }
}
