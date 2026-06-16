<div x-data="comingSoon"
     x-show="open" x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4"
     x-transition.opacity.duration.200ms>
    <div class="absolute inset-0 bg-black/40" @click="open = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-8 text-center"
         @click.outside="open = false"
         x-show="open" x-transition:enter="animate-fade-in-up">
        <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Coming Soon</h3>
        <p class="text-sm text-gray-500 mb-6">This feature is not available yet. Stay tuned for updates!</p>
        <button @click="open = false"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
            Got it
        </button>
    </div>
</div>
