<header class="{{ $darkMode ? 'bg-slate-800/95 backdrop-blur-sm border-slate-700' : 'bg-white/95 backdrop-blur-sm border-gray-200' }}
                border-b shadow-sm sticky top-0 z-40 transition-all duration-300">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left Section: Logo and Brand -->
            <div class="flex items-center space-x-4">
                <!-- Mobile Sidebar Toggle -->
                <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 rounded-lg {{ $darkMode ? 'text-slate-400 hover:text-slate-200 hover:bg-slate-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}
                               transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <!-- Logo and Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}"
                       class="flex items-center space-x-3 hover:opacity-80 transition-opacity group">
                        <div class="relative">
                            <img src="{{ asset('storage/logos/family-cloud-logo.png') }}"
                                 alt="Family Cloud Logo"
                                 class="h-10 w-auto transition-transform group-hover:scale-105">
                            <div class="absolute inset-0 bg-blue-500/20 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="hidden sm:block">
                            <h1 class="text-xl font-bold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }}
                                       bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                Family Cloud
                            </h1>
                            <p class="text-xs {{ $darkMode ? 'text-slate-400' : 'text-slate-500' }} -mt-1">
                                Family Dashboard
                            </p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Center Section: Search (hidden on mobile) -->
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <div class="relative w-full" x-data="{ searchOpen: false, searchQuery: '' }">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search {{ $darkMode ? 'text-slate-400' : 'text-gray-400' }} text-sm"></i>
                    </div>
                    <input type="text"
                           x-model="searchQuery"
                           @focus="searchOpen = true"
                           @blur="setTimeout(() => searchOpen = false, 200)"
                           placeholder="Search files, photos, galleries..."
                           class="block w-full pl-10 pr-3 py-2 border {{ $darkMode ? 'border-slate-600 bg-slate-700 text-slate-100 placeholder-slate-400' : 'border-gray-300 bg-white text-gray-900 placeholder-gray-500' }}
                                  rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  transition-all duration-200 text-sm">

                    <!-- Search Dropdown -->
                    <div x-show="searchOpen && searchQuery.length > 0"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute top-full left-0 right-0 mt-2 {{ $darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-200' }}
                                border rounded-lg shadow-lg z-50">
                        <div class="p-3">
                            <div class="text-xs font-medium {{ $darkMode ? 'text-slate-400' : 'text-gray-500' }} mb-2">
                                Quick Actions
                            </div>
                            <div class="space-y-1">
                                <a href="{{ route('family.photos.index') }}"
                                   class="flex items-center space-x-3 p-2 rounded-md {{ $darkMode ? 'hover:bg-slate-700 text-slate-200' : 'hover:bg-gray-100 text-gray-700' }} transition-colors">
                                    <i class="fas fa-images w-4 text-blue-500"></i>
                                    <span class="text-sm">Search Photos</span>
                                </a>
                                <a href="{{ route('family.files.index') }}"
                                   class="flex items-center space-x-3 p-2 rounded-md {{ $darkMode ? 'hover:bg-slate-700 text-slate-200' : 'hover:bg-gray-100 text-gray-700' }} transition-colors">
                                    <i class="fas fa-file w-4 text-green-500"></i>
                                    <span class="text-sm">Search Files</span>
                                </a>
                                <a href="{{ route('family.galleries.index') }}"
                                   class="flex items-center space-x-3 p-2 rounded-md {{ $darkMode ? 'hover:bg-slate-700 text-slate-200' : 'hover:bg-gray-100 text-gray-700' }} transition-colors">
                                    <i class="fas fa-folder-open w-4 text-purple-500"></i>
                                    <span class="text-sm">Search Galleries</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Section: Storage, Notifications, User Menu -->
            <div class="flex items-center space-x-3">
                <!-- Storage Widget -->
                <div class="hidden md:block">
                    @include('components.storage-widget')
                </div>

                <!-- Notifications -->
                <div class="relative" x-data="{ notificationsOpen: false }">
                    <button @click="notificationsOpen = !notificationsOpen"
                            class="relative p-2 rounded-lg {{ $darkMode ? 'text-slate-400 hover:text-slate-200 hover:bg-slate-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}
                                   transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-bell text-lg"></i>
                        <!-- Notification Badge -->
                        <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-xs text-white font-medium">3</span>
                        </span>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div x-show="notificationsOpen"
                         @click.away="notificationsOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-80 {{ $darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-200' }}
                                border rounded-lg shadow-lg z-50">
                        <div class="p-3 border-b {{ $darkMode ? 'border-slate-700' : 'border-gray-200' }}">
                            <h3 class="text-sm font-semibold {{ $darkMode ? 'text-slate-100' : 'text-gray-900' }}">
                                Notifications
                            </h3>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <div class="p-3 {{ $darkMode ? 'hover:bg-slate-700' : 'hover:bg-gray-50' }} transition-colors cursor-pointer">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-upload text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm {{ $darkMode ? 'text-slate-200' : 'text-gray-900' }} font-medium">
                                            New file uploaded
                                        </p>
                                        <p class="text-xs {{ $darkMode ? 'text-slate-400' : 'text-gray-500' }}">
                                            vacation-photos.zip • 2 minutes ago
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- More notifications... -->
                        </div>
                        <div class="p-3 border-t {{ $darkMode ? 'border-slate-700' : 'border-gray-200' }}">
                            <a href="#" class="text-sm text-blue-500 hover:text-blue-600 font-medium">
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center space-x-2 p-2 rounded-lg {{ $darkMode ? 'text-slate-200 hover:bg-slate-700' : 'text-gray-700 hover:bg-gray-100' }}
                                   transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 group">
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center
                                        text-white font-semibold text-sm group-hover:scale-105 transition-transform">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                                <div class="text-xs {{ $darkMode ? 'text-slate-400' : 'text-gray-500' }}">
                                    {{ Auth::user()->email }}
                                </div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-xs {{ $darkMode ? 'text-slate-400' : 'text-gray-400' }}
                                  transform transition-transform duration-200"
                           :class="{ 'rotate-180': userMenuOpen }"></i>
                    </button>

                    <!-- User Dropdown Menu -->
                    <div x-show="userMenuOpen"
                         @click.away="userMenuOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-56 {{ $darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-200' }}
                                border rounded-lg shadow-lg z-50">
                        <div class="p-3 border-b {{ $darkMode ? 'border-slate-700' : 'border-gray-200' }}">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center
                                            text-white font-semibold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium {{ $darkMode ? 'text-slate-100' : 'text-gray-900' }}">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div class="text-xs {{ $darkMode ? 'text-slate-400' : 'text-gray-500' }}">
                                        {{ Auth::user()->email }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('home') }}"
                               class="flex items-center px-4 py-2 text-sm {{ $darkMode ? 'text-slate-200 hover:bg-slate-700' : 'text-gray-700 hover:bg-gray-100' }}
                                      transition-colors group">
                                <i class="fas fa-home w-4 mr-3 text-blue-500 group-hover:scale-110 transition-transform"></i>
                                Public Home
                            </a>
                            <a href="{{ route('family.storage.index') }}"
                               class="flex items-center px-4 py-2 text-sm {{ $darkMode ? 'text-slate-200 hover:bg-slate-700' : 'text-gray-700 hover:bg-gray-100' }}
                                      transition-colors group">
                                <i class="fas fa-hdd w-4 mr-3 text-green-500 group-hover:scale-110 transition-transform"></i>
                                My Storage
                            </a>
                            <a href="{{ route('family.profile.edit') }}"
                               class="flex items-center px-4 py-2 text-sm {{ $darkMode ? 'text-slate-200 hover:bg-slate-700' : 'text-gray-700 hover:bg-gray-100' }}
                                      transition-colors group">
                                <i class="fas fa-user w-4 mr-3 text-purple-500 group-hover:scale-110 transition-transform"></i>
                                Profile Settings
                            </a>
                            <a href="{{ route('family.settings.index') }}"
                               class="flex items-center px-4 py-2 text-sm {{ $darkMode ? 'text-slate-200 hover:bg-slate-700' : 'text-gray-700 hover:bg-gray-100' }}
                                      transition-colors group">
                                <i class="fas fa-cog w-4 mr-3 text-gray-500 group-hover:scale-110 transition-transform"></i>
                                Settings
                            </a>
                        </div>

                        <div class="border-t {{ $darkMode ? 'border-slate-700' : 'border-gray-200' }} py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex items-center w-full px-4 py-2 text-sm {{ $darkMode ? 'text-red-400 hover:bg-slate-700' : 'text-red-600 hover:bg-gray-100' }}
                                               transition-colors group">
                                    <i class="fas fa-sign-out-alt w-4 mr-3 group-hover:scale-110 transition-transform"></i>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
