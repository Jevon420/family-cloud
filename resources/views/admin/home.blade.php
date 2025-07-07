@extends('admin.layouts.app')

@section('title', 'Global Admin Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Global Admin Dashboard</h1>
    <p class="mt-1 text-sm text-gray-600">
        Welcome to the Family Cloud global administration panel. Monitor all system components, manage users, and configure global settings.
    </p>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_users'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Photos</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_photos'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Files</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_files'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Storage Used</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $stats['storage_used'] ?? '0 MB' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Role Distribution -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">User Role Distribution</h3>
            <div class="space-y-3">
                @foreach($roleStats as $role => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-3
                            @if($role === 'admin') bg-red-500
                            @elseif($role === 'developer') bg-blue-500
                            @elseif($role === 'family') bg-green-500
                            @else bg-gray-500
                            @endif"></div>
                        <span class="text-sm font-medium text-gray-900 capitalize">{{ $role }}</span>
                    </div>
                    <span class="text-sm text-gray-500">{{ $count }} users</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">System Health</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900">Database Connection</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Active
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900">Cache System</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Running
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900">Queue Workers</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Operational
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900">Storage Available</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ $stats['storage_available'] ?? '1.2 GB' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent System Activity</h3>
        <div class="flow-root">
            <ul class="-mb-8">
                <li class="relative pb-8">
                    <div class="relative flex space-x-3">
                        <div>
                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">New user <strong class="text-gray-900">John Doe</strong> registered</p>
                            </div>
                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                <time datetime="2025-01-01">2 hours ago</time>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="relative pb-8">
                    <div class="relative flex space-x-3">
                        <div>
                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">System backup completed successfully</p>
                            </div>
                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                <time datetime="2025-01-01">4 hours ago</time>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="relative">
                    <div class="relative flex space-x-3">
                        <div>
                            <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">Storage usage warning: 85% capacity reached</p>
                            </div>
                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                <time datetime="2025-01-01">6 hours ago</time>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h4 class="text-lg font-medium text-gray-900 mb-4">Dashboard Access</h4>
        <div class="space-y-3">
            <a href="{{ route('admin.developer') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                Developer Portal
            </a>
            <a href="{{ route('admin.family') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                Family Portal
            </a>
            <a href="{{ route('home') }}" class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                Public Site
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h4 class="text-lg font-medium text-gray-900 mb-4">System Management</h4>
        <div class="space-y-3">
            <button onclick="clearCache()" class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                Clear System Cache
            </button>
            <button onclick="backupDatabase()" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                Backup Database
            </button>
            <a href="{{ route('admin.settings.system') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                System Settings
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h4 class="text-lg font-medium text-gray-900 mb-4">User Management</h4>
        <div class="space-y-3">
            <a href="{{ route('admin.settings.users') }}" class="block w-full bg-red-600 hover:bg-red-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                Manage Users
            </a>
            <button onclick="exportUsers()" class="block w-full bg-orange-600 hover:bg-orange-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                Export User Data
            </button>
            <button onclick="viewLogs()" class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                View Activity Logs
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function clearCache() {
    if (confirm('Are you sure you want to clear the system cache?')) {
        alert('System cache cleared successfully!');
    }
}

function backupDatabase() {
    if (confirm('Are you sure you want to create a database backup?')) {
        alert('Database backup started. You will be notified when complete.');
    }
}

function exportUsers() {
    if (confirm('Export all user data to CSV?')) {
        alert('User data export started. Download will begin shortly.');
    }
}

function viewLogs() {
    alert('Activity logs opened in new window.');
}
</script>
@endpush
@endsection
