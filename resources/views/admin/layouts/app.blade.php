<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-colors duration-300">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Global Admin Portal') - {{ $siteName ?? config('app.name') }}</title>
    <link rel="icon" type="image/jpg" href="{{ asset($siteLogo ?? 'storage/logos/family-cloud-logo.png') }}">
    <meta name="description"
        content="@yield('meta_description', 'Global Admin Portal - ' . ($siteDescription ?? 'Family Cloud System Administration'))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome 6.4.0 with correct SRI hash -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col transition-colors duration-300">
    <div id="app" class="flex flex-1 min-h-0">
        @include('admin.layouts.partials.sidebar')
        <div class="flex-1 flex flex-col min-h-0 bg-white transition-colors duration-300">
            @include('admin.layouts.partials.header')
            <main class="flex-1 p-2 md:p-8 bg-gray-50 transition-colors duration-300">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <!-- Theme Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Theme management
            let currentTheme = localStorage.getItem('theme') || 'system';

            function applyTheme(theme) {
                // Remove dark class first
                document.documentElement.classList.remove('dark');
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else if (theme === 'system') {
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.classList.add('dark');
                    }
                }
                updateThemeUI(theme);
            }

            function updateThemeUI(theme) {
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
                            iconElement.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.293 14.293A8 8 0 119.707 6.707a8.001 8.001 0 107.586 7.586z" /></svg>';
                            textElement.textContent = 'Dark';
                        } else if (theme === 'light') {
                            iconElement.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.93l-.71-.71M12 5a7 7 0 100 14a7 7 0 000-14z" /></svg>';
                            textElement.textContent = 'Light';
                        } else {
                            iconElement.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.93l-.71-.71" /></svg>';
                            textElement.textContent = 'System';
                        }
                    }
                });
            }

            function setTheme(theme) {
                currentTheme = theme;
                localStorage.setItem('theme', theme);
                applyTheme(theme);
            }

            // Initialize theme on page load
            applyTheme(currentTheme);

            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (localStorage.getItem('theme') === 'system') {
                    applyTheme('system');
                }
            });

            // Setup dropdown functionality
            function setupDropdown(toggleId, menuId) {
                const toggle = document.getElementById(toggleId);
                const menu = document.getElementById(menuId);
                if (!toggle || !menu) return;
                toggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    menu.classList.toggle('hidden');
                    const otherMenuId = toggleId === 'themeToggleDropdown' ? 'themeDropdownMenuMobile' : 'themeDropdownMenu';
                    const otherMenu = document.getElementById(otherMenuId);
                    if (otherMenu) {
                        otherMenu.classList.add('hidden');
                    }
                });
                menu.querySelectorAll('[data-theme]').forEach(btn => {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        setTheme(btn.getAttribute('data-theme'));
                        menu.classList.add('hidden');
                    });
                });
            }
            setupDropdown('themeToggleDropdown', 'themeDropdownMenu');
            setupDropdown('themeToggleDropdownMobile', 'themeDropdownMenuMobile');
            // Mobile menu button functionality
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function (e) {
                    e.stopPropagation();
                    mobileMenu.classList.toggle('hidden');
                    document.getElementById('themeDropdownMenuMobile')?.classList.add('hidden');
                    document.getElementById('themeDropdownMenu')?.classList.add('hidden');
                });
            }
            // Close all dropdowns when clicking outside
            document.addEventListener('click', function (e) {
                const themeDropdowns = ['themeDropdownMenu', 'themeDropdownMenuMobile'];
                themeDropdowns.forEach(dropdownId => {
                    const dropdown = document.getElementById(dropdownId);
                    const toggle = document.getElementById(dropdownId.replace('Menu', ''));
                    if (dropdown && toggle && !dropdown.contains(e.target) && !toggle.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const referrer = document.referrer;
            const adminUrlPattern = /\/admin\//;
            const currentUrl = window.location.href;

            // Prevent preloader from loading within views/admin/*
            if (!adminUrlPattern.test(referrer) && !adminUrlPattern.test(currentUrl)) {
                const preloader = document.createElement('div');
                preloader.id = 'preloader';
                preloader.style.position = 'fixed';
                preloader.style.top = '0';
                preloader.style.left = '0';
                preloader.style.width = '100%';
                preloader.style.height = '100%';
                preloader.style.backgroundColor = '#f3f4f6';
                preloader.style.zIndex = '9999';
                preloader.style.display = 'flex';
                preloader.style.justifyContent = 'center';
                preloader.style.alignItems = 'center';

                const contentWrapper = document.createElement('div');
                contentWrapper.style.textAlign = 'center';

                const gif = document.createElement('img');
                gif.src = '{{ asset('storage/logos/preloader/admin-preloader.gif') }}';
                gif.alt = 'Loading...';
                gif.style.marginBottom = '20px';
                gif.style.margin = 'auto';

                const userName = '{{ auth()->user()->name }}';
                const userRole = '{{ auth()->user()->getRoleNames()->first() }}';
                const welcomeText = document.createElement('h1');
                welcomeText.textContent = `Welcome ${userName} to your ${userRole} portal`;
                welcomeText.style.fontSize = '24px';
                welcomeText.style.color = '#4f46e5';
                welcomeText.style.marginBottom = '10px';

                const loadingText = document.createElement('p');
                loadingText.textContent = 'Loading, please wait...';
                loadingText.style.fontSize = '16px';
                loadingText.style.color = '#6b7280';
                loadingText.style.animation = 'pulse 1.5s infinite';

                contentWrapper.appendChild(gif);
                contentWrapper.appendChild(welcomeText);
                contentWrapper.appendChild(loadingText);

                preloader.appendChild(contentWrapper);

                const style = document.createElement('style');
                style.textContent = `
                    @keyframes pulse {
                        0%, 100% {
                            opacity: 1;
                        }
                        50% {
                            opacity: 0.5;
                        }
                    }
                `;
                document.head.appendChild(style);

                document.body.appendChild(preloader);

                // Ensure preloader shows for at least 5 seconds
                const minimumLoadTime = 5000;
                const startTime = Date.now();

                window.addEventListener('load', function () {
                    const elapsedTime = Date.now() - startTime;
                    const remainingTime = minimumLoadTime - elapsedTime;

                    setTimeout(function () {
                        preloader.style.display = 'none';
                    }, remainingTime > 0 ? remainingTime : 0);
                });

                // Failsafe to remove preloader after 5 seconds
                setTimeout(function () {
                    preloader.style.display = 'none';
                }, 5000);
            }
        });
    </script>
</body>

</html>
