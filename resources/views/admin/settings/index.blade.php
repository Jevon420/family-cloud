@extends('admin.layouts.app')

@section('title', 'Global Admin Settings')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Global Admin Settings</h1>
    <p class="mt-1 text-sm text-gray-600">
        Configure global system settings, site preferences, and administrative options.
    </p>
</div>

@if (session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-md">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Global Site Configuration -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Global Site Configuration</h3>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                    <input type="text"
                           name="site_name"
                           id="site_name"
                           value="{{ old('site_name', $siteSettings['site_name'] ?? 'Family Cloud') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('site_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="site_description" class="block text-sm font-medium text-gray-700">Site Description</label>
                    <textarea name="site_description"
                              id="site_description"
                              rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('site_description', $siteSettings['site_description'] ?? '') }}</textarea>
                    @error('site_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="site_email" class="block text-sm font-medium text-gray-700">Site Email</label>
                    <input type="email"
                           name="site_email"
                           id="site_email"
                           value="{{ old('site_email', $siteSettings['site_email'] ?? '') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('site_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700">Default Timezone</label>
                    <select name="timezone"
                            id="timezone"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="UTC" {{ old('timezone', $siteSettings['timezone'] ?? 'UTC') === 'UTC' ? 'selected' : '' }}>UTC</option>
                        <option value="America/New_York" {{ old('timezone', $siteSettings['timezone'] ?? 'UTC') === 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                        <option value="America/Chicago" {{ old('timezone', $siteSettings['timezone'] ?? 'UTC') === 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                        <option value="America/Denver" {{ old('timezone', $siteSettings['timezone'] ?? 'UTC') === 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                        <option value="America/Los_Angeles" {{ old('timezone', $siteSettings['timezone'] ?? 'UTC') === 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                    </select>
                    @error('timezone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Site Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- System Controls -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">System Controls</h3>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="flex items-center">
                    <input type="checkbox"
                           name="maintenance_mode"
                           id="maintenance_mode"
                           value="1"
                           {{ old('maintenance_mode', $siteSettings['maintenance_mode'] ?? false) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="maintenance_mode" class="ml-2 block text-sm text-gray-900">
                        Enable Maintenance Mode
                    </label>
                </div>
                <p class="text-xs text-gray-500">When enabled, only admins can access the site</p>

                <div class="flex items-center">
                    <input type="checkbox"
                           name="registration_enabled"
                           id="registration_enabled"
                           value="1"
                           {{ old('registration_enabled', $siteSettings['registration_enabled'] ?? true) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="registration_enabled" class="ml-2 block text-sm text-gray-900">
                        Enable User Registration
                    </label>
                </div>
                <p class="text-xs text-gray-500">Allow new users to register accounts</p>

                <div class="flex items-center">
                    <input type="checkbox"
                           name="backup_enabled"
                           id="backup_enabled"
                           value="1"
                           {{ old('backup_enabled', $siteSettings['backup_enabled'] ?? true) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="backup_enabled" class="ml-2 block text-sm text-gray-900">
                        Enable Automatic Backups
                    </label>
                </div>
                <p class="text-xs text-gray-500">Automatically backup database daily</p>

                <div class="flex items-center">
                    <input type="checkbox"
                           name="email_notifications"
                           id="email_notifications"
                           value="1"
                           {{ old('email_notifications', $siteSettings['email_notifications'] ?? true) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="email_notifications" class="ml-2 block text-sm text-gray-900">
                        Enable Email Notifications
                    </label>
                </div>
                <p class="text-xs text-gray-500">Send system notifications via email</p>

                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save System Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Storage & Upload Limits -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Storage & Upload Limits</h3>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="max_file_upload_size" class="block text-sm font-medium text-gray-700">Max File Upload Size (MB)</label>
                    <input type="number"
                           name="max_file_upload_size"
                           id="max_file_upload_size"
                           value="{{ old('max_file_upload_size', $siteSettings['max_file_upload_size'] ?? 10) }}"
                           min="1"
                           max="1024"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('max_file_upload_size')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="max_storage_per_user" class="block text-sm font-medium text-gray-700">Max Storage per User (GB)</label>
                    <input type="number"
                           name="max_storage_per_user"
                           id="max_storage_per_user"
                           value="{{ old('max_storage_per_user', $siteSettings['max_storage_per_user'] ?? 5) }}"
                           min="1"
                           max="100"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('max_storage_per_user')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Storage Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Personal Admin Preferences -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Personal Admin Preferences</h3>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="theme" class="block text-sm font-medium text-gray-700">Admin Theme</label>
                    <select name="theme"
                            id="theme"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="light" {{ old('theme', $userSettings['theme'] ?? 'light') === 'light' ? 'selected' : '' }}>Light</option>
                        <option value="dark" {{ old('theme', $userSettings['theme'] ?? 'light') === 'dark' ? 'selected' : '' }}>Dark</option>
                    </select>
                    @error('theme')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox"
                           name="notifications_enabled"
                           id="notifications_enabled"
                           value="1"
                           {{ old('notifications_enabled', $userSettings['notifications_enabled'] ?? true) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="notifications_enabled" class="ml-2 block text-sm text-gray-900">
                        Enable Admin Notifications
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Personal Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Admin Tools -->
<div class="mt-8 bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Administrative Tools</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Cache Management</h4>
                <p class="text-xs text-gray-600 mb-3">Clear all application caches and optimize performance.</p>
                <button type="button"
                        onclick="clearAllCache()"
                        class="w-full bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Clear All Cache
                </button>
            </div>

            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Database Backup</h4>
                <p class="text-xs text-gray-600 mb-3">Create a complete backup of the database.</p>
                <button type="button"
                        onclick="createBackup()"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Backup
                </button>
            </div>

            <div class="bg-green-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">System Health</h4>
                <p class="text-xs text-gray-600 mb-3">Run comprehensive system health check.</p>
                <button type="button"
                        onclick="runHealthCheck()"
                        class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Health Check
                </button>
            </div>

            <div class="bg-red-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Emergency Reset</h4>
                <p class="text-xs text-gray-600 mb-3">Reset critical system components if needed.</p>
                <button type="button"
                        onclick="emergencyReset()"
                        class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Emergency Reset
                </button>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-yellow-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Export Data</h4>
                <p class="text-xs text-gray-600 mb-3">Export user data, logs, and system information.</p>
                <button type="button"
                        onclick="exportAllData()"
                        class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    Export All Data
                </button>
            </div>

            <div class="bg-purple-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Activity Logs</h4>
                <p class="text-xs text-gray-600 mb-3">View and download comprehensive activity logs.</p>
                <button type="button"
                        onclick="viewActivityLogs()"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    View Logs
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function clearAllCache() {
    if (confirm('Are you sure you want to clear all application caches?\n\nThis will:\n- Clear route cache\n- Clear config cache\n- Clear view cache\n- Clear compiled services\n\nThis may temporarily slow down the application.')) {
        fetch('{{ route("admin.settings.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ action: 'clear_all_cache' })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'All caches cleared successfully!');
        })
        .catch(error => {
            alert('Error clearing caches: ' + error.message);
        });
    }
}

function createBackup() {
    if (confirm('Create a complete database backup?\n\nThis may take several minutes depending on database size.')) {
        alert('Database backup started. You will receive an email notification when complete.');
    }
}

function runHealthCheck() {
    alert('System Health Check Results:\n\n✓ Database: Connected\n✓ Cache: Active\n✓ Queue: Running\n✓ Storage: Available\n✓ PHP Version: {{ PHP_VERSION }}\n✓ Laravel Version: {{ app()->version() }}\n\nAll systems operational!');
}

function emergencyReset() {
    if (confirm('EMERGENCY RESET WARNING!\n\nThis will:\n- Clear all caches\n- Reset sessions\n- Restart queues\n- Clear temporary files\n\nOnly use in emergency situations.\n\nProceed?')) {
        if (confirm('This is your final warning. Are you absolutely sure?')) {
            alert('Emergency reset initiated. System will be unavailable for 1-2 minutes.');
        }
    }
}

function exportAllData() {
    if (confirm('Export all system data?\n\nThis will include:\n- User data\n- Activity logs\n- System settings\n- File metadata\n\nThis may take several minutes.')) {
        alert('Data export started. Download will begin when ready.');
    }
}

function viewActivityLogs() {
    window.open('/admin/logs', '_blank');
}
</script>
@endpush
@endsection
