<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name', 'Family Cloud') }}</title>
    <link rel="icon" type="image/jpg" href="{{ asset('storage/logos/family-cloud-logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <!-- Wasabi Image Auto-Refresh -->
    <script src="{{ asset('js/wasabi-image-refresh.js') }}"></script>

    @stack('styles')

    @php
        // Get user settings
        $userTheme = \App\Models\UserSetting::where('user_id', auth()->id())
            ->where('key', 'theme')
            ->first();
        $theme = $userTheme ? $userTheme->value : 'light';

        $userDarkMode = \App\Models\UserSetting::where('user_id', auth()->id())
            ->where('key', 'dark_mode')
            ->first();
        $darkMode = $userDarkMode ? ($userDarkMode->value === 'true') : false;
    @endphp

    <style>
        /* Modern CSS Variables and Themes */
        :root {
            --primary-50: #eff6ff;
            --primary-100: #dbeafe;
            --primary-200: #bfdbfe;
            --primary-300: #93c5fd;
            --primary-400: #60a5fa;
            --primary-500: #3b82f6;
            --primary-600: #2563eb;
            --primary-700: #1d4ed8;
            --primary-800: #1e40af;
            --primary-900: #1e3a8a;

            --gradient-primary: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
            --gradient-secondary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            --gradient-warning: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);

            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);

            --border-radius: 0.75rem;
            --border-radius-lg: 1rem;
            --border-radius-xl: 1.5rem;

            --font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Dark mode variables */
        .dark {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-tertiary: #94a3b8;
            --border-color: #334155;
        }

        /* Light mode variables */
        .light {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #64748b;
            --border-color: #e2e8f0;
        }

        /* Base styles */
        * {
            font-family: var(--font-family);
        }

        /* Modern animations */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        .animate-slide-up {
            animation: slideUp 0.3s ease-out;
        }

        .animate-bounce-in {
            animation: bounceIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: {{ $darkMode ? '#1e293b' : '#f1f5f9' }};
        }

        ::-webkit-scrollbar-thumb {
            background: {{ $darkMode ? '#475569' : '#cbd5e1' }};
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: {{ $darkMode ? '#64748b' : '#94a3b8' }};
        }

        /* Glass morphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-dark {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Hover effects */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Theme colors based on user settings */
        :root {
            --primary-color: {{ $theme === 'light' ? '#3b82f6' : ($theme === 'blue' ? '#3b82f6' : ($theme === 'green' ? '#10b981' : ($theme === 'purple' ? '#8b5cf6' : '#3b82f6'))) }};
            --primary-hover: {{ $theme === 'light' ? '#2563eb' : ($theme === 'blue' ? '#2563eb' : ($theme === 'green' ? '#059669' : ($theme === 'purple' ? '#7c3aed' : '#2563eb'))) }};
        }
    </style>
    @stack('styles')
