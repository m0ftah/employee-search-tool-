<div class="flex items-center gap-2">
    <a href="{{ route('lang.switch', ['locale' => 'en']) }}" 
       class="flex items-center justify-center px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 {{ app()->getLocale() === 'en' ? 'bg-primary-600 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}"
       title="English">
        EN
    </a>
    
    <a href="{{ route('lang.switch', ['locale' => 'ar']) }}" 
       class="flex items-center justify-center px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 {{ app()->getLocale() === 'ar' ? 'bg-primary-600 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}"
       title="العربية">
        AR
    </a>
</div>
