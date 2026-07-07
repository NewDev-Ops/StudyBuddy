<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Profile') }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">Manage your account settings</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white/95 backdrop-blur-sm shadow-sm border border-gray-200 sm:rounded-lg relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-blue-400 to-blue-500"></div>
                <div class="p-4 sm:p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-sm shadow-sm border border-gray-200 sm:rounded-lg relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 via-emerald-400 to-emerald-500"></div>
                <div class="p-4 sm:p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.update-university-form')
                    </div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-sm shadow-sm border border-gray-200 sm:rounded-lg relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 via-purple-400 to-purple-500"></div>
                <div class="p-4 sm:p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.peer-network-toggle')
                    </div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-sm shadow-sm border border-gray-200 sm:rounded-lg relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-500 via-red-400 to-red-500"></div>
                <div class="p-4 sm:p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
