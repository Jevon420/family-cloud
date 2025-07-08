@extends('family.layouts.app')

@section('title', $page ? $page->getContent('title', 'Family Dashboard') : 'Family Dashboard')

@push('styles')
<style>
    .collapsible-section {
        transition: max-height 0.3s ease;
    }
    .collapsible-section.collapsed {
        max-height: 0;
        overflow: hidden;
    }
    .collapsible-section.expanded {
        max-height: 1000px;
    }
</style>
@endpush

@section('content')
@php
    $pageTitle = $page->getContent('title', 'Family Dashboard');
    $pageDescription = $page->getContent('description', 'Welcome to the Family Cloud family portal. Share photos, files, and stay connected with your loved ones.');
@endphp

<div class="mb-8 px-4 md:px-8">
    <h1 class="text-3xl font-extrabold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">{{ $pageTitle }}</h1>
    <p class="mt-2 text-base {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">
        {{ $pageDescription }}
    </p>
</div>

<!-- Storage Summary Section -->
<div class="mb-8 px-4 md:px-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Storage Overview</h2>
        <a href="{{ route('family.storage.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">View Details →</a>
    </div>

    <!-- Storage Progress Bar -->
    <div class="mb-6 p-4 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Storage Used</span>
            <span class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                {{ number_format($storageSummary['usedStorage'] / (1024 * 1024), 2) }} MB of
                {{ number_format($storageSummary['maxStorage'] / (1024 * 1024), 2) }} MB
            </span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ min(($storageSummary['usedStorage'] / $storageSummary['maxStorage']) * 100, 100) }}%"></div>
        </div>
    </div>

    <!-- Storage Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <div class="p-4 bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg shadow-lg">
            <h3 class="text-sm font-semibold text-white">Files</h3>
            <p class="text-2xl font-bold text-white">{{ $storageSummary['totalFiles'] }}</p>
            <p class="text-xs text-blue-100">{{ number_format($storageSummary['fileStorage'] / (1024 * 1024), 2) }} MB</p>
        </div>
        <div class="p-4 bg-gradient-to-r from-green-500 to-green-700 rounded-lg shadow-lg">
            <h3 class="text-sm font-semibold text-white">Folders</h3>
            <p class="text-2xl font-bold text-white">{{ $storageSummary['totalFolders'] }}</p>
        </div>
        <div class="p-4 bg-gradient-to-r from-purple-500 to-purple-700 rounded-lg shadow-lg">
            <h3 class="text-sm font-semibold text-white">Photos</h3>
            <p class="text-2xl font-bold text-white">{{ $storageSummary['totalPhotos'] }}</p>
            <p class="text-xs text-purple-100">{{ number_format($storageSummary['photoStorage'] / (1024 * 1024), 2) }} MB</p>
        </div>
        <div class="p-4 bg-gradient-to-r from-indigo-500 to-indigo-700 rounded-lg shadow-lg">
            <h3 class="text-sm font-semibold text-white">Galleries</h3>
            <p class="text-2xl font-bold text-white">{{ $storageSummary['totalGalleries'] }}</p>
        </div>
        <div class="p-4 bg-gradient-to-r from-orange-500 to-orange-700 rounded-lg shadow-lg">
            <h3 class="text-sm font-semibold text-white">Used</h3>
            <p class="text-lg font-bold text-white">{{ number_format($storageSummary['usedStorage'] / (1024 * 1024), 2) }} MB</p>
        </div>
        <div class="p-4 bg-gradient-to-r from-gray-500 to-gray-700 rounded-lg shadow-lg">
            <h3 class="text-sm font-semibold text-white">Available</h3>
            <p class="text-lg font-bold text-white">{{ number_format(($storageSummary['maxStorage'] - $storageSummary['usedStorage']) / (1024 * 1024), 2) }} MB</p>
        </div>
    </div>
</div>

@if(!$simpleDashboard)
<!-- Quick Actions -->
<div class="mb-8 px-4 md:px-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Quick Actions</h2>
        <button class="md:hidden text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}" onclick="toggleSection('quickActions')">
            <span id="quickActionsToggle">Hide</span>
        </button>
    </div>
    <div id="quickActions" class="collapsible-section expanded">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <a href="{{ route('family.galleries.create') }}" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-indigo-700 to-indigo-500 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-transform">
                <div class="rounded-full bg-indigo-100 p-4 w-16 h-16 flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white">Create Gallery</h3>
            </a>

            <a href="{{ route('family.files.create') }}" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-indigo-700 to-indigo-500 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-transform">
                <div class="rounded-full bg-indigo-100 p-4 w-16 h-16 flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white">Upload File</h3>
            </a>

            <a href="{{ route('family.folders.create') }}" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-indigo-700 to-indigo-500 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-transform">
                <div class="rounded-full bg-indigo-100 p-4 w-16 h-16 flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white">Create Folder</h3>
            </a>
        </div>
    </div>
