<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>

    <title>{{ __('app.candidate_registration') }} - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [dir="rtl"] {
            direction: rtl;
        }
        [dir="ltr"] {
            direction: ltr;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 min-h-screen">
    <!-- Language Switcher -->
    <div class="fixed top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} z-50">
        <div class="group relative">
            <div class="relative inline-flex items-center bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-slate-700 overflow-hidden backdrop-blur-sm hover:shadow-xl transition-shadow duration-300">
                <button onclick="switchLanguage('en')" 
                        class="px-4 py-2.5 text-sm font-semibold transition-all duration-300 {{ app()->getLocale() === 'en' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-inner' : 'text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700' }} flex items-center gap-2 min-w-[80px] justify-center relative z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.724 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L12 11.236 11.618 14z" clip-rule="evenodd"/>
                    </svg>
                    <span>EN</span>
                </button>
                <div class="w-px h-8 bg-gray-200 dark:bg-slate-600"></div>
                <button onclick="switchLanguage('ar')" 
                        class="px-4 py-2.5 text-sm font-semibold transition-all duration-300 {{ app()->getLocale() === 'ar' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-inner' : 'text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-700' }} flex items-center gap-2 min-w-[80px] justify-center relative z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm8 8v2h1v-2h-1zm-2-2H7v4h6v-4zm0-4H7v4h6V7zm-7 4v2H4v-2h2zm8-4v2h-1V7h1zM9 9H7v2h2V9zm6 0h-2v2h2V9z" clip-rule="evenodd"/>
                    </svg>
                    <span>AR</span>
                </button>
            </div>
        </div>
    </div>
    
    <script>
        function switchLanguage(locale) {
            window.location.href = '{{ route('lang.switch', ['locale' => '__LOCALE__']) }}'.replace('__LOCALE__', locale);
        }
    </script>

    <div class="flex items-center justify-center min-h-screen p-4 sm:p-6 py-8">
        <div class="w-full max-w-4xl">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ __('app.join_our_platform') }}
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    {{ __('app.create_candidate_profile') }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-slate-700">
                <!-- Progress Indicator -->
                <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 h-1.5"></div>
                
                <div class="p-6 sm:p-8 lg:p-10">

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-r-lg shadow-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-red-800 dark:text-red-200 mb-2">{{ __('app.please_fix_errors') }}</h3>
                                    <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 rounded-r-lg shadow-sm">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('candidate.register') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- User Information Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3 pb-4 border-b-2 border-gray-200 dark:border-slate-700">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('app.account_information') }}</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.create_login_credentials') }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ __('app.full_name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="{{ __('app.full_name') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                                <div class="space-y-2">
                                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ __('app.email_address') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="your.email@example.com"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                                <div class="space-y-2">
                                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ __('common.password') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="password" name="password" required placeholder="{{ __('common.password') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                                <div class="space-y-2">
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ __('common.confirm_password') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="{{ __('common.confirm_password') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                            </div>
                        </div>

                        <!-- Candidate Profile Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3 pb-4 border-b-2 border-gray-200 dark:border-slate-700">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('app.profile_information') }}</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.tell_us_about_yourself') }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ __('app.phone_number') }}
                                    </label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="{{ __('app.phone_number') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                                <div class="space-y-2">
                                    <label for="location" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ __('common.location') }}
                                    </label>
                                    <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="{{ __('common.location') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                                <div class="space-y-2">
                                    <label for="education_level" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ __('app.education_level') }}
                                    </label>
                                    <select id="education_level" name="education_level"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500 cursor-pointer">
                                        <option value="">{{ __('app.select_education_level') }}</option>
                                        <option value="high_school" {{ old('education_level') == 'high_school' ? 'selected' : '' }}>{{ __('app.high_school') }}</option>
                                        <option value="diploma" {{ old('education_level') == 'diploma' ? 'selected' : '' }}>{{ __('app.diploma') }}</option>
                                        <option value="bachelor" {{ old('education_level') == 'bachelor' ? 'selected' : '' }}>{{ __('app.bachelor') }}</option>
                                        <option value="master" {{ old('education_level') == 'master' ? 'selected' : '' }}>{{ __('app.master') }}</option>
                                        <option value="phd" {{ old('education_level') == 'phd' ? 'selected' : '' }}>{{ __('app.phd') }}</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label for="years_of_experience" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ __('app.years_of_experience') }}
                                    </label>
                                    <input type="number" id="years_of_experience" name="years_of_experience" value="{{ old('years_of_experience') }}" min="0" placeholder="0"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label for="skills" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ __('app.skills') }}
                                    </label>
                                    <input type="text" id="skills" name="skills" value="{{ old('skills') }}" placeholder="{{ __('app.skills_placeholder') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('app.separate_skills_with_commas') }}
                                    </p>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label for="resume" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ __('app.resume_upload') }}
                                    </label>
                                    <div class="relative group">
                                        <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx"
                                            class="w-full px-4 py-12 border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 cursor-pointer hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/10">
                                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                            <svg class="w-10 h-10 text-gray-400 dark:text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-600 dark:text-slate-300">{{ __('app.click_to_upload') }}</span>
                                            <span class="text-xs text-gray-400 dark:text-slate-500 mt-1">PDF, DOC, or DOCX</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('app.maximum_file_size') }}</p>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label for="certifications" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ __('app.certifications') }}
                                    </label>
                                    <textarea id="certifications" name="certifications" rows="3" placeholder="{{ __('app.list_certifications') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none shadow-sm hover:border-gray-300 dark:hover:border-slate-500">{{ old('certifications') }}</textarea>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label for="bio" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ __('app.bio') }}
                                    </label>
                                    <textarea id="bio" name="bio" rows="4" placeholder="{{ __('app.tell_us_about_yourself_placeholder') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none shadow-sm hover:border-gray-300 dark:hover:border-slate-500">{{ old('bio') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="pt-8 border-t-2 border-gray-200 dark:border-slate-700">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <a href="/" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex items-center group font-medium">
                                    <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} group-hover:{{ app()->getLocale() === 'ar' ? 'translate-x-1' : '-translate-x-1' }} transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    {{ __('app.back_to_home') }}
                                </a>
                                <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 hover:from-blue-700 hover:via-blue-600 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center space-x-2 text-base">
                                    <span>{{ __('app.create_account') }}</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                {{ __('app.terms_and_privacy') }}
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-gray-600 dark:text-gray-300">
                    {{ __('app.already_have_account') }} 
                    <a href="/admin/login" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline transition-colors">{{ __('app.sign_in_here') }}</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

