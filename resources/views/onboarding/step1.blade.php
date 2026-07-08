<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Revisor — Onboarding</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col items-center justify-center py-12 px-4 relative" style="background-color: #f1f5f9; background-image: radial-gradient(circle at 1px 1px, rgba(59,130,246,0.06) 1px, transparent 0); background-size: 24px 24px;">

    {{-- Decorative blobs --}}
    <div class="absolute top-0 left-0 w-72 h-72 bg-blue-100/30 rounded-full blur-3xl pointer-events-none -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-100/20 rounded-full blur-3xl pointer-events-none translate-x-1/3 translate-y-1/3"></div>

    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg w-full max-w-md px-8 py-10 animate-fade-in-up relative">

        {{-- Progress --}}
        <div class="flex items-center gap-2 mb-8">
            <div class="flex-1 h-1.5 bg-gradient-to-r from-blue-600 to-blue-500 rounded-full shadow-sm"></div>
            <div class="flex-1 h-1.5 bg-gray-200 rounded-full"></div>
        </div>

        {{-- Logo --}}
        <div class="flex flex-col items-center mb-6 animate-fade-in" style="animation-delay: 100ms">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-2.5 mb-3 animate-logo-pulse shadow-md">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Welcome to Revisor</h1>
            <p class="text-xs text-gray-400 mt-0.5 italic">Built by students for students</p>
            <p class="text-sm text-gray-500 mt-2 text-center">Let's set up your account. Where do you study?</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('onboarding.store1') }}" class="animate-fade-in-up" style="animation-delay: 200ms">
            @csrf

            <div class="mb-5">
                <label for="university_id" class="block text-sm font-medium text-gray-700 mb-1.5">University</label>
                <select name="university_id" id="university_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Select your university</option>
                    @foreach($universities as $uni)
                        <option value="{{ $uni->id }}" {{ old('university_id') == $uni->id ? 'selected' : '' }}>
                            {{ $uni->name }}
                        </option>
                    @endforeach
                </select>
                @error('university_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 mb-6">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                <span class="text-xs text-gray-400 uppercase tracking-wide">or</span>
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2.5 rounded-lg transition-all text-sm shadow-sm hover:shadow-md mb-3">
                Continue
            </button>

            <a href="{{ route('onboarding.step2') }}"
                class="block text-center text-sm text-gray-400 hover:text-blue-600 transition">
                Skip for now
            </a>
        </form>
    </div>

    <x-coming-soon-modal />
</body>
</html>
