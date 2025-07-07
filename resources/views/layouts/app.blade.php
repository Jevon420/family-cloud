<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Family Cloud') - {{ config('app.name') }}</title>
    <link rel="icon" type="image/jpg" href="{{ asset('storage/logos/family-cloud-logo.png') }}">
    <meta name="description"
        content="@yield('meta_description', 'Family Cloud - Share and manage your family memories')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome 6.4.0 with correct SRI hash -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />



    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        @include('layouts.partials.header')

        <main>
            @yield('content')
        </main>

        @include('layouts.partials.footer')
    </div>

    @yield('scripts')
    @stack('scripts')

    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #e0e0e0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.5);
        }

        #preloader img {
            width: 150px;

            margin: auto;
            animation: bounce 1.5s infinite;
        }

        #welcome-text {
            font-size: 1.8rem;
            color: #333;
            margin-top: 20px;
            margin: auto;
            text-align: center;
            animation: fadeIn 2s ease-in-out;
        }

        #loading-bar {
            width: 80%;
            height: 20px;
            background-color: #ddd;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
            margin: auto;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #loading-bar div {
            height: 100%;
            background: linear-gradient(90deg, #4caf50, #81c784);
            width: 0;
            animation: progress 4s linear forwards;
        }

        #loading-percentage {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.9rem;
            color: #fff;
            line-height: 20px;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes progress {
            from {
                width: 0;
            }

            to {
                width: 100%;
            }
        }
    </style>

    <div id="preloader">
        <div>
            <img src="{{ asset('storage/logos/preloader/cloud-loader.gif') }}" alt="Loading">
            <img src="{{ asset('storage/logos/preloader/file-loader.gif') }}" alt="Loading">
            <div id="welcome-text">Welcome to Family Cloud!<br>Sharing and managing your family memories.</div>
            <div id="loading-bar">
                <div></div>
                <div id="loading-percentage">0%</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const referrer = document.referrer;
            const siteUrl = '{{ url('/') }}';
            const preloader = document.getElementById('preloader');
            const loadingPercentage = document.getElementById('loading-percentage');
            const progressBar = document.querySelector('#loading-bar div');

            let progress = 0;
            const interval = setInterval(() => {
                progress += 1;
                loadingPercentage.textContent = `${progress}%`;
                if (progress >= 100) {
                    clearInterval(interval);
                }
            }, 40);

            if (!referrer || !referrer.startsWith(siteUrl)) {
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 4000);
            } else {
                preloader.style.display = 'none';
            }
        });
    </script>
</body>

</html>
