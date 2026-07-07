<nav x-data="{ open: false }" class="bg-white shadow-sm">
    <div class="px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <div class="bg-blue-600 rounded-lg p-1.5">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="font-bold text-gray-900">Revisor</span>
            </a>
            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-medium ml-1">Student</span>
            <span class="hidden sm:inline text-xs text-gray-400 ml-2">Built by students for students</span>
        </div>

        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}"
                class="text-sm text-gray-600 hover:text-gray-900 {{ request()->routeIs('dashboard') ? 'font-semibold text-gray-900' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('messages.index') }}"
                class="text-sm text-gray-600 hover:text-gray-900 {{ request()->routeIs('messages.*') ? 'font-semibold text-gray-900' : '' }} relative">
                Messages
                @php $msgCount = \App\Models\Message::where('receiver_id', Auth::id())->whereNull('read_at')->count(); @endphp
                @if($msgCount > 0)
                    <span class="absolute -top-2 -right-4 bg-blue-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-tight">{{ $msgCount > 99 ? '99+' : $msgCount }}</span>
                @endif
            </a>
            <a href="{{ route('study-wrapped') }}"
                class="text-sm text-gray-600 hover:text-gray-900 {{ request()->routeIs('study-wrapped') ? 'font-semibold text-gray-900' : '' }}">
                Study Wrapped
            </a>
            <a href="{{ route('feedback.create') }}"
                class="text-sm text-gray-600 hover:text-gray-900">
                Feedback
            </a>
            <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-700">Logout</button>
            </form>
        </div>

        {{-- Hamburger --}}
        <div class="sm:hidden -me-2 flex items-center">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Responsive Navigation --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}"
                class="block py-2 text-sm {{ request()->routeIs('dashboard') ? 'font-semibold text-blue-600' : 'text-gray-600' }}">
                Dashboard
            </a>
            <a href="{{ route('messages.index') }}"
                class="block py-2 text-sm {{ request()->routeIs('messages.*') ? 'font-semibold text-blue-600' : 'text-gray-600' }}">
                Messages
                @php $msgCount = \App\Models\Message::where('receiver_id', Auth::id())->whereNull('read_at')->count(); @endphp
                @if($msgCount > 0)
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold ml-1">{{ $msgCount > 99 ? '99+' : $msgCount }}</span>
                @endif
            </a>
            <a href="{{ route('study-wrapped') }}"
                class="block py-2 text-sm {{ request()->routeIs('study-wrapped') ? 'font-semibold text-blue-600' : 'text-gray-600' }}">
                Study Wrapped
            </a>
            <a href="{{ route('feedback.create') }}"
                class="block py-2 text-sm text-gray-600">
                Feedback
            </a>
        </div>

        <div class="pt-4 pb-2 border-t border-gray-200 px-4">
            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500 mb-2">{{ Auth::user()->email }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-700 py-2">Logout</button>
            </form>
        </div>
    </div>
</nav>
