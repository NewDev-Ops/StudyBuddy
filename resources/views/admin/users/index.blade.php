<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisor — Manage Users</title>
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
            <a href="{{ route('admin.feedback') }}" class="text-sm text-gray-600 hover:text-gray-900 relative">
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

    <div class="max-w-6xl mx-auto px-6 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to Dashboard</a>
                <h1 class="text-2xl font-bold text-gray-900 mt-1">Manage Users</h1>
                <p class="text-sm text-gray-500 mt-1">Promote users to admin or demote them back to student.</p>
            </div>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-6">
            {{ session('error') }}
        </div>
        @endif

        {{-- Search --}}
        <form method="GET" action="{{ route('admin.users') }}" class="mb-6">
            <div class="flex gap-2 max-w-sm">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-sm font-semibold shrink-0">
                    Search
                </button>
                @if($search)
                <a href="{{ route('admin.users') }}"
                    class="border border-gray-300 text-gray-600 hover:text-gray-800 px-3 py-2 rounded-lg transition text-sm font-semibold shrink-0 flex items-center">
                    Clear
                </a>
                @endif
            </div>
        </form>

        {{-- Users table --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
                        <th class="px-6 py-3 font-medium">Name</th>
                        <th class="px-6 py-3 font-medium">Email</th>
                        <th class="px-6 py-3 font-medium">Role</th>
                        <th class="px-6 py-3 font-medium">University</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="inline-block px-2 py-0.5 text-xs font-semibold bg-purple-100 text-purple-700 rounded-full">Admin</span>
                            @else
                                <span class="inline-block px-2 py-0.5 text-xs font-semibold bg-gray-100 text-gray-600 rounded-full">Student</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->university?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            @if($user->role === 'student')
                                <button type="button" onclick='openPromoteModal(@json($user))'
                                    class="text-emerald-600 hover:text-emerald-800 font-semibold text-sm">Promote to Admin</button>
                            @else
                                <button type="button" onclick='openDemoteModal(@json($user))'
                                    class="text-amber-600 hover:text-amber-800 font-semibold text-sm">Demote to Student</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">
                            @if($search)
                                No users match "{{ $search }}".
                            @else
                                No users found.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== PROMOTE MODAL ===== --}}
    <div id="promote-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="closePromoteModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 animate-fade-in-up">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Promote to Admin</h3>
                    <p class="text-sm text-gray-500">This grants full admin privileges.</p>
                </div>
            </div>
            <p class="text-sm text-gray-700 mb-6">
                This will grant <strong id="promote-name"></strong> access to all admin features, including user management, university management, and resource moderation. Continue?
            </p>
            <form id="promote-form" method="POST">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="closePromoteModal()"
                        class="flex-1 border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg transition text-sm hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        Promote to Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== DEMOTE MODAL ===== --}}
    <div id="demote-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="closeDemoteModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 animate-fade-in-up">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="demote-title">Demote to Student</h3>
                    <p class="text-sm text-gray-500">This removes admin access immediately.</p>
                </div>
            </div>
            <p class="text-sm text-gray-700 mb-2" id="demote-body">
                This will revoke <strong id="demote-name"></strong>'s admin access. They will lose all admin panel privileges immediately. Continue?
            </p>
            <div id="demote-self-warning" class="hidden text-sm text-red-600 bg-red-50 rounded-lg p-3 mb-4 border border-red-200">
                You are about to demote yourself. You will immediately lose access to the admin panel and will not be able to undo this action without another admin.
            </div>
            <form id="demote-form" method="POST">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="closeDemoteModal()"
                        class="flex-1 border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg transition text-sm hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        Demote to Student
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPromoteModal(user) {
            document.getElementById('promote-name').textContent = user.name;
            document.getElementById('promote-form').action = '/admin/users/' + user.id + '/promote';
            document.getElementById('promote-modal').classList.remove('hidden');
        }

        function closePromoteModal() {
            document.getElementById('promote-modal').classList.add('hidden');
        }

        function openDemoteModal(user) {
            document.getElementById('demote-name').textContent = user.name;
            document.getElementById('demote-form').action = '/admin/users/' + user.id + '/demote';

            const isSelf = user.id === {{ auth()->id() }};
            const warning = document.getElementById('demote-self-warning');

            if (isSelf) {
                warning.classList.remove('hidden');
                document.getElementById('demote-title').textContent = 'Demote Yourself to Student';
                document.getElementById('demote-body').innerHTML = 'This will revoke <strong>' + user.name + '</strong>\'s (you) admin access.';
            } else {
                warning.classList.add('hidden');
                document.getElementById('demote-title').textContent = 'Demote to Student';
                document.getElementById('demote-body').innerHTML = 'This will revoke <strong>' + user.name + '</strong>\'s admin access. They will lose all admin panel privileges immediately. Continue?';
            }

            document.getElementById('demote-modal').classList.remove('hidden');
        }

        function closeDemoteModal() {
            document.getElementById('demote-modal').classList.add('hidden');
        }
    </script>

</body>
</html>
