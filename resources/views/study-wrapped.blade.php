<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $year }} Study Wrapped
            </h2>
            @php
                $mostStudiedMinutes = isset($perSubjectMinutes[$wrapped->most_studied_subject]) ? (int) $perSubjectMinutes[$wrapped->most_studied_subject]->total_minutes : 0;
                $mostNeglectedMinutes = $wrapped->most_neglected_subject && isset($perSubjectMinutes[$wrapped->most_neglected_subject]) ? (int) $perSubjectMinutes[$wrapped->most_neglected_subject]->total_minutes : 0;
            @endphp
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

            {{-- Year badge --}}
            <div class="text-center mb-7 animate-fade-in">
                <span class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-bold px-5 py-1.5 rounded-full shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $year }} Recap
                </span>
            </div>

            {{-- 2x2 Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Most Studied --}}
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-sm border border-blue-100/50 p-6 animate-fade-in-up relative"
                     style="animation-delay: 100ms" x-data="{ showTip: false }">
                    <div class="absolute inset-0 overflow-hidden rounded-2xl pointer-events-none">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-blue-50 to-transparent rounded-bl-full"></div>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Most Studied</p>
                            @if($wrapped->most_studied_subject)
                            <p class="text-lg font-bold text-gray-900">{{ $wrapped->most_studied_subject }}</p>
                            @else
                            <p class="text-sm text-gray-400">Not enough data yet</p>
                            @endif
                        </div>
                        <div class="relative z-20">
                            <button @mouseenter="showTip = true" @mouseleave="showTip = false" @touchstart.prevent="showTip = !showTip" class="text-gray-300 hover:text-blue-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                            <div x-show="showTip" x-cloak @click.away="showTip = false" class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 p-3.5 text-[11px] text-gray-600 leading-relaxed z-30">
                                @if($wrapped->most_studied_subject)
                                <p>You spent <strong class="text-gray-900">{{ $mostStudiedMinutes }} min</strong> on <strong class="text-gray-900">{{ $wrapped->most_studied_subject }}</strong> this year — more than any other subject.</p>
                                @if($sessionCount > 0)
                                <p class="mt-1.5">Across <strong class="text-gray-900">{{ $sessionCount }} session{{ $sessionCount !== 1 ? 's' : '' }}</strong>, averaging <strong class="text-gray-900">{{ $avgSessionLength }} min</strong> per session.</p>
                                @endif
                                @else
                                <p>Log revision sessions to see your most-studied subject.</p>
                                @endif
                                <div class="absolute -top-1 right-4 w-2 h-2 bg-white border-l border-t border-gray-100 rotate-45"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Most Neglected --}}
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-sm border border-amber-100/50 p-6 animate-fade-in-up relative"
                     style="animation-delay: 200ms" x-data="{ showTip: false }">
                    <div class="absolute inset-0 overflow-hidden rounded-2xl pointer-events-none">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-amber-50 to-transparent rounded-bl-full"></div>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Most Neglected</p>
                            @if($wrapped->most_neglected_subject)
                            <p class="text-lg font-bold text-gray-900">{{ $wrapped->most_neglected_subject }}</p>
                            @else
                            <p class="text-sm text-gray-400">Not enough data yet</p>
                            @endif
                        </div>
                        <div class="relative z-20">
                            <button @mouseenter="showTip = true" @mouseleave="showTip = false" @touchstart.prevent="showTip = !showTip" class="text-gray-300 hover:text-amber-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                            <div x-show="showTip" x-cloak @click.away="showTip = false" class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 p-3.5 text-[11px] text-gray-600 leading-relaxed z-30">
                                @if($wrapped->most_neglected_subject)
                                <p><strong class="text-gray-900">{{ $wrapped->most_neglected_subject }}</strong> received the least study time with only <strong class="text-gray-900">{{ $mostNeglectedMinutes }} min</strong> logged all year.</p>
                                @if($mostStudiedMinutes > 0)
                                <p class="mt-1.5">That's <strong class="text-gray-900">{{ $mostStudiedMinutes > 0 ? round((1 - $mostNeglectedMinutes / max($mostStudiedMinutes, 1)) * 100) : 0 }}%</strong> less than your most-studied subject.</p>
                                @endif
                                @else
                                <p>Add at least two subjects and log sessions to see which one needs more attention.</p>
                                @endif
                                <div class="absolute -top-1 right-4 w-2 h-2 bg-white border-l border-t border-gray-100 rotate-45"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Highest Performing --}}
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-sm border border-emerald-100/50 p-6 animate-fade-in-up relative"
                     style="animation-delay: 300ms" x-data="{ showTip: false }">
                    <div class="absolute inset-0 overflow-hidden rounded-2xl pointer-events-none">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-emerald-50 to-transparent rounded-bl-full"></div>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Highest Performing</p>
                            @if($wrapped->highest_performing_subject)
                            <p class="text-lg font-bold text-gray-900">{{ $wrapped->highest_performing_subject }}</p>
                            @else
                            <p class="text-sm text-gray-400">No marks recorded yet</p>
                            @endif
                        </div>
                        <div class="relative z-20">
                            <button @mouseenter="showTip = true" @mouseleave="showTip = false" @touchstart.prevent="showTip = !showTip" class="text-gray-300 hover:text-emerald-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                            <div x-show="showTip" x-cloak @click.away="showTip = false" class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 p-3.5 text-[11px] text-gray-600 leading-relaxed z-30">
                                @if($wrapped->highest_performing_subject)
                                <p>Your highest average score this year, calculated from all exams, tests, and assignments.</p>
                                @if($overallPerformance)
                                <p class="mt-1.5">Your overall average across all subjects is <strong class="text-gray-900">{{ number_format($overallPerformance, 1) }}%</strong>.</p>
                                @endif
                                @else
                                <p>Record marks to find out which subject you're performing best in.</p>
                                @endif
                                <div class="absolute -top-1 right-4 w-2 h-2 bg-white border-l border-t border-gray-100 rotate-45"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Hours --}}
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-sm border border-purple-100/50 p-6 animate-fade-in-up relative"
                     style="animation-delay: 400ms" x-data="{ showTip: false }">
                    <div class="absolute inset-0 overflow-hidden rounded-2xl pointer-events-none">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-purple-50 to-transparent rounded-bl-full"></div>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Revision Hours</p>
                            <p class="text-lg font-bold text-gray-900">
                                {{ $wrapped->total_hours > 0 ? number_format($wrapped->total_hours, 1) . ' hrs' : 'No sessions logged yet' }}
                            </p>
                        </div>
                        <div class="relative z-20">
                            <button @mouseenter="showTip = true" @mouseleave="showTip = false" @touchstart.prevent="showTip = !showTip" class="text-gray-300 hover:text-purple-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                            <div x-show="showTip" x-cloak @click.away="showTip = false" class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 p-3.5 text-[11px] text-gray-600 leading-relaxed z-30">
                                @if($wrapped->total_hours > 0)
                                <p>You logged <strong class="text-gray-900">{{ number_format($wrapped->total_hours, 1) }} hours</strong> across <strong class="text-gray-900">{{ $sessionCount }} session{{ $sessionCount !== 1 ? 's' : '' }}</strong> this year.</p>
                                <p class="mt-1.5">Average session length: <strong class="text-gray-900">{{ $avgSessionLength }} min</strong>.</p>
                                @else
                                <p>Log your first revision session to start tracking your study hours.</p>
                                @endif
                                <div class="absolute -top-1 right-4 w-2 h-2 bg-white border-l border-t border-gray-100 rotate-45"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-center mt-8 animate-fade-in" style="animation-delay: 500ms">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition group">
                    <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
