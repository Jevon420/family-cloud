<aside class="bg-white dark:bg-gray-900 shadow-sm border-r dark:border-gray-700 w-64 flex-shrink-0 sm:block hidden" id="sidebar">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            @if(auth()->user()->hasRole('Global Admin'))
                Global Admin
            @else
                Admin Panel
            @endif
        </h2>
        <nav class="space-y-2">
            <a href="{{ route('admin.home') }}" class="@if(request()->routeIs('admin.home')) bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 @else text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('admin.home')) text-indigo-500 dark:text-indigo-400 @else text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z" />
                </svg>
                Admin Dashboard
            </a>

            <a href="{{ route('admin.settings.index') }}" class="@if(request()->routeIs('admin.settings.index')) bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 @else text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('admin.settings.index')) text-indigo-500 dark:text-indigo-400 @else text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Basic Settings
            </a>

            @if(auth()->user()->hasRole('Global Admin'))
            <a href="{{ route('admin.settings.comprehensive.index') }}" class="@if(request()->routeIs('admin.settings.comprehensive.*')) bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 @else text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('admin.settings.comprehensive.*')) text-indigo-500 dark:text-indigo-400 @else text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                </svg>
                <span class="flex items-center">
                    Advanced Settings
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">Global Admin</span>
                </span>
            </a>
            @endif

            <a href="{{ route('admin.settings.users') }}" class="@if(request()->routeIs('admin.settings.users*')) bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 @else text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('admin.settings.users*')) text-indigo-500 dark:text-indigo-400 @else text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                User Management
            </a>

            <a href="{{ route('admin.galleries.index') }}" class="@if(request()->routeIs('admin.galleries.*')) bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 @else text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('admin.galleries.*')) text-indigo-500 dark:text-indigo-400 @else text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Gallery Management
            </a>

            <a href="{{ route('admin.photos.index') }}" class="@if(request()->routeIs('admin.photos.*')) bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 @else text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('admin.photos.*')) text-indigo-500 dark:text-indigo-400 @else text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Photo Management
            </a>

            <a href="{{ route('admin.files.index') }}" class="@if(request()->routeIs('admin.files.*')) bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 @else text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('admin.files.*')) text-indigo-500 dark:text-indigo-400 @else text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                File Management
            </a>

            <a href="{{ route('admin.folders.index') }}" class="@if(request()->routeIs('admin.folders.*')) bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 @else text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('admin.folders.*')) text-indigo-500 dark:text-indigo-400 @else text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                </svg>
                Folder Management
            </a>

            <a href="{{ route('admin.settings.system') }}" class="@if(request()->routeIs('admin.settings.system*')) bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 @else text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('admin.settings.system*')) text-indigo-500 dark:text-indigo-400 @else text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
                System Info
            </a>
        </nav>

        <div class="mt-8">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">All Dashboards</h3>
            <nav class="space-y-1">
                <a href="{{ route('admin.developer') }}" class="text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 mr-3 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                    Developer Portal
                </a>
                <a href="{{ route('admin.family') }}" class="text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 mr-3 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Family Portal
                </a>
                <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400 mr-3 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Public Site
                </a>
            </nav>
        </div>

        <div class="mt-8">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">Quick Actions</h3>
            <nav class="space-y-1">
                <a href="#" onclick="clearCache()" class="text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                    Clear Cache
                </a>
                <a href="#" onclick="viewLogs()" class="text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                    View Logs
                </a>
                <a href="#" onclick="backupDatabase()" class="text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                    Backup Database
                </a>
            </nav>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Toggle -->
<button type="button" class="sm:hidden fixed top-4 left-4 bg-indigo-600 text-white p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="sidebar-toggle">
    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<script>
    document.getElementById('sidebar-toggle').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('hidden');
    });
</script>
