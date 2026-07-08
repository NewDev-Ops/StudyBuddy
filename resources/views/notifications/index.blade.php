@php
    $unreadCount = \App\Models\Message::where('receiver_id', Auth::id())->whereNull('read_at')->count();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notifications</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Pending Requests --}}
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pending Requests
                    @if($pendingRequests->isNotEmpty())
                        <span class="text-xs bg-amber-100 text-amber-700 font-semibold px-2 py-0.5 rounded-full">{{ $pendingRequests->count() }}</span>
                    @endif
                </h3>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    @forelse($pendingRequests as $req)
                        <div class="flex items-center justify-between gap-4 px-5 py-4 border-b border-gray-100 last:border-0" data-request-id="{{ $req->id }}" data-sender-id="{{ $req->sender_id }}">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-sm font-bold text-white shadow-sm shrink-0">
                                    {{ collect(preg_split('/\s+/', $req->sender->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('') }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $req->sender->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">wants to connect with you &middot; {{ $req->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" onclick="notifAccept({{ $req->id }}, this)"
                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                    Accept
                                </button>
                                <button type="button" onclick="notifReject({{ $req->id }}, this)"
                                        class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg transition">
                                    Reject
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 px-6">
                            <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-gray-400">No pending requests</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Accepted Notifications --}}
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Accepted
                </h3>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    @forelse($acceptedNotifications as $notif)
                        <a href="{{ route('messages.show', $notif->receiver) }}"
                           class="flex items-center gap-4 px-5 py-4 border-b border-gray-100 last:border-0 hover:bg-gradient-to-r hover:from-emerald-50/50 hover:to-transparent transition-all duration-150 group">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-sm font-bold text-white shadow-sm shrink-0">
                                {{ collect(preg_split('/\s+/', $notif->receiver->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('') }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $notif->receiver->name }}</p>
                                <p class="text-xs text-gray-500">Accepted your connection request &middot; {{ $notif->responded_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Accepted</span>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-400 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @empty
                        <div class="text-center py-10 px-6">
                            <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-gray-400">No accepted requests yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Rejected Notifications --}}
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Declined
                </h3>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    @forelse($rejectedNotifications as $notif)
                        <div class="flex items-center justify-between gap-4 px-5 py-4 border-b border-gray-100 last:border-0">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-sm font-bold text-red-500 shrink-0">
                                    {{ collect(preg_split('/\s+/', $notif->receiver->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('') }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $notif->receiver->name }}</p>
                                    <p class="text-xs text-gray-500">Declined your request &middot; {{ $notif->responded_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('messages.show', $notif->receiver) }}"
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition shadow-sm shrink-0">
                                Send Again
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-10 px-6">
                            <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <p class="text-sm text-gray-400">No declined requests</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const BASE_URL = '{{ url("") }}';

    function notifAccept(requestId, btn) {
        btn.disabled = true;
        fetch(BASE_URL + '/chat-requests/' + requestId + '/accept', {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const row = btn.closest('[data-request-id]');
                row.innerHTML = '<div class="flex items-center gap-3 text-sm text-emerald-700 font-medium py-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Accepted! Redirecting to chat...</div>';
                setTimeout(() => {
                    window.location.href = BASE_URL + '/messages/' + row.getAttribute('data-sender-id');
                }, 1200);
            }
        })
        .catch(() => { btn.disabled = false; });
    }

    function notifReject(requestId, btn) {
        btn.disabled = true;
        fetch(BASE_URL + '/chat-requests/' + requestId + '/reject', {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const row = btn.closest('[data-request-id]');
                row.innerHTML = '<p class="text-sm text-gray-500 font-medium py-2">Request dismissed.</p>';
                setTimeout(() => row.remove(), 1000);
            }
        })
        .catch(() => { btn.disabled = false; });
    }
</script>
