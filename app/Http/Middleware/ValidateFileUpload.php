<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SettingsService;

class ValidateFileUpload
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($request->hasFile('file') || $request->hasFile('files')) {
            $files = $request->hasFile('files') ? $request->file('files') : [$request->file('file')];

            foreach ($files as $file) {
                if (!$file) continue;

                // Check file size
                $maxSize = $this->settingsService->getMaxFileUploadSize() * 1024 * 1024; // Convert MB to bytes
                if ($file->getSize() > $maxSize) {
                    return back()->withErrors([
                        'file' => "File size exceeds maximum allowed size of {$this->settingsService->getMaxFileUploadSize()} MB."
                    ]);
                }

                // Check file type
                $allowedTypes = $this->settingsService->getAllowedFileTypes();
                $extension = strtolower($file->getClientOriginalExtension());

                if (!in_array($extension, $allowedTypes)) {
                    return back()->withErrors([
                        'file' => "File type '{$extension}' is not allowed. Allowed types: " . implode(', ', $allowedTypes)
                    ]);
                }
            }
        }

        return $next($request);
    }
}
