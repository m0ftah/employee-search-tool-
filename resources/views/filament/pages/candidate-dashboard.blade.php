<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Welcome, {{ auth()->user()->name }}
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Application Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Applications</h3>
                <p class="text-3xl font-bold text-primary-600">5</p>
                <p class="text-sm text-gray-600">Total applications submitted</p>
            </div>

            <!-- Profile Completeness -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Profile Strength</h3>
                <p class="text-3xl font-bold text-primary-600">75%</p>
                <p class="text-sm text-gray-600">Complete your profile</p>
            </div>

            <!-- New Jobs -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">New Jobs</h3>
                <p class="text-3xl font-bold text-primary-600">12</p>
                <p class="text-sm text-gray-600">Posted this week</p>
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">Recent Applications</h3>
            <!-- Add applications table here -->
        </div>
    </x-filament::section>
</x-filament-panels::page>
