@extends('family.layouts.app')

@section('title', 'Edit File')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Edit File</h1>
    </div>

    <form action="{{ route('family.files.update', $file->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-900' }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2 {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}" for="title">Title</label>
            <input type="text" name="title" id="title" value="{{ $file->title }}" class="shadow appearance-none border rounded w-full py-2 px-3 {{ $darkMode ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-900' }}">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2 {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}" for="description">Description</label>
            <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 {{ $darkMode ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-900' }}">{{ $file->description }}</textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
