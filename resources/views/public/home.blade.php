@extends('layouts.app')

@section('title', $page->getContent('title', 'Welcome to Family Cloud'))
@section('meta_description', $page->meta_description ?? 'Welcome to Family Cloud - Share and manage your family memories')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">{{ $page->getContent('hero_title', 'Welcome to') }}</span>
                        <span class="block text-yellow-300 xl:inline">{{ $page->getContent('hero_subtitle', 'Family Cloud') }}</span>
                    </h1>
                    <p class="mt-3 text-base text-indigo-100 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        {{ $page->getContent('hero_description', 'A secure and private platform for sharing family memories, photos, and important files with your loved ones.') }}
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="{{ route('galleries.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                                View Galleries
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="{{ route('about') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 md:py-4 md:text-lg md:px-10">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="{{ $page->getImagePath('hero_image', 'https://images.unsplash.com/photo-1516802273409-68526ee1bdd6?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80') }}" alt="Family memories">
    </div>
</div>

<!-- Features Section -->
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
            <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Features</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                {{ $page->getContent('features_title', 'Everything you need for family sharing') }}
            </p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                {{ $page->getContent('features_description', 'Secure, private, and easy to use. Family Cloud provides all the tools you need to share and organize your family memories.') }}
            </p>
        </div>

        <div class="mt-10">
            <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                <div class="relative">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Photo Galleries</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        Create beautiful photo galleries to showcase your family memories and special moments.
                    </dd>
                </div>

                <div class="relative">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">File Sharing</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        Securely share documents, videos, and other important files with family members.
                    </dd>
                </div>

                <div class="relative">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Privacy & Security</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        Your family's privacy is our priority. All content is encrypted and securely stored.
                    </dd>
                </div>

                <div class="relative">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Family Collaboration</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        Invite family members to contribute and collaborate on shared albums and folders.
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- Recent Content Section -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">Recent Content</h2>
            <p class="mt-4 text-lg text-gray-500">Check out the latest additions to our family cloud</p>
        </div>

        <div class="mt-12 grid gap-8 lg:grid-cols-3 lg:gap-x-5 lg:gap-y-8">
            <!-- Recent Galleries -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Latest Galleries</h3>
                <div class="space-y-4">
                    <!-- You can add dynamic content here -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="h-32 bg-gray-200 rounded mb-3"></div>
                        <h4 class="font-medium text-gray-900">Family Vacation 2024</h4>
                        <p class="text-sm text-gray-500">25 photos</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('galleries.index') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">View all galleries →</a>
                </div>
            </div>

            <!-- Recent Photos -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Latest Photos</h3>
                <div class="grid grid-cols-2 gap-2">
                    <div class="aspect-square bg-gray-200 rounded"></div>
                    <div class="aspect-square bg-gray-200 rounded"></div>
                    <div class="aspect-square bg-gray-200 rounded"></div>
                    <div class="aspect-square bg-gray-200 rounded"></div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('photos.index') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">View all photos →</a>
                </div>
            </div>

            <!-- Recent Files -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Files</h3>
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-white rounded-lg shadow">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">Family_Budget_2024.pdf</p>
                            <p class="text-sm text-gray-500">2.1 MB</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('files.index') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">View all files →</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
