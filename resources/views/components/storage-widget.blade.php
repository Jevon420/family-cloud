<!-- Storage Widget for Header -->
<div class="flex items-center space-x-2 text-sm">
    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 1.79 4 4 4h8c2.21 0 4-1.79 4-4V7c0-2.21-1.79-4-4-4H8c-2.21 0-4 1.79-4 4z" />
    </svg>
    <div class="hidden md:block">
        <div class="w-16 bg-gray-200 rounded-full h-1.5">
            <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300"
                 style="width: {{ min(100, auth()->user()->getStorageUsagePercentage()) }}%"></div>
        </div>
    </div>
    <span class="text-gray-600">{{ auth()->user()->getFormattedAvailable() }}</span>
</div>
