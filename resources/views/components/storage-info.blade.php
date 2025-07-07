<!-- Storage Info Component -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-4">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-semibold text-gray-900">Storage Usage</h3>
        <div class="text-sm text-gray-500">
            {{ auth()->user()->getFormattedUsed() }} of {{ auth()->user()->getFormattedQuota() }} used
        </div>
    </div>

    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-3">
        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
             style="width: {{ min(100, auth()->user()->getStorageUsagePercentage()) }}%"></div>
    </div>

    <div class="flex justify-between text-sm text-gray-600">
        <span>Available: {{ auth()->user()->getFormattedAvailable() }}</span>
        <span>{{ auth()->user()->getStorageUsagePercentage() }}% used</span>
    </div>

    @if(auth()->user()->getStorageUsagePercentage() > 90)
        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800">
                        Your storage is almost full. Consider deleting some files to free up space.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
