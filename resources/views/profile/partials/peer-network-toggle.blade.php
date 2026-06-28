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
            <form method="POST" action="{{ route('profile.peer-network.toggle') }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition"
                    onclick="return confirm('When visible, only your name, university, and strong subjects are shown. Your marks stay private. You can leave anytime.')">
                    Join peer network
                </button>
            </form>
        @endif
    </div>
</section>
