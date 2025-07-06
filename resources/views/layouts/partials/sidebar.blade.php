<aside class="bg-white shadow-sm border-r w-64 flex-shrink-0">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Navigation</h2>
        <nav class="space-y-2">
            <a href="{{ route('home') }}" class="@if(request()->routeIs('home')) bg-indigo-100 text-indigo-700 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('home')) text-indigo-500 @else text-gray-400 group-hover:text-gray-500 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z" />
                </svg>
                Home
            </a>

            <a href="{{ route('galleries.index') }}" class="@if(request()->routeIs('galleries.*')) bg-indigo-100 text-indigo-700 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('galleries.*')) text-indigo-500 @else text-gray-400 group-hover:text-gray-500 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7v14a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2h10a2 2 0 012 2z" />
                </svg>
                Galleries
            </a>

            <a href="{{ route('photos.index') }}" class="@if(request()->routeIs('photos.*')) bg-indigo-100 text-indigo-700 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('photos.*')) text-indigo-500 @else text-gray-400 group-hover:text-gray-500 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Photos
            </a>

            <a href="{{ route('files.index') }}" class="@if(request()->routeIs('files.*')) bg-indigo-100 text-indigo-700 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('files.*')) text-indigo-500 @else text-gray-400 group-hover:text-gray-500 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Files
            </a>

            <a href="{{ route('folders.index') }}" class="@if(request()->routeIs('folders.*')) bg-indigo-100 text-indigo-700 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="@if(request()->routeIs('folders.*')) text-indigo-500 @else text-gray-400 group-hover:text-gray-500 @endif mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z" />
                </svg>
                Folders
            </a>
        </nav>

        @auth
        <div class="mt-8">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Shared Content</h3>
            <nav class="space-y-1">
                <a href="{{ route('shared.galleries.index') }}" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    Shared Galleries
                </a>
                <a href="{{ route('shared.photos.index') }}" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    Shared Photos
                </a>
                <a href="{{ route('shared.files.index') }}" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    Shared Files
                </a>
                <a href="{{ route('shared.folders.index') }}" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    Shared Folders
                </a>
            </nav>
        </div>
        @endauth
    </div>
</aside>
