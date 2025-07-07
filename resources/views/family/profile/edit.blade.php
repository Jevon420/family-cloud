@extends('family.layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold {{ $darkMode ? 'text-white' : 'text-gray-800' }}">My Profile</h1>
        <a href="{{ route('family.profile.password') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'text-gray-300 bg-gray-700 hover:bg-gray-600 border-gray-600' : 'text-gray-700 bg-white hover:bg-gray-50' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Change Password
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden {{ $darkMode ? 'bg-gray-800' : '' }}">
        <div class="p-6">
            <form action="{{ route('family.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-1">
                                Full Name
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : '' }}"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-1">
                                Email Address
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : '' }}"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="location" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-1">
                                Location
                            </label>
                            <input type="text" name="location" id="location" value="{{ old('location', $profile->location ?? '') }}"
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : '' }}">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="birth_date" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-1">
                                Birth Date
                            </label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $profile->birth_date ? $profile->birth_date->format('Y-m-d') : '') }}"
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : '' }}">
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-1">
                                Profile Photo
                            </label>
                            <div class="flex items-center mb-4">
                                @if(isset($profile->avatar))
                                    <img src="{{ asset('storage/' . $profile->avatar) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover">
                                @else
                                    <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center {{ $darkMode ? 'bg-gray-700' : '' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md {{ $darkMode ? 'border-gray-600' : '' }}">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 {{ $darkMode ? 'text-gray-400' : 'text-gray-400' }}" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 {{ $darkMode ? 'text-gray-400' : '' }}">
                                        <label for="profile_photo" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }} focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a photo</span>
                                            <input id="profile_photo" name="profile_photo" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                                        PNG, JPG, GIF up to 2MB
                                    </p>
                                </div>
                            </div>
                            @error('profile_photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="bio" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-1">
                                Bio
                            </label>
                            <textarea name="bio" id="bio" rows="6"
                                     class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : '' }}">{{ old('bio', $profile->bio ?? '') }}</textarea>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
