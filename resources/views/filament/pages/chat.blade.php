<x-filament-panels::page>
    <div class="w-full h-full min-h-[calc(100vh-8rem)] flex rounded-lg overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900" wirechat>
        <!-- Single Unified WireChat Interface -->
        <div class="w-full h-full flex">
            <!-- Chat List Sidebar -->
            <div class="relative w-full h-full border-r border-gray-200 dark:border-gray-700 md:w-[360px] lg:w-[400px] xl:w-[420px] shrink-0 overflow-hidden flex flex-col bg-gray-50 dark:bg-gray-800">
                <livewire:wirechat.chats/>
            </div>
            
            <!-- Main Chat Area - Shows conversation when selected, welcome message when empty -->
            <main class="hidden md:flex h-full min-h-full flex-1 bg-gradient-to-br from-teal-50 via-white to-orange-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 relative overflow-y-auto" style="contain:content">
                <!-- WireChat conversation component - shows messages when chat is selected -->
                <livewire:wirechat.conversation/>
                
                <!-- Welcome message (shown when no conversation is selected) -->
                <div class="m-auto text-center justify-center flex gap-4 flex-col items-center px-6 py-12">
                    <!-- Icon -->
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-teal-400 to-orange-400 dark:from-teal-600 dark:to-orange-600 flex items-center justify-center mb-4 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    
                    <!-- Welcome Message -->
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-2">
                        {{ __('app.welcome_to_chat') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 max-w-md text-center leading-relaxed">
                        {{ __('app.select_conversation_to_start') }}
                    </p>
                    
                    <!-- Helper Text -->
                    <div class="mt-6 p-4 rounded-lg bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm border border-gray-200 dark:border-gray-700 max-w-md">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ __('app.tip') }}:</span>
                            {{ __('app.use_search_to_find_contacts') }}
                        </p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-filament-panels::page>
