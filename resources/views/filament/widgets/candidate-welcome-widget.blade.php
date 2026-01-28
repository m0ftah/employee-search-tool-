<x-filament-widgets::widget>
    <div class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800"
         style="background: linear-gradient(135deg, rgba(20, 184, 166, 0.05) 0%, rgba(245, 158, 11, 0.05) 100%); overflow: hidden; position: relative;">
        
        <!-- Decorative elements -->
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(20, 184, 166, 0.1) 0%, transparent 70%); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -20px; left: -20px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, transparent 70%); border-radius: 50%;"></div>

        <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">
            <div class="p-4 bg-teal-50 dark:bg-teal-900/30 rounded-full text-teal-600 dark:text-teal-400">
                <span class="text-4xl">👋</span>
            </div>
            
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white mb-2">
                    {{ app()->getLocale() == 'ar' ? 'أهلاً بك في منصتنا' : 'Welcome to our platform' }}, {{ auth()->user()->name }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed">
                    {{ app()->getLocale() == 'ar' ? 'نتمنى لك رحلة ناجحة في العثور على فرصتك التالية' : 'We wish you a successful journey in finding your next opportunity' }}
                </p>
            </div>

            <div class="hidden lg:block opacity-20 dark:opacity-30">
                <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="h-20 w-auto grayscale">
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
