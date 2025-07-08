<aside class="relative bg-gradient-to-br from-gray-900 via-indigo-800 to-indigo-900 shadow-lg rounded-lg w-full md:w-64 md:min-h-screen md:flex-shrink-0" x-data="{ open: false }">
    <!-- Mobile menu button -->
    <div class="md:hidden flex items-center justify-between bg-gradient-to-r from-indigo-900 to-indigo-700 px-4 py-3 rounded-t-lg">
        <div class="text-white font-semibold">Menu</div>
        <button @click="open = !open" class="text-white focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Sidebar content -->
    <nav class="hidden md:block md:sticky md:top-0 md:h-screen md:overflow-y-auto" :class="{'block': open, 'hidden': !open}">
        <div class="px-4 py-6 space-y-4">
            <a href="{{ route('family.home') }}" class="block px-4 py-3 rounded-lg text-white font-medium bg-gradient-to-r from-indigo-700 to-indigo-500 shadow-md hover:shadow-lg transform hover:scale-105 transition-transform">
                <div class="flex items-center">
                    <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-7-7v14" />
                    </svg>
                    Dashboard
                </div>
            </a>

            <a href="{{ route('family.galleries.index') }}" class="block px-4 py-3 rounded-lg text-white font-medium bg-gradient-to-r from-indigo-700 to-indigo-500 shadow-md hover:shadow-lg transform hover:scale-105 transition-transform">
                <div class="flex items-center">
                    <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Galleries
                </div>
            </a>

            <a href="{{ route('family.photos.index') }}" class="block px-4 py-3 rounded-lg text-white font-medium bg-gradient-to-r from-indigo-700 to-indigo-500 shadow-md hover:shadow-lg transform hover:scale-105 transition-transform">
                <div class="flex items-center">
                    <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Photos
                </div>
            </a>

            <a href="{{ route('family.files.index') }}" class="block px-4 py-3 rounded-lg text-white font-medium bg-gradient-to-r from-indigo-700 to-indigo-500 shadow-md hover:shadow-lg transform hover:scale-105 transition-transform">
                <div class="flex items-center">
                    <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Files
                </div>
            </a>

            <a href="{{ route('family.folders.index') }}" class="block px-4 py-3 rounded-lg text-white font-medium bg-gradient-to-r from-indigo-700 to-indigo-500 shadow-md hover:shadow-lg transform hover:scale-105 transition-transform">
                <div class="flex items-center">
                    <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    Folders
                </div>
            </a>

            <div class="pt-4 pb-3 border-t {{ $darkMode ? 'border-gray-700' : 'border-indigo-700' }}">
                <div class="px-3 space-y-1">
                    <a href="{{ route('family.profile.edit') }}" class="block px-4 py-3 rounded-lg text-white font-medium bg-gradient-to-r from-indigo-700 to-indigo-500 shadow-md hover:shadow-lg transform hover:scale-105 transition-transform">
                        <div class="flex items-center">
                            <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile
                        </div>
                    </a>

                    <a href="{{ route('family.storage') }}" class="block px-4 py-3 rounded-lg text-white font-medium bg-gradient-to-r from-indigo-700 to-indigo-500 shadow-md hover:shadow-lg transform hover:scale-105 transition-transform">
                        <div class="flex items-center">
                            <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 1.79 4 4 4h8c2.21 0 4-1.79 4-4V7c0-2.21-1.79-4-4-4H8c-2.21 0-4 1.79-4 4z" />
                            </svg>
                            Storage
                        </div>
                    </a>

                    <a href="{{ route('family.settings.index') }}" class="block px-4 py-3 rounded-lg text-white font-medium bg-gradient-to-r from-indigo-700 to-indigo-500 shadow-md hover:shadow-lg transform hover:scale-105 transition-transform">
                        <div class="flex items-center">
                            <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </div>
                    </a>
                    <a href="{{ route('family.help.index') }}" class="block px-4 py-3 rounded-lg text-white font-medium bg-gradient-to-r from-indigo-700 to-indigo-500 shadow-md hover:shadow-lg transform hover:scale-105 transition-transform">
                        <div class="flex items-center">
                            <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Help
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</aside>
