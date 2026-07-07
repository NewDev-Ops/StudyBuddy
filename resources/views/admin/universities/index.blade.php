<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisor — Manage Universities</title>
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

        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-sm shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-gray-400 hover:text-gray-600 transition">&larr; Back to Dashboard</a>
                    <h1 class="text-2xl font-bold text-gray-900 mt-0.5">Manage Universities</h1>
                </div>
            </div>
            <button type="button" onclick="openAddModal()"
                class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold px-4 py-2 rounded-lg transition-all text-sm shadow-sm hover:shadow-md">
                + Add University
            </button>
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
                        <th class="px-6 py-3 font-medium">Location</th>
                        <th class="px-6 py-3 font-medium text-center">Students</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($universities as $university)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-transparent transition">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $university->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $university->location }}</td>
                        <td class="px-6 py-4 text-center text-gray-500">{{ $university->users_count }}</td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" onclick='openEditModal(@json($university))'
                                class="text-blue-600 hover:text-blue-800 font-semibold text-sm mr-3">Edit</button>
                            <button type="button" onclick='openDeleteModal(@json($university))'
                                class="text-red-600 hover:text-red-800 font-semibold text-sm">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                No universities yet.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="h-16 bg-gradient-to-t from-blue-50/50 to-transparent pointer-events-none mt-8 -mx-6"></div>
    </div>

    {{-- ===== ADD/EDIT MODAL ===== --}}
    <div id="form-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="closeFormModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 animate-fade-in-up">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900" id="form-modal-title">Add University</h3>
                <button type="button" onclick="closeFormModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="form-modal-form" method="POST">
                @csrf
                <div id="form-method-spoof"></div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="field-name" maxlength="255"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="location" id="field-location" maxlength="255"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeFormModal()"
                        class="flex-1 border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg transition text-sm hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== DELETE MODAL ===== --}}
    <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 animate-fade-in-up">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Delete University</h3>
                    <p class="text-sm text-gray-500">This action cannot be undone.</p>
                </div>
            </div>
            <p class="text-sm text-gray-700 mb-2">
                Are you sure you want to delete <strong id="delete-name"></strong>?
            </p>
            <p id="delete-warning" class="text-sm text-amber-600 bg-amber-50 rounded-lg p-3 mb-4 hidden"></p>
            <form id="delete-form" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg transition text-sm hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('form-modal-title').textContent = 'Add University';
            document.getElementById('form-modal-form').action = '{{ route("admin.universities.store") }}';
            document.getElementById('form-method-spoof').innerHTML = '';
            document.getElementById('field-name').value = '';
            document.getElementById('field-location').value = '';
            document.getElementById('form-modal').classList.remove('hidden');
        }

        function openEditModal(university) {
            document.getElementById('form-modal-title').textContent = 'Edit University';
            document.getElementById('form-modal-form').action = '/admin/universities/' + university.id;
            document.getElementById('form-method-spoof').innerHTML = '@method("PATCH")';
            document.getElementById('field-name').value = university.name;
            document.getElementById('field-location').value = university.location;
            document.getElementById('form-modal').classList.remove('hidden');
        }

        function closeFormModal() {
            document.getElementById('form-modal').classList.add('hidden');
        }

        function openDeleteModal(university) {
            document.getElementById('delete-name').textContent = university.name;
            document.getElementById('delete-form').action = '/admin/universities/' + university.id;
            const warning = document.getElementById('delete-warning');
            if (university.users_count > 0) {
                warning.textContent = university.users_count + ' student(s) are linked to this university — they will be unassigned, not deleted.';
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }
    </script>

</body>
</html>
