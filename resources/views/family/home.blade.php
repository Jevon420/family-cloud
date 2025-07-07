@extends('family.layouts.app')

@section('title', $page ? $page->getContent('title', 'Family Dashboard') : 'Family Dashboard')

@section('content')
@php
    // Get user settings
    $userSimpleDashboard = \App\Models\UserSetting::where('user_id', auth()->id())
        ->where('key', 'simple_dashboard')
        ->first();
    $simpleDashboard = $userSimpleDashboard ? ($userSimpleDashboard->value === 'true') : false;

    $userDarkMode = \App\Models\UserSetting::where('user_id', auth()->id())
        ->where('key', 'dark_mode')
        ->first();
    $darkMode = $userDarkMode ? ($userDarkMode->value === 'true') : false;
@endphp

<div class="mb-8">
    <h1 class="text-2xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Family Dashboard</h1>
    <p class="mt-1 text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">
        {{ $page ? $page->getContent('description', 'Welcome to the Family Cloud family portal. Share photos, files, and stay connected with your loved ones.') : 'Welcome to the Family Cloud family portal. Share photos, files, and stay connected with your loved ones.' }}
    </p>
</div>

@if(!$simpleDashboard)
<!-- Quick Action Buttons -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-6">
    <a href="{{ route('family.galleries.create') }}" class="flex items-center justify-center p-5 {{ $darkMode ? 'bg-gray-700 hover:bg-gray-600' : 'bg-white hover:bg-gray-50' }} rounded-lg shadow">
        <div class="text-center">
            <div class="rounded-full {{ $darkMode ? 'bg-gray-800' : 'bg-indigo-100' }} p-3 mx-auto w-16 h-16 flex items-center justify-center mb-3">
                <svg class="h-8 w-8 {{ $darkMode ? 'text-indigo-300' : 'text-indigo-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <h3 class="{{ $darkMode ? 'text-white' : 'text-gray-900' }} font-medium">Create Gallery</h3>
        </div>
    </a>

    <a href="{{ route('family.files.create') }}" class="flex items-center justify-center p-5 {{ $darkMode ? 'bg-gray-700 hover:bg-gray-600' : 'bg-white hover:bg-gray-50' }} rounded-lg shadow">
        <div class="text-center">
            <div class="rounded-full {{ $darkMode ? 'bg-gray-800' : 'bg-indigo-100' }} p-3 mx-auto w-16 h-16 flex items-center justify-center mb-3">
                <svg class="h-8 w-8 {{ $darkMode ? 'text-indigo-300' : 'text-indigo-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="{{ $darkMode ? 'text-white' : 'text-gray-900' }} font-medium">Upload File</h3>
        </div>
    </a>

    <a href="{{ route('family.folders.create') }}" class="flex items-center justify-center p-5 {{ $darkMode ? 'bg-gray-700 hover:bg-gray-600' : 'bg-white hover:bg-gray-50' }} rounded-lg shadow">
        <div class="text-center">
            <div class="rounded-full {{ $darkMode ? 'bg-gray-800' : 'bg-indigo-100' }} p-3 mx-auto w-16 h-16 flex items-center justify-center mb-3">
                <svg class="h-8 w-8 {{ $darkMode ? 'text-indigo-300' : 'text-indigo-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="{{ $darkMode ? 'text-white' : 'text-gray-900' }} font-medium">Create Folder</h3>
        </div>
    </a>
</div>
@endif

