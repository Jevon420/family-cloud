@extends('family.layouts.app')

@section('title', 'Edit Photo - ' . $photo->title)

@section('content')
<div class="container px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <a href="{{ route('family.photos.show', $photo->id) }}" class="mr-2 text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            <h1 class="text-2xl font-semibold {{ $darkMode ? 'text-white' : 'text-gray-800' }}">Edit Photo</h1>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden {{ $darkMode ? 'bg-gray-800' : '' }}">
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <img src="{{ route('admin.storage.signedUrl', ['path' => $photo->file_path, 'type' => 'long']) }}" alt="{{ $photo->title }}"
                         class="w-full h-auto rounded-lg shadow-sm">
                </div>

                <div>
                    <form action="{{ route('family.photos.update', $photo->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">
                                Photo Title
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $photo->title) }}"
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : '' }}"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4"
                                     class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : '' }}">{{ old('description', $photo->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('family.photos.show', $photo->id) }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'text-gray-300 bg-gray-700 hover:bg-gray-600 border-gray-600' : 'text-gray-700 bg-white hover:bg-gray-50' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit"
                                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
