@extends('family.layouts.app')

@section('title', 'Create New Gallery')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Create New Gallery</h1>
        <a href="{{ route('family.galleries.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Galleries
        </a>
    </div>

    <div class="{{ $darkMode ? 'bg-gray-800' : 'bg-white' }} shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('family.galleries.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="mb-6">
                <label for="title" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Title</label>
                <div class="mt-1">
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required class="{{ $darkMode ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300' }} block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Description (optional)</label>
                <div class="mt-1">
                    <textarea id="description" name="description" rows="3" class="{{ $darkMode ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300' }} block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                </div>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="cover_image" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Cover Image (optional)</label>
                <div class="mt-1">
                    <input type="file" id="cover_image" name="cover_image" accept="image/*" class="{{ $darkMode ? 'bg-gray-700 text-white' : 'bg-white text-gray-900' }} relative block w-full px-3 py-2 border {{ $darkMode ? 'border-gray-600' : 'border-gray-300' }} placeholder-gray-500 text-sm rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10">
                </div>
                <p class="mt-1 text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">Upload a representative image for this gallery. Max 2MB.</p>
                @error('cover_image')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Create Gallery
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
