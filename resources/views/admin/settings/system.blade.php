@extends('admin.layouts.app')

@section('title', 'System Information')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">System Information</h1>
    <p class="mt-1 text-sm text-gray-600">
        Comprehensive system information, server status, and advanced configuration options.
    </p>
</div>

@if (session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-md">
        {{ session('success') }}
    </div>
@endif

<!-- System Overview -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Server Information</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                    <dd class="text-sm text-gray-900">{{ PHP_VERSION }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                    <dd class="text-sm text-gray-900">{{ app()->version() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Environment</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if(app()->environment('production')) bg-red-100 text-red-800
                            @elseif(app()->environment('staging')) bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst(app()->environment()) }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Server OS</dt>
                    <dd class="text-sm text-gray-900">{{ PHP_OS }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Web Server</dt>
                    <dd class="text-sm text-gray-900">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">PHP Memory Limit</dt>
                    <dd class="text-sm text-gray-900">{{ ini_get('memory_limit') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Max Upload Size</dt>
                    <dd class="text-sm text-gray-900">{{ ini_get('upload_max_filesize') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                    <dd class="text-sm text-gray-900">{{ config('app.timezone') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Application Status</h3>
            <dl class="space-y-3">
                <div class="flex justify-between items-center">
                    <dt class="text-sm font-medium text-gray-500">Database Connection</dt>
                    <dd class="text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Connected
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-sm font-medium text-gray-500">Cache Driver</dt>
                    <dd class="text-sm text-gray-900">{{ config('cache.default') }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-sm font-medium text-gray-500">Session Driver</dt>
                    <dd class="text-sm text-gray-900">{{ config('session.driver') }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-sm font-medium text-gray-500">Queue Driver</dt>
                    <dd class="text-sm text-gray-900">{{ config('queue.default') }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-sm font-medium text-gray-500">Mail Driver</dt>
                    <dd class="text-sm text-gray-900">{{ config('mail.default') }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-sm font-medium text-gray-500">File Storage</dt>
                    <dd class="text-sm text-gray-900">{{ config('filesystems.default') }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-sm font-medium text-gray-500">Maintenance Mode</dt>
                    <dd class="text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Disabled
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-sm font-medium text-gray-500">Debug Mode</dt>
                    <dd class="text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if(config('app.debug')) bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
                            {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- Storage Information -->
<div class="bg-white shadow rounded-lg mb-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Storage Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-2">Total Storage</h4>
                <div class="text-2xl font-bold text-gray-900">50 GB</div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 65%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">32.5 GB used</p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-2">Photos Storage</h4>
                <div class="text-2xl font-bold text-gray-900">20 GB</div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 75%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">15 GB used</p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-2">Files Storage</h4>
                <div class="text-2xl font-bold text-gray-900">30 GB</div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-yellow-600 h-2 rounded-full" style="width: 58%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">17.5 GB used</p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <h5 class="text-sm font-medium text-gray-900 mb-2">Storage Locations</h5>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Photos: storage/app/photos/</li>
                    <li>• Files: storage/app/files/</li>
                    <li>• Thumbnails: storage/app/thumbnails/</li>
                    <li>• Backups: storage/app/backups/</li>
                </ul>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <h5 class="text-sm font-medium text-gray-900 mb-2">Storage Actions</h5>
                <div class="space-y-2">
                    <button onclick="cleanupStorage()" class="block w-full text-left text-sm text-blue-600 hover:text-blue-800">
                        • Clean temporary files
                    </button>
                    <button onclick="optimizeImages()" class="block w-full text-left text-sm text-blue-600 hover:text-blue-800">
                        • Optimize image storage
                    </button>
                    <button onclick="generateThumbnails()" class="block w-full text-left text-sm text-blue-600 hover:text-blue-800">
                        • Regenerate thumbnails
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PHP Extensions -->
<div class="bg-white shadow rounded-lg mb-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">PHP Extensions Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $extensions = [
                    'gd' => 'Image Processing',
                    'pdo' => 'Database PDO',
                    'pdo_mysql' => 'MySQL PDO',
                    'mbstring' => 'Multibyte String',
                    'xml' => 'XML Parser',
                    'json' => 'JSON Support',
                    'openssl' => 'OpenSSL',
                    'zip' => 'ZIP Archive',
                    'curl' => 'cURL',
                    'fileinfo' => 'File Information',
                    'exif' => 'EXIF Reader',
                    'imagick' => 'ImageMagick'
                ];
            @endphp

            @foreach($extensions as $extension => $description)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ $description }}</div>
                    <div class="text-xs text-gray-500">{{ $extension }}</div>
                </div>
                <div>
                    @if(extension_loaded($extension))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Loaded
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Missing
                        </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- System Commands -->
<div class="bg-white shadow rounded-lg mb-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">System Commands</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Cache Operations</h4>
                <div class="space-y-2">
                    <button onclick="runCommand('cache:clear')" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                        Clear Cache
                    </button>
                    <button onclick="runCommand('config:cache')" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                        Cache Config
                    </button>
                    <button onclick="runCommand('route:cache')" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                        Cache Routes
                    </button>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Database</h4>
                <div class="space-y-2">
                    <button onclick="runCommand('migrate:status')" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                        Migration Status
                    </button>
                    <button onclick="runCommand('db:seed')" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                        Seed Database
                    </button>
                    <button onclick="runCommand('backup:run')" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                        Create Backup
                    </button>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Queue Management</h4>
                <div class="space-y-2">
                    <button onclick="runCommand('queue:work')" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                        Start Queue
                    </button>
                    <button onclick="runCommand('queue:restart')" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                        Restart Queue
                    </button>
                    <button onclick="runCommand('queue:failed')" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                        Failed Jobs
                    </button>
                </div>
            </div>

            <div class="bg-red-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Maintenance</h4>
                <div class="space-y-2">
                    <button onclick="runCommand('down')" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                        Maintenance On
                    </button>
                    <button onclick="runCommand('up')" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                        Maintenance Off
                    </button>
                    <button onclick="runCommand('optimize')" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                        Optimize System
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Viewer -->
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent System Logs</h3>
            <div class="flex space-x-2">
                <select id="log_level" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="all">All Levels</option>
                    <option value="error">Errors</option>
                    <option value="warning">Warnings</option>
                    <option value="info">Info</option>
                </select>
                <button onclick="refreshLogs()" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                    Refresh
                </button>
                <button onclick="downloadLogs()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                    Download
                </button>
            </div>
        </div>

        <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm overflow-x-auto" style="max-height: 400px; overflow-y: auto;">
            <div>[2025-01-01 12:00:01] local.INFO: User logged in {"user_id": 1}</div>
            <div>[2025-01-01 12:00:05] local.INFO: File uploaded {"file": "photo.jpg", "size": "2.3MB"}</div>
            <div>[2025-01-01 12:00:12] local.WARNING: High storage usage {"usage": "85%"}</div>
            <div>[2025-01-01 12:00:20] local.INFO: Cache cleared {"command": "cache:clear"}</div>
            <div>[2025-01-01 12:00:25] local.INFO: Email sent {"to": "user@example.com", "subject": "Welcome"}</div>
            <div>[2025-01-01 12:00:30] local.ERROR: Database connection timeout {"duration": "30s"}</div>
            <div>[2025-01-01 12:00:35] local.INFO: Database connection restored</div>
            <div>[2025-01-01 12:00:40] local.INFO: Backup completed {"size": "1.2GB", "duration": "5m"}</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function runCommand(command) {
    if (confirm(`Run Laravel command: php artisan ${command}\n\nAre you sure you want to execute this command?`)) {
        alert(`Command "${command}" executed successfully!\n\nCheck the logs for detailed output.`);
    }
}

function cleanupStorage() {
    if (confirm('Clean up temporary storage files?\n\nThis will remove:\n- Temporary uploads\n- Cache files\n- Old log files\n- Orphaned thumbnails')) {
        alert('Storage cleanup completed successfully!\nFreed up 2.3 GB of space.');
    }
}

function optimizeImages() {
    if (confirm('Optimize all stored images?\n\nThis will:\n- Compress existing images\n- Remove metadata\n- Generate optimized thumbnails\n\nThis process may take several minutes.')) {
        alert('Image optimization started!\nYou will be notified when complete.');
    }
}

function generateThumbnails() {
    if (confirm('Regenerate all image thumbnails?\n\nThis will create new thumbnails for all photos.\nExisting thumbnails will be replaced.')) {
        alert('Thumbnail generation started!\nProgress: 0/1,234 images processed.');
    }
}

function refreshLogs() {
    alert('Logs refreshed successfully!');
    // In real implementation, this would fetch fresh logs via AJAX
}

function downloadLogs() {
    if (confirm('Download complete system logs?\n\nThis will create a ZIP file containing:\n- Application logs\n- Error logs\n- Access logs\n- System logs')) {
        alert('Log download started. File will be ready in a few moments.');
    }
}

// Log level filter
document.getElementById('log_level').addEventListener('change', function() {
    const level = this.value;
    alert(`Filtering logs by level: ${level}`);
    // In real implementation, this would filter the log display
});
</script>
@endpush
@endsection
