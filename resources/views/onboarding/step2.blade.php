<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Revisor — Onboarding</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center py-12 px-4">

    <div class="bg-white rounded-2xl shadow-md w-full max-w-md px-8 py-10 animate-fade-in-up">

        {{-- Progress --}}
        <div class="flex items-center gap-2 mb-8">
            <div class="flex-1 h-1.5 bg-blue-600 rounded-full"></div>
            <div class="flex-1 h-1.5 bg-blue-600 rounded-full"></div>
        </div>

        {{-- Header --}}
        <div class="flex flex-col items-center mb-6 animate-fade-in" style="animation-delay: 100ms">
            <div class="bg-blue-600 rounded-xl p-2.5 mb-3 animate-logo-pulse">
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
                            <form method="POST" action="{{ route('onboarding.deleteSubject', $subject) }}" onsubmit="return confirm('Remove this subject?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition">
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
            <a href="{{ route('onboarding.complete') }}"
                class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                {{ $userSubjects->isNotEmpty() ? 'Finish Setup' : 'Skip for now' }}
            </a>
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
</body>
</html>