<!-- Recent Galleries -->
<div class="mt-10">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Recent Galleries</h2>
        <a href="{{ route('family.galleries.index') }}" class="text-sm {{ $darkMode ? 'text-indigo-300 hover:text-indigo-200' : 'text-indigo-600 hover:text-indigo-900' }}">View All</a>
    </div>
    <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @php
            // Get recent galleries from the authenticated user
            $recentGalleries = \App\Models\Gallery::where('user_id', auth()->id())
                ->withCount('photos')
                ->latest()
                ->take(4)
                ->get();
        @endphp

        @forelse($recentGalleries as $gallery)
        <div class="group relative">
            <div class="aspect-w-4 aspect-h-3 overflow-hidden rounded-lg {{ $darkMode ? 'bg-gray-700' : 'bg-gray-100' }}">
                @if($gallery->cover_image)
                    <img src="{{ asset('storage/' . $gallery->cover_image) }}" alt="{{ $gallery->title }}" class="object-cover">
                @else
                    <img src="https://placehold.co/600x400?text=No+Cover" alt="Gallery placeholder" class="object-cover">
                @endif
                <div class="flex items-end p-4 opacity-0 group-hover:opacity-100" aria-hidden="true">
                    <div class="w-full rounded-md {{ $darkMode ? 'bg-gray-800 bg-opacity-75 text-white' : 'bg-white bg-opacity-75 text-gray-900' }} py-2 px-4 text-center text-sm font-medium backdrop-blur backdrop-filter">View Gallery</div>
                </div>
            </div>
            <div class="mt-2 flex items-center justify-between">
                <h3 class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                    <a href="{{ route('family.galleries.show', $gallery->slug) }}">
                        <span class="absolute inset-0"></span>
                        {{ $gallery->title }}
                    </a>
                </h3>
                <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $gallery->photos_count }} photos</p>
            </div>
            <p class="mt-1 text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">Added {{ $gallery->created_at->format('F j, Y') }}</p>
        </div>
        @empty
        <div class="col-span-full py-10 text-center {{ $darkMode ? 'bg-gray-700 text-gray-300' : 'bg-gray-100 text-gray-500' }} rounded-lg">
            <svg class="mx-auto h-12 w-12 {{ $darkMode ? 'text-gray-400' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">No galleries</h3>
            <p class="mt-1 text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">Get started by creating a new gallery.</p>
            <div class="mt-6">
                <a href="{{ route('family.galleries.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    New Gallery
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Recent Files -->
<div class="mt-10">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Recent Files</h2>
        <a href="{{ route('family.files.index') }}" class="text-sm {{ $darkMode ? 'text-indigo-300 hover:text-indigo-200' : 'text-indigo-600 hover:text-indigo-900' }}">View All</a>
    </div>

    @php
        // Get recent files from the authenticated user
        $recentFiles = \App\Models\File::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();
    @endphp

    <div class="mt-4 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
        <table class="min-w-full divide-y {{ $darkMode ? 'divide-gray-600' : 'divide-gray-300' }}">
            <thead class="{{ $darkMode ? 'bg-gray-700' : 'bg-gray-50' }}">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Size</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Added</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Download</span>
                    </th>
                </tr>
            </thead>
            <tbody class="{{ $darkMode ? 'bg-gray-800 divide-y divide-gray-700' : 'bg-white divide-y divide-gray-200' }}">
                @forelse($recentFiles as $file)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">{{ $file->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }}">{{ round($file->file_size / 1024, 2) }} KB</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }}">{{ Str::upper(pathinfo($file->filename, PATHINFO_EXTENSION)) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }}">{{ $file->created_at->format('F j, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('family.files.download', $file->id) }}" class="{{ $darkMode ? 'text-indigo-300 hover:text-indigo-200' : 'text-indigo-600 hover:text-indigo-900' }}">Download</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                        <svg class="mx-auto h-12 w-12 {{ $darkMode ? 'text-gray-400' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">No files</h3>
                        <p class="mt-1 text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">Get started by uploading a file.</p>
                        <div class="mt-6">
                            <a href="{{ route('family.files.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Upload File
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Family Announcements -->
<div class="mt-10">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Family Announcements</h2>
    </div>

    @php
        // Get recent announcements
        $announcements = \App\Models\Announcement::with('creator')
            ->latest()
            ->take(3)
            ->get();
    @endphp

    <div class="mt-4 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="{{ $darkMode ? 'divide-y divide-gray-700' : 'divide-y divide-gray-200' }}">
            @forelse($announcements as $announcement)
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium {{ $darkMode ? 'text-indigo-300' : 'text-indigo-600' }} truncate">{{ $announcement->title }}</p>
                        <div class="ml-2 flex-shrink-0 flex">
                            @if($announcement->created_at->isAfter(now()->subDays(3)))
                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $darkMode ? 'bg-green-900 text-green-100' : 'bg-green-100 text-green-800' }}">New</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2 sm:flex sm:justify-between">
                        <div class="sm:flex">
                            <p class="flex items-center text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }}">
                                Posted by {{ $announcement->creator ? $announcement->creator->name : 'Unknown' }}
                            </p>
                        </div>
                        <div class="mt-2 flex items-center text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} sm:mt-0">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 {{ $darkMode ? 'text-gray-400' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            <p>{{ $announcement->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">{{ Str::limit($announcement->content, 150) }}</p>
                    </div>
                </div>
            </li>
            @empty
            <li>
                <div class="px-4 py-8 sm:px-6 text-center">
                    <p class="{{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">No announcements available.</p>
                </div>
            </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

