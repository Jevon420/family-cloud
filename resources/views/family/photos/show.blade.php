@extends('family.layouts.app')

@section('title', $photo->title)

@section('content')
<div class="container px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <a href="{{ route('family.photos.index') }}" class="mr-2 text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            <h1 class="text-2xl font-semibold {{ $darkMode ? 'text-white' : 'text-gray-800' }}">{{ $photo->title }}</h1>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('family.photos.edit', $photo->id) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Edit Photo
            </a>
            <a href="{{ route('family.photos.download', $photo->id) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Download
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden {{ $darkMode ? 'bg-gray-800' : '' }}">
        <div class="p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row justify-between mb-4">
                <div>
                    <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">
                        From gallery: <a href="{{ route('family.galleries.show', $photo->gallery->slug) }}"
                                      class="text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                                      {{ $photo->gallery->title }}</a>
                    </p>
                    <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">
                        Added: {{ $photo->created_at->format('M d, Y') }}
                    </p>
                </div>
                <div class="flex mt-2 sm:mt-0 space-x-2">
                    @if($prevPhoto)
                        <a href="{{ route('family.photos.show', $prevPhoto->id) }}"
                           class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'text-white bg-gray-700 hover:bg-gray-600 border-gray-600' : 'text-gray-700 bg-white hover:bg-gray-50' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Previous
                        </a>
                    @endif

                    @if($nextPhoto)
                        <a href="{{ route('family.photos.show', $nextPhoto->id) }}"
                           class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'text-white bg-gray-700 hover:bg-gray-600 border-gray-600' : 'text-gray-700 bg-white hover:bg-gray-50' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Next
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <div class="my-4 bg-gray-100 p-1 rounded-lg {{ $darkMode ? 'bg-gray-700' : '' }}">
                <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->title }}"
                     class="w-full h-auto max-h-[80vh] object-contain mx-auto">
            </div>

            @if($photo->description)
                <div class="mt-4">
                    <h3 class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Description</h3>
                    <p class="mt-2 {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">{{ $photo->description }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
