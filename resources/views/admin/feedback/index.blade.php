<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisor — Feedback Inbox</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

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
            <span class="hidden sm:inline text-xs text-gray-400 ml-2">Built by students for students</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>
            <a href="{{ route('admin.feedback') }}" class="text-sm text-gray-900 font-semibold relative">
                Feedback
                @php $unreadCount = \App\Models\Feedback::where('is_read', false)->count(); @endphp
                @if($unreadCount > 0)
                    <span class="absolute -top-2 -right-4 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-tight">{{ $unreadCount }}</span>
                @endif
            </a>
            <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-700">Logout</button>
            </form>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-6 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to Dashboard</a>
                <h1 class="text-2xl font-bold text-gray-900 mt-1">Feedback Inbox</h1>
                <p class="text-sm text-gray-500 mt-1">All student feedback, newest first.</p>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm mb-6">
            {{ session('success') }}
        </div>
        @endif

        <div class="space-y-4">
            @forelse($feedback as $item)
            <div class="bg-white rounded-xl shadow-sm border {{ $item->is_read ? 'border-gray-100' : 'border-blue-200 bg-blue-50/30' }} p-5 transition hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            @if($item->category)
                            <span class="inline-block px-2 py-0.5 text-[10px] font-semibold bg-gray-100 text-gray-600 rounded-full">{{ $item->category }}</span>
                            @endif
                            @if(!$item->is_read)
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500 shrink-0" title="Unread"></span>
                            @endif
                            <span class="text-xs text-gray-400 ml-auto">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $item->message }}</p>
                    </div>
                    @if(!$item->is_read)
                    <form method="POST" action="{{ route('admin.feedback.mark-read', $item) }}" class="shrink-0">
                        @csrf
                        <button type="submit"
                            class="text-xs text-blue-600 hover:text-blue-800 font-semibold whitespace-nowrap">
                            Mark Read
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm text-gray-400">No feedback yet.</p>
            </div>
            @endforelse
        </div>
    </div>

</body>
</html>
