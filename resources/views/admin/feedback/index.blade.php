<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisor — Feedback Inbox</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen" style="background-color: #f1f5f9; background-image: radial-gradient(circle at 1px 1px, rgba(59,130,246,0.06) 1px, transparent 0); background-size: 24px 24px;">

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
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-900 transition-colors relative group">
                Dashboard
                <span class="absolute -bottom-1 left-0 right-0 h-0.5 bg-blue-600 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
            </a>
            <a href="{{ route('admin.feedback') }}" class="text-sm text-blue-600 font-semibold transition-colors relative group">
                Feedback
                <span class="absolute -bottom-1 left-0 right-0 h-0.5 bg-blue-600 rounded-full scale-x-100 transition-transform origin-left"></span>
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

    <div class="max-w-4xl mx-auto px-6 py-10">

        <div class="flex items-center gap-4 mb-8">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs text-gray-400 hover:text-gray-600 transition">&larr; Back to Dashboard</a>
                <h1 class="text-2xl font-bold text-gray-900 mt-0.5">Feedback Inbox</h1>
                <p class="text-sm text-gray-400 mt-0.5">All student feedback, newest first.</p>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-gradient-to-r from-emerald-50 to-emerald-100/50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm mb-6 flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="space-y-3">
            @forelse($feedback as $item)
            <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border {{ $item->is_read ? 'border-gray-100' : 'border-blue-300 bg-blue-50/40' }} p-5 transition-all hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1.5">
                            @if($item->category)
                            <span class="inline-block px-2 py-0.5 text-[10px] font-semibold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600 rounded-full">{{ $item->category }}</span>
                            @endif
                            @if(!$item->is_read)
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500 shrink-0 animate-pulse" title="Unread"></span>
                            @endif
                            <span class="text-xs text-gray-400 ml-auto">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $item->message }}</p>
                    </div>
                    @if(!$item->is_read)
                    <form method="POST" action="{{ route('admin.feedback.mark-read', $item) }}" class="shrink-0">
                        @csrf
                        <button type="submit"
                            class="text-xs text-blue-600 hover:text-blue-800 font-semibold whitespace-nowrap bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition">
                            Mark Read
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-400 font-medium">No feedback yet.</p>
                <p class="text-xs text-gray-400 mt-1">Student submissions will appear here.</p>
            </div>
            @endforelse
        </div>

        <div class="h-16 bg-gradient-to-t from-blue-50/50 to-transparent pointer-events-none mt-8 -mx-6"></div>
    </div>

</body>
</html>
