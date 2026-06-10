<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight animate-fade-in">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- ============================================================ --}}
                {{-- LEFT SIDEBAR — Study Next                                     --}}
                {{-- ============================================================ --}}
                <aside class="lg:col-span-3 space-y-5 animate-fade-in-up" style="animation-delay: 100ms">

                    {{-- Study Next Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <h3 class="text-sm font-bold text-gray-900">Study Next</h3>
                        </div>

                        {{-- Recommended Subject --}}
                        <div class="bg-blue-50 rounded-xl p-4 mb-5">
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mb-1">Recommended</p>
                            <p class="text-sm font-bold text-gray-900 leading-snug">Algorithms &amp; Complexity</p>
                            <p class="text-xs text-gray-500 mt-1">Focus on Dynamic Programming. Last reviewed 3 days ago.</p>
                            <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-700 mt-3 transition">
                                Start Session
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        {{-- Quick Resources --}}
                        <div class="mb-5">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Quick Resources</h4>
                            </div>
                            <div class="space-y-2">
                                <a href="#" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition group">
                                    <div class="bg-blue-100 rounded-lg p-1.5">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-700 group-hover:text-blue-600 transition">Lecture 12: Cache Mapping</span>
                                </a>
                                <a href="#" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition group">
                                    <div class="bg-blue-100 rounded-lg p-1.5">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-700 group-hover:text-blue-600 transition">Weekly Practice Quiz</span>
                                </a>
                            </div>
                        </div>

                        {{-- Peer Insights --}}
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Peer Insights</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700">SJ</div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Sarah Jenkins</p>
                                        <p class="text-xs text-gray-500">Oxford University</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700">MC</div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Marcus Chen</p>
                                        <p class="text-xs text-gray-500">Imperial College</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- ============================================================ --}}
                {{-- MAIN CONTENT — My Subjects                                     --}}
                {{-- ============================================================ --}}
                <main class="lg:col-span-6 space-y-4 animate-fade-in-up" style="animation-delay: 200ms">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-xl font-bold text-gray-900">My Subjects</h2>
                        <span class="text-sm text-gray-500">5 Active Courses</span>
                    </div>

                    {{-- Subject Cards --}}
                    @php
                        $subjects = [
                            ['name' => 'Computer Systems Architecture', 'last_revised' => '2 hours ago',   'average' => 82, 'color' => 'emerald'],
                            ['name' => 'Statistical Inference',         'last_revised' => 'Yesterday',       'average' => 74, 'color' => 'emerald'],
                            ['name' => 'Algorithms & Complexity',       'last_revised' => '3 days ago',      'average' => 68, 'color' => 'amber'],
                            ['name' => 'Software Engineering',         'last_revised' => '5 days ago',      'average' => 91, 'color' => 'emerald'],
                            ['name' => 'Machine Learning Basics',      'last_revised' => '1 week ago',      'average' => 59, 'color' => 'red'],
                        ];
                    @endphp

                    @foreach($subjects as $i => $subject)
                        <a href="#"
                           class="flex items-center justify-between bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md hover:border-blue-200 transition-all duration-200 group animate-fade-in-up"
                           style="animation-delay: {{ 300 + ($i * 100) }}ms">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition">{{ $subject['name'] }}</p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs text-gray-500">Last revised {{ $subject['last_revised'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Average</p>
                                    <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-xs font-bold
                                        {{ $subject['average'] >= 80 ? 'text-emerald-700 bg-emerald-50' : ($subject['average'] >= 70 ? 'text-emerald-700 bg-emerald-50' : ($subject['average'] >= 60 ? 'text-amber-600 bg-amber-50' : 'text-red-500 bg-red-50')) }}">
                                        {{ $subject['average'] }}%
                                    </span>
                                </div>
                                <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    @endforeach

                    {{-- View All --}}
                    <div class="text-center pt-4 animate-fade-in" style="animation-delay: 900ms">
                        <a href="#" class="text-sm font-medium text-gray-500 hover:text-blue-600 transition">
                            View All Academic History
                        </a>
                    </div>
                </main>

                {{-- ============================================================ --}}
                {{-- RIGHT SIDEBAR — Quick Actions                                  --}}
                {{-- ============================================================ --}}
                <aside class="lg:col-span-3 space-y-5 animate-fade-in-up" style="animation-delay: 300ms">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">Quick Actions</h4>
                        <div class="space-y-3">
                            <a href="#"
                               class="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Log Revision Session
                            </a>
                            <a href="#"
                               class="flex items-center justify-center gap-2 w-full border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold py-2.5 rounded-lg transition text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Record a Mark
                            </a>
                        </div>

                        {{-- Study Wrapped --}}
                        <div class="mt-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Study Wrapped</p>
                                    <p class="text-xs text-gray-500">2024</p>
                                </div>
                                <svg class="w-4 h-4 text-blue-600 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">View semester highlights</p>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="py-6 border-t border-gray-200 animate-fade-in" style="animation-delay: 1000ms">
        <p class="text-center text-xs text-gray-400">&copy; 2026 Revisor Academic Tracking. All rights reserved.</p>
    </div>
</x-app-layout>
