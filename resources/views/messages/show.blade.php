@php
    $unreadCount = \App\Models\Message::where('receiver_id', Auth::id())->whereNull('read_at')->count();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('messages.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-xs font-bold text-emerald-700">
                {{ collect(preg_split('/\s+/', $user->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('') }}
            </div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $user->name }}</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col" style="height: 70vh;">
                <div id="message-container" class="flex-1 overflow-y-auto px-5 py-4 space-y-3" style="background-image: radial-gradient(circle at 1px 1px, rgba(59,130,246,0.04) 1px, transparent 0); background-size: 24px 24px;">
                    @forelse($messages as $msg)
                        <div class="flex {{ $msg->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }} animate-fade-in-up" style="animation-duration: 0.2s">
                            <div class="max-w-[75%] {{ $msg->sender_id === Auth::id() ? 'bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-2xl rounded-br-md shadow-sm' : 'bg-gray-100 text-gray-900 rounded-2xl rounded-bl-md' }} px-4 py-2.5">
                                <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                                <p class="text-[10px] mt-1 {{ $msg->sender_id === Auth::id() ? 'text-blue-200' : 'text-gray-400' }} flex items-center gap-1">
                                    {{ $msg->created_at->format('g:i A') }}
                                    @if($msg->sender_id === Auth::id())
                                        @if($msg->read_at)
                                            <svg class="w-3 h-3 text-blue-200" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L10 13.586l7.293-7.293a1 1 0 111.414 1.414l-8 8z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-[8px]">Read</span>
                                        @else
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full gap-3">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">Start a conversation</p>
                            <p class="text-gray-400 text-xs">Send a message to {{ $user->name }} below!</p>
                        </div>
                    @endforelse
                </div>

                <div class="border-t border-gray-200 px-4 py-3 bg-white shadow-[0_-1px_3px_-1px_rgba(0,0,0,0.05)]">
                    <form id="message-form" class="flex gap-2">
                        @csrf
                        <input type="text" id="message-input" maxlength="2000" required autocomplete="off"
                               placeholder="Type your message..."
                               class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <button type="submit" id="send-btn"
                                class="inline-flex items-center gap-1.5 px-5 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Send
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('message-container');
        const form = document.getElementById('message-form');
        const input = document.getElementById('message-input');
        const btn = document.getElementById('send-btn');

        container.scrollTop = container.scrollHeight;

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const text = input.value.trim();
            if (!text) return;

            btn.disabled = true;
            input.disabled = true;

            try {
                const response = await fetch('{{ route("messages.store", $user) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({ message: text }),
                });

                const data = await response.json();

                if (data.success) {
                    const div = document.createElement('div');
                    div.className = 'flex justify-end';
                    const now = new Date();
                    const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0') + ' ' + (now.getHours() >= 12 ? 'PM' : 'AM');
                    div.innerHTML = `
                        <div class="max-w-[75%] bg-blue-600 text-white rounded-2xl rounded-br-md px-4 py-2.5">
                            <p class="text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(text)}</p>
                            <p class="text-[10px] mt-1 text-blue-200">${timeStr}</p>
                        </div>
                    `;
                    container.insertBefore(div, container.firstChild);
                    container.scrollTop = container.scrollHeight;
                    input.value = '';
                }
            } catch (err) {
                console.error('Send failed', err);
            } finally {
                btn.disabled = false;
                input.disabled = false;
                input.focus();
            }
        });

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }
    });
</script>
