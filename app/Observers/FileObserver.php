<?php

namespace App\Observers;

use App\Models\File;
use App\Services\StorageManagementService;

class FileObserver
{
    protected $storageService;

    public function __construct(StorageManagementService $storageService)
    {
        $this->storageService = $storageService;
    }

    public function created(File $file)
    {
        $this->updateStorage($file);
    }

    public function updated(File $file)
    {
        $this->updateStorage($file);
    }

    public function deleted(File $file)
    {
        $this->updateStorage($file);
    }

    protected function updateStorage(File $file)
    {
        $this->storageService->calculateUserStorageUsage($file->user_id);
        $this->storageService->updateUserStorage($file->user()->first());
    }
}
