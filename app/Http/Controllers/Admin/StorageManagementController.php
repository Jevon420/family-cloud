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

        return redirect()->route('admin.storage.index')
            ->with('success', 'Storage settings updated successfully.');
    }

    /**
     * Recalculate all user quotas
     */
    public function recalculateQuotas()
    {
        $this->storageService->updateAllUserQuotas();

        return redirect()->route('admin.storage.index')
            ->with('success', 'User storage quotas recalculated successfully.');
    }
}
