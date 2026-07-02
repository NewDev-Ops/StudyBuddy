<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $year }} Study Wrapped
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('study-wrapped', ['year' => $year - 1]) }}"
                    class="text-sm text-gray-500 hover:text-gray-700 transition">&larr; {{ $year - 1 }}</a>
                <span class="text-sm text-gray-300">|</span>
                <a href="{{ route('study-wrapped', ['year' => $year + 1]) }}"
                    class="text-sm text-gray-500 hover:text-gray-700 transition">{{ $year + 1 }} &rarr;</a>
                <span class="text-sm text-gray-300">|</span>
                <a href="{{ route('study-wrapped', ['year' => $year, 'regenerate' => 1]) }}"
                    class="text-sm text-gray-500 hover:text-gray-700 transition">Regenerate</a>
                <span class="text-sm text-gray-300">|</span>
                <a href="{{ route('report.student') }}"
                    class="text-sm text-blue-600 hover:text-blue-800 font-semibold transition">Download Report</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- 2x2 Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Most Studied --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 animate-fade-in-up"
                     style="animation-delay: 100ms">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Most Studied</p>
                            @if($wrapped->most_studied_subject)
                            <p class="text-lg font-bold text-gray-900">{{ $wrapped->most_studied_subject }}</p>
                            @else
                            <p class="text-sm text-gray-400">Not enough data yet</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Most Neglected --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 animate-fade-in-up"
                     style="animation-delay: 200ms">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Most Neglected</p>
                            @if($wrapped->most_neglected_subject)
                            <p class="text-lg font-bold text-gray-900">{{ $wrapped->most_neglected_subject }}</p>
                            @else
                            <p class="text-sm text-gray-400">Not enough data yet</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Highest Performing --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 animate-fade-in-up"
                     style="animation-delay: 300ms">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Highest Performing</p>
                            @if($wrapped->highest_performing_subject)
                            <p class="text-lg font-bold text-gray-900">{{ $wrapped->highest_performing_subject }}</p>
                            @else
                            <p class="text-sm text-gray-400">No marks recorded yet</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Total Hours --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 animate-fade-in-up"
                     style="animation-delay: 400ms">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Revision Hours</p>
                            <p class="text-lg font-bold text-gray-900">
                                {{ $wrapped->total_hours > 0 ? number_format($wrapped->total_hours, 1) . ' hrs' : 'No sessions logged yet' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-center mt-8 animate-fade-in" style="animation-delay: 500ms">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
