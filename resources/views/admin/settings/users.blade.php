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
                        <dd class="text-lg font-medium text-gray-900">{{ $totalUsers }}</dd>
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
                        <dd class="text-lg font-medium text-gray-900">{{ $totalUsers }}</dd>
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
                        <dd class="text-lg font-medium text-gray-900">{{ $totalUsers }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $totalUsers }}</dd>
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
                    <!-- Sample Users - This would be populated from the controller -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=John+Doe&color=7F9CF5&background=EBF4FF" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">John Doe</div>
                                    <div class="text-sm text-gray-500">john@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Admin
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            2 hours ago
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            1.2 GB / 5 GB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2">
                                <button type="button" onclick="editUser(1)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button type="button" onclick="changeUserRole(1)" class="text-blue-600 hover:text-blue-900">Change Role</button>
                                <button type="button" onclick="deactivateUser(1)" class="text-red-600 hover:text-red-900">Deactivate</button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Jane+Smith&color=7F9CF5&background=EBF4FF" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Jane Smith</div>
                                    <div class="text-sm text-gray-500">jane@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Developer
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            1 day ago
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            0.8 GB / 5 GB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2">
                                <button type="button" onclick="editUser(2)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button type="button" onclick="changeUserRole(2)" class="text-blue-600 hover:text-blue-900">Change Role</button>
                                <button type="button" onclick="deactivateUser(2)" class="text-red-600 hover:text-red-900">Deactivate</button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Bob+Johnson&color=7F9CF5&background=EBF4FF" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Bob Johnson</div>
                                    <div class="text-sm text-gray-500">bob@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Family
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            3 days ago
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            2.1 GB / 5 GB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2">
                                <button type="button" onclick="editUser(3)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button type="button" onclick="changeUserRole(3)" class="text-blue-600 hover:text-blue-900">Change Role</button>
                                <button type="button" onclick="deactivateUser(3)" class="text-red-600 hover:text-red-900">Deactivate</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
                <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">3</span> of <span class="font-medium">3</span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">1</a>
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </nav>
                </div>
            </div>
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
