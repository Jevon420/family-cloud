<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\StorageManagementService;

class CheckUserStorage
{
    protected $storageService;

    public function __construct(StorageManagementService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasFile('file') || $request->hasFile('photo') || $request->hasFile('image') || $request->hasFile('cover_image')) {
            $files = [];

            if ($request->hasFile('file')) $files[] = $request->file('file');
            if ($request->hasFile('photo')) $files[] = $request->file('photo');
            if ($request->hasFile('image')) $files[] = $request->file('image');
            if ($request->hasFile('cover_image')) $files[] = $request->file('cover_image');

            $totalSize = 0;
            foreach ($files as $file) {
                $totalSize += $file->getSize();
            }

            if (!$this->storageService->canUserUpload(auth()->id(), $totalSize)) {
                return redirect()->back()
                    ->withErrors(['storage' => 'You have exceeded your storage quota. Please free up some space or contact an administrator.'])
                    ->withInput();
            }
        }

        return $next($request);
    }
}
