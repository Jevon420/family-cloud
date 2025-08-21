<!-- Sidebar Overlay for Mobile -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

<!-- Sidebar -->
<aside class="fixed lg:static inset-y-0 left-0 z-50 w-64 lg:w-72
              {{ $darkMode ? 'bg-slate-900/95' : 'bg-white/95' }}
              backdrop-blur-sm border-r {{ $darkMode ? 'border-slate-700' : 'border-gray-200' }}
              transform transition-transform duration-300 ease-in-out lg:transform-none"
    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }" x-data="{
           activeRoute: '{{ request()->route()->getName() }}',
           collapsedSections: {}
       }">

    <!-- Sidebar Header -->
    <div
        class="h-16 flex items-center justify-between px-6 border-b {{ $darkMode ? 'border-slate-700' : 'border-gray-200' }}">
        <div class="flex items-center space-x-3">
            <div
                class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                <i class="fas fa-cloud text-white text-sm"></i>
            </div>
            <span class="font-semibold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }}">Navigation</span>
        </div>
        <button @click="sidebarOpen = false"
            class="lg:hidden p-1 rounded-md {{ $darkMode ? 'text-slate-400 hover:text-slate-200' : 'text-gray-400 hover:text-gray-600' }}">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <!-- Main Navigation -->
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('family.home') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ request()->routeIs('family.home') ? ($darkMode ? 'bg-blue-600 text-white shadow-lg' : 'bg-blue-50 text-blue-700 border border-blue-200') : ($darkMode ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100') }}">
                <div class="flex items-center justify-center w-5 h-5 mr-3">
                    <i class="fas fa-home {{ request()->routeIs('family.home') ? 'text-current' : 'text-blue-500' }} group-hover:scale-110 transition-transform"></i>
                </div>
                <span>Dashboard</span>
                @if(request()->routeIs('family.home'))
                    <div class="ml-auto w-2 h-2 bg-current rounded-full"></div>
                @endif
            </a>

            <!-- Galleries -->
            <a href="{{ route('family.galleries.index') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ request()->routeIs('family.galleries.*') ? ($darkMode ? 'bg-purple-600 text-white shadow-lg' : 'bg-purple-50 text-purple-700 border border-purple-200') : ($darkMode ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100') }}">
                <div class="flex items-center justify-center w-5 h-5 mr-3">
                    <i class="fas fa-images {{ request()->routeIs('family.galleries.*') ? 'text-current' : 'text-purple-500' }} group-hover:scale-110 transition-transform"></i>
                </div>
                <span>Galleries</span>
                @if(request()->routeIs('family.galleries.*'))
                    <div class="ml-auto w-2 h-2 bg-current rounded-full"></div>
                @endif
            </a>

            <!-- Photos -->
            <a href="{{ route('family.photos.index') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ request()->routeIs('family.photos.*') ? ($darkMode ? 'bg-green-600 text-white shadow-lg' : 'bg-green-50 text-green-700 border border-green-200') : ($darkMode ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100') }}">
                <div class="flex items-center justify-center w-5 h-5 mr-3">
                    <i class="fas fa-camera {{ request()->routeIs('family.photos.*') ? 'text-current' : 'text-green-500' }} group-hover:scale-110 transition-transform"></i>
                </div>
                <span>Photos</span>
                @if(request()->routeIs('family.photos.*'))
                    <div class="ml-auto w-2 h-2 bg-current rounded-full"></div>
                @endif
            </a>

            <!-- Files -->
            <a href="{{ route('family.files.index') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ request()->routeIs('family.files.*') ? ($darkMode ? 'bg-orange-600 text-white shadow-lg' : 'bg-orange-50 text-orange-700 border border-orange-200') : ($darkMode ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100') }}">
                <div class="flex items-center justify-center w-5 h-5 mr-3">
                    <i
                        class="fas fa-file-alt {{ request()->routeIs('family.files.*') ? 'text-current' : 'text-orange-500' }} group-hover:scale-110 transition-transform"></i>
                </div>
                <span>Files</span>
                @if(request()->routeIs('family.files.*'))
                    <div class="ml-auto w-2 h-2 bg-current rounded-full"></div>
                @endif
            </a>

            <!-- Folders -->
            <a href="{{ route('family.folders.index') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ request()->routeIs('family.folders.*') ? ($darkMode ? 'bg-indigo-600 text-white shadow-lg' : 'bg-indigo-50 text-indigo-700 border border-indigo-200') : ($darkMode ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100') }}">
                <div class="flex items-center justify-center w-5 h-5 mr-3">
                    <i class="fas fa-folder {{ request()->routeIs('family.folders.*') ? 'text-current' : 'text-indigo-500' }} group-hover:scale-110 transition-transform"></i>
                </div>
                <span>Folders</span>
                @if(request()->routeIs('family.folders.*'))
                    <div class="ml-auto w-2 h-2 bg-current rounded-full"></div>
                @endif
            </a>
        </div>

        <!-- Divider -->
        <div class="pt-4 mt-4 border-t {{ $darkMode ? 'border-slate-700' : 'border-gray-200' }}">
            <div class="px-3 mb-2">
                <h3
                    class="text-xs font-semibold {{ $darkMode ? 'text-slate-400' : 'text-gray-500' }} uppercase tracking-wider">
                    Account
                </h3>
            </div>

            <div class="space-y-1">
                <!-- Profile -->
                <a href="{{ route('family.profile.edit') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('family.profile.*')? ($darkMode ? 'bg-pink-600 text-white shadow-lg' : 'bg-pink-50 text-pink-700 border border-pink-200'): ($darkMode ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100') }}">
                    <div class="flex items-center justify-center w-5 h-5 mr-3">
                        <i class="fas fa-user {{ request()->routeIs('family.profile.*') ? 'text-current' : 'text-pink-500' }} group-hover:scale-110 transition-transform"></i>
                    </div>
                    <span>Profile</span>
                    @if(request()->routeIs('family.profile.*'))
                        <div class="ml-auto w-2 h-2 bg-current rounded-full"></div>
                    @endif
                </a>

                <!-- Storage -->
                <a href="{{ route('family.storage.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('family.storage.*') ? ($darkMode ? 'bg-cyan-600 text-white shadow-lg' : 'bg-cyan-50 text-cyan-700 border border-cyan-200') : ($darkMode ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100') }}">
                    <div class="flex items-center justify-center w-5 h-5 mr-3">
                        <i class="fas fa-hdd {{ request()->routeIs('family.storage.*') ? 'text-current' : 'text-cyan-500' }} group-hover:scale-110 transition-transform"></i>
                    </div>
                    <span>Storage</span>
                    @if(request()->routeIs('family.storage.*'))
                        <div class="ml-auto w-2 h-2 bg-current rounded-full"></div>
                    @endif
                </a>

                <!-- Settings -->
                <a href="{{ route('family.settings.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('family.settings.*') ? ($darkMode ? 'bg-slate-600 text-white shadow-lg' : 'bg-slate-50 text-slate-700 border border-slate-200') : ($darkMode ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100') }}">
                    <div class="flex items-center justify-center w-5 h-5 mr-3">
                        <i class="fas fa-cog {{ request()->routeIs('family.settings.*') ? 'text-current' : 'text-slate-500' }} group-hover:scale-110 transition-transform"></i>
                    </div>
                    <span>Settings</span>
                    @if(request()->routeIs('family.settings.*'))
                        <div class="ml-auto w-2 h-2 bg-current rounded-full"></div>
                    @endif
                </a>

                <!-- Help & Support -->
                <a href="{{ route('family.help.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('family.help*')? ($darkMode ? 'bg-emerald-600 text-white shadow-lg' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') : ($darkMode ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100') }}">
                    <div class="flex items-center justify-center w-5 h-5 mr-3">
                        <i class="fas fa-question-circle {{ request()->routeIs('family.help*') ? 'text-current' : 'text-emerald-500' }} group-hover:scale-110 transition-transform"></i>
                    </div>
                    <span>Help & Support</span>
                    @if(request()->routeIs('family.help*'))
                        <div class="ml-auto w-2 h-2 bg-current rounded-full"></div>
                    @endif

                <!-- Help -->
                <a href="{{ route('family.help.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('family.help.*') ? ($darkMode ? 'bg-yellow-600 text-white shadow-lg' : 'bg-yellow-50 text-yellow-700 border border-yellow-200') : ($darkMode ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100') }}">
                    <div class="flex items-center justify-center w-5 h-5 mr-3">
                        <i class="fas fa-question-circle {{ request()->routeIs('family.help.*') ? 'text-current' : 'text-yellow-500' }} group-hover:scale-110 transition-transform"></i>
                    </div>
                    <span>Help & Support</span>
                    {{ request()->routeIs('family.help.*') ? '<div class="ml-auto w-2 h-2 bg-current rounded-full"></div>' : '' }}
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="pt-4 mt-4 border-t {{ $darkMode ? 'border-slate-700' : 'border-gray-200' }}">
            <div class="px-3 mb-3">
                <h3
                    class="text-xs font-semibold {{ $darkMode ? 'text-slate-400' : 'text-gray-500' }} uppercase tracking-wider">
                    Quick Actions
                </h3>
            </div>

            <div class="space-y-2">
                <!-- Upload Button -->
                <button type="button" class="w-full flex items-center justify-center px-3 py-2 text-sm font-medium rounded-lg
                               bg-gradient-to-r from-blue-500 to-purple-600 text-white
                               hover:from-blue-600 hover:to-purple-700
                               transform hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg group">
                    <i class="fas fa-upload mr-2 group-hover:scale-110 transition-transform"></i>
                    Upload Files
                </button>

                <!-- Create Gallery Button -->
                <button type="button" class="w-full flex items-center justify-center px-3 py-2 text-sm font-medium rounded-lg
                               {{ $darkMode ? 'bg-slate-700 text-slate-200 hover:bg-slate-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}
                               transition-all duration-200 group">
                    <i class="fas fa-plus mr-2 group-hover:scale-110 transition-transform"></i>
                    New Gallery
                </button>
            </div>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t {{ $darkMode ? 'border-slate-700' : 'border-gray-200' }}">
        <div class="flex items-center space-x-3 p-3 rounded-lg {{ $darkMode ? 'bg-slate-800' : 'bg-gray-50' }}">
            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center
                        text-white font-semibold text-sm">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium {{ $darkMode ? 'text-slate-100' : 'text-gray-900' }} truncate">
                    {{ Auth::user()->name }}
                </div>
                <div class="text-xs {{ $darkMode ? 'text-slate-400' : 'text-gray-500' }} truncate">
                    {{ Auth::user()->email }}
                </div>
            </div>
            <button class="p-1 rounded-md {{ $darkMode ? 'text-slate-400 hover:text-slate-200' : 'text-gray-400 hover:text-gray-600' }}
                           transition-colors">
                <i class="fas fa-ellipsis-v text-sm"></i>
            </button>
        </div>
    </div>
</aside>
