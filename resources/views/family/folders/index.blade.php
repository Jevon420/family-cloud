@extends('family.layouts.app')

@section('content')
<div class="container mx-auto p-2 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900">Folders</h1>
    <div class="mt-4">
        @if($folders->isEmpty())
            <p class="text-gray-600">No folders found.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($folders as $folder)
                    <div class="bg-white shadow rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-gray-800">{{ $folder->name }}</h2>
                        <p class="text-sm text-gray-600">Created at: {{ $folder->created_at->format('d M Y') }}</p>
                        <a href="{{ route('family.folders.show', $folder->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Folder</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
