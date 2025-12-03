<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>

    <title>Candidate Registration - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 min-h-screen">
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
                    Join Our Platform
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    Create your candidate profile and start applying for jobs
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
                                    <h3 class="text-sm font-semibold text-red-800 dark:text-red-200 mb-2">Please fix the following errors:</h3>
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
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Account Information</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Create your login credentials</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Enter your full name"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                                <div class="space-y-2">
                                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="your.email@example.com"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                                <div class="space-y-2">
                                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="password" name="password" required placeholder="Create a strong password"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                                <div class="space-y-2">
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Confirm Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password"
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
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Profile Information</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tell us about yourself</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Phone Number
                                    </label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 123-4567"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                                <div class="space-y-2">
                                    <label for="location" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Location
                                    </label>
                                    <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="City, Country"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                                <div class="space-y-2">
                                    <label for="education_level" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Education Level
                                    </label>
                                    <select id="education_level" name="education_level"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500 cursor-pointer">
                                        <option value="">Select Education Level</option>
                                        <option value="high_school" {{ old('education_level') == 'high_school' ? 'selected' : '' }}>High School</option>
                                        <option value="diploma" {{ old('education_level') == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                        <option value="bachelor" {{ old('education_level') == 'bachelor' ? 'selected' : '' }}>Bachelor</option>
                                        <option value="master" {{ old('education_level') == 'master' ? 'selected' : '' }}>Master</option>
                                        <option value="phd" {{ old('education_level') == 'phd' ? 'selected' : '' }}>PhD</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label for="years_of_experience" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Years of Experience
                                    </label>
                                    <input type="number" id="years_of_experience" name="years_of_experience" value="{{ old('years_of_experience') }}" min="0" placeholder="0"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label for="skills" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Skills
                                    </label>
                                    <input type="text" id="skills" name="skills" value="{{ old('skills') }}" placeholder="e.g., PHP, Laravel, JavaScript, React"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300 dark:hover:border-slate-500">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Separate multiple skills with commas
                                    </p>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label for="resume" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Resume (PDF, DOC, DOCX)
                                    </label>
                                    <div class="relative group">
                                        <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx"
                                            class="w-full px-4 py-12 border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 cursor-pointer hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/10">
                                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                            <svg class="w-10 h-10 text-gray-400 dark:text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-600 dark:text-slate-300">Click to upload or drag and drop</span>
                                            <span class="text-xs text-gray-400 dark:text-slate-500 mt-1">PDF, DOC, or DOCX</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Maximum file size: 10MB</p>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label for="certifications" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Certifications
                                    </label>
                                    <textarea id="certifications" name="certifications" rows="3" placeholder="List your professional certifications..."
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none shadow-sm hover:border-gray-300 dark:hover:border-slate-500">{{ old('certifications') }}</textarea>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label for="bio" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Bio
                                    </label>
                                    <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself..."
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none shadow-sm hover:border-gray-300 dark:hover:border-slate-500">{{ old('bio') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="pt-8 border-t-2 border-gray-200 dark:border-slate-700">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <a href="/" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex items-center group font-medium">
                                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Back to Home
                                </a>
                                <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 hover:from-blue-700 hover:via-blue-600 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center space-x-2 text-base">
                                    <span>Create Account</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                By registering, you agree to our Terms of Service
                                and Privacy Policy
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-gray-600 dark:text-gray-300">
                    Already have an account? 
                    <a href="/admin/login" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline transition-colors">Sign in here</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

