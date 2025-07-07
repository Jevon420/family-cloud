@extends('family.layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="container px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <a href="{{ route('family.profile.edit') }}" class="mr-2 text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            <h1 class="text-2xl font-semibold {{ $darkMode ? 'text-white' : 'text-gray-800' }}">Change Password</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden {{ $darkMode ? 'bg-gray-800' : '' }}">
        <div class="p-6">
            <form action="{{ route('family.profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="max-w-md mx-auto">
                    <div class="mb-6">
                        <label for="current_password" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-1">
                            Current Password
                        </label>
                        <input type="password" name="current_password" id="current_password"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : '' }}"
                               required>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-1">
                            New Password
                        </label>
                        <input type="password" name="password" id="password"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : '' }}"
                               required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-1">
                            Confirm New Password
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : '' }}"
                               required>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit"
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
