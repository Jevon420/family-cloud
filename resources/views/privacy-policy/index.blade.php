@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold mb-4">{{ $page->name }}</h1>
        <p class="text-lg text-gray-600">{{ $page->meta_description }}</p>
    </div>

    <div class="prose max-w-none mx-auto">
        {!! $page->contents->where('key', 'description')->first()->value ?? '' !!}
    </div>

    @if($page->images->where('label', 'banner_image')->first())
    <div class="mt-8">
        <img src="{{ asset($page->images->where('label', 'banner_image')->first()->path) }}" alt="{{ $page->images->where('label', 'banner_image')->first()->alt_text }}" class="w-full rounded-lg shadow-md">
    </div>
    @endif
</div>
@endsection
