@extends('admin.layouts.app')

@section('title', 'Edit File')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-wrap justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit File: {{ $file->name }}</h1>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.files.show', $file) }}" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 mr-2">
                View File
            </a>
            <a href="{{ route('admin.files.index') }}" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Back to Files
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('admin.files.update', $file) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Current File</label>
                <div class="mt-2 mb-4">
                    @if(strpos($file->mime_type, 'image/') === 0)
                    <img src="{{ Storage::url($file->file_path) }}" alt="{{ $file->name }}" class="max-h-64 rounded w-full sm:w-auto">
                    @else
                    <div class="flex items-center">
                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="ml-2 text-sm">{{ $file->name }} ({{ number_format($file->file_size / 1024, 2) }} KB)</span>
                    </div>
                    @endif
                </div>

                <label for="file" class="block text-sm font-medium text-gray-700">Replace File</label>
                <input type="file" name="file" id="file" class="mt-1 block w-full">
                <p class="mt-1 text-sm text-gray-500">Leave empty to keep the current file. Maximum file size: 50MB.</p>
                @error('file')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $file->name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description', $file->description) }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700">Owner</label>
                <select name="user_id" id="user_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $file->user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                    @endforeach
                </select>
                @error('user_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="folder_id" class="block text-sm font-medium text-gray-700">Folder</label>
                <select name="folder_id" id="folder_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Root (No Folder)</option>
                    @foreach($folders as $folder)
                    <option value="{{ $folder->id }}" {{ old('folder_id', $file->folder_id) == $folder->id ? 'selected' : '' }}>
                        {{ $folder->name }} ({{ $folder->user->name }})
                    </option>
                    @endforeach
                </select>
                @error('folder_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="visibility" class="block text-sm font-medium text-gray-700">Visibility</label>
                <select name="visibility" id="visibility" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    <option value="private" {{ old('visibility', $file->visibility) == 'private' ? 'selected' : '' }}>Private</option>
                    <option value="public" {{ old('visibility', $file->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="shared" {{ old('visibility', $file->visibility) == 'shared' ? 'selected' : '' }}>Shared</option>
                </select>
                @error('visibility')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                <select name="tags[]" id="tags" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" multiple>
                    @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ (old('tags') && in_array($tag->id, old('tags'))) || (empty(old('tags')) && in_array($tag->id, $selectedTags)) ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">Hold Ctrl/Cmd to select multiple tags.</p>
                @error('tags')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update File
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
