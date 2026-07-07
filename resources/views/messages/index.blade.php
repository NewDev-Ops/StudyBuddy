@php
    $unreadCount = \App\Models\Message::where('receiver_id', Auth::id())->whereNull('read_at')->count();
    $pendingRequests = \App\Models\ConnectRequest::with('sender')
        ->where('receiver_id', Auth::id())
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Messages</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Conversations</h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        @isset($conversations)
                            {{ $conversations->count() }} conversation{{ $conversations->count() !== 1 ? 's' : '' }}
                        @endisset
                    </p>
                </div>
                @if($unreadCount > 0)
                    <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-2.5 py-1 rounded-full">{{ $unreadCount }} unread</span>
                @endif
            </div>

            {{-- Pending Connection Requests --}}
            @if($pendingRequests->isNotEmpty())
                <div class="mb-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Pending Connection Requests</h4>
                    <div class="space-y-2">
                        @foreach($pendingRequests as $req)
                            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-xs font-bold text-white shadow-sm shrink-0">
                                        {{ collect(preg_split('/\s+/', $req->sender->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('') }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $req->sender->name }}</p>
                                        <p class="text-xs text-gray-500">wants to connect with you</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <button type="button" onclick="acceptRequest({{ $req->id }}, this)"
                                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                        Accept
                                    </button>
                                    <button type="button" onclick="rejectRequest({{ $req->id }}, this)"
                                            class="px-3 py-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg transition">
                                        Reject
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @forelse($conversations as $conv)
                    <a href="{{ route('messages.show', $conv->peer) }}"
                       class="flex items-center gap-4 px-5 py-4 hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-transparent border-b border-gray-100 last:border-0 transition-all duration-150">
                        <div class="relative">
                            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-sm font-bold text-white shadow-sm shrink-0">
                                {{ collect(preg_split('/\s+/', $conv->peer->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('') }}
                            </div>
                            @if($conv->unread)
                                <span class="absolute -top-0.5 -right-0.5 w-3.5 h-3.5 bg-blue-500 border-2 border-white rounded-full"></span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold {{ $conv->unread ? 'text-gray-900' : 'text-gray-700' }}">{{ $conv->peer->name }}</span>
                                @if($conv->unread)
                                    <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] rounded-full bg-gradient-to-r from-blue-600 to-blue-500 text-white text-[10px] font-bold px-1 shadow-sm">{{ $conv->unread > 99 ? '99+' : $conv->unread }}</span>
                                @endif
                                <span class="ml-auto text-[11px] text-gray-400">{{ $conv->last_time->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs mt-0.5 truncate {{ $conv->unread ? 'font-semibold text-gray-700' : 'text-gray-500' }}">
                                {{ $conv->last_message }}
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-400 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @empty
                    <div class="text-center py-20 px-6 relative overflow-hidden">
                        <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(circle at 1px 1px, #3b82f6 1px, transparent 0); background-size: 20px 20px;"></div>
                        <div class="relative">
                            <svg class="w-16 h-16 text-blue-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-gray-500 text-sm font-medium">No conversations yet</p>
                            <p class="text-gray-400 text-xs mt-1.5 max-w-xs mx-auto leading-relaxed">Connect with peers from the dashboard to start chatting. Your messages will appear here.</p>
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 mt-5 text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const BASE_URL = '{{ url("") }}';

    function acceptRequest(id, btn) {
        btn.disabled = true;
        fetch(BASE_URL + '/chat-requests/' + id + '/accept', {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const card = btn.closest('.bg-amber-50');
                card.innerHTML = '<p class="text-sm text-emerald-700 font-medium py-1">Request accepted! You can now chat.</p>';
                setTimeout(() => {
                    card.remove();
                    if (document.querySelectorAll('.bg-amber-50').length === 0) {
                        document.querySelector('.mb-6')?.remove();
                    }
                }, 2000);
            }
        })
        .catch(() => { btn.disabled = false; });
    }

    function rejectRequest(id, btn) {
        btn.disabled = true;
        fetch(BASE_URL + '/chat-requests/' + id + '/reject', {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const card = btn.closest('.bg-amber-50');
                card.innerHTML = '<p class="text-sm text-gray-500 font-medium py-1">Request rejected.</p>';
                setTimeout(() => {
                    card.remove();
                    if (document.querySelectorAll('.bg-amber-50').length === 0) {
                        document.querySelector('.mb-6')?.remove();
                    }
                }, 2000);
            }
        })
        .catch(() => { btn.disabled = false; });
    }
</script>
