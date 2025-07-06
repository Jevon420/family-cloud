@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-center text-xl font-bold mb-6">Logging Out...</h2>
            <p class="text-center text-gray-500 mb-8">Please wait while we log you out of your account.</p>

            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</div>

<script>
    // Automatically submit the logout form when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('logout-form').submit();
    });
</script>
@endsection
