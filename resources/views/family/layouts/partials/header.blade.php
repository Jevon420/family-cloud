<header class="bg-white shadow">
    <div class="px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
        <div class="flex items-center">
            <a href="{{ route('family.home') }}" class="text-xl font-bold text-indigo-600">
                Family Cloud <span class="text-gray-600 text-sm">Family Dashboard</span>
            </a>
        </div>

        <div class="flex items-center space-x-4">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-1 text-gray-700 hover:text-gray-900 focus:outline-none">
                    <span>{{ Auth::user()->name }}</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                    <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Public Home</a>
                    <a href="{{ route('family.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