</head>
<body class="{{ $darkMode ? 'dark bg-slate-900 text-slate-100' : 'light bg-gray-50 text-slate-900' }} font-sans antialiased transition-colors duration-300">
    <div class="min-h-screen flex flex-col animate-fade-in"
         x-data="{
             sidebarOpen: false,
             notifications: [],
             showNotifications: false
         }"
         x-init="
             // Initialize notification system
             window.addEventListener('notification', event => {
                 notifications.push({
                     id: Date.now(),
                     type: event.detail.type || 'info',
                     message: event.detail.message,
                     timeout: event.detail.timeout || 5000
                 });

                 setTimeout(() => {
                     notifications = notifications.filter(n => n.id !== notifications[notifications.length - 1].id);
                 }, event.detail.timeout || 5000);
             });
         ">

        <!-- Notification System -->
        <div class="fixed top-4 right-4 z-50 space-y-2" x-show="notifications.length > 0">
            <template x-for="notification in notifications" :key="notification.id">
                <div class="max-w-sm bg-white dark:bg-slate-800 rounded-lg shadow-lg border-l-4 p-4 animate-slide-up"
                     :class="{
                         'border-blue-500': notification.type === 'info',
                         'border-green-500': notification.type === 'success',
                         'border-yellow-500': notification.type === 'warning',
                         'border-red-500': notification.type === 'error'
                     }"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-full"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-full">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i :class="{
                                'fas fa-info-circle text-blue-500': notification.type === 'info',
                                'fas fa-check-circle text-green-500': notification.type === 'success',
                                'fas fa-exclamation-triangle text-yellow-500': notification.type === 'warning',
                                'fas fa-times-circle text-red-500': notification.type === 'error'
                            }"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="notification.message"></p>
                        </div>
                        <button @click="notifications = notifications.filter(n => n.id !== notification.id)"
                                class="ml-4 inline-flex text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
        @include('family.layouts.partials.header', ['theme' => $theme, 'darkMode' => $darkMode])

        <div class="flex flex-1 overflow-hidden">
            @include('family.layouts.partials.sidebar', ['theme' => $theme, 'darkMode' => $darkMode])

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto {{ $darkMode ? 'bg-slate-900' : 'bg-gray-50' }} transition-colors duration-300">
                <div class="container mx-auto p-4 lg:p-6 xl:p-8">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-6 {{ $darkMode ? 'bg-green-900/50 border-green-700/50 text-green-100' : 'bg-green-50 border-green-200 text-green-800' }}
                                    border-l-4 p-4 rounded-lg shadow-sm animate-slide-up hover-lift"
                             role="alert"
                             x-data="{ show: true }"
                             x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                                    <span class="font-medium">{{ session('success') }}</span>
                                </div>
                                <button @click="show = false"
                                        class="text-green-400 hover:text-green-600 dark:hover:text-green-300 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 {{ $darkMode ? 'bg-red-900/50 border-red-700/50 text-red-100' : 'bg-red-50 border-red-200 text-red-800' }}
                                    border-l-4 p-4 rounded-lg shadow-sm animate-slide-up hover-lift"
                             role="alert"
                             x-data="{ show: true }"
                             x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-3 text-red-500"></i>
                                    <span class="font-medium">{{ session('error') }}</span>
                                </div>
                                <button @click="show = false"
                                        class="text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="mb-6 {{ $darkMode ? 'bg-yellow-900/50 border-yellow-700/50 text-yellow-100' : 'bg-yellow-50 border-yellow-200 text-yellow-800' }}
                                    border-l-4 p-4 rounded-lg shadow-sm animate-slide-up hover-lift"
                             role="alert"
                             x-data="{ show: true }"
                             x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle mr-3 text-yellow-500"></i>
                                    <span class="font-medium">{{ session('warning') }}</span>
                                </div>
                                <button @click="show = false"
                                        class="text-yellow-400 hover:text-yellow-600 dark:hover:text-yellow-300 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Page Content -->
                    <div class="animate-fade-in">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>

        @include('layouts.partials.footer')
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const referrer = document.referrer;
            const familyUrlPattern = /\/family\//;
            const currentUrl = window.location.href;

            // Prevent preloader from loading within views/family/*
            if (!familyUrlPattern.test(referrer) && !familyUrlPattern.test(currentUrl)) {
                const preloader = document.createElement('div');
                preloader.id = 'preloader';
                preloader.style.position = 'fixed';
                preloader.style.top = '0';
                preloader.style.left = '0';
                preloader.style.width = '100%';
                preloader.style.height = '100%';
                preloader.style.backgroundColor = '{{ $darkMode ? '#1a202c' : '#f3f4f6' }}';
                preloader.style.zIndex = '9999';
                preloader.style.display = 'flex';
                preloader.style.justifyContent = 'center';
                preloader.style.alignItems = 'center';

                const contentWrapper = document.createElement('div');
                contentWrapper.style.textAlign = 'center';

                const gif = document.createElement('img');
                gif.src = '{{ asset('storage/logos/preloader/loader.gif') }}';
                gif.alt = 'Loading...';
                gif.style.marginBottom = '20px';
                gif.style.margin = 'auto';

                const userName = '{{ auth()->user()->name }}';
                const welcomeText = document.createElement('h1');
                welcomeText.textContent = `Welcome ${userName} to your Cloud`;
                welcomeText.style.fontSize = '24px';
                welcomeText.style.color = '{{ $darkMode ? '#ffffff' : '#4f46e5' }}';
                welcomeText.style.marginBottom = '10px';

                const loadingText = document.createElement('p');
                loadingText.textContent = 'Loading, please wait...';
                loadingText.style.fontSize = '16px';
                loadingText.style.color = '{{ $darkMode ? '#d1d5db' : '#6b7280' }}';
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

                // Ensure preloader shows for at least 3 seconds
                const minimumLoadTime = 3000;
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
    @stack('scripts')
</body>
</html>
