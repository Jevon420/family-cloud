<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StorageManagementService;
use App\Models\User;

class UpdateStorageUsage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:update-usage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update storage usage for all users';

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
        $storageService = new StorageManagementService();

        $this->info('Updating storage usage for all users...');

        $users = User::all();
        $bar = $this->output->createProgressBar(count($users));

        foreach ($users as $user) {
            // Calculate actual storage usage
            $usage = $storageService->calculateUserStorageUsage($user->id);
            $user->update(['storage_used_gb' => $usage]);

            // Check if user has quota set
            if ($user->storage_quota_gb <= 0) {
                $perUserQuota = $storageService->calculatePerUserQuota();
                $user->update(['storage_quota_gb' => $perUserQuota]);
                $this->line(" Set missing quota for user: {$user->name}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nStorage usage updated successfully!");

        return 0;
    }
}
