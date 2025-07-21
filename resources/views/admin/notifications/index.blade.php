@extends('admin.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">All Notifications</h1>
    <div class="bg-white shadow rounded-lg divide-y">
        @forelse(auth()->user()->notifications as $notification)
            <div class="px-6 py-4 @if($notification->read_at === null) bg-red-50 @endif flex justify-between items-center">
                <div>
                    <div class="font-semibold @if($notification->read_at === null) text-red-800 @endif">
                        {{ $notification->data['title'] ?? 'Notification' }}
                    </div>
                    <div class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
                <div>
                    @if($notification->read_at === null)
                        <form method="POST" action="{{ route('admin.notifications.markAsRead', $notification->id) }}">
                            @csrf
                            <button type="submit" class="text-xs px-3 py-1 bg-indigo-100 text-indigo-700 rounded">Mark as read</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="px-6 py-4 text-gray-500">No notifications found.</div>
        @endforelse
    </div>
</div>
@endsection
