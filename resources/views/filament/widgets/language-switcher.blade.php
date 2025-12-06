<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            {{ __('common.language') }}
        </x-slot>
        
        <x-slot name="description">
            {{ __('common.select_language') }}
        </x-slot>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('lang.switch', ['locale' => 'en']) }}" 
               class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all duration-200 {{ app()->getLocale() === 'en' ? 'bg-primary-600 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.724 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L12 11.236 11.618 14z" clip-rule="evenodd"/>
                </svg>
                <span class="font-semibold">English</span>
                @if(app()->getLocale() === 'en')
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </a>
            
            <a href="{{ route('lang.switch', ['locale' => 'ar']) }}" 
               class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all duration-200 {{ app()->getLocale() === 'ar' ? 'bg-primary-600 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm8 8v2h1v-2h-1zm-2-2H7v4h6v-4zm0-4H7v4h6V7zm-7 4v2H4v-2h2zm8-4v2h-1V7h1zM9 9H7v2h2V9zm6 0h-2v2h2V9z" clip-rule="evenodd"/>
                </svg>
                <span class="font-semibold">العربية</span>
                @if(app()->getLocale() === 'ar')
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

