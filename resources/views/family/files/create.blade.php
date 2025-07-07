@extends('family.layouts.app')

@section('title', 'Upload File')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Upload File</h1>
    </div>

    <form action="{{ route('family.files.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-900' }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-2 {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}" for="file">File</label>
            <input type="file" name="file" id="file" class="shadow appearance-none border rounded w-full py-2 px-3 {{ $darkMode ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-900' }}">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2 {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}" for="title">Title</label>
            <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 {{ $darkMode ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-900' }}">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2 {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}" for="description">Description</label>
            <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 {{ $darkMode ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-900' }}"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                Upload
            </button>
        </div>
    </form>
</div>
@endsection
