<header class="bg-white shadow-sm border-b">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <img src="{{ asset('storage/logos/family-cloud-logo.png') }}" alt="Family Cloud Logo" class="h-12 w-auto">
                        </a>
                    </div>

                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900">
                        Family Cloud <span class="text-sm bg-red-100  text-red-800 px-2 py-1 rounded-full">Global Admin</span>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <button type="button" class="sm:hidden fixed top-4 right-4 ml-4 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="mobile-menu-button">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="hidden sm:flex space-x-8 sm:-my-px sm:ml-10" id="desktop-menu">
                <a href="{{ route('admin.home') }}" class="@if(request()->routeIs('admin.home')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                    Dashboard
                </a>
                <a href="{{ route('admin.settings.index') }}" class="@if(request()->routeIs('admin.settings.*')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                    Settings
                </a>
                <a href="{{ route('admin.settings.users') }}" class="@if(request()->routeIs('admin.settings.users')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                    Users
                </a>
                <a href="{{ route('admin.settings.system') }}" class="@if(request()->routeIs('admin.settings.system')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                    System
                </a>
            </div>

            <!-- User dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 gap-4">
                <!-- Theme Toggle Dropdown -->
                <div class="relative inline-block text-left">
                    <button id="themeToggleDropdown" type="button" class="flex items-center gap-2 px-3 py-2 bg-white border border-gray-300 rounded-md shadow text-gray-700 hover:bg-gray-100 focus:outline-none transition-colors duration-200" aria-haspopup="true" aria-expanded="false">
                        <span id="themeDropdownIcon" class="transition-transform duration-300">
                            <!-- Icon will be set by JS -->
                        </span>
                        <span id="themeDropdownText">Theme</span>
                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 20 20" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l3-3 3 3m0 6l-3 3-3-3" /></svg>
                    </button>
                    <div id="themeDropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow-lg py-1 transition-all duration-200" role="menu">
                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200" data-theme="light" role="menuitem">
                            <span class="mr-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.93l-.71-.71M12 5a7 7 0 100 14a7 7 0 000-14z" /></svg></span> Light
                        </button>
                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200" data-theme="dark" role="menuitem">
                            <span class="mr-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.293 14.293A8 8 0 119.707 6.707a8.001 8.001 0 107.586 7.586z" /></svg></span> Dark
                        </button>
                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200" data-theme="system" role="menuitem">
                            <span class="mr-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.93l-.71-.71" /></svg></span> System
                        </button>
                    </div>
                </div>
                <!-- End Theme Toggle Dropdown -->
                <div class="relative">
                    <button @click="userMenuOpen = !userMenuOpen" type="button" class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button">
                        <span class="sr-only">Open user menu</span>
                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ auth()->user()->name }}">
                    </button>

                    <div x-show="userMenuOpen"
                         @click.away="userMenuOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                         role="menu"
                         aria-orientation="vertical"
                         aria-labelledby="user-menu-button"
                         tabindex="-1">
                        <div class="px-4 py-2 text-xs text-gray-500">
                            {{ auth()->user()->name }}
                        </div>

                        @if(auth()->user()->hasRole('Global Admin'))
                            <a href="{{ route('admin.home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Global Admin Dashboard</a>
                            <a href="{{ route('admin.settings.comprehensive.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Global Settings</a>
                        @elseif(auth()->user()->hasRole('Admin'))
                            <a href="{{ route('admin.home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Admin Dashboard</a>
                        @endif

                        @if(auth()->user()->hasRole('Developer'))
                            <a href="{{ route('developer.home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Developer Dashboard</a>
                        @endif

                        @if(auth()->user()->hasRole('Family'))
                            <a href="{{ route('family.home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Family Dashboard</a>
                        @endif

                        <a href="{{ route('family.storage.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">My Storage</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden sm:hidden" id="mobile-menu">
            <div class="px-4 py-2">
                <!-- Theme Toggle Dropdown for mobile -->
                <div class="relative inline-block text-left mb-2 w-full">
                    <button id="themeToggleDropdownMobile" type="button" class="flex items-center justify-between gap-2 px-3 py-2 w-full bg-white border border-gray-300 rounded-md shadow text-gray-700 hover:bg-gray-100 focus:outline-none transition-colors duration-200" aria-haspopup="true" aria-expanded="false">
                        <div class="flex items-center">
                            <span id="themeDropdownIconMobile" class="transition-transform duration-300 mr-2">
                                <!-- Icon will be set by JS -->
                            </span>
                            <span id="themeDropdownTextMobile">Theme</span>
                        </div>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 20 20" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l3-3 3 3m0 6l-3 3-3-3" /></svg>
                    </button>
                    <div id="themeDropdownMenuMobile" class="hidden absolute z-50 left-0 right-0 mt-2 bg-white border border-gray-200 rounded-md shadow-lg py-1 transition-all duration-200" role="menu">
                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200" data-theme="light" role="menuitem">
                            <span class="mr-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.93l-.71-.71M12 5a7 7 0 100 14a7 7 0 000-14z" /></svg></span> Light
                        </button>
                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200" data-theme="dark" role="menuitem">
                            <span class="mr-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.293 14.293A8 8 0 119.707 6.707a8.001 8.001 0 107.586 7.586z" /></svg></span> Dark
                        </button>
                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200" data-theme="system" role="menuitem">
                            <span class="mr-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.93l-.71-.71" /></svg></span> System
                        </button>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
            <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
            <a href="{{ route('admin.settings.users') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Users</a>
            <a href="{{ route('admin.settings.system') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">System</a>
        </div>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenuDropdown = document.querySelector('[x-show="userMenuOpen"]');

        // Ensure dropdown is closed on load
        let userMenuOpen = false;
        userMenuDropdown.style.display = 'none';

        userMenuButton.addEventListener('click', function () {
            userMenuOpen = !userMenuOpen;
            userMenuDropdown.style.display = userMenuOpen ? 'block' : 'none';
        });

        document.addEventListener('click', function (e) {
            if (!userMenuButton.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                userMenuDropdown.style.display = 'none';
                userMenuOpen = false;
            }
        });
    });
</script>
