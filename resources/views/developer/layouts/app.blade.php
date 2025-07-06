<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Developer Portal') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Developer Portal - Family Cloud Admin Dashboard')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="{{ asset('js/app.js') }}" defer></script>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        @include('developer.layouts.partials.header')

        <div class="flex">
            @include('developer.layouts.partials.sidebar')

            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
