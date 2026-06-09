<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisor — Admin Dashboard</title>
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

        <!-- Placeholder sections -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-gray-400 text-sm text-center">More admin features coming soon — manage universities, resources and peer network here.</p>
        </div>
    </div>

</body>
</html>