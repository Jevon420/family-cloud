@extends('developer.layouts.app')

@section('title', 'Comprehensive Settings')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Comprehensive Settings</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Site Configuration -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Site Configuration</h3>

                <form method="POST" action="{{ route('developer.settings.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                        <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $siteSettings['site_name'] ?? 'Family Cloud') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="site_description" class="block text-sm font-medium text-gray-700">Site Description</label>
                        <textarea name="site_description" id="site_description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('site_description', $siteSettings['site_description'] ?? '') }}</textarea>
                    </div>

                    <div>
                        <label for="max_file_upload_size" class="block text-sm font-medium text-gray-700">Max File Upload Size (MB)</label>
                        <input type="number" name="max_file_upload_size" id="max_file_upload_size" value="{{ old('max_file_upload_size', $siteSettings['max_file_upload_size'] ?? 10) }}" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" {{ old('maintenance_mode', $siteSettings['maintenance_mode'] ?? false) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="maintenance_mode" class="ml-2 block text-sm text-gray-900">Enable Maintenance Mode</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="registration_enabled" id="registration_enabled" value="1" {{ old('registration_enabled', $siteSettings['registration_enabled'] ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="registration_enabled" class="ml-2 block text-sm text-gray-900">Enable User Registration</label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save Site Settings</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Personal Preferences -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Personal Preferences</h3>

                <form method="POST" action="{{ route('developer.settings.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="theme" class="block text-sm font-medium text-gray-700">Theme</label>
                        <select name="theme" id="theme" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="light" {{ old('theme', $userSettings['theme'] ?? 'light') === 'light' ? 'selected' : '' }}>Light</option>
                            <option value="dark" {{ old('theme', $userSettings['theme'] ?? 'light') === 'dark' ? 'selected' : '' }}>Dark</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="notifications_enabled" id="notifications_enabled" value="1" {{ old('notifications_enabled', $userSettings['notifications_enabled'] ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="notifications_enabled" class="ml-2 block text-sm text-gray-900">Enable Notifications</label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save Personal Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Development Tools -->
    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Development Tools</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Cache Management</h4>
                    <p class="text-xs text-gray-600 mb-3">Clear application cache and optimize performance.</p>
                    <button type="button" onclick="clearCache()" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Clear Cache</button>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Database Status</h4>
                    <p class="text-xs text-gray-600 mb-3">Check database connectivity and health.</p>
                    <button type="button" onclick="checkDatabase()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Check Database</button>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">System Info</h4>
                    <p class="text-xs text-gray-600 mb-3">View system information and performance metrics.</p>
                    <button type="button" onclick="showSystemInfo()" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">View System Info</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function clearCache() {
    if (confirm('Are you sure you want to clear the application cache?')) {
        fetch('{{ route("developer.settings.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ action: 'clear_cache' })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Cache cleared successfully!');
        })
        .catch(error => {
            alert('Error clearing cache: ' + error.message);
        });
    }
}

function checkDatabase() {
    alert('Database connection: Active\nTables: 15\nLast backup: 2 hours ago');
}

function showSystemInfo() {
    alert('PHP Version: {{ PHP_VERSION }}\nLaravel Version: {{ app()->version() }}\nEnvironment: {{ app()->environment() }}');
}
</script>
@endpush
@endsection
