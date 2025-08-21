@extends('family.layouts.app')

@section('title', $page ? $page->getContent('title', 'Family Dashboard') : 'Family Dashboard')

@push('styles')
<style>
    /* Modern Dashboard Animations */
    .dashboard-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }

    .stat-card {
        background: linear-gradient(135deg, var(--tw-gradient-stops));
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px) scale(1.02);
    }

    .progress-ring {
        transition: stroke-dasharray 0.8s ease-in-out;
    }

    @keyframes countUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .count-animation {
        animation: countUp 0.6s ease-out forwards;
    }

    .gallery-grid-item {
        transition: all 0.3s ease;
    }

    .gallery-grid-item:hover {
        transform: scale(1.03);
    }

    .file-item {
        transition: all 0.2s ease;
    }

    .file-item:hover {
        background-color: var(--hover-bg);
        transform: translateX(4px);
    }

    /* Modern gradient overlays */
    .gradient-overlay {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.8) 0%, rgba(168, 85, 247, 0.8) 100%);
    }

    .welcome-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endpush

@section('content')
@php
    $pageTitle = $page->getContent('title', 'Family Dashboard');
    $pageDescription = $page->getContent('description', 'Welcome to the Family Cloud family portal. Share photos, files, and stay connected with your loved ones.');
    $user = auth()->user();
    $currentHour = now()->format('H');
    $greeting = $currentHour < 12 ? 'Good morning' : ($currentHour < 18 ? 'Good afternoon' : 'Good evening');
@endphp