</div>
@endif

<!-- Recent Galleries Section -->
<div class="mb-8 px-4 md:px-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Recent Galleries</h2>
        <div class="flex items-center space-x-4">
            <a href="{{ route('family.galleries.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">View All →</a>
            <button class="md:hidden text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}" onclick="toggleSection('recentGalleries')">
                <span id="recentGalleriesToggle">Hide</span>
            </button>
        </div>
    </div>
    <div id="recentGalleries" class="collapsible-section expanded">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($recentGalleries as $gallery)
            <div class="relative group">
                <div class="aspect-w-4 aspect-h-3 overflow-hidden rounded-lg shadow-lg {{ $darkMode ? 'bg-gray-700' : 'bg-gray-100' }}">
                    @if($gallery->cover_image)
                        <img src="{{ asset('storage/' . $gallery->cover_image) }}" alt="{{ $gallery->name }}" class="object-cover">
                    @else
                        <img src="https://placehold.co/600x400?text=No+Cover" alt="Gallery placeholder" class="object-cover">
                    @endif
                    <div class="absolute inset-0 flex items-end p-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="w-full rounded-md {{ $darkMode ? 'bg-gray-800 bg-opacity-75 text-white' : 'bg-white bg-opacity-75 text-gray-900' }} py-2 px-4 text-center text-sm font-medium backdrop-blur backdrop-filter">View Gallery</div>
                    </div>
                </div>
                <div class="mt-2 flex items-center justify-between">
                    <h3 class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                        <a href="{{ route('family.galleries.show', $gallery->slug) }}">
                            <span class="absolute inset-0"></span>
                            {{ $gallery->name }}
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
</div>

<!-- Recent Files Section -->
<div class="mb-8 px-4 md:px-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Recent Files</h2>
        <div class="flex items-center space-x-4">
            <a href="{{ route('family.files.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">View All →</a>
            <button class="md:hidden text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}" onclick="toggleSection('recentFiles')">
                <span id="recentFilesToggle">Hide</span>
            </button>
        </div>
    </div>
    <div id="recentFiles" class="collapsible-section expanded">
        <div class="{{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
            @forelse($recentFiles as $file)
            <div class="border-b {{ $darkMode ? 'border-gray-700' : 'border-gray-200' }} p-4 hover:{{ $darkMode ? 'bg-gray-700' : 'bg-gray-50' }} transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">{{ $file->name }}</h3>
                            <p class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ number_format($file->size / 1024, 2) }} KB • {{ $file->created_at->format('M j, Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('family.files.show', $file->id) }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">View</a>
                </div>
            </div>
            @empty
            <div class="text-center py-8 {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                <p>No recent files</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Folders Section -->
<div class="mb-8 px-4 md:px-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Recent Folders</h2>
        <div class="flex items-center space-x-4">
            <a href="{{ route('family.folders.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">View All →</a>
            <button class="md:hidden text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}" onclick="toggleSection('recentFolders')">
                <span id="recentFoldersToggle">Hide</span>
            </button>
        </div>
    </div>
    <div id="recentFolders" class="collapsible-section expanded">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @forelse($recentFolders as $folder)
            <div class="p-4 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                            <a href="{{ route('family.folders.show', $folder->id) }}">{{ $folder->name }}</a>
                        </h3>
                        <p class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $folder->files_count }} files • {{ $folder->created_at->format('M j, Y') }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8 {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                <p>No recent folders</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    const toggle = document.getElementById(sectionId + 'Toggle');

    if (section.classList.contains('expanded')) {
        section.classList.remove('expanded');
        section.classList.add('collapsed');
        toggle.textContent = 'Show';
    } else {
        section.classList.remove('collapsed');
        section.classList.add('expanded');
        toggle.textContent = 'Hide';
    }
}

// Auto-collapse sections on mobile
if (window.innerWidth <= 768) {
    document.addEventListener('DOMContentLoaded', function() {
        const sections = ['quickActions', 'recentGalleries', 'recentFiles', 'recentFolders'];
        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            const toggle = document.getElementById(sectionId + 'Toggle');
            if (section && toggle) {
                section.classList.remove('expanded');
                section.classList.add('collapsed');
                toggle.textContent = 'Show';
            }
        });
    });
}
</script>
@endpush
@endsection

