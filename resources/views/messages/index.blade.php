@php
    $unreadCount = \App\Models\Message::where('receiver_id', Auth::id())->whereNull('read_at')->count();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Messages</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @forelse($conversations as $conv)
                    <a href="{{ route('messages.show', $conv->peer) }}"
                       class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 border-b border-gray-100 last:border-0 transition group">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-sm font-bold text-emerald-700 shrink-0">
                            {{ collect(preg_split('/\s+/', $conv->peer->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('') }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900 {{ $conv->unread ? '' : '' }}">{{ $conv->peer->name }}</span>
                                @if($conv->unread)
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold">{{ $conv->unread }}</span>
                                @endif
                                <span class="ml-auto text-[11px] text-gray-400">{{ $conv->last_time->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5 truncate {{ $conv->unread ? 'font-semibold text-gray-700' : '' }}">
                                {{ $conv->last_message }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-16 px-4">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-gray-500 text-sm font-medium">No conversations yet</p>
                        <p class="text-gray-400 text-xs mt-1">Connect with peers from the dashboard to start chatting.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
