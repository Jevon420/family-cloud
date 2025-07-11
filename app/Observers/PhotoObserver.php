<?php

namespace App\Observers;

use App\Models\Photo;
use App\Services\StorageManagementService;

class PhotoObserver
{
    protected $storageService;

    public function __construct(StorageManagementService $storageService)
    {
        $this->storageService = $storageService;
    }

    public function created(Photo $photo)
    {
        $this->updateStorage($photo);
    }

    public function updated(Photo $photo)
    {
        $this->updateStorage($photo);
    }

    public function deleted(Photo $photo)
    {
        $this->updateStorage($photo);
    }

    protected function updateStorage(Photo $photo)
    {
        $this->storageService->calculateUserStorageUsage($photo->user_id);
        $this->storageService->updateUserStorage($photo->user()->first());
    }
}
