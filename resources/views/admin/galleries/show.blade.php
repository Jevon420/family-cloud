@extends('admin.layouts.app')

@section('title', $gallery->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-wrap justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">{{ $gallery->name }}</h1>
        <div class="flex flex-wrap">
            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 mr-2">
                Edit Gallery
            </a>
            <a href="{{ route('admin.galleries.index') }}" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Back to Galleries
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="md:flex">
            <div class="md:flex-shrink-0">
                @if($gallery->cover_image)
                <img class="h-48 w-full object-cover md:w-48" src="{{ Storage::url($gallery->cover_image) }}" alt="{{ $gallery->name }}">
                @else
                <div class="h-48 w-full bg-gray-200 flex items-center justify-center md:w-48">
                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                @endif
            </div>
            <div class="p-6">
                <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Gallery Details</div>
                <p class="mt-2 text-gray-600">{{ $gallery->description }}</p>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Owner</h3>
                        <p class="mt-1">{{ $gallery->user->name }} ({{ $gallery->user->email }})</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Visibility</h3>
                        <p class="mt-1">{{ $gallery->visibility }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
