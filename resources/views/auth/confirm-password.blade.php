@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Confirm Password</h2>

            <div class="mb-4 text-sm text-gray-700">
                <p>This is a secure area of the application. Please confirm your password before continuing.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Confirm Password
                    </button>
                </div>

                @if (Route::has('password.request'))
                <div class="text-center mt-4">
                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                        Forgot your password?
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
