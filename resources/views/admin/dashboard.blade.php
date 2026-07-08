<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisor Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen" style="background-color: #f1f5f9; background-image: radial-gradient(circle at 1px 1px, rgba(59,130,246,0.06) 1px, transparent 0); background-size: 24px 24px;">

    <!-- Navbar -->
    <nav class="bg-white/90 backdrop-blur-md border-b border-gray-200/60 sticky top-0 z-40 px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 group">
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg p-1.5 shadow-sm group-hover:shadow-md transition-shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Revisor</span>
            </a>
            <span class="text-[11px] bg-gradient-to-r from-blue-600 to-blue-700 text-white px-2 py-0.5 rounded-full font-semibold ml-1 shadow-sm">Admin</span>
            <span class="hidden sm:inline text-xs text-gray-400 ml-2 italic">Built by students for students</span>
        </div>
        <div class="flex items-center gap-5">
            <a href="{{ route('admin.feedback') }}" class="text-sm text-gray-500 hover:text-gray-900 transition-colors relative group">
                Feedback
                <span class="absolute -bottom-1 left-0 right-0 h-0.5 bg-blue-600 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                @php $unreadCount = \App\Models\Feedback::where('is_read', false)->count(); @endphp
                @if($unreadCount > 0)
                    <span class="absolute -top-2 -right-4 bg-gradient-to-r from-red-500 to-red-400 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-tight shadow-sm">{{ $unreadCount }}</span>
                @endif
            </a>
            <div class="h-5 w-px bg-gray-200"></div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span class="font-medium text-gray-700">{{ auth()->user()->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-400 hover:text-red-500 transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-5xl mx-auto px-6 py-10">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-400 text-sm mt-0.5">Manage universities, resources and the peer network.</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-200 p-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-blue-50/50 to-transparent rounded-bl-full -z-0"></div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1 relative z-10">Total Students</p>
                <p class="text-2xl font-bold text-gray-900 relative z-10">{{ \App\Models\User::where('role', 'student')->count() }}</p>
            </div>
            <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-200 p-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-emerald-50/50 to-transparent rounded-bl-full -z-0"></div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1 relative z-10">Universities</p>
                <p class="text-2xl font-bold text-gray-900 relative z-10">{{ \App\Models\University::count() }}</p>
            </div>
            <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-200 p-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-purple-50/50 to-transparent rounded-bl-full -z-0"></div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1 relative z-10">Resources</p>
                <p class="text-2xl font-bold text-gray-900 relative z-10">{{ \App\Models\Resource::count() }}</p>
            </div>
        </div>

        <!-- Management cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users') }}"
                class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm p-5 hover:shadow-md transition-all border border-gray-200 group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-purple-50/50 to-transparent rounded-bl-full -z-0"></div>
                <div class="flex items-center gap-3 mb-3 relative z-10">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 group-hover:text-purple-600 transition">Users</h3>
                        <p class="text-xs text-gray-400">{{ \App\Models\User::count() }} total</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-purple-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400 relative z-10">Promote or demote admin users.</p>
            </a>

            <a href="{{ route('admin.universities') }}"
                class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm p-5 hover:shadow-md transition-all border border-gray-200 group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-blue-50/50 to-transparent rounded-bl-full -z-0"></div>
                <div class="flex items-center gap-3 mb-3 relative z-10">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition">Universities</h3>
                        <p class="text-xs text-gray-400">{{ \App\Models\University::count() }} total</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400 relative z-10">Add, edit or remove universities.</p>
            </a>

            <a href="{{ route('admin.resources') }}"
                class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm p-5 hover:shadow-md transition-all border border-gray-200 group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-emerald-50/50 to-transparent rounded-bl-full -z-0"></div>
                <div class="flex items-center gap-3 mb-3 relative z-10">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 group-hover:text-emerald-600 transition">Resources</h3>
                        <p class="text-xs text-gray-400">{{ \App\Models\Resource::count() }} total</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-emerald-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400 relative z-10">Add, edit or remove learning resources.</p>
            </a>

            <a href="{{ route('admin.peer-network') }}"
                class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm p-5 hover:shadow-md transition-all border border-gray-200 group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-amber-50/50 to-transparent rounded-bl-full -z-0"></div>
                <div class="flex items-center gap-3 mb-3 relative z-10">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 group-hover:text-amber-600 transition">Peer Network</h3>
                        <p class="text-xs text-gray-400">{{ \App\Models\User::where('role', 'student')->where('is_opted_in', true)->count() }} opted in</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-amber-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400 relative z-10">Moderate peer network visibility.</p>
            </a>
        </div>

        <h2 class="text-lg font-bold text-gray-900 mt-10 mb-4">Reports</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('admin.reports.overview') }}"
                class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm p-5 hover:shadow-md transition-all border border-gray-200 group text-center">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition text-sm">Platform Overview</h3>
                <p class="text-xs text-gray-400 mt-1">Users, activity, subjects, resources</p>
            </a>
            <a href="{{ route('admin.reports.students') }}"
                class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm p-5 hover:shadow-md transition-all border border-gray-200 group text-center">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 group-hover:text-purple-600 transition text-sm">Student Activity</h3>
                <p class="text-xs text-gray-400 mt-1">Per-student breakdown</p>
            </a>
            <a href="{{ route('admin.reports.feedback') }}"
                class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm p-5 hover:shadow-md transition-all border border-gray-200 group text-center">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 group-hover:text-emerald-600 transition text-sm">Feedback Report</h3>
                <p class="text-xs text-gray-400 mt-1">All feedback submissions</p>
            </a>
        </div>

        <div class="h-16 bg-gradient-to-t from-blue-50/50 to-transparent pointer-events-none mt-8 -mx-6"></div>
    </div>
</body>
</html>