<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StorageManagementService;
use Illuminate\Http\Request;

class StorageManagementController extends Controller
{
    protected $storageService;

    public function __construct(StorageManagementService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Display storage management dashboard
     */
    public function index()
    {
        $statistics = $this->storageService->getStorageStatistics();

        return view('admin.storage.index', compact('statistics'));
    }

    /**
     * Update storage settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'total_storage_gb' => 'required|numeric|min:0',
            'storage_percentage' => 'required|numeric|min:1|max:100',
            'auto_detect_storage' => 'boolean',
        ]);

        $this->storageService->updateStorageSettings([
            'total_storage_gb' => $request->total_storage_gb,
            'storage_percentage' => $request->storage_percentage,
            'auto_detect_storage' => $request->has('auto_detect_storage'),
        ]);

        // After updating settings, recalculate actual storage usage
        $this->storageService->recalculateUserStorage();

        return redirect()->route('admin.storage.index')
            ->with('success', 'Storage settings updated and user quotas recalculated successfully.');
    }

    /**
     * Recalculate all user quotas
     */
    public function recalculateQuotas()
    {
        // First update the storage quotas based on current settings
        $this->storageService->updateAllUserQuotas();

        // Then recalculate actual storage usage for each user
        $this->storageService->recalculateUserStorage();

        return redirect()->route('admin.storage.index')
            ->with('success', 'User storage quotas and usage recalculated successfully.');
    }
}
