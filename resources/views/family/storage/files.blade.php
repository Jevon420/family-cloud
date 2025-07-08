@extends('family.layouts.app')

@section('title', 'File Storage Details')

@section('content')
<div class="container mx-auto px-4 md:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">File Storage Details</h1>
            <p class="mt-2 text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">
                Total: {{ number_format($totalSize / (1024 * 1024), 2) }} MB across {{ $files->total() }} files
            </p>
        </div>
        <a href="{{ route('family.storage.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Storage
        </a>
    </div>

    <!-- File Types Summary -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-4">File Types Breakdown</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($fileTypes as $type)
            <div class="p-4 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">{{ $type->mime_type ?: 'Unknown' }}</h3>
                    <span class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $type->count }} files</span>
                </div>
                <div class="text-lg font-bold text-blue-500">{{ number_format($type->total_size / (1024 * 1024), 2) }} MB</div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $totalSize > 0 ? ($type->total_size / $totalSize) * 100 : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Files List -->
    <div class="{{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
        <div class="px-6 py-4 border-b {{ $darkMode ? 'border-gray-700' : 'border-gray-200' }}">
            <h2 class="text-lg font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">All Files (Sorted by Size)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y {{ $darkMode ? 'divide-gray-700' : 'divide-gray-200' }}">
                <thead class="{{ $darkMode ? 'bg-gray-700' : 'bg-gray-50' }}">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="{{ $darkMode ? 'bg-gray-800' : 'bg-white' }} divide-y {{ $darkMode ? 'divide-gray-700' : 'divide-gray-200' }}">
                    @foreach($files as $file)
                    <tr class="hover:{{ $darkMode ? 'bg-gray-700' : 'bg-gray-50' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <svg class="h-8 w-8 {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">{{ $file->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium {{ $darkMode ? 'bg-gray-700 text-gray-300' : 'bg-gray-100 text-gray-800' }} rounded-full">
                                {{ $file->mime_type ?: 'Unknown' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-900' }}">
                            {{ number_format($file->size / 1024, 2) }} KB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                            {{ $file->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('family.files.show', $file->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t {{ $darkMode ? 'border-gray-700' : 'border-gray-200' }}">
            {{ $files->links() }}
        </div>
    </div>
</div>
@endsection
