<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemConfiguration;
use App\Services\StorageManagementService;

class StorageSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $storageService = new StorageManagementService();

        // Create initial storage settings
        $storageService->updateStorageSettings([
            'total_storage_gb' => $storageService->detectAvailableStorage(),
            'storage_percentage' => 80.00,
            'auto_detect_storage' => true,
        ]);

        // Update all user quotas
        $storageService->updateAllUserQuotas();

        $this->command->info('Storage settings initialized successfully.');
    }
}
