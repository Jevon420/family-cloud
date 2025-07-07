@extends('family.layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Help Center</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white shadow-lg rounded-lg p-6 transform transition-transform hover:scale-105">
            <h2 class="text-xl font-semibold text-indigo-600 mb-4">Files</h2>
            <p class="text-gray-700">Learn how to create, edit, and delete files in your cloud storage.</p>
            <a href="{{ route('family.help.show', ['section' => 'files']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Details</a>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6 transform transition-transform hover:scale-105">
            <h2 class="text-xl font-semibold text-indigo-600 mb-4">Folders</h2>
            <p class="text-gray-700">Organize your files by creating, editing, and deleting folders.</p>
            <a href="{{ route('family.help.show', ['section' => 'folders']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Details</a>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6 transform transition-transform hover:scale-105">
            <h2 class="text-xl font-semibold text-indigo-600 mb-4">Galleries</h2>
            <p class="text-gray-700">Manage your photo galleries with options to create, edit, and delete them.</p>
            <a href="{{ route('family.help.show', ['section' => 'galleries']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Details</a>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6 transform transition-transform hover:scale-105">
            <h2 class="text-xl font-semibold text-indigo-600 mb-4">Photos</h2>
            <p class="text-gray-700">Upload, edit, and delete photos in your galleries.</p>
            <a href="{{ route('family.help.show', ['section' => 'photos']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Details</a>
        </div>
    </div>
</div>
@endsection
