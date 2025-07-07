@extends('family.layouts.app')

@section('title', 'My Photos')

@section('content')
<div class="container px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold {{ $darkMode ? 'text-white' : 'text-gray-800' }}">My Photos</h1>
        <div>
            <a href="{{ route('family.photos.index') }}?layout={{ $galleryLayout === 'grid' ? 'list' : 'grid' }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <span>{{ $galleryLayout === 'grid' ? 'List View' : 'Grid View' }}</span>
            </a>
        </div>
    </div>

    @if($photos->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-6 mb-6 {{ $darkMode ? 'bg-gray-800 text-white' : '' }}">
            <p class="text-center text-gray-500 {{ $darkMode ? 'text-gray-400' : '' }}">You don't have any photos yet.</p>
        </div>
    @else
        @if($galleryLayout === 'grid')
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($photos as $photo)
                    <div class="bg-white rounded-lg overflow-hidden shadow-md {{ $darkMode ? 'bg-gray-800' : '' }}">
                        <a href="{{ route('family.photos.show', $photo->id) }}" class="block relative pb-[75%]">
                            <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->title }}"
                                class="absolute inset-0 w-full h-full object-cover">
                        </a>
                        <div class="p-4">
                            <h3 class="font-semibold {{ $darkMode ? 'text-white' : 'text-gray-800' }}">{{ $photo->title }}</h3>
                            <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">
                                In gallery: <a href="{{ route('family.galleries.show', $photo->gallery->slug) }}"
                                   class="text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                                    {{ $photo->gallery->title }}
                                </a>
                            </p>
                            <div class="mt-2 flex space-x-2">
                                <a href="{{ route('family.photos.edit', $photo->id) }}"
                                   class="text-xs inline-flex items-center px-2 py-1 border border-transparent rounded-md text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                                    Edit
                                </a>
                                <a href="{{ route('family.photos.download', $photo->id) }}"
                                   class="text-xs inline-flex items-center px-2 py-1 border border-transparent rounded-md text-green-600 hover:text-green-800 {{ $darkMode ? 'text-green-400 hover:text-green-300' : '' }}">
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
                        <li class="flex items-center py-4 px-6 hover:bg-gray-50 {{ $darkMode ? 'hover:bg-gray-700' : '' }}">
                            <div class="flex-shrink-0 h-16 w-16 mr-4">
                                <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->title }}"
                                    class="h-full w-full object-cover rounded">
                            </div>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('family.photos.show', $photo->id) }}"
                                   class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }} hover:underline">
                                    {{ $photo->title }}
                                </a>
                                <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                                    Gallery: <a href="{{ route('family.galleries.show', $photo->gallery->slug) }}"
                                       class="text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                                        {{ $photo->gallery->title }}
                                    </a>
                                </p>
                                <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                                    Added: {{ $photo->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 ml-4 flex space-x-2">
                                <a href="{{ route('family.photos.edit', $photo->id) }}"
                                   class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Edit
                                </a>
                                <a href="{{ route('family.photos.download', $photo->id) }}"
                                   class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Download
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-6">
            {{ $photos->links() }}
        </div>
    @endif
</div>
@endsection