<div class="space-y-8 animate-fade-in">
    <!-- Welcome Hero Section -->
    <div class="relative overflow-hidden">
        <div class="welcome-gradient rounded-2xl p-8 md:p-12">
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div class="text-white">
                        <h1 class="text-3xl md:text-4xl font-bold mb-2">
                            {{ $greeting }}, {{ $user->name }}! 👋
                        </h1>
                        <p class="text-white/90 text-lg mb-6 max-w-2xl">
                            {{ $pageDescription }}
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('family.files.create') }}"
                               class="btn-modern bg-white/20 text-white hover:bg-white/30 backdrop-blur-sm border border-white/30">
                                <i class="fas fa-upload mr-2"></i>
                                Upload Files
                            </a>
                            <a href="{{ route('family.galleries.create') }}"
                               class="btn-modern bg-white text-indigo-600 hover:bg-white/90">
                                <i class="fas fa-images mr-2"></i>
                                Create Gallery
                            </a>
                        </div>
                    </div>
                    <div class="hidden lg:block">
                        <div class="w-32 h-32 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <i class="fas fa-cloud text-4xl text-white/80"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-white/5 rounded-full"></div>
        </div>
    </div>

    <!-- Storage Overview Section -->
    <div class="dashboard-card card-modern p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }}">
                    Storage Overview
                </h2>
                <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-slate-600' }} mt-1">
                    Manage your cloud storage space
                </p>
            </div>
            <a href="{{ route('family.storage.index') }}"
               class="btn-secondary text-sm">
                <i class="fas fa-chart-line mr-2"></i>
                View Details
            </a>
        </div>

        <!-- Storage Progress -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium {{ $darkMode ? 'text-slate-300' : 'text-slate-700' }}">
                    Storage Used
                </span>
                <span class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-slate-500' }}">
                    {{ number_format($storageSummary['usedStorage'] / (1024 * 1024), 1) }} MB of
                    {{ number_format($storageSummary['maxStorage'] / (1024 * 1024), 1) }} MB
                </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-3 overflow-hidden">
                @php $usagePercentage = $storageSummary['maxStorage'] > 0 ? min(($storageSummary['usedStorage'] / $storageSummary['maxStorage']) * 100, 100) : 0; @endphp
                <div class="h-full rounded-full transition-all duration-1000 ease-out
                           {{ $usagePercentage > 80 ? 'bg-gradient-to-r from-red-500 to-red-600' :
                              ($usagePercentage > 60 ? 'bg-gradient-to-r from-yellow-500 to-orange-500' :
                               'bg-gradient-to-r from-blue-500 to-purple-600') }}"
                     style="width: {{ $usagePercentage }}%">
                </div>
            </div>
            @if($usagePercentage > 80)
                <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="text-sm text-red-700 dark:text-red-300">
                            Storage space is running low. Consider cleaning up old files.
                        </span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Storage Statistics Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="stat-card from-blue-500 to-blue-600 p-4 rounded-xl text-white count-animation hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">Files</p>
                        <p class="text-2xl font-bold">{{ number_format($storageSummary['totalFiles']) }}</p>
                        <p class="text-blue-100 text-xs mt-1">
                            {{ number_format($storageSummary['fileStorage'] / (1024 * 1024), 1) }} MB
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card from-green-500 to-green-600 p-4 rounded-xl text-white count-animation hover-lift"
                 style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-xs font-medium uppercase tracking-wide">Folders</p>
                        <p class="text-2xl font-bold">{{ number_format($storageSummary['totalFolders']) }}</p>
                        <p class="text-green-100 text-xs mt-1">Organized</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-folder text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card from-purple-500 to-purple-600 p-4 rounded-xl text-white count-animation hover-lift"
                 style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-xs font-medium uppercase tracking-wide">Photos</p>
                        <p class="text-2xl font-bold">{{ number_format($storageSummary['totalPhotos']) }}</p>
                        <p class="text-purple-100 text-xs mt-1">
                            {{ number_format($storageSummary['photoStorage'] / (1024 * 1024), 1) }} MB
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-camera text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card from-indigo-500 to-indigo-600 p-4 rounded-xl text-white count-animation hover-lift"
                 style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-xs font-medium uppercase tracking-wide">Galleries</p>
                        <p class="text-2xl font-bold">{{ number_format($storageSummary['totalGalleries']) }}</p>
                        <p class="text-indigo-100 text-xs mt-1">Collections</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-images text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card from-orange-500 to-orange-600 p-4 rounded-xl text-white count-animation hover-lift"
                 style="animation-delay: 0.4s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-xs font-medium uppercase tracking-wide">Used</p>
                        <p class="text-xl font-bold">{{ number_format($storageSummary['usedStorage'] / (1024 * 1024), 1) }}</p>
                        <p class="text-orange-100 text-xs mt-1">MB</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hdd text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card from-cyan-500 to-cyan-600 p-4 rounded-xl text-white count-animation hover-lift"
                 style="animation-delay: 0.5s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-cyan-100 text-xs font-medium uppercase tracking-wide">Available</p>
                        <p class="text-xl font-bold">{{ number_format(($storageSummary['maxStorage'] - $storageSummary['usedStorage']) / (1024 * 1024), 1) }}</p>
                        <p class="text-cyan-100 text-xs mt-1">MB</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cloud text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!$simpleDashboard)
    <!-- Quick Actions Section -->
    <div class="dashboard-card card-modern p-6" x-data="{ expanded: true }">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }}">
                    Quick Actions
                </h2>
                <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-slate-600' }} mt-1">
                    Create and manage your content
                </p>
            </div>
            <button @click="expanded = !expanded"
                    class="lg:hidden btn-secondary text-sm">
                <i class="fas fa-chevron-down transition-transform duration-200"
                   :class="{ 'rotate-180': !expanded }"></i>
                <span x-text="expanded ? 'Hide' : 'Show'" class="ml-2"></span>
            </button>
        </div>

        <div x-show="expanded"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Create Gallery Action -->
            <a href="{{ route('family.galleries.create') }}"
               class="group relative overflow-hidden bg-gradient-to-br from-purple-500 to-purple-700
                      rounded-2xl p-6 text-white transition-all duration-300 hover-lift">
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4
                                group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-images text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Create Gallery</h3>
                    <p class="text-purple-100 text-sm">
                        Organize your photos into beautiful collections
                    </p>
                </div>
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full
                            group-hover:scale-110 transition-transform duration-300"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-white/0 to-white/10
                            opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>

            <!-- Upload Files Action -->
            <a href="{{ route('family.files.create') }}"
               class="group relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700
                      rounded-2xl p-6 text-white transition-all duration-300 hover-lift">
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4
                                group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-cloud-upload-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Upload Files</h3>
                    <p class="text-blue-100 text-sm">
                        Share documents, images, and media files
                    </p>
                </div>
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full
                            group-hover:scale-110 transition-transform duration-300"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-white/0 to-white/10
                            opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>

            <!-- Create Folder Action -->
            <a href="{{ route('family.folders.create') }}"
               class="group relative overflow-hidden bg-gradient-to-br from-green-500 to-green-700
                      rounded-2xl p-6 text-white transition-all duration-300 hover-lift">
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4
                                group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-folder-plus text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Create Folder</h3>
                    <p class="text-green-100 text-sm">
                        Organize your files in structured folders
                    </p>
                </div>
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full
                            group-hover:scale-110 transition-transform duration-300"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-white/0 to-white/10
                            opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
        </div>
    </div>
    @endif

    <!-- Recent Galleries Section -->
    <div class="dashboard-card card-modern p-6" x-data="{ expanded: true }">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }}">
                    Recent Galleries
                </h2>
                <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-slate-600' }} mt-1">
                    Your latest photo collections
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('family.galleries.index') }}"
                   class="btn-secondary text-sm">
                    <i class="fas fa-th-large mr-2"></i>
                    View All
                </a>
                <button @click="expanded = !expanded"
                        class="lg:hidden btn-secondary text-sm">
                    <i class="fas fa-chevron-down transition-transform duration-200"
                       :class="{ 'rotate-180': !expanded }"></i>
                </button>
            </div>
        </div>

        <div x-show="expanded"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            @forelse($recentGalleries as $gallery)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($recentGalleries as $gallery)
                <div class="gallery-grid-item group relative">
                    <div class="aspect-w-4 aspect-h-3 overflow-hidden rounded-xl {{ $darkMode ? 'bg-slate-700' : 'bg-gray-100' }} shadow-md">
                        @if($gallery->cover_image)
                            <img src="{{ route('public.storage.signed-url', ['path' => $gallery->cover_image, 'type' => 'long']) }}"
                                 alt="{{ $gallery->name }}"
                                 class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center">
                                    <i class="fas fa-images text-4xl {{ $darkMode ? 'text-slate-500' : 'text-gray-400' }} mb-2"></i>
                                    <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-gray-500' }}">No Cover Image</p>
                                </div>
                            </div>
                        @endif

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent
                                    opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-4 left-4 right-4">
                                <div class="flex items-center justify-between text-white">
                                    <div>
                                        <p class="text-sm font-medium">{{ $gallery->photos_count }} photos</p>
                                        <p class="text-xs opacity-75">{{ $gallery->created_at->format('M j, Y') }}</p>
                                    </div>
                                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                                        <i class="fas fa-arrow-right text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Info -->
                    <div class="mt-4">
                        <h3 class="font-semibold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }}
                                   group-hover:text-purple-600 transition-colors">
                            <a href="{{ route('family.galleries.show', $gallery->slug) }}" class="absolute inset-0"></a>
                            {{ $gallery->name }}
                        </h3>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-slate-600' }}">
                                {{ $gallery->photos_count }} photos
                            </p>
                            <p class="text-xs {{ $darkMode ? 'text-slate-500' : 'text-slate-500' }}">
                                {{ $gallery->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-purple-100 to-purple-200
                            dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-images text-3xl text-purple-500"></i>
                </div>
                <h3 class="text-lg font-semibold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }} mb-2">
                    No galleries yet
                </h3>
                <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-slate-600' }} mb-6 max-w-sm mx-auto">
                    Create your first gallery to organize and share your photos with family members.
                </p>
                <a href="{{ route('family.galleries.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Create Your First Gallery
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Files Section -->
    <div class="dashboard-card card-modern p-6" x-data="{ expanded: true }">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }}">
                    Recent Files
                </h2>
                <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-slate-600' }} mt-1">
                    Your latest uploaded files
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('family.files.index') }}"
                   class="btn-secondary text-sm">
                    <i class="fas fa-file-alt mr-2"></i>
                    View All
                </a>
                <button @click="expanded = !expanded"
                        class="lg:hidden btn-secondary text-sm">
                    <i class="fas fa-chevron-down transition-transform duration-200"
                       :class="{ 'rotate-180': !expanded }"></i>
                </button>
            </div>
        </div>

        <div x-show="expanded"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            @forelse($recentFiles as $file)
            <div class="space-y-1">
                @foreach($recentFiles as $file)
                <div class="file-item p-4 border-b border-gray-200 dark:border-slate-700 last:border-b-0
                            hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-all duration-200 group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            <!-- File Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600
                                            flex items-center justify-center text-white group-hover:scale-105 transition-transform">
                                    @php
                                        $extension = strtolower(pathinfo($file->name, PATHINFO_EXTENSION));
                                        $iconClass = 'fas fa-file'; // default

                                        if ($extension === 'pdf') {
                                            $iconClass = 'fas fa-file-pdf';
                                        } elseif (in_array($extension, ['doc', 'docx'])) {
                                            $iconClass = 'fas fa-file-word';
                                        } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                            $iconClass = 'fas fa-file-excel';
                                        } elseif (in_array($extension, ['ppt', 'pptx'])) {
                                            $iconClass = 'fas fa-file-powerpoint';
                                        } elseif (in_array($extension, ['zip', 'rar', '7z'])) {
                                            $iconClass = 'fas fa-file-archive';
                                        } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                            $iconClass = 'fas fa-file-image';
                                        } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv'])) {
                                            $iconClass = 'fas fa-file-video';
                                        } elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) {
                                            $iconClass = 'fas fa-file-audio';
                                        } elseif ($extension === 'txt') {
                                            $iconClass = 'fas fa-file-alt';
                                        }
                                    @endphp
                                    <i class="{{ $iconClass }} text-lg"></i>
                                </div>
                            </div>

                            <!-- File Details -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }}
                                           group-hover:text-blue-600 transition-colors truncate">
                                    {{ $file->name }}
                                </h3>
                                <div class="flex items-center space-x-4 mt-1">
                                    <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-slate-600' }}">
                                        {{ number_format($file->size / 1024, 1) }} KB
                                    </p>
                                    <p class="text-sm {{ $darkMode ? 'text-slate-500' : 'text-slate-500' }}">
                                        {{ $file->created_at->diffForHumans() }}
                                    </p>
                                    @if($file->folder)
                                    <div class="flex items-center text-xs {{ $darkMode ? 'text-slate-500' : 'text-slate-500' }}">
                                        <i class="fas fa-folder mr-1"></i>
                                        {{ $file->folder->name }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('family.files.show', $file->id) }}"
                               class="p-2 rounded-lg {{ $darkMode ? 'hover:bg-slate-600' : 'hover:bg-gray-100' }}
                                      transition-colors" title="View file">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('family.files.download', $file->id) }}"
                               class="p-2 rounded-lg {{ $darkMode ? 'hover:bg-slate-600' : 'hover:bg-gray-100' }}
                                      transition-colors" title="Download file">
                                <i class="fas fa-download text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-blue-200
                            dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-3xl text-blue-500"></i>
                </div>
                <h3 class="text-lg font-semibold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }} mb-2">
                    No files yet
                </h3>
                <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-slate-600' }} mb-6 max-w-sm mx-auto">
                    Upload your first file to start sharing documents, images, and more with your family.
                </p>
                <a href="{{ route('family.files.create') }}" class="btn-primary">
                    <i class="fas fa-upload mr-2"></i>
                    Upload Your First File
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Folders Section -->
    <div class="dashboard-card card-modern p-6" x-data="{ expanded: true }">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }}">
                    Recent Folders
                </h2>
                <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-slate-600' }} mt-1">
                    Your organized file collections
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('family.folders.index') }}"
                   class="btn-secondary text-sm">
                    <i class="fas fa-folder mr-2"></i>
                    View All
                </a>
                <button @click="expanded = !expanded"
                        class="lg:hidden btn-secondary text-sm">
                    <i class="fas fa-chevron-down transition-transform duration-200"
                       :class="{ 'rotate-180': !expanded }"></i>
                </button>
            </div>
        </div>

        <div x-show="expanded"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            @forelse($recentFolders as $folder)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recentFolders as $folder)
                <div class="group p-4 rounded-xl border border-gray-200 dark:border-slate-700
                            hover:border-yellow-300 dark:hover:border-yellow-600
                            transition-all duration-200 hover-lift">
                    <div class="flex items-center space-x-4">
                        <!-- Folder Icon -->
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-600
                                        flex items-center justify-center text-white
                                        group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-folder text-xl"></i>
                            </div>
                        </div>

                        <!-- Folder Details -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }}
                                       group-hover:text-yellow-600 transition-colors truncate">
                                <a href="{{ route('family.folders.show', $folder->id) }}" class="absolute inset-0"></a>
                                {{ $folder->name }}
                            </h3>
                            <div class="flex items-center space-x-3 mt-2">
                                <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-slate-600' }}">
                                    {{ $folder->files_count }} files
                                </p>
                                <p class="text-sm {{ $darkMode ? 'text-slate-500' : 'text-slate-500' }}">
                                    {{ $folder->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if($folder->description)
                            <p class="text-xs {{ $darkMode ? 'text-slate-500' : 'text-slate-500' }} mt-1 truncate">
                                {{ $folder->description }}
                            </p>
                            @endif
                        </div>

                        <!-- Arrow Icon -->
                        <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-arrow-right text-yellow-500"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-yellow-100 to-yellow-200
                            dark:from-yellow-900/30 dark:to-yellow-800/30 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-folder text-3xl text-yellow-500"></i>
                </div>
                <h3 class="text-lg font-semibold {{ $darkMode ? 'text-slate-100' : 'text-slate-900' }} mb-2">
                    No folders yet
                </h3>
                <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-slate-600' }} mb-6 max-w-sm mx-auto">
                    Create folders to organize your files and keep everything structured and easy to find.
                </p>
                <a href="{{ route('family.folders.create') }}" class="btn-primary">
                    <i class="fas fa-folder-plus mr-2"></i>
                    Create Your First Folder
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Progressive loading animation for dashboard cards
    const cards = document.querySelectorAll('.dashboard-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'all 0.6s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });

    // Auto-collapse sections on mobile for better UX
    if (window.innerWidth <= 768) {
        // Auto-collapse non-essential sections on mobile
        const toggleButtons = document.querySelectorAll('[x-data] button[\\@click*="expanded"]');
        toggleButtons.forEach(button => {
            // Only auto-collapse Quick Actions on mobile
            if (button.closest('[x-data]').querySelector('h2')?.textContent.includes('Quick Actions')) {
                button.click();
            }
        });
    }

    // Add intersection observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, observerOptions);

    // Observe all stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => observer.observe(card));

    // Add smooth hover effects for gallery items
    const galleryItems = document.querySelectorAll('.gallery-grid-item');
    galleryItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.03) translateY(-4px)';
        });

        item.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) translateY(0)';
        });
    });

    // Enhanced file item interactions
    const fileItems = document.querySelectorAll('.file-item');
    fileItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.w-12.h-12 > i');
            if (icon) {
                icon.style.transform = 'scale(1.1)';
            }
        });

        item.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.w-12.h-12 > i');
            if (icon) {
                icon.style.transform = 'scale(1)';
            }
        });
    });

    // Add loading states for action buttons
    const actionButtons = document.querySelectorAll('a[href*="create"], a[href*="upload"]');
    actionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!this.dataset.loading) {
                this.dataset.loading = 'true';
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';

                // Reset after 3 seconds if navigation doesn't happen
                setTimeout(() => {
                    if (this.dataset.loading) {
                        this.innerHTML = originalText;
                        delete this.dataset.loading;
                    }
                }, 3000);
            }
        });
    });
});

// Storage usage animation
function animateStorageBar() {
    const progressBar = document.querySelector('.h-3 > div[style*="width"]');
    if (progressBar) {
        const width = progressBar.style.width;
        progressBar.style.width = '0%';
        progressBar.style.transition = 'width 2s ease-out';

        setTimeout(() => {
            progressBar.style.width = width;
        }, 500);
    }
}

// Run storage animation when the section comes into view
const storageSection = document.querySelector('.dashboard-card');
if (storageSection) {
    const storageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStorageBar();
                storageObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    storageObserver.observe(storageSection);
}
</script>
@endpush
@endsection

