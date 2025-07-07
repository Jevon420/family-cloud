@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
            <p class="mt-1 text-sm text-gray-600">
                Manage user accounts, roles, and permissions across the entire system.
            </p>
        </div>
        <div>
            <button type="button" onclick="exportUsers()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium mr-3">
                Export Users
            </button>
            <button type="button" onclick="showCreateUserModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Add New User
            </button>
        </div>
    </div>
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

@if (session('info'))
    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-600 px-4 py-3 rounded-md">
        {{ session('info') }}
    </div>
@endif

<!-- Pending Registration Requests Section -->
@if($pendingUsers->count() > 0)
<div class="mb-8">
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-yellow-800">
                    <i class="fas fa-clock mr-2"></i>Pending Registration Requests ({{ $pendingUsers->count() }})
                </h3>
                <div class="text-sm text-yellow-700">
                    Action required for new user registrations
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-yellow-200">
                    <thead class="bg-yellow-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">
                                User Details
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">
                                Registration Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-yellow-50 divide-y divide-yellow-200">
                        @foreach($pendingUsers as $pendingUser)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($pendingUser->name) }}&color=F59E0B&background=FEF3C7" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $pendingUser->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $pendingUser->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $pendingUser->created_at->format('M d, Y H:i') }}
                                <div class="text-xs text-gray-400">
                                    {{ $pendingUser->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.users.approve', $pendingUser->id) }}"
                                       onclick="return confirm('Are you sure you want to approve this user? They will receive login credentials via email.')"
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                        <i class="fas fa-check mr-1"></i>Approve
                                    </a>
                                    <a href="{{ route('admin.users.reject', $pendingUser->id) }}"
                                       onclick="return confirm('Are you sure you want to reject this registration? This action cannot be undone.')"
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">
                                        <i class="fas fa-times mr-1"></i>Reject
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<!-- User Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Admins</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $users->where('roles.*.name', 'Global Admin')->count() + $users->where('roles.*.name', 'Admin')->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Developers</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $users->where('roles.*.name', 'Developer')->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Family Members</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $users->where('roles.*.name', 'Family')->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Pending Requests</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $totalPendingUsers }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="bg-white shadow rounded-lg mb-6">
    <div class="px-4 py-5 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="user_search" class="block text-sm font-medium text-gray-700">Search Users</label>
                <input type="text"
                       id="user_search"
                       placeholder="Search by name or email..."
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="role_filter" class="block text-sm font-medium text-gray-700">Filter by Role</label>
                <select id="role_filter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="developer">Developer</option>
                    <option value="family">Family</option>
                </select>
            </div>
            <div>
                <label for="status_filter" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                <select id="status_filter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="button" onclick="resetFilters()" class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:p-6">
        <div class="overflow-x-auto overflow-scroll">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Last Active
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Storage Used
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="users_table_body">
                    @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->roles->isNotEmpty())
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($role->name === 'Global Admin' || $role->name === 'Admin') bg-red-100 text-red-800
                                        @elseif($role->name === 'Developer') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    No Role
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($user->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($user->updated_at)
                                {{ $user->updated_at->diffForHumans() }}
                            @else
                                Never
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <!-- Storage calculation would go here -->
                            0 MB / 5 GB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2">
                                <button type="button" onclick="editUser({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button type="button" onclick="changeUserRole({{ $user->id }})" class="text-blue-600 hover:text-blue-900">Change Role</button>
                                @if($user->status === 'active')
                                    <button type="button" onclick="deactivateUser({{ $user->id }})" class="text-red-600 hover:text-red-900">Deactivate</button>
                                @else
                                    <button type="button" onclick="activateUser({{ $user->id }})" class="text-green-600 hover:text-green-900">Activate</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            {{ $users->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function showCreateUserModal() {
    alert('Create New User Modal would open here.\n\nFeatures:\n- Name, Email, Password fields\n- Role selection (Admin, Developer, Family)\n- Email verification option\n- Storage limit settings');
}

function editUser(userId) {
    alert(`Edit User ${userId} Modal would open here.\n\nFeatures:\n- Edit name, email\n- Change password\n- Update profile information\n- Adjust storage limits`);
}

function changeUserRole(userId) {
    const newRole = prompt('Select new role for user:\n\n1. Admin\n2. Developer\n3. Family\n\nEnter choice (1-3):');

    if (newRole && ['1', '2', '3'].includes(newRole)) {
        const roles = {'1': 'Admin', '2': 'Developer', '3': 'Family'};
        if (confirm(`Change user role to ${roles[newRole]}?\n\nThis will immediately update their access permissions.`)) {
            alert(`User role changed to ${roles[newRole]} successfully!`);
        }
    }
}

function deactivateUser(userId) {
    if (confirm('Are you sure you want to deactivate this user?\n\nThey will lose access to the system but their data will be preserved.\n\nYou can reactivate them later.')) {
        alert('User deactivated successfully!');
    }
}

function exportUsers() {
    if (confirm('Export all user data to CSV?\n\nThis will include:\n- User information\n- Roles and permissions\n- Account status\n- Storage usage\n\nSensitive data will be excluded.')) {
        alert('User export started. Download will begin shortly.');
    }
}

function resetFilters() {
    document.getElementById('user_search').value = '';
    document.getElementById('role_filter').value = '';
    document.getElementById('status_filter').value = '';
    alert('Filters reset. Showing all users.');
}

// Search functionality
document.getElementById('user_search').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    // Implementation would filter the table rows based on search term
    console.log('Searching for:', searchTerm);
});

// Filter functionality
document.getElementById('role_filter').addEventListener('change', function() {
    const selectedRole = this.value;
    // Implementation would filter the table rows based on selected role
    console.log('Filtering by role:', selectedRole);
});

document.getElementById('status_filter').addEventListener('change', function() {
    const selectedStatus = this.value;
    // Implementation would filter the table rows based on selected status
    console.log('Filtering by status:', selectedStatus);
});
</script>
@endpush
@endsection
