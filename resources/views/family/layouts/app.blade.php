<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name', 'Family Cloud') }}</title>
    <link rel="icon" type="image/jpg" href="{{ asset('storage/logos/family-cloud-logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

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

            <main class="flex-1 p-4 md:p-6 overflow-y-auto {{ $darkMode ? 'bg-gray-800' : 'bg-gray-50' }}">
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
</body>
</html>
