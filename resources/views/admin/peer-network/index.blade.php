<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisor — Peer Network Moderation</title>
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

    <div class="max-w-5xl mx-auto px-6 py-10">

        <div class="flex items-center gap-4 mb-8">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs text-gray-400 hover:text-gray-600 transition">&larr; Back to Dashboard</a>
                <h1 class="text-2xl font-bold text-gray-900 mt-0.5">Peer Network Moderation</h1>
                <p class="text-sm text-gray-400 mt-0.5">Students who have opted into the peer network.</p>
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

        <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100/50 text-left text-gray-500 text-xs uppercase tracking-wide">
                        <th class="px-6 py-3 font-medium">Name</th>
                        <th class="px-6 py-3 font-medium">Email</th>
                        <th class="px-6 py-3 font-medium">University</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-transparent transition">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $student->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $student->email }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $student->university?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" onclick='openRemoveModal(@json($student))'
                                class="text-red-600 hover:text-red-800 font-semibold text-sm">Remove</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857"/>
                                </svg>
                                No students have opted into the peer network yet.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="h-16 bg-gradient-to-t from-blue-50/50 to-transparent pointer-events-none mt-8 -mx-6"></div>
    </div>

    {{-- ===== REMOVE MODAL ===== --}}
    <div id="remove-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="closeRemoveModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 animate-fade-in-up">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Remove from Peer Network</h3>
                    <p class="text-sm text-gray-500">Peer visibility will be revoked.</p>
                </div>
            </div>
            <p class="text-sm text-gray-700 mb-6">
                This will remove <strong id="remove-name"></strong> from the peer network. They can opt back in themselves later. Continue?
            </p>
            <form id="remove-form" method="POST">
                @csrf
                @method('PATCH')
                <div class="flex gap-3">
                    <button type="button" onclick="closeRemoveModal()"
                        class="flex-1 border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg transition text-sm hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        Remove
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRemoveModal(student) {
            document.getElementById('remove-name').textContent = student.name;
            document.getElementById('remove-form').action = '/admin/peer-network/' + student.id + '/remove';
            document.getElementById('remove-modal').classList.remove('hidden');
        }

        function closeRemoveModal() {
            document.getElementById('remove-modal').classList.add('hidden');
        }
    </script>

</body>
</html>
