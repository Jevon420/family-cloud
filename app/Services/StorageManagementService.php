<?php

namespace App\Services;

use App\Models\User;
use App\Models\SystemConfiguration;
use Illuminate\Support\Facades\Storage;

class StorageManagementService
{
    /**
     * Detect available storage space in GB
     */
    public function detectAvailableStorage(): float
    {
        $disk = Storage::disk('local');
        $path = $disk->getAdapter()->getPathPrefix();

        // Get disk free space in bytes
        $freeBytes = disk_free_space($path);

        // Convert to GB
        $freeGB = $freeBytes / (1024 * 1024 * 1024);

        return round($freeGB, 2);
    }

    /**
     * Get total storage available for the system
     */
    public function getTotalSystemStorage(): float
    {
        $config = SystemConfiguration::where('key', 'storage_settings')->first();

        if (!$config) {
            return $this->detectAvailableStorage();
        }

        if ($config->auto_detect_storage) {
            return $this->detectAvailableStorage();
        }

        return $config->total_storage_gb;
    }

    /**
     * Calculate assigned storage (percentage of total)
     */
    public function getAssignedStorage(): float
    {
        $totalStorage = $this->getTotalSystemStorage();
        $config = SystemConfiguration::where('key', 'storage_settings')->first();

        $percentage = $config ? $config->storage_percentage : 80.00;

        return round(($totalStorage * $percentage) / 100, 2);
    }

    /**
     * Calculate storage quota per user
     */
    public function calculatePerUserQuota(): float
    {
        $assignedStorage = $this->getAssignedStorage();
        $totalUsers = User::count();

        if ($totalUsers === 0) {
            return 0;
        }

        return round($assignedStorage / $totalUsers, 2);
    }

    /**
     * Update all users' storage quotas
     */
    public function updateAllUserQuotas(): void
    {
        $perUserQuota = $this->calculatePerUserQuota();

        User::chunk(100, function ($users) use ($perUserQuota) {
            foreach ($users as $user) {
                $user->update(['storage_quota_gb' => $perUserQuota]);
            }
        });
    }

    /**
     * Update system storage settings
     */
    public function updateStorageSettings(array $settings): void
    {
        $config = SystemConfiguration::firstOrCreate(['key' => 'storage_settings']);

        $config->update([
            'total_storage_gb' => $settings['total_storage_gb'] ?? $this->detectAvailableStorage(),
            'assigned_storage_gb' => $settings['assigned_storage_gb'] ?? $this->getAssignedStorage(),
            'storage_percentage' => $settings['storage_percentage'] ?? 80.00,
            'auto_detect_storage' => $settings['auto_detect_storage'] ?? true,
        ]);

        // Update user quotas after changing settings
        $this->updateAllUserQuotas();
    }

    /**
     * Get storage statistics
     */
    public function getStorageStatistics(): array
    {
        $totalStorage = $this->getTotalSystemStorage();
        $assignedStorage = $this->getAssignedStorage();
        $userCount = User::count();
        $perUserQuota = $this->calculatePerUserQuota();
        $totalUsedStorage = User::sum('storage_used_gb');

        return [
            'total_storage_gb' => $totalStorage,
            'assigned_storage_gb' => $assignedStorage,
            'available_storage_gb' => $assignedStorage - $totalUsedStorage,
            'user_count' => $userCount,
            'per_user_quota_gb' => $perUserQuota,
            'total_used_storage_gb' => $totalUsedStorage,
            'storage_percentage_used' => $assignedStorage > 0 ? round(($totalUsedStorage / $assignedStorage) * 100, 2) : 0,
        ];
    }

    /**
     * Calculate user's storage usage from files
     */
    public function calculateUserStorageUsage($userId): float
    {
        // This would calculate based on actual files stored
        // For now, we'll return a placeholder
        return 0;
    }

    /**
     * Check if user has enough storage for upload
     */
    public function canUserUpload($userId, $fileSizeBytes): bool
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        $fileSizeGB = $fileSizeBytes / (1024 * 1024 * 1024);
        $availableGB = $user->storage_quota_gb - $user->storage_used_gb;

        return $fileSizeGB <= $availableGB;
    }
}
