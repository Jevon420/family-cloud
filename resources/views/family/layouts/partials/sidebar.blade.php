<aside class="bg-indigo-800 w-full md:w-64 md:min-h-screen md:flex-shrink-0" x-data="{ open: false }">
    <!-- Mobile menu button -->
    <div class="md:hidden flex items-center justify-between bg-indigo-900 px-4 py-3">
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
        <div class="px-2 py-4 space-y-1">
            <a href="{{ route('family.home') }}" class="block px-3 py-2 rounded-md text-white font-medium hover:bg-indigo-700 {{ request()->routeIs('family.home') ? 'bg-indigo-900' : '' }}">
                Dashboard
            </a>

            <a href="#" class="block px-3 py-2 rounded-md text-white font-medium hover:bg-indigo-700">
                Photos & Galleries
            </a>

            <a href="#" class="block px-3 py-2 rounded-md text-white font-medium hover:bg-indigo-700">
                Files & Folders
            </a>

            <a href="#" class="block px-3 py-2 rounded-md text-white font-medium hover:bg-indigo-700">
                Shared With Me
            </a>

            <a href="#" class="block px-3 py-2 rounded-md text-white font-medium hover:bg-indigo-700">
                Messages
            </a>

            <a href="#" class="block px-3 py-2 rounded-md text-white font-medium hover:bg-indigo-700">
                Calendar
            </a>

            <div class="pt-4 pb-3 border-t border-indigo-700">
                <div class="px-3 space-y-1">
                    <a href="#" class="block px-3 py-2 rounded-md text-white font-medium hover:bg-indigo-700">
                        Profile
                    </a>
                    <a href="#" class="block px-3 py-2 rounded-md text-white font-medium hover:bg-indigo-700">
                        Help
                    </a>
                </div>
            </div>
        </div>
    </nav>
</aside>
