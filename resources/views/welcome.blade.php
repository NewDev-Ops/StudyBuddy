<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisor Master Your Revision</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center py-12 px-4">

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-md w-full max-w-sm px-8 py-10 text-center animate-fade-in-up">

        <!-- Logo -->
        <div class="flex flex-col items-center mb-6 animate-fade-in" style="animation-delay: 150ms">
            <div class="bg-blue-600 rounded-xl p-2.5 mb-3 animate-logo-pulse">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Revisor</h1>
            <p class="text-sm text-gray-500 mt-1">Take control of your academic progress</p>
        </div>

        <!-- Features -->
        <div class="text-left space-y-3 mb-7">
            <div class="flex items-start gap-3 animate-fade-in-up" style="animation-delay: 300ms">
                <div class="bg-blue-50 rounded-lg p-1.5 mt-0.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Track Revision Sessions</p>
                    <p class="text-xs text-gray-500">Log study time per subject and never lose track again</p>
                </div>
            </div>
            <div class="flex items-start gap-3 animate-fade-in-up" style="animation-delay: 420ms">
                <div class="bg-blue-50 rounded-lg p-1.5 mt-0.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Monitor Your Marks</p>
                    <p class="text-xs text-gray-500">Record assessments and see your performance over time</p>
                </div>
            </div>
            <div class="flex items-start gap-3 animate-fade-in-up" style="animation-delay: 540ms">
                <div class="bg-blue-50 rounded-lg p-1.5 mt-0.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Connect with Peers</p>
                    <p class="text-xs text-gray-500">Find high-performing students in your weakest subjects</p>
                </div>
            </div>
        </div>

        <!-- CTA Button -->
        <a href="{{ route('login') }}"
            class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm animate-fade-in-up"
            style="animation-delay: 660ms">
            Get Started
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        @auth
        <a href="{{ route('dashboard') }}"
            class="w-full flex items-center justify-center mt-3 text-sm text-blue-600 hover:underline animate-fade-in"
            style="animation-delay: 780ms">
            Go to my Dashboard →
        </a>
        @endauth

    </div>

    <!-- Footer -->
    <p class="text-xs text-gray-400 mt-6 animate-fade-in" style="animation-delay: 900ms">Helping students master their revision.</p>
    <div class="flex gap-4 mt-2 text-xs text-gray-400 animate-fade-in" style="animation-delay: 1000ms">
        <span class="uppercase tracking-wide cursor-not-allowed opacity-50">Support</span>
        <span class="uppercase tracking-wide cursor-not-allowed opacity-50">Status</span>
        <span class="uppercase tracking-wide cursor-not-allowed opacity-50">Academic Integrity</span>
    </div>

</body>
</html>