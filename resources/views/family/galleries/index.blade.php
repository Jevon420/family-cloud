@extends('family.layouts.app')

@section('title', 'My Galleries')

@section('content')
<div class="container mx-auto px-2 sm:px-4 lg:px-8">
    <!-- Adjusted layout for mobile view -->
    <div class="flex flex-wrap justify-between items-center mb-6">
        <h1 class="text-2xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }} w-full sm:w-auto mb-4 sm:mb-0">My Galleries</h1>
        <a href="{{ route('family.galleries.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 w-full sm:w-auto">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            New Gallery
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="flex flex-wrap justify-between items-center mb-6">
        <div x-data="{ open: false }" class="w-full sm:w-auto">
            <button @click="open = !open" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" class="mt-2 bg-white shadow-md rounded-md p-4 space-y-2 w-full sm:w-auto">
                <form method="GET" action="{{ route('family.galleries.index') }}" class="space-y-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search galleries..."
                           class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full">
                    <select name="sort" id="sortSelect" class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full">
                        <option value="">Sort By</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date</option>
                    </select>
                    <select name="sort_order" id="sortOrder" class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full">Search</button>
                </form>
                <a href="{{ route('family.galleries.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md shadow-sm text-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 w-full">Reset</a>
            </div>
        </div>
    </div>

    <!-- Layout Options -->
    <div class="mb-6 flex justify-end space-x-2 flex-wrap">
        <button type="button" onclick="window.location.href='{{ request()->fullUrlWithQuery(['layout' => 'grid']) }}'" class="px-3 py-1 rounded {{ $galleryLayout === 'grid' ? 'bg-indigo-600 text-white' : ($darkMode ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gray-200 text-gray-700 hover:bg-gray-300') }}">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 012 2v2a2 2 01-2 2h-2a2 2 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 012 2v2a2 2 01-2 2h-2a2 2 01-2-2v-2z" />
            </svg>
        </button>
        <button type="button" onclick="window.location.href='{{ request()->fullUrlWithQuery(['layout' => 'masonry']) }}'" class="px-3 py-1 rounded {{ $galleryLayout === 'masonry' ? 'bg-indigo-600 text-white' : ($darkMode ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gray-200 text-gray-700 hover:bg-gray-300') }}">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v4H5V5zm0 6h2v4H5v-4zm4 0h2v4H9v-4zm4 0h2v4h-2v-4z" />
            </svg>
        </button>
        <button type="button" onclick="window.location.href='{{ request()->fullUrlWithQuery(['layout' => 'list']) }}'" class="px-3 py-1 rounded {{ $galleryLayout === 'list' ? 'bg-indigo-600 text-white' : ($darkMode ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gray-200 text-gray-700 hover:bg-gray-300') }}">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    @if($galleries->isEmpty())
        <div class="text-center py-12 {{ $darkMode ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-500' }} rounded-lg">
            <svg class="mx-auto h-12 w-12 {{ $darkMode ? 'text-gray-400' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">No galleries</h3>
            <p class="mt-1 text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }}">Get started by creating a new gallery.</p>
            <div class="mt-6">
                <a href="{{ route('family.galleries.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    New Gallery
                </a>
            </div>
        </div>
    @else
        @if($galleryLayout === 'grid')
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach($galleries as $gallery)
                <div class="group relative">
                    <div class="aspect-w-4 aspect-h-3 overflow-hidden rounded-lg {{ $darkMode ? 'bg-gray-700' : 'bg-gray-100' }} relative">
                        @if($gallery->cover_image)
                            <img src="{{ route('admin.storage.signedUrl', ['path' => $gallery->cover_image, 'type' => 'long']) }}" alt="{{ $gallery->name }}" class="object-cover w-full h-full" style="width:100%;height:220px;object-fit:cover;">
                        @else
                            <img src="https://placehold.co/600x400?text=No+Cover" alt="Gallery placeholder" class="object-cover w-full h-full" style="width:100%;height:220px;object-fit:cover;">
                        @endif
                        <div class="absolute inset-0 flex items-end p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200" aria-hidden="true">
                            <div class="w-full rounded-md {{ $darkMode ? 'bg-gray-800 bg-opacity-75 text-white' : 'bg-white bg-opacity-75 text-gray-900' }} py-2 px-4 text-center text-sm font-medium backdrop-blur backdrop-filter">View Gallery</div>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center justify-between">
                        <h3 class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                            <a href="{{ route('family.galleries.show', $gallery->slug) }}">
                                <span class="absolute inset-0"></span>
                                {{ $gallery->name }}
                            </a>
                        </h3>
                        <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $gallery->photos_count }} photos</p>
                    </div>
                    <p class="mt-1 text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">Added {{ $gallery->created_at->format('F j, Y') }}</p>
                    <div class="mt-2 flex space-x-2">
                        <a href="{{ route('family.galleries.show', $gallery->slug) }}" class="px-2 py-1 bg-indigo-600 text-white rounded text-xs hover:bg-indigo-700">View</a>
                        <a href="{{ route('family.galleries.edit', $gallery->slug) }}" class="px-2 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Edit</a>
                        <form action="{{ route('family.galleries.update', $gallery->slug) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this gallery?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Delete</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @elseif($galleryLayout === 'masonry')
            <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-6">
                @foreach($galleries as $gallery)
                <div class="group relative mb-6 break-inside-avoid">
                    <div class="overflow-hidden rounded-lg {{ $darkMode ? 'bg-gray-700' : 'bg-gray-100' }}">
                        @if($gallery->cover_image)
                            <img src="{{ route('admin.storage.signedUrl', ['path' => $gallery->cover_image, 'type' => 'long']) }}" alt="{{ $gallery->name }}" class="w-full">
                        @else
                            <img src="https://placehold.co/600x{{ 300 + ($loop->index % 3) * 100 }}?text=No+Cover" alt="Gallery placeholder" class="w-full">
                        @endif
                        <div class="p-4">
                            <h3 class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                                <a href="{{ route('family.galleries.show', $gallery->slug) }}" class="block">
                                    {{ $gallery->name }}
                                </a>
                            </h3>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $gallery->photos_count }} photos</p>
                                <p class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $gallery->created_at->format('F j, Y') }}</p>
                            </div>
                            <div class="mt-2 flex space-x-2">
                                <a href="{{ route('family.galleries.show', $gallery->slug) }}" class="px-2 py-1 bg-indigo-600 text-white rounded text-xs hover:bg-indigo-700">View</a>
                                <a href="{{ route('family.galleries.edit', $gallery->slug) }}" class="px-2 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Edit</a>
                                <form action="{{ route('family.galleries.update', $gallery->slug) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this gallery?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="{{ $darkMode ? 'bg-gray-800' : 'bg-white' }} shadow overflow-hidden sm:rounded-md">
                <ul role="list" class="{{ $darkMode ? 'divide-y divide-gray-700' : 'divide-y divide-gray-200' }}">
                    @foreach($galleries as $gallery)
                    <li>
                        <div class="flex items-center justify-between px-4 py-4 sm:px-6">
                            <div>
                                <p class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }} truncate">{{ $gallery->name }}</p>
                                @if($gallery->description)
                                    <p class="flex items-center text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }}">{{ Str::limit($gallery->description, 100) }}</p>
                                @endif
                                <p class="mt-1 text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">Added {{ $gallery->created_at->format('F j, Y') }}</p>
                            </div>
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                <a href="{{ route('family.galleries.show', $gallery->slug) }}" class="px-2 py-1 bg-indigo-600 text-white rounded text-xs hover:bg-indigo-700">View</a>
                                <a href="{{ route('family.galleries.edit', $gallery->slug) }}" class="px-2 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Edit</a>
                                <form action="{{ route('family.galleries.update', $gallery->slug) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this gallery?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Delete</button>
                                </form>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Updated pagination links for responsiveness -->
        <div class="mt-6 w-full">
            {{ $galleries->links() }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const container = document.querySelector('.container');

        form.addEventListener('change', function (event) {
            event.preventDefault();

            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            fetch(`{{ route('family.galleries.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                container.innerHTML = data.html;
            })
            .catch(error => console.error('Error:', error));
        });

        const sortDropdown = document.querySelector('select[name="sort"]');
        const sortOrderDropdown = document.getElementById('sortOrder');

        sortDropdown.addEventListener('change', function () {
            const selectedSort = sortDropdown.value;

            sortOrderDropdown.innerHTML = '';

            if (selectedSort === 'name') {
                sortOrderDropdown.innerHTML = `
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                `;
            } else if (selectedSort === 'date') {
                sortOrderDropdown.innerHTML = `
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Latest</option>
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Earliest</option>
                `;
            }
        });

        const resetButton = document.querySelector('a[href="{{ route('family.galleries.index') }}"]');

        resetButton.addEventListener('click', function (event) {
            event.preventDefault();

            form.reset();

            sortOrderDropdown.innerHTML = `
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            `;

            fetch(`{{ route('family.galleries.index') }}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                container.innerHTML = data.html;
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const images = document.querySelectorAll('img');

        images.forEach(image => {
            const preloader = document.createElement('div');
            preloader.className = 'image-preloader';
            preloader.style.cssText = 'width: 100%; height: 100%; background: url(/path/to/preloader.gif) center center no-repeat;';
            image.parentElement.style.position = 'relative';
            image.parentElement.appendChild(preloader);

            image.onload = function () {
                preloader.remove();
            };

            image.onerror = function () {
                preloader.style.background = 'url(/path/to/error-image.png) center center no-repeat';
            };
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lazyImages = document.querySelectorAll('img');

        lazyImages.forEach(image => {
            image.setAttribute('loading', 'lazy');
        });
    });
</script>

<style>
    .image-preloader {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
    }
</style>
@endsection
