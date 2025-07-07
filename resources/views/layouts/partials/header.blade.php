<header class="bg-white shadow-sm border-b">
    <div x-data="{ mobileMenuOpen: false, userMenuOpen: false }">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <img src="{{ asset('storage/logos/family-cloud-logo.png') }}" alt="Family Cloud Logo" class="h-12 w-auto">
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <a href="{{ route('home') }}" class="@if(request()->routeIs('home')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Home
                        </a>
                        @auth
                            @if(auth()->user()->hasRole('Global Admin'))
                                <a href="{{ route('admin.home') }}" class="@if(request()->routeIs('admin.*')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Global Admin
                                </a>
                            @elseif(auth()->user()->hasRole('Admin'))
                                <a href="{{ route('admin.home') }}" class="@if(request()->routeIs('admin.*')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Admin Dashboard
                                </a>
                            @elseif(auth()->user()->hasRole('Developer'))
                                <a href="{{ route('developer.home') }}" class="@if(request()->routeIs('developer.*')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                   Dev Dashboard
                                </a>
                            @elseif(auth()->user()->hasRole('Family'))
                                <a href="{{ route('family.home') }}" class="@if(request()->routeIs('family.*')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                   My Dashboard
                                </a>
                            @endif
                        @endauth
                        <a href="{{ route('about') }}" class="@if(request()->routeIs('about')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            About
                        </a>
                        <a href="{{ route('galleries.index') }}" class="@if(request()->routeIs('galleries.*')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Galleries
                        </a>
                        <a href="{{ route('photos.index') }}" class="@if(request()->routeIs('photos.*')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Photos
                        </a>
                        <a href="{{ route('files.index') }}" class="@if(request()->routeIs('files.*')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Files
                        </a>
                        <a href="{{ route('contact') }}" class="@if(request()->routeIs('contact')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Contact
                        </a>
                    </div>
                </div>

                <!-- Authentication Links -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    @auth
                        <div class="ml-3 relative">
                            <div>
                                <button @click="userMenuOpen = !userMenuOpen" type="button" class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ auth()->user()->name }}">
                                </button>
                            </div>

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

                                <a href="{{ route('family.storage') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">My Storage</a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="ml-4 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-medium">Register</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg x-show="!mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile menu -->
        <div class="sm:hidden" x-show="mobileMenuOpen">
            <div class="pt-2 pb-3 space-y-1 max-h-[70vh] overflow-y-auto">
                <a href="{{ route('home') }}" class="@if(request()->routeIs('home')) bg-indigo-50 border-indigo-500 text-indigo-700 @else border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Home</a>
                @auth
                    @if(auth()->user()->hasRole('Global Admin'))
                        <a href="{{ route('admin.home') }}" class="@if(request()->routeIs('admin.*')) bg-indigo-50 border-indigo-500 text-indigo-700 @else border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Global Admin</a>
                        <a href="{{ route('admin.settings.comprehensive.index') }}" class="@if(request()->routeIs('admin.settings.comprehensive.index')) bg-indigo-50 border-indigo-500 text-indigo-700 @else border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Global Settings</a>
                    @elseif(auth()->user()->hasRole('Admin'))
                        <a href="{{ route('admin.home') }}" class="@if(request()->routeIs('admin.*')) bg-indigo-50 border-indigo-500 text-indigo-700 @else border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Admin Dashboard</a>
                    @elseif(auth()->user()->hasRole('Developer'))
                        <a href="{{ route('developer.home') }}" class="@if(request()->routeIs('developer.*')) bg-indigo-50 border-indigo-500 text-indigo-700 @else border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Dev Dashboard</a>
                    @elseif(auth()->user()->hasRole('Family'))
                        <a href="{{ route('family.home') }}" class="@if(request()->routeIs('family.*')) bg-indigo-50 border-indigo-500 text-indigo-700 @else border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Dashboard</a>
                    @endif
                @endauth
                <a href="{{ route('about') }}" class="@if(request()->routeIs('about')) bg-indigo-50 border-indigo-500 text-indigo-700 @else border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium">About</a>
                <a href="{{ route('galleries.index') }}" class="@if(request()->routeIs('galleries.*')) bg-indigo-50 border-indigo-500 text-indigo-700 @else border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Galleries</a>
                <a href="{{ route('photos.index') }}" class="@if(request()->routeIs('photos.*')) bg-indigo-50 border-indigo-500 text-indigo-700 @else border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Photos</a>
                <a href="{{ route('files.index') }}" class="@if(request()->routeIs('files.*')) bg-indigo-50 border-indigo-500 text-indigo-700 @else border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Files</a>
                <a href="{{ route('contact') }}" class="@if(request()->routeIs('contact')) bg-indigo-50 border-indigo-500 text-indigo-700 @else border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Contact</a>
            </div>

            <!-- Mobile Authentication Menu -->
            <div class="pt-4 pb-3 border-t border-gray-200">
                @auth
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ auth()->user()->name }}">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        @if(auth()->user()->hasRole('Global Admin'))
                            <a href="{{ route('admin.home') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Global Admin Dashboard</a>
                            <a href="{{ route('admin.settings.comprehensive.index') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Global Settings</a>
                        @elseif(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.home') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Admin Dashboard</a>
                        @endif

                        @if(auth()->user()->hasRole('Developer'))
                            <a href="{{ route('developer.home') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Developer Dashboard</a>
                        @endif

                        @if(auth()->user()->hasRole('Family'))
                            <a href="{{ route('family.home') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Family Dashboard</a>
                        @endif

                        <a href="{{ route('family.storage') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">My Storage</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mt-3 space-y-1 px-2">
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
