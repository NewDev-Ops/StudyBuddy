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
                <div id="message-container" class="flex-1 overflow-y-auto px-5 py-4 space-y-3">
                    @forelse($messages as $msg)
                        <div class="flex {{ $msg->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[75%] {{ $msg->sender_id === Auth::id() ? 'bg-blue-600 text-white rounded-2xl rounded-br-md' : 'bg-gray-100 text-gray-900 rounded-2xl rounded-bl-md' }} px-4 py-2.5">
                                <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                                <p class="text-[10px] mt-1 {{ $msg->sender_id === Auth::id() ? 'text-blue-200' : 'text-gray-400' }} flex items-center gap-1">
                                    {{ $msg->created_at->format('g:i A') }}
                                    @if($msg->sender_id === Auth::id() && $msg->read_at)
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center h-full">
                            <p class="text-gray-400 text-sm">Send a message to start the conversation!</p>
                        </div>
                    @endforelse
                </div>

                <div class="border-t border-gray-200 px-4 py-3 bg-gray-50">
                    <form id="message-form" class="flex gap-2">
                        @csrf
                        <input type="text" id="message-input" maxlength="2000" required autocomplete="off"
                               placeholder="Type your message..."
                               class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <button type="submit" id="send-btn"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
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
