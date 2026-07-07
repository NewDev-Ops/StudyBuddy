<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisor — Manage Universities</title>
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

    <div class="max-w-5xl mx-auto px-6 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to Dashboard</a>
                <h1 class="text-2xl font-bold text-gray-900 mt-1">Manage Universities</h1>
            </div>
            <button type="button" onclick="openAddModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                + Add University
            </button>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm mb-6">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
                        <th class="px-6 py-3 font-medium">Name</th>
                        <th class="px-6 py-3 font-medium">Location</th>
                        <th class="px-6 py-3 font-medium text-center">Students</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($universities as $university)
                    <tr class="hover:bg-gray-50 transition">
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
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-sm">No universities yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
