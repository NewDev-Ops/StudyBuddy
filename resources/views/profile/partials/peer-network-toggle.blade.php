<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Peer Network</h2>
        <p class="mt-1 text-sm text-gray-600">Control your visibility to other students in the peer network.</p>
    </header>

    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
        <div>
            <p class="text-sm font-medium text-gray-900">
                @if(auth()->user()->is_opted_in)
                    You are currently <span class="text-emerald-600">visible</span> to other students
                @else
                    You are currently <span class="text-gray-400">hidden</span> from other students
                @endif
            </p>
            <p class="text-xs text-gray-500 mt-0.5">
                Only your name, university, and strong subjects are ever shown. Your marks stay private.
            </p>
        </div>

        @if(auth()->user()->is_opted_in)
            <form method="POST" action="{{ route('profile.peer-network.toggle') }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="bg-white border border-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                    Leave peer network
                </button>
            </form>
        @else
            <div x-data="{ showConfirm: false }">
                <button type="button" @click="showConfirm = true"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                    Join peer network
                </button>
                <div x-show="showConfirm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/40" @click="showConfirm = false"></div>
                    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 animate-fade-in-up">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Join Peer Network</h3>
                                <p class="text-sm text-gray-500">Become visible as a study partner</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 mb-6">
                            When visible, only your name, university, and strong subjects are shown. Your marks stay private. You can leave anytime.
                        </p>
                        <div class="flex gap-3">
                            <button type="button" @click="showConfirm = false"
                                class="flex-1 border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg transition text-sm hover:bg-gray-50">
                                Cancel
                            </button>
                            <form method="POST" action="{{ route('profile.peer-network.toggle') }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                                    Join
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
