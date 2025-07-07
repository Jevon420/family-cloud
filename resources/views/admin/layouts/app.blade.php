<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-colors duration-300">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Global Admin Portal') - {{ $siteName ?? config('app.name') }}</title>
    <link rel="icon" type="image/jpg" href="{{ asset($siteLogo ?? 'storage/logos/family-cloud-logo.png') }}">
    <meta name="description" content="@yield('meta_description', 'Global Admin Portal - ' . ($siteDescription ?? 'Family Cloud System Administration'))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="{{ asset('js/app.js') }}" defer></script>

    @stack('styles')
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col transition-colors duration-300">
    <div id="app" class="flex flex-1 min-h-0">
        @include('admin.layouts.partials.sidebar')
        <div class="flex-1 flex flex-col min-h-0 bg-white dark:bg-gray-900 transition-colors duration-300">
            @include('admin.layouts.partials.header')
            <main class="flex-1 p-6 md:p-8 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
