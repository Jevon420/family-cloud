<!-- Modern Storage Widget for Header -->
<div class="relative group" x-data="{ showDetails: false }">
    <div @mouseenter="showDetails = true"
         @mouseleave="showDetails = false"
         class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ $darkMode ?? false ? 'bg-slate-700/50 hover:bg-slate-700' : 'bg-gray-100/50 hover:bg-gray-100' }}
                transition-all duration-200 cursor-pointer">

        <!-- Storage Icon -->
        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 text-white">
            <i class="fas fa-hdd text-sm"></i>
        </div>

        <!-- Storage Progress and Info -->
        <div class="hidden sm:flex flex-col space-y-1">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-medium {{ $darkMode ?? false ? 'text-slate-300' : 'text-gray-700' }}">
                    Storage
                </span>
                <span class="text-xs {{ $darkMode ?? false ? 'text-slate-400' : 'text-gray-500' }}">
                    {{ auth()->user()->getFormattedAvailable() }}
                </span>
            </div>

            <!-- Progress Bar -->
            <div class="w-20 bg-gray-200 dark:bg-slate-600 rounded-full h-1.5 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 ease-out
                           {{ auth()->user()->getStorageUsagePercentage() > 80 ? 'bg-gradient-to-r from-red-500 to-red-600' :
                              (auth()->user()->getStorageUsagePercentage() > 60 ? 'bg-gradient-to-r from-yellow-500 to-orange-500' :
                               'bg-gradient-to-r from-blue-500 to-cyan-500') }}"
                     style="width: {{ min(100, auth()->user()->getStorageUsagePercentage()) }}%">
                </div>
            </div>
        </div>

        <!-- Mobile View -->
        <div class="sm:hidden">
            <i class="fas fa-hdd {{ $darkMode ?? false ? 'text-slate-400' : 'text-gray-500' }} text-lg"></i>
        </div>
    </div>

    <!-- Detailed Storage Tooltip -->
    <div x-show="showDetails"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute top-full right-0 mt-2 w-72 {{ $darkMode ?? false ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-200' }}
                border rounded-lg shadow-lg z-50 p-4">

        <!-- Header -->
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold {{ $darkMode ?? false ? 'text-slate-100' : 'text-gray-900' }}">
                Storage Overview
            </h3>
            <a href="{{ route('family.storage.index') }}"
               class="text-xs text-blue-500 hover:text-blue-600 font-medium">
                View Details →
            </a>
        </div>

        <!-- Storage Stats -->
        <div class="space-y-3">
            <!-- Usage Bar -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs {{ $darkMode ?? false ? 'text-slate-300' : 'text-gray-600' }}">
                        Used: {{ auth()->user()->getFormattedUsed() }}
                    </span>
                    <span class="text-xs {{ $darkMode ?? false ? 'text-slate-400' : 'text-gray-500' }}">
                        {{ number_format(auth()->user()->getStorageUsagePercentage(), 1) }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-slate-600 rounded-full h-2 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 ease-out
                               {{ auth()->user()->getStorageUsagePercentage() > 80 ? 'bg-gradient-to-r from-red-500 to-red-600' :
                                  (auth()->user()->getStorageUsagePercentage() > 60 ? 'bg-gradient-to-r from-yellow-500 to-orange-500' :
                                   'bg-gradient-to-r from-blue-500 to-cyan-500') }}"
                         style="width: {{ min(100, auth()->user()->getStorageUsagePercentage()) }}%">
                    </div>
                </div>
            </div>

            <!-- Storage Breakdown -->
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                        <span class="{{ $darkMode ?? false ? 'text-slate-300' : 'text-gray-600' }}">Photos</span>
                    </div>
                    <span class="{{ $darkMode ?? false ? 'text-slate-400' : 'text-gray-500' }}">65%</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        <span class="{{ $darkMode ?? false ? 'text-slate-300' : 'text-gray-600' }}">Files</span>
                    </div>
                    <span class="{{ $darkMode ?? false ? 'text-slate-400' : 'text-gray-500' }}">25%</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 rounded-full bg-purple-500"></div>
                        <span class="{{ $darkMode ?? false ? 'text-slate-300' : 'text-gray-600' }}">Other</span>
                    </div>
                    <span class="{{ $darkMode ?? false ? 'text-slate-400' : 'text-gray-500' }}">10%</span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="pt-2 border-t {{ $darkMode ?? false ? 'border-slate-700' : 'border-gray-200' }}">
                <div class="flex space-x-2">
                    <button class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md
                                   bg-blue-500 text-white hover:bg-blue-600 transition-colors">
                        Upload
                    </button>
                    <button class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md
                                   {{ $darkMode ?? false ? 'bg-slate-700 text-slate-200 hover:bg-slate-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}
                                   transition-colors">
                        Cleanup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
