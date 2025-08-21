@extends('family.layouts.app')

@section('title', 'My Photos')

@section('content')
<!-- Adjusted layout for mobile view -->
<div class="container p-2 px-2 sm:px-4 py-6">
    <div x-data="{ showSearchOptions: false }" class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <!-- Mobile Options Icon -->
        <button type="button" x-on:click="showSearchOptions = !showSearchOptions" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }} w-full sm:hidden">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V5zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z" clip-rule="evenodd" />
            </svg>
            Search Options
        </button>

        <!-- Mobile Dropdown for Search Options -->
        <div x-show="showSearchOptions" x-cloak class="absolute top-full left-0 w-full bg-white shadow-md rounded-md mt-2 z-10">
            <form method="GET" action="{{ route('family.photos.index') }}" class="flex flex-col space-y-2 px-4 py-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search photos..."
                       class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <select name="gallery" class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Galleries</option>
                    @foreach($galleries as $gallery)
                        <option value="{{ $gallery->id }}" {{ request('gallery') == $gallery->id ? 'selected' : '' }}>{{ $gallery->name }}</option>
                    @endforeach
                </select>
                <select name="sort" id="sortSelect" class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Sort By</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date</option>
                    <option value="type" {{ request('sort') == 'type' ? 'selected' : '' }}>Type</option>
                </select>
                <select name="sort_order" id="sortOrder" class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Search</button>
            </form>
            <a href="{{ route('family.photos.index') }}" class="block px-4 py-2 bg-gray-600 text-white rounded-md shadow-sm text-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Reset</a>
        </div>

        <!-- Desktop View -->
        <h1 class="text-2xl font-semibold {{ $darkMode ? 'text-white' : 'text-gray-800' }} w-full sm:w-auto mb-4 sm:mb-0">My Photos</h1>
        <div class="hidden sm:flex flex-wrap space-x-2 w-full sm:w-auto">
            <form method="GET" action="{{ route('family.photos.index') }}" class="flex flex-wrap items-center space-x-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search photos..."
                       class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <select name="gallery" class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Galleries</option>
                    @foreach($galleries as $gallery)
                        <option value="{{ $gallery->id }}" {{ request('gallery') == $gallery->id ? 'selected' : '' }}>{{ $gallery->name }}</option>
                    @endforeach
                </select>
                <select name="sort" id="sortSelect" class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Sort By</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date</option>
                    <option value="type" {{ request('sort') == 'type' ? 'selected' : '' }}>Type</option>
                </select>
                <select name="sort_order" id="sortOrder" class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Search</button>
            </form>
            <a href="{{ route('family.photos.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md shadow-sm text-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Reset</a>
        </div>
    </div>

    @if($photos->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-6 mb-6 {{ $darkMode ? 'bg-gray-800 text-white' : '' }}">
            <p class="text-center text-gray-500 {{ $darkMode ? 'text-gray-400' : '' }}">You don't have any photos yet.</p>
        </div>
    @else
        @if($galleryLayout === 'grid')
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($photos as $photo)
                    <div class="bg-white rounded-lg overflow-hidden shadow-md {{ $darkMode ? 'bg-gray-800' : '' }}">
                        <a href="{{ route('family.photos.show', $photo->id) }}" class="block relative pb-[75%]">
                            <img src="{{ route('public.storage.signed-url', ['path' => $photo->thumbnail_path ?? $photo->file_path, 'type' => 'short']) }}" alt="{{ $photo->name }}"
                                class="absolute inset-0 w-full h-full object-cover">
                        </a>
                        <div class="p-4">
                            <h3 class="font-semibold {{ $darkMode ? 'text-white' : 'text-gray-800' }}">{{ $photo->name }}</h3>
                            <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">
                                In gallery: <a href="{{ route('family.galleries.show', $photo->gallery->slug) }}"
                                   class="text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                                    {{ $photo->gallery->name }}
                                </a>
                            </p>
                            <div class="mt-2 flex flex-wrap space-x-2">
                                <a href="{{ route('family.photos.edit', $photo->id) }}"
                                   class="text-xs inline-flex items-center px-2 py-1 border border-transparent rounded-md text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }} w-full sm:w-auto">
                                    Edit
                                </a>
                                <a href="{{ route('family.photos.download', $photo->id) }}"
                                   class="text-xs inline-flex items-center px-2 py-1 border border-transparent rounded-md text-green-600 hover:text-green-800 {{ $darkMode ? 'text-green-400 hover:text-green-300' : '' }} w-full sm:w-auto">
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden {{ $darkMode ? 'bg-gray-800' : '' }}">
                <ul class="divide-y divide-gray-200 {{ $darkMode ? 'divide-gray-700' : '' }}">
                    @foreach($photos as $photo)
                        <li class="flex flex-wrap items-center py-4 px-4 sm:px-6 hover:bg-gray-50 {{ $darkMode ? 'hover:bg-gray-700' : '' }}">
                            <div class="flex-shrink-0 h-16 w-16 mr-4">
                                <img src="{{ route('public.storage.signed-url', ['path' => $photo->thumbnail_path ?? $photo->file_path, 'type' => 'short']) }}" alt="{{ $photo->name }}"
                                    class="h-full w-full object-cover rounded">
                            </div>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('family.photos.show', $photo->id) }}"
                                   class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }} hover:underline">
                                    {{ $photo->name }}
                                </a>
                                <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                                    Gallery: <a href="{{ route('family.galleries.show', $photo->gallery->slug) }}"
                                       class="text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                                        {{ $photo->gallery->name }}
                                    </a>
                                </p>
                                <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                                    Added: {{ $photo->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 ml-4 flex flex-wrap space-x-2">
                                <a href="{{ route('family.photos.edit', $photo->id) }}"
                                   class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full sm:w-auto">
                                    Edit
                                </a>
                                <a href="{{ route('family.photos.download', $photo->id) }}"
                                   class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 w-full sm:w-auto">
                                    Download
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-6 w-full">
            {{ $photos->links() }}
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

            fetch(`{{ route('family.photos.index') }}?${params.toString()}`, {
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
            } else if (selectedSort === 'type') {
                sortOrderDropdown.innerHTML = `
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>A-Z</option>
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Z-A</option>
                `;
            }
        });

        const resetButton = document.querySelector('a[href="{{ route('family.photos.index') }}"]');

        resetButton.addEventListener('click', function (event) {
            event.preventDefault();

            // Clear form fields
            form.reset();

            // Reset dropdowns
            sortOrderDropdown.innerHTML = `
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            `;

            // Fetch default content
            fetch(`{{ route('family.photos.index') }}`, {
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
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('searchOptions', () => ({
            showSearchOptions: false
        }));
    });
</script>
@endpush
