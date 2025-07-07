@extends('family.layouts.app')

@section('title', 'User Settings')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">User Settings</h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
                <p>Customize your Family Cloud experience.</p>
            </div>

            <form method="POST" action="{{ route('family.settings.update') }}" class="mt-5 space-y-6">
                @csrf
                @method('PUT')

                <!-- Theme Selection -->
                <div>
                    <label for="theme" class="block text-sm font-medium text-gray-700">Theme</label>
                    <select id="theme" name="theme" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="light" {{ $theme === 'light' ? 'selected' : '' }}>Light</option>
                        <option value="indigo" {{ $theme === 'indigo' ? 'selected' : '' }}>Indigo</option>
                        <option value="blue" {{ $theme === 'blue' ? 'selected' : '' }}>Blue</option>
                        <option value="green" {{ $theme === 'green' ? 'selected' : '' }}>Green</option>
                        <option value="purple" {{ $theme === 'purple' ? 'selected' : '' }}>Purple</option>
                    </select>
                </div>

                <!-- Dark Mode Toggle -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="dark_mode" name="dark_mode" type="checkbox" value="1" {{ $darkMode ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="dark_mode" class="font-medium text-gray-700">Dark Mode</label>
                        <p class="text-gray-500">Enable dark mode for a more comfortable viewing experience in low light.</p>
                    </div>
                </div>

                <!-- Simple Dashboard Toggle -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="simple_dashboard" name="simple_dashboard" type="checkbox" value="1" {{ $simpleDashboard ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="simple_dashboard" class="font-medium text-gray-700">Simple Dashboard</label>
                        <p class="text-gray-500">Use a simplified dashboard layout with fewer elements.</p>
                    </div>
                </div>

                <!-- Gallery Layout Selection -->
                <div>
                    <label for="gallery_layout" class="block text-sm font-medium text-gray-700">Gallery Layout</label>
                    <select id="gallery_layout" name="gallery_layout" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="grid" {{ $galleryLayout === 'grid' ? 'selected' : '' }}>Grid</option>
                        <option value="masonry" {{ $galleryLayout === 'masonry' ? 'selected' : '' }}>Masonry</option>
                        <option value="list" {{ $galleryLayout === 'list' ? 'selected' : '' }}>List</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500">Choose how your photos and galleries are displayed.</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="mt-10 bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Profile Information</h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
                <p>Update your account's profile information.</p>
            </div>

            <div class="mt-5 space-y-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input type="text" value="{{ $user->name }}" disabled class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 bg-gray-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input type="text" value="{{ $user->email }}" disabled class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 bg-gray-100">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('family.profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
