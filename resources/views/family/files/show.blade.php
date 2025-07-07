@extends('family.layouts.app')

@section('title', $file->title)

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">{{ $file->title }}</h1>
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-900' }}">
        <p class="mb-4 text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">{{ $file->description }}</p>
        <p class="mb-4 text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Size: {{ round($file->file_size / 1024, 2) }} KB</p>
        <p class="mb-4 text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Type: {{ Str::upper(pathinfo($file->filename, PATHINFO_EXTENSION)) }}</p>
        <a href="{{ route('family.files.download', $file->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
            Download
        </a>
    </div>
</div>
@endsection
