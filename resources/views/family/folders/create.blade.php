@extends('family.layouts.app')

@section('title', 'Create New Folder')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Create New Folder</h1>
        <a href="{{ route('family.folders.index', ['parent_id' => request('parent_id')]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Folders
        </a>
    </div>

    <div class="{{ $darkMode ? 'bg-gray-800' : 'bg-white' }} shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('family.folders.store') }}" method="POST" class="p-6">
            @csrf

            @if(request('parent_id'))
                <input type="hidden" name="parent_id" value="{{ request('parent_id') }}">
            @endif

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Name</label>
                <div class="mt-1">
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="{{ $darkMode ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300' }} block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                @error('name')
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

            @if(!request('parent_id'))
            <div class="mb-6">
                <label for="parent_id" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Parent Folder (optional)</label>
                <div class="mt-1">
                    <select id="parent_id" name="parent_id" class="{{ $darkMode ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300' }} block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">None (Root)</option>
                        @foreach($folders as $folder)
                            <option value="{{ $folder->id }}" {{ $parentId == $folder->id ? 'selected' : '' }}>{{ $folder->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('parent_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z" clip-rule="evenodd" />
                        <path d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z" />
                    </svg>
                    Create Folder
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
