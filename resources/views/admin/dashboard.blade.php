<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisor Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="bg-blue-600 rounded-lg p-1.5">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <span class="font-bold text-gray-900">Revisor</span>
            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-medium ml-1">Admin</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-700">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-5xl mx-auto px-6 py-10">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
        <p class="text-gray-500 text-sm mb-8">Manage universities, resources and the peer network.</p>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Students</p>
                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::where('role', 'student')->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Universities</p>
                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\University::count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Resources</p>
                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Resource::count() }}</p>
            </div>
        </div>

        <!-- Management cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users') }}"
                class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition border border-gray-100 group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <p class="text-xs text-gray-400">Promote or demote admin users.</p>
            </a>

            <a href="{{ route('admin.universities') }}"
                class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition border border-gray-100 group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <p class="text-xs text-gray-400">Add, edit or remove universities.</p>
            </a>

            <a href="{{ route('admin.resources') }}"
                class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition border border-gray-100 group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition">Resources</h3>
                        <p class="text-xs text-gray-400">{{ \App\Models\Resource::count() }} total</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400">Add, edit or remove learning resources.</p>
            </a>

            <a href="{{ route('admin.peer-network') }}"
                class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition border border-gray-100 group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition">Peer Network</h3>
                        <p class="text-xs text-gray-400">{{ \App\Models\User::where('role', 'student')->where('is_opted_in', true)->count() }} opted in</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400">Moderate peer network visibility.</p>
            </a>
        </div>
    </div>
</body>
</html>