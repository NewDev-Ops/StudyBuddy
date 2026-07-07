<x-app-layout>
    <div class="py-8" x-data="{ showConfirm: false, confirmAction: '', confirmLabel: '', showSuggester: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- ============================================================ --}}
                {{-- LEFT SIDEBAR — Study Next                                     --}}
                {{-- ============================================================ --}}
                <aside class="lg:col-span-3 space-y-5 animate-fade-in-up" style="animation-delay: 100ms">

                    {{-- Study Next Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <h3 class="text-sm font-bold text-gray-900">Study Next</h3>
                            @if(!empty($allSubjectScores))
                            <button @click="showSuggester = true" class="ml-auto text-gray-400 hover:text-blue-600 transition" title="Why this suggestion?">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                            @endif
                        </div>

                        @if($suggestedSubject)
                        <div class="bg-blue-50 rounded-xl p-4 mb-5">
                            {{-- Suggested Subject --}}
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $suggestedSubject->color_code }}"></div>
                                <p class="text-sm font-bold text-gray-900">{{ $suggestedSubject->name }}</p>
                            </div>
                            @php
                                $bd = $suggestionBreakdown;
                                if ($bd['mode'] === 'composite') {
                                    $parts = [];
                                    if ($bd['weighted_avg_percent'] !== null) {
                                        $parts[] = $bd['weighted_avg_percent'] . '% average';
                                    }
                                    $parts[] = 'Only ' . $bd['total_minutes'] . ' min logged';
                                    $reason = implode(' • ', $parts);
                                } elseif ($bd['total_minutes'] === 0) {
                                    $reason = "You haven't studied this subject yet";
                                } else {
                                    $reason = 'No marks yet • Only ' . $bd['total_minutes'] . ' min logged';
                                }
                            @endphp
                            <p class="text-xs text-gray-500 mt-1">{{ $reason }}</p>
                            <button onclick="document.getElementById('log-revision-modal').classList.remove('hidden')"
                                class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 mt-3 hover:text-blue-700 transition">
                                Start Session
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                        @else
                        <div class="bg-gray-50 rounded-xl p-4 mb-5">
                            <p class="text-xs text-gray-400 text-center">Add a subject to get started</p>
                        </div>
                        @endif

                        @php
                            function resourceSource(string $url): string {
                                $host = parse_url($url, PHP_URL_HOST) ?: '';
                                if (str_contains($host, 'khanacademy')) return 'Khan Academy';
                                if (str_contains($host, 'mit.edu')) return 'MIT';
                                if (str_contains($host, 'youtube') || str_contains($host, 'youtu.be')) return 'YouTube';
                                return 'Resource';
                            }
                        @endphp

                        {{-- Quick Resources --}}
                        <div class="mb-5">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Quick Resources</h4>
                            </div>
                            <div class="space-y-2">
                                @forelse($recommendedResources as $resource)
                                <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer"
                                    class="flex flex-col gap-1 p-2 rounded-lg w-full text-left hover:bg-blue-50 transition group">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-blue-100 rounded-lg p-1.5 shrink-0">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-sm text-gray-700 group-hover:text-blue-700 transition font-medium block truncate">{{ $resource->title }}</span>
                                            <span class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">{{ resourceSource($resource->url) }}</span>
                                        </div>
                                    </div>
                                    @if($suggestedSubject)
                                    <span class="text-[10px] text-blue-500 ml-9">Recommended for {{ $suggestedSubject->name }}</span>
                                    @endif
                                </a>
                                @empty
                                <div class="text-center py-4 px-2">
                                    <p class="text-xs text-gray-400">No resources yet for <strong class="text-gray-500">{{ $suggestedSubject->name ?? 'this subject' }}</strong>.</p>
                                    <p class="text-[10px] text-gray-400 mt-1">Resources are curated by your university. Check back later or suggest new ones via feedback.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Peer Insights --}}
                        <div x-data="{ showPeerInfo: false }">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Peer Insights</h4>
                                @if($peerComparisonMode === 'relative')
                                    <span class="inline-block ml-1 px-1.5 py-0.5 text-[9px] font-semibold uppercase tracking-wider text-blue-600 bg-blue-50 rounded">Students scoring higher than you</span>
                                @elseif($peerComparisonMode === 'absolute')
                                    <span class="inline-block ml-1 px-1.5 py-0.5 text-[9px] font-semibold uppercase tracking-wider text-amber-600 bg-amber-50 rounded">Top performers in this subject</span>
                                @endif
                            </div>
                            <div class="space-y-3">
                                @if($peerSuggestions === null)
                                    <p class="text-xs text-gray-400 text-center py-3">
                                        Join the peer network to see study partner suggestions.
                                        <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-800 font-semibold block mt-1">Go to Profile Settings</a>
                                    </p>
                                @elseif($peerSuggestions->isEmpty())
                                    <p class="text-xs text-gray-400 text-center py-3">No peer matches yet for this subject.</p>
                                @else
                                    @foreach($peerSuggestions as $peer)
                                    <div class="rounded-lg p-1 -ml-1" x-data="{ showTip: false }">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-xs font-bold text-emerald-700 shrink-0">
                                                {{ collect(preg_split('/\s+/', $peer->name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('') }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $peer->name }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ $peer->university_name ?? 'Unknown University' }}</p>
                                                <p class="text-[10px] text-emerald-600 font-medium mt-0.5">Strong in {{ $peer->subject_name }}</p>
                                            </div>
                                            <div class="relative shrink-0">
                                                <button @mouseenter="showTip = true" @mouseleave="showTip = false" @touchstart.prevent="showTip = !showTip" class="text-gray-400 hover:text-blue-600 transition p-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </button>
                                                <div x-show="showTip" x-cloak @click.away="showTip = false" class="absolute bottom-full right-0 mb-2 w-52 bg-gray-900 text-white text-[11px] rounded-lg p-2.5 shadow-lg z-10 leading-relaxed">
                                                    @if($peerComparisonMode === 'relative')
                                                    Matched because they score higher than you in <strong>{{ $suggestedSubject->name ?? 'this subject' }}</strong>, suggesting they can offer helpful strategies.
                                                    @else
                                                    Matched as a top performer in <strong>{{ $suggestedSubject->name ?? 'this subject' }}</strong>, suggesting they have strong study habits to learn from.
                                                    @endif
                                                    <div class="absolute -bottom-1 right-3 w-2 h-2 bg-gray-900 rotate-45"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-1.5 ml-12">
                                            <a href="{{ route('messages.show', $peer->id) }}"
                                               class="text-[11px] text-blue-600 hover:text-blue-800 font-semibold">
                                                Send Message
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            @if($peerSuggestions !== null && !$peerSuggestions->isEmpty())
                            <button @click="showPeerInfo = !showPeerInfo" class="mt-3 flex items-center gap-1 text-[10px] text-gray-400 hover:text-blue-600 transition font-medium">
                                <svg class="w-3 h-3" :class="{ 'rotate-90': showPeerInfo }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                How peers are selected
                            </button>
                            <div x-show="showPeerInfo" x-collapse class="mt-2 text-[10px] text-gray-400 leading-relaxed space-y-1.5">
                                @if($peerComparisonMode === 'relative')
                                <p>You have marks in this subject, so we found <strong class="text-gray-500">students scoring higher than you</strong> at your university first, then beyond.</p>
                                @else
                                <p>You don't have marks yet in this subject, so we show <strong class="text-gray-500">top performers (&ge;70%)</strong> at your university first, then beyond.</p>
                                @endif
                                <p>Only students who opted into the peer network appear. Your data is never shared without your consent.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </aside>

                {{-- ============================================================ --}}
                {{-- MAIN CONTENT — My Subjects                                     --}}
                {{-- ============================================================ --}}
                <main class="lg:col-span-6 space-y-4 animate-fade-in-up" style="animation-delay: 200ms">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-xl font-bold text-gray-900">My Subjects</h2>
                        <span class="text-sm text-gray-500">{{ $subjects->count() }} Active Course{{ $subjects->count() !== 1 ? 's' : '' }}</span>
                    </div>

                    {{-- Add Subject Form --}}
                    <div class="animate-fade-in-up relative z-20" style="animation-delay: 250ms" x-data="subjectSearch()" @click.away="showDropdown = false">
                        <form method="POST" action="{{ route('subjects.store') }}" class="flex gap-2">
                            @csrf
                            <div class="flex-1 relative">
                                <input type="text" name="name" placeholder="Add a new subject..."
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
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
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-sm font-semibold shrink-0">
                                Add
                            </button>
                        </form>
                    </div>

                    {{-- Subject Cards --}}
                    @if($subjects->isNotEmpty())
                        @foreach($subjects as $i => $subject)
                            <div class="flex items-center justify-between bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md hover:border-blue-200 transition-all duration-200 group animate-fade-in-up"
                                 style="animation-delay: {{ 300 + ($i * 100) }}ms">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: {{ $subject->color_code }}20">
                                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $subject->color_code }}"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition">{{ $subject->name }}</p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-xs text-gray-500">Added {{ $subject->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="button"
                                    onclick="openDeleteModal('{{ $subject->name }}', '{{ route('subjects.destroy', $subject) }}')"
                                    class="text-gray-300 hover:text-red-500 transition p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center animate-fade-in relative z-0" style="animation-delay: 300ms">
                            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="text-sm text-gray-500">No subjects yet. Add your first subject above!</p>
                        </div>
                    @endif

                </main>

                {{-- ============================================================ --}}
                {{-- RIGHT SIDEBAR — Quick Actions + Recent Sessions              --}}
                {{-- ============================================================ --}}
                <aside class="lg:col-span-3 space-y-5 animate-fade-in-up" style="animation-delay: 300ms">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">Quick Actions</h4>
                        <div class="space-y-3">
                            <button onclick="document.getElementById('log-revision-modal').classList.remove('hidden')"
                               class="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Log Revision Session
                            </button>
                            <button onclick="document.getElementById('record-mark-modal').classList.remove('hidden')"
                               class="flex items-center justify-center gap-2 w-full border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold py-2.5 rounded-lg transition text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Record a Mark
                            </button>
                            <a href="{{ route('feedback.create') }}"
                               class="flex items-center justify-center gap-2 w-full border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-2.5 rounded-lg transition text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                Send Feedback
                            </a>
                        </div>

                        {{-- Study Wrapped --}}
                        <a href="{{ route('study-wrapped') }}"
                            class="mt-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100 w-full text-left hover:from-blue-100 hover:to-indigo-100 transition group block">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-400 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition">Study Wrapped</p>
                                    <p class="text-xs text-gray-500">{{ now()->year }}</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 ml-auto group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-400 mt-2 group-hover:text-gray-500 transition">View semester highlights</p>
                        </a>
                    </div>

                    {{-- Recent Sessions --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">Recent Sessions</h4>
                        @if($recentSessions->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($recentSessions as $session)
                                    <div class="flex items-center justify-between group">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $session->subject->color_code }}"></div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $session->subject->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $session->duration_minutes }}m &middot; {{ $session->date->format('M d') }}</p>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('revision-sessions.destroy', $session) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" @click.prevent="confirmAction = '{{ route('revision-sessions.destroy', $session) }}'; confirmLabel = 'this session'; showConfirm = true" class="text-gray-300 hover:text-red-500 transition opacity-0 group-hover:opacity-100 p-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 text-center py-3">No sessions logged yet.</p>
                        @endif
                    </div>

                    {{-- Recent Marks --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">Recent Marks</h4>
                        @if($recentMarks->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($recentMarks as $mark)
                                    <div class="flex items-center justify-between group">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $mark->subject->color_code }}"></div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $mark->assessment_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $mark->subject->name }} &middot; {{ $mark->date->format('M d') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold {{ $mark->percentage() >= 70 ? 'text-emerald-600' : ($mark->percentage() >= 50 ? 'text-amber-600' : 'text-red-500') }}">
                                                {{ $mark->percentage() }}%
                                            </span>
                                            <form method="POST" action="{{ route('marks.destroy', $mark) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" @click.prevent="confirmAction = '{{ route('marks.destroy', $mark) }}'; confirmLabel = 'this mark'; showConfirm = true" class="text-gray-300 hover:text-red-500 transition opacity-0 group-hover:opacity-100 p-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 text-center py-3">No marks recorded yet.</p>
                        @endif
                    </div>
                </aside>

            </div>
        </div>

    {{-- ============================================================ --}}
    {{-- Subject Suggester Modal                                       --}}
    {{-- ============================================================ --}}
    <div x-show="showSuggester" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="showSuggester = false">
        <div class="absolute inset-0 bg-black/40" @click="showSuggester = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 animate-fade-in-up max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-900">How Subjects Are Ranked</h3>
                <button @click="showSuggester = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            @php
                $top = $suggestionBreakdown;
                $explanation = '';
                if ($top) {
                    if ($top['mode'] === 'composite' && $top['weighted_avg_percent'] !== null) {
                        $explanation = "<strong>{$top['subject_name']}</strong> needs attention — your <strong>{$top['weighted_avg_percent']}%</strong> average is the lowest, and you've only logged <strong>{$top['total_minutes']} min</strong> of study time.";
                    } elseif ($top['total_minutes'] === 0) {
                        $explanation = "You haven't studied <strong>{$top['subject_name']}</strong> yet — adding a revision session will help us track your progress.";
                    } else {
                        $explanation = "No marks recorded yet for <strong>{$top['subject_name']}</strong>, but you've logged <strong>{$top['total_minutes']} min</strong> — start recording marks to unlock performance insights.";
                    }
                }
            @endphp

            @if($explanation)
            <div class="bg-blue-50 rounded-xl p-4 mb-5 text-sm text-gray-800 leading-relaxed">
                {!! $explanation !!}
            </div>
            @endif

            @if(!empty($allSubjectScores))
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider w-8">#</th>
                            <th class="text-left pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Subject</th>
                            <th class="text-right pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Min Logged</th>
                            <th class="text-right pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Avg %</th>
                            <th class="text-right pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allSubjectScores as $i => $s)
                        <tr class="border-b border-gray-50 {{ $i === 0 ? 'bg-blue-50/50' : '' }}">
                            <td class="py-2.5 text-gray-500 font-mono text-xs">{{ $i + 1 }}</td>
                            <td class="py-2.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $s['color_code'] }}"></div>
                                    <span class="font-medium text-gray-900 {{ $i === 0 ? 'font-bold' : '' }}">{{ $s['subject_name'] }}</span>
                                </div>
                            </td>
                            <td class="py-2.5 text-right text-gray-600 font-mono text-xs">{{ $s['total_minutes'] }}</td>
                            <td class="py-2.5 text-right font-mono text-xs {{ $s['weighted_avg_percent'] !== null ? ($s['weighted_avg_percent'] >= 70 ? 'text-emerald-600' : ($s['weighted_avg_percent'] >= 50 ? 'text-amber-600' : 'text-red-500')) : 'text-gray-400' }}">
                                {{ $s['weighted_avg_percent'] !== null ? $s['weighted_avg_percent'] . '%' : '—' }}
                            </td>
                            <td class="py-2.5 text-right font-mono text-xs font-bold {{ $i === 0 ? 'text-blue-600' : 'text-gray-500' }}">{{ number_format($s['priority_score'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div x-data="{ showHow: false }" class="mt-5">
                <button @click="showHow = !showHow" class="flex items-center gap-1 text-xs text-gray-500 hover:text-blue-600 transition font-medium">
                    <svg class="w-3.5 h-3.5" :class="{ 'rotate-90': showHow }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    How does this work?
                </button>
                <div x-show="showHow" x-collapse class="mt-3 text-xs text-gray-500 leading-relaxed space-y-2">
                    <p>Each subject is scored on two dimensions:</p>
                    <ul class="list-disc pl-4 space-y-1">
                        <li><strong class="text-gray-700">Neglect score</strong> — how little you've studied it compared to your most-studied subject (0 = most studied, 1 = not studied at all).</li>
                        <li><strong class="text-gray-700">Underperformance score</strong> — how low your marks are (0 = perfect score, 1 = no marks).</li>
                    </ul>
                    <p>These are averaged equally to produce a <strong class="text-gray-700">priority score</strong>. The subject with the highest priority score is recommended as your "Study Next."</p>
                </div>
            </div>
            @else
            <div class="text-center py-6">
                <p class="text-sm text-gray-400">Add subjects to see how they rank.</p>
            </div>
            @endif
        </div>
    </div>

    </div>

    {{-- ============================================================ --}}
    {{-- Log Revision Session Modal                                    --}}
    {{-- ============================================================ --}}
    <div id="log-revision-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('log-revision-modal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 animate-fade-in-up">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-900">Log Revision Session</h3>
                <button onclick="document.getElementById('log-revision-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('revision-sessions.store') }}">
                @csrf
                <div class="space-y-4">
                    {{-- Subject --}}
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <select name="subject_id" id="subject_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Select a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Duration --}}
                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes)</label>
                        <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', 30) }}" min="1" max="1440" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('duration_minutes')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date --}}
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('date')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                        <textarea name="notes" id="notes" rows="3" maxlength="1000" placeholder="What topics did you cover? By sharing with us your topics we can better tailor our suggestions to you."
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('log-revision-modal').classList.add('hidden')"
                        class="flex-1 border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg transition text-sm hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        Save Session
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- Record a Mark Modal                                           --}}
    {{-- ============================================================ --}}
    <div id="record-mark-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('record-mark-modal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 animate-fade-in-up">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-900">Record a Mark</h3>
                <button onclick="document.getElementById('record-mark-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('marks.store') }}">
                @csrf
                <div class="space-y-4">
                    {{-- Subject --}}
                    <div>
                        <label for="mark_subject_id" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <select name="subject_id" id="mark_subject_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Select a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Assessment Name --}}
                    <div>
                        <label for="assessment_name" class="block text-sm font-medium text-gray-700 mb-1">Assessment Name</label>
                        <input type="text" name="assessment_name" id="assessment_name" value="{{ old('assessment_name') }}" placeholder="e.g. Midterm Exam" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('assessment_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" id="type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Select type</option>
                            @foreach(['Exam', 'Quiz', 'Assignment', 'Project', 'Coursework', 'Test', 'Other'] as $t)
                                <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Score / Max Score --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="score" class="block text-sm font-medium text-gray-700 mb-1">Score</label>
                            <input type="number" name="score" id="score" value="{{ old('score') }}" min="0" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('score')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="max_score" class="block text-sm font-medium text-gray-700 mb-1">Max Score</label>
                            <input type="number" name="max_score" id="max_score" value="{{ old('max_score', 100) }}" min="1" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('max_score')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Date --}}
                    <div>
                        <label for="mark_date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="date" id="mark_date" value="{{ old('date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('date')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('record-mark-modal').classList.add('hidden')"
                        class="flex-1 border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg transition text-sm hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        Save Mark
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- Delete Subject Confirmation Modal                             --}}
    {{-- ============================================================ --}}
    <div id="delete-subject-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 animate-fade-in-up">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Delete Subject</h3>
                    <p class="text-sm text-gray-500">This action cannot be undone.</p>
                </div>
            </div>
            <p class="text-sm text-gray-700 mb-6">
                Are you sure you want to remove <strong id="delete-subject-name"></strong>?
            </p>
            <form id="delete-subject-form" method="POST">
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

    {{-- ============================================================ --}}
    {{-- Confirm Delete Modal                                          --}}
    {{-- ============================================================ --}}
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
                    <h3 class="text-lg font-bold text-gray-900">Confirm Delete</h3>
                    <p class="text-sm text-gray-500">This action cannot be undone.</p>
                </div>
            </div>
            <p class="text-sm text-gray-700 mb-6">
                Are you sure you want to delete <strong x-text="confirmLabel"></strong>?
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
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Footer --}}
    <div class="py-6 border-t border-gray-200 animate-fade-in" style="animation-delay: 1000ms">
        <p class="text-center text-xs text-gray-400">&copy; 2026 Revisor Academic Tracking. Built by students for students. All rights reserved.</p>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 z-50 bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg text-sm font-medium animate-fade-in-up';
                toast.textContent = '{{ session('success') }}';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            });
        </script>
    @endif

    <script>
        function openDeleteModal(name, url) {
            document.getElementById('delete-subject-name').textContent = name;
            document.getElementById('delete-subject-form').action = url;
            document.getElementById('delete-subject-modal').classList.remove('hidden');
        }
        function closeDeleteModal() {
            document.getElementById('delete-subject-modal').classList.add('hidden');
        }

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
</x-app-layout>
