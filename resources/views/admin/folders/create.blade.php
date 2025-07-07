@extends('admin.layouts.app')

@section('title', 'Create Folder')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Create New Folder</h1>
        <a href="{{ route('admin.folders.index') }}" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            Back to Folders
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('admin.folders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700">Owner</label>
                <select name="user_id" id="user_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                    @endforeach
                </select>
                @error('user_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Folder</label>
                <select name="parent_id" id="parent_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">None (Root Folder)</option>
                    @foreach($parentFolders as $parentFolder)
                    <option value="{{ $parentFolder->id }}" {{ old('parent_id') == $parentFolder->id ? 'selected' : '' }}>
                        {{ $parentFolder->name }} (Owner: {{ $parentFolder->user->name }})
                    </option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">Optional. If not selected, this will be a root-level folder.</p>
                @error('parent_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="visibility" class="block text-sm font-medium text-gray-700">Visibility</label>
                <select name="visibility" id="visibility" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                    <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="shared" {{ old('visibility') == 'shared' ? 'selected' : '' }}>Shared</option>
                </select>
                @error('visibility')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover Image</label>
                <input type="file" name="cover_image" id="cover_image" class="mt-1 block w-full" accept="image/*">
                <p class="mt-1 text-sm text-gray-500">Optional. Maximum file size: 2MB.</p>
                @error('cover_image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                <select name="tags[]" id="tags" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" multiple>
                    @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ (old('tags') && in_array($tag->id, old('tags'))) ? 'selected' : '' }}>
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
                    Create Folder
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
