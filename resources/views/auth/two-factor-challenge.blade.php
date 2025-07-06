@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Two Factor Authentication</h2>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4 text-sm text-gray-700">
                <p>Please confirm access to your account by entering the authentication code provided by your authenticator application.</p>
            </div>

            <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Authentication Code</label>
                    <input id="code" type="text" name="code" autofocus autocomplete="one-time-code"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Verify
                    </button>
                </div>
            </form>

            <div class="mt-4">
                <p class="text-sm text-gray-700 mb-4">If you lost your device, you may use a recovery code.</p>

                <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="recovery_code" class="block text-sm font-medium text-gray-700">Recovery Code</label>
                        <input id="recovery_code" type="text" name="recovery_code" autocomplete="one-time-code"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Use Recovery Code
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
