<header class="bg-white dark:bg-gray-900 shadow-sm border-b dark:border-gray-700">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Family Cloud <span class="text-sm bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-100 px-2 py-1 rounded-full">Global Admin</span>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <button type="button" class="sm:hidden ml-4 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="mobile-menu-button">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="hidden sm:flex space-x-8 sm:-my-px sm:ml-10" id="desktop-menu">
                <a href="{{ route('admin.home') }}" class="@if(request()->routeIs('admin.home')) border-indigo-500 text-gray-900 dark:text-white @else border-transparent text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 hover:border-gray-300 dark:hover:border-gray-500 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                    Dashboard
                </a>
                <a href="{{ route('admin.settings.index') }}" class="@if(request()->routeIs('admin.settings.*')) border-indigo-500 text-gray-900 dark:text-white @else border-transparent text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 hover:border-gray-300 dark:hover:border-gray-500 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                    Settings
                </a>
                <a href="{{ route('admin.settings.users') }}" class="@if(request()->routeIs('admin.settings.users')) border-indigo-500 text-gray-900 dark:text-white @else border-transparent text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 hover:border-gray-300 dark:hover:border-gray-500 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                    Users
                </a>
                <a href="{{ route('admin.settings.system') }}" class="@if(request()->routeIs('admin.settings.system')) border-indigo-500 text-gray-900 dark:text-white @else border-transparent text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 hover:border-gray-300 dark:hover:border-gray-500 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                    System
                </a>
            </div>

            <!-- User dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 gap-4">
                <!-- Theme Toggle Dropdown -->
                <div class="relative inline-block text-left">
                    <button id="themeToggleDropdown" type="button" class="flex items-center gap-2 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors duration-200" aria-haspopup="true" aria-expanded="false">
                        <span id="themeDropdownIcon" class="transition-transform duration-300">
                            <!-- Icon will be set by JS -->
                        </span>
                        <span id="themeDropdownText">Theme</span>
                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 20 20" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l3-3 3 3m0 6l-3 3-3-3" /></svg>
                    </button>
                    <div id="themeDropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg py-1 transition-all duration-200" role="menu">
                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" data-theme="light" role="menuitem">
                            <span class="mr-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.93l-.71-.71M12 5a7 7 0 100 14a7 7 0 000-14z" /></svg></span> Light
                        </button>
                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" data-theme="dark" role="menuitem">
                            <span class="mr-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.293 14.293A8 8 0 019.707 6.707a8.001 8.001 0 107.586 7.586z" /></svg></span> Dark
                        </button>
                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" data-theme="system" role="menuitem">
                            <span class="mr-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.93l-.71-.71" /></svg></span> System
                        </button>
                    </div>
                </div>
                <!-- End Theme Toggle Dropdown -->
                <div class="relative">
                    <button type="button" class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button">
                        <span class="sr-only">Open user menu</span>
                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ auth()->user()->name }}">
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden sm:hidden" id="mobile-menu">
            <div class="px-4 py-2">
                <!-- Theme Toggle Dropdown for mobile -->
                <div class="relative inline-block text-left mb-2 w-full">
                    <button id="themeToggleDropdownMobile" type="button" class="flex items-center justify-between gap-2 px-3 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors duration-200" aria-haspopup="true" aria-expanded="false">
                        <div class="flex items-center">
                            <span id="themeDropdownIconMobile" class="transition-transform duration-300">
                                <!-- Icon will be set by JS -->
                            </span>
                            <span id="themeDropdownTextMobile">Theme</span>
                        </div>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 20 20" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l3-3 3 3m0 6l-3 3-3-3" /></svg>
                    </button>
                    <div id="themeDropdownMenuMobile" class="hidden absolute z-50 left-0 right-0 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg py-1 transition-all duration-200" role="menu">
                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" data-theme="light" role="menuitem">
                            <span class="mr-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.93l-.71-.71M12 5a7 7 0 100 14a7 7 0 000-14z" /></svg></span> Light
                        </button>
                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" data-theme="dark" role="menuitem">
                            <span class="mr-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.293 14.293A8 8 0 019.707 6.707a8.001 8.001 0 107.586 7.586z" /></svg></span> Dark
                        </button>
                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" data-theme="system" role="menuitem">
                            <span class="mr-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.93l-.71-.71" /></svg></span> System
                        </button>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.home') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Dashboard</a>
            <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Settings</a>
            <a href="{{ route('admin.settings.users') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Users</a>
            <a href="{{ route('admin.settings.system') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">System</a>
        </div>
    </nav>
</header>

