@extends('family.layouts.app')

@section('title', 'Storage Details')

@section('content')
<div class="container mx-auto px-4 md:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Storage Details</h1>
            <p class="mt-2 text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">Detailed breakdown of your storage usage</p>
        </div>
        <a href="{{ route('family.home') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Storage Overview -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Storage Usage Chart -->
        <div class="p-6 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
            <h3 class="text-lg font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-4">Storage Usage</h3>
            <div class="relative">
                <div class="flex justify-center mb-4">
                    <div class="relative w-32 h-32">
                        <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 36 36">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="{{ $darkMode ? '#374151' : '#E5E7EB' }}" stroke-width="2"/>
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#4F46E5" stroke-width="2" stroke-dasharray="{{ $storageData['summary']['usagePercentage'] }}, 100"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-2xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                                {{ number_format($storageData['summary']['usagePercentage'], 1) }}%
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-center space-y-2">
                    <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">
                        {{ number_format($storageData['summary']['totalUsed'] / (1024 * 1024), 2) }} MB used of
                        {{ number_format($storageData['summary']['maxStorage'] / (1024 * 1024), 2) }} MB
                    </p>
                    <p class="text-xs {{ $darkMode ? 'text-gray-500' : 'text-gray-500' }}">
                        {{ number_format($storageData['summary']['availableStorage'] / (1024 * 1024), 2) }} MB available
                    </p>
                </div>
            </div>
        </div>

        <!-- Storage Breakdown -->
        <div class="p-6 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
            <h3 class="text-lg font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-4">Storage Breakdown</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">Files</span>
                    <span class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                        {{ number_format($storageData['summary']['fileStorage'] / (1024 * 1024), 2) }} MB
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $storageData['summary']['totalUsed'] > 0 ? ($storageData['summary']['fileStorage'] / $storageData['summary']['totalUsed']) * 100 : 0 }}%"></div>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">Photos</span>
                    <span class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                        {{ number_format($storageData['summary']['photoStorage'] / (1024 * 1024), 2) }} MB
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $storageData['summary']['totalUsed'] > 0 ? ($storageData['summary']['photoStorage'] / $storageData['summary']['totalUsed']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="mb-8 grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-blue-500">{{ $storageData['summary']['totalFiles'] }}</div>
            <div class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">Files</div>
        </div>
        <div class="p-4 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-green-500">{{ $storageData['summary']['totalFolders'] }}</div>
            <div class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">Folders</div>
        </div>
        <div class="p-4 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-purple-500">{{ $storageData['summary']['totalPhotos'] }}</div>
            <div class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">Photos</div>
        </div>
        <div class="p-4 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-indigo-500">{{ $storageData['summary']['totalGalleries'] }}</div>
            <div class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">Galleries</div>
        </div>
    </div>

    <!-- Detailed Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- File Types -->
        <div class="p-6 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">File Types</h3>
                <a href="{{ route('family.storage.files') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">View All →</a>
            </div>
            <div class="space-y-3">
                @foreach($storageData['fileTypes']->take(5) as $type)
                <div class="flex justify-between items-center">
                    <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">{{ $type->mime_type ?: 'Unknown' }}</span>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $type->count }} files</span>
                        <span class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                            {{ number_format($type->total_size / 1024, 2) }} KB
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Photo Types -->
        <div class="p-6 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Photo Types</h3>
                <a href="{{ route('family.storage.photos') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">View All →</a>
            </div>
            <div class="space-y-3">
                @foreach($storageData['photoTypes']->take(5) as $type)
                <div class="flex justify-between items-center">
                    <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">{{ $type->mime_type ?: 'Unknown' }}</span>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $type->count }} photos</span>
                        <span class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                            {{ number_format($type->total_size / 1024, 2) }} KB
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Largest Files -->
        <div class="p-6 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
            <h3 class="text-lg font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-4">Largest Files</h3>
            <div class="space-y-3">
                @foreach($storageData['largestFiles']->take(5) as $file)
                <div class="flex justify-between items-center">
                    <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }} truncate">{{ $file->name }}</span>
                    <span class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                        {{ number_format($file->size / 1024, 2) }} KB
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Largest Photos -->
        <div class="p-6 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
            <h3 class="text-lg font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-4">Largest Photos</h3>
            <div class="space-y-3">
                @foreach($storageData['largestPhotos']->take(5) as $photo)
                <div class="flex justify-between items-center">
                    <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }} truncate">{{ $photo->name }}</span>
                    <span class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                        {{ number_format($photo->file_size / 1024, 2) }} KB
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Storage Overview by Type -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Wasabi Storage Overview (User Content) -->
        <div class="p-6 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
            <h3 class="text-lg font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-4">
                <i class="fas fa-cloud mr-2"></i>Wasabi Storage (Your Content)
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">Used:</span>
                    <span class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                        {{ number_format($storageData['summary']['wasabiTotalUsed'] / (1024 * 1024), 2) }} MB
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">Available:</span>
                    <span class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                        {{ number_format($storageData['summary']['wasabiAvailableStorage'] / (1024 * 1024), 2) }} MB
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $storageData['summary']['wasabiUsagePercentage'] }}%"></div>
                </div>
                <p class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                    Files, Photos, Galleries stored on Wasabi Cloud
                </p>
            </div>
        </div>

        <!-- Local Storage Overview (Profile Data) -->
        <div class="p-6 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
            <h3 class="text-lg font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-4">
                <i class="fas fa-server mr-2"></i>Local Storage (Profile Data)
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">Profile Image:</span>
                    <span class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                        {{ $storageData['summary']['localStorageUsedFormatted'] }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full" style="width: 5%"></div>
                </div>
                <p class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                    Profile image and account data stored locally
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
