<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name', 'Family Cloud') }}</title>
    <link rel="icon" type="image/jpg" href="{{ asset('storage/logos/family-cloud-logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Font Awesome 6.4.0 with correct SRI hash -->
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
        /* Theme colors based on user settings */
        :root {
            --primary-color: {{ $theme === 'light' ? '#4f46e5' : ($theme === 'blue' ? '#3b82f6' : ($theme === 'green' ? '#10b981' : ($theme === 'purple' ? '#8b5cf6' : '#4f46e5'))) }};
            --primary-hover: {{ $theme === 'light' ? '#4338ca' : ($theme === 'blue' ? '#2563eb' : ($theme === 'green' ? '#059669' : ($theme === 'purple' ? '#7c3aed' : '#4338ca'))) }};
        }
    </style>
</head>
<body class="{{ $darkMode ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-900' }} font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        @include('family.layouts.partials.header', ['theme' => $theme, 'darkMode' => $darkMode])

        <div class="flex flex-col md:flex-row flex-1">
            @include('family.layouts.partials.sidebar', ['theme' => $theme, 'darkMode' => $darkMode])

            <main class="flex-1 md:p-6 overflow-y-auto {{ $darkMode ? 'bg-gray-800' : 'bg-gray-50' }}">
                @if(session('success'))
                    <div class="mb-4 {{ $darkMode ? 'bg-green-900 border-green-700 text-green-100' : 'bg-green-100 border-green-500 text-green-700' }} border-l-4 p-4 rounded" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 {{ $darkMode ? 'bg-red-900 border-red-700 text-red-100' : 'bg-red-100 border-red-500 text-red-700' }} border-l-4 p-4 rounded" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

        @include('layouts.partials.footer')
    </div>

    @stack('scripts')

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
</body>
</html>
