@extends('family.layouts.app')

@section('title', 'Upload New File')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Upload New File</h1>
        <a href="{{ route('family.files.index', ['folder_id' => request('folder_id')]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Files
        </a>
    </div>

    <div class="{{ $darkMode ? 'bg-gray-800' : 'bg-white' }} shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('family.files.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            @if(request('folder_id'))
                <input type="hidden" name="folder_id" value="{{ request('folder_id') }}">
            @endif

            <div class="mb-6">
                <label for="file" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">File</label>
                <div class="mt-1">
                    <input type="file" id="file" name="file" required class="{{ $darkMode ? 'bg-gray-700 text-white' : 'bg-white text-gray-900' }} relative block w-full px-3 py-2 border {{ $darkMode ? 'border-gray-600' : 'border-gray-300' }} placeholder-gray-500 text-sm rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10">
                </div>
                @error('file')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

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

            @if(!request('folder_id'))
            <div class="mb-6">
                <label for="folder_id" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Folder (optional)</label>
                <div class="mt-1">
                    <select id="folder_id" name="folder_id" class="{{ $darkMode ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300' }} block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">None (Root)</option>
                        @foreach($folders as $folder)
                            <option value="{{ $folder->id }}" {{ $folderId == $folder->id ? 'selected' : '' }}>{{ $folder->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('folder_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    Upload File
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