<script>
    // Wait for DOMContentLoaded to ensure all elements exist
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('mobile-menu-button').addEventListener('click', function(e) {
            e.stopPropagation();
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
            // Close theme dropdown if open
            const themeDropdownMenuMobile = document.getElementById('themeDropdownMenuMobile');
            if (themeDropdownMenuMobile && !themeDropdownMenuMobile.classList.contains('hidden')) {
                themeDropdownMenuMobile.classList.add('hidden');
            }
        });

        // Sync theme across all elements that have the same theme
        function syncAllThemeElements(theme) {
            document.querySelectorAll('[data-theme]').forEach(btn => {
                const btnTheme = btn.getAttribute('data-theme');
                if (btnTheme === theme) {
                    btn.classList.add('bg-gray-200', 'dark:bg-gray-700');
                } else {
                    btn.classList.remove('bg-gray-200', 'dark:bg-gray-700');
                }
            });
            ['', 'Mobile'].forEach(suffix => {
                const iconElement = document.getElementById(`themeDropdownIcon${suffix}`);
                const textElement = document.getElementById(`themeDropdownText${suffix}`);
                if (iconElement && textElement) {
                    if (theme === 'dark') {
                        iconElement.innerHTML = '<svg class="h-5 w-5 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.293 14.293A8 8 0 019.707 6.707a8.001 8.001 0 107.586 7.586z" /></svg>';
                        textElement.textContent = 'Dark';
                    } else if (theme === 'light') {
                        iconElement.innerHTML = '<svg class="h-5 w-5 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.93l-.71-.71M12 5a7 7 0 100 14a7 7 0 000-14z" /></svg>';
                        textElement.textContent = 'Light';
                    } else {
                        iconElement.innerHTML = '<svg class="h-5 w-5 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.93l-.71-.71" /></svg>';
                        textElement.textContent = 'System';
                    }
                }
            });
        }

        // THEME TOGGLE LOGIC (desktop & mobile)
        function themeDropdownInit({toggleId, menuId, iconId, textId}) {
            const themeToggleDropdown = document.getElementById(toggleId);
            const themeDropdownMenu = document.getElementById(menuId);
            const themeDropdownIcon = document.getElementById(iconId);
            const themeDropdownText = document.getElementById(textId);
            if (!themeToggleDropdown || !themeDropdownMenu || !themeDropdownIcon || !themeDropdownText) return;
            const themeButtons = themeDropdownMenu.querySelectorAll('button[data-theme]');
            let currentTheme = localStorage.getItem('theme') || 'system';

            function applyTheme(theme) {
                document.body.classList.add('transition-colors');
                if (theme === 'system') {
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.classList.add('dark');
                        console.log('Theme changed: system (dark)');
                    } else {
                        document.documentElement.classList.remove('dark');
                        console.log('Theme changed: system (light)');
                    }
                } else if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    console.log('Theme changed: dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    console.log('Theme changed: light');
                }
                syncAllThemeElements(theme);
                setTimeout(() => document.body.classList.remove('transition-colors'), 500);
            }

            function setTheme(theme) {
                currentTheme = theme;
                localStorage.setItem('theme', theme);
                applyTheme(theme);
            }

            applyTheme(currentTheme);

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (localStorage.getItem('theme') === 'system') applyTheme('system');
            });

            themeToggleDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                themeDropdownMenu.classList.toggle('hidden');
                if (toggleId === 'themeToggleDropdown' && document.getElementById('themeDropdownMenuMobile')) {
                    document.getElementById('themeDropdownMenuMobile').classList.add('hidden');
                }
                if (toggleId === 'themeToggleDropdownMobile' && document.getElementById('themeDropdownMenu')) {
                    document.getElementById('themeDropdownMenu').classList.add('hidden');
                }
            });

            document.addEventListener('click', function(e) {
                if (!themeDropdownMenu.contains(e.target) && !themeToggleDropdown.contains(e.target)) {
                    themeDropdownMenu.classList.add('hidden');
                }
            });

            themeButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    setTheme(btn.getAttribute('data-theme'));
                    themeDropdownMenu.classList.add('hidden');
                });
            });
        }

        themeDropdownInit({
            toggleId: 'themeToggleDropdown',
            menuId: 'themeDropdownMenu',
            iconId: 'themeDropdownIcon',
            textId: 'themeDropdownText'
        });
        themeDropdownInit({
            toggleId: 'themeToggleDropdownMobile',
            menuId: 'themeDropdownMenuMobile',
            iconId: 'themeDropdownIconMobile',
            textId: 'themeDropdownTextMobile'
        });
        const savedTheme = localStorage.getItem('theme') || 'system';
        syncAllThemeElements(savedTheme);
    });
</script>
