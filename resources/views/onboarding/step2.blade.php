<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Revisor — Onboarding</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col items-center justify-center py-12 px-4 relative" style="background-color: #f1f5f9; background-image: radial-gradient(circle at 1px 1px, rgba(59,130,246,0.06) 1px, transparent 0); background-size: 24px 24px;">

    {{-- Decorative blobs --}}
    <div class="absolute top-0 right-0 w-72 h-72 bg-blue-100/30 rounded-full blur-3xl pointer-events-none translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-100/20 rounded-full blur-3xl pointer-events-none -translate-x-1/3 translate-y-1/3"></div>

    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg w-full max-w-md px-8 py-10 animate-fade-in-up relative"
         x-data="{ showConfirm: false, confirmAction: '', confirmSubject: '' }">

        {{-- Progress --}}
        <div class="flex items-center gap-2 mb-8">
            <div class="flex-1 h-1.5 bg-gradient-to-r from-blue-600 to-blue-500 rounded-full shadow-sm"></div>
            <div class="flex-1 h-1.5 bg-gradient-to-r from-blue-600 to-blue-500 rounded-full shadow-sm"></div>
        </div>

        {{-- Header --}}
        <div class="flex flex-col items-center mb-6 animate-fade-in" style="animation-delay: 100ms">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-2.5 mb-3 animate-logo-pulse shadow-md">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Add Your Subjects</h1>
            <p class="text-sm text-gray-500 mt-1 text-center">Add at least one subject you want to track.</p>
        </div>

        {{-- Add Subject Form --}}
        <div class="mb-5 animate-fade-in-up" style="animation-delay: 200ms" x-data="subjectSearch()" @click.away="showDropdown = false">
            <form method="POST" action="{{ route('onboarding.storeSubject') }}">
                @csrf
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Computer Science"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required x-model="query" @input="search">
                        <div x-show="showDropdown && results.length > 0" x-transition
                            class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                            <template x-for="result in results" :key="result">
                                <button type="button" @mousedown.prevent="selectSuggestion(result)"
                                    class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <span x-text="result"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition text-sm font-semibold shrink-0">
                        Add
                    </button>
                </div>
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </form>
        </div>

        {{-- Suggested Subjects --}}
        @if($suggestedSubjects->isNotEmpty())
            <div class="mb-5 animate-fade-in" style="animation-delay: 300ms">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Suggested from community</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($suggestedSubjects as $suggested)
                        <form method="POST" action="{{ route('onboarding.addSuggested') }}">
                            @csrf
                            <input type="hidden" name="name" value="{{ $suggested }}">
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-full hover:bg-blue-100 transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ $suggested }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- User's Subjects --}}
        @if($userSubjects->isNotEmpty())
            <div class="mb-6 animate-fade-in" style="animation-delay: 400ms">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Your subjects</p>
                <div class="space-y-2">
                    @foreach($userSubjects as $subject)
                        <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2.5 group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $subject->color_code }}"></div>
                                <span class="text-sm text-gray-700">{{ $subject->name }}</span>
                            </div>
                            <form method="POST" action="{{ route('onboarding.deleteSubject', $subject) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click.prevent="confirmAction = '{{ route('onboarding.deleteSubject', $subject) }}'; confirmSubject = '{{ $subject->name }}'; showConfirm = true" class="text-gray-400 hover:text-red-500 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="animate-fade-in-up" style="animation-delay: 500ms">
            <form method="POST" action="{{ route('onboarding.complete') }}">
                @csrf

                {{-- Peer Network Opt-In --}}
                <div class="mb-5">
                    <label class="flex items-start gap-3 cursor-pointer">
                        {{-- Hidden field ensures a value is ALWAYS sent (0 = unchecked, 1 = checked) --}}
                        <input type="hidden" name="is_opted_in" value="0">
                        <input type="checkbox" name="is_opted_in" value="1"
                            class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 shadow-sm">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Join the peer network</p>
                            <p class="text-xs text-gray-500 mt-0.5">Let other students discover you as a study partner. Only your name, university, and strong subjects are ever shown. Your marks stay private. You can change this anytime.</p>
                        </div>
                    </label>
                </div>

                <button type="submit"
                    class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                    {{ $userSubjects->isNotEmpty() ? 'Finish Setup' : 'Skip for now' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Confirm Delete Modal --}}
    <div x-show="showConfirm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" @click="showConfirm = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 animate-fade-in-up">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Remove Subject</h3>
                    <p class="text-sm text-gray-500">This will only remove it from your list.</p>
                </div>
            </div>
            <p class="text-sm text-gray-700 mb-6">
                Are you sure you want to remove <strong x-text="confirmSubject"></strong>?
            </p>
            <form method="POST" x-bind:action="confirmAction">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" @click="showConfirm = false"
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
        function subjectSearch() {
            return {
                query: '',
                results: [],
                showDropdown: false,
                search: async function() {
                    if (this.query.length < 2) {
                        this.results = [];
                        this.showDropdown = false;
                        return;
                    }
                    try {
                        const response = await fetch('{{ route("subjects.search") }}?q=' + encodeURIComponent(this.query), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        this.results = await response.json();
                        this.showDropdown = this.results.length > 0;
                    } catch (e) {
                        this.results = [];
                        this.showDropdown = false;
                    }
                },
                selectSuggestion: function(value) {
                    this.query = value;
                    this.showDropdown = false;
                }
            }
        }
    </script>

    <x-coming-soon-modal />
</body>
</html>
