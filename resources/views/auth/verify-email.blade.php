@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Verify Your Email Address</h2>

            @if (session('resent'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    A fresh verification link has been sent to your email address.
                </div>
            @endif

            <p class="mb-4 text-gray-700">
                Before proceeding, please check your email for a verification link.
                If you did not receive the email,
            </p>

            <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
                @csrf

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Resend Verification Email
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
