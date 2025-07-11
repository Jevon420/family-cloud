<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\StorageManagementService;

class UpdateUserStorageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:update-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update storage usage for all users based on their files, photos, and galleries.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $storageService = app(StorageManagementService::class);

        User::chunk(100, function ($users) use ($storageService) {
            foreach ($users as $user) {
                $storageService->updateUserStorage($user);
            }
        });

        $this->info('User storage updated successfully.');

        return Command::SUCCESS;
    }
}
