<nav x-data="{ open: false }" class="bg-white/90 backdrop-blur-md border-b border-gray-200/60 sticky top-0 z-40">
    <div class="px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg p-1.5 shadow-sm group-hover:shadow-md transition-shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Revisor</span>
            </a>
            <span class="text-[11px] bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-2 py-0.5 rounded-full font-semibold ml-1 shadow-sm">Student</span>
            <span class="hidden sm:inline text-xs text-gray-400 ml-2 italic">Built by students for students</span>
        </div>

        <div class="flex items-center gap-5">
            <a href="{{ route('dashboard') }}"
                class="text-sm text-gray-500 hover:text-gray-900 {{ request()->routeIs('dashboard') ? 'font-semibold text-blue-600' : '' }} transition-colors relative group">
                Dashboard
                <span class="absolute -bottom-1 left-0 right-0 h-0.5 bg-blue-600 rounded-full scale-x-0 {{ request()->routeIs('dashboard') ? 'scale-x-100' : 'group-hover:scale-x-100' }} transition-transform origin-left"></span>
            </a>
            <a href="{{ route('messages.index') }}"
                class="text-sm text-gray-500 hover:text-gray-900 {{ request()->routeIs('messages.*') ? 'font-semibold text-blue-600' : '' }} transition-colors relative group">
                Messages
                <span class="absolute -bottom-1 left-0 right-0 h-0.5 bg-blue-600 rounded-full scale-x-0 {{ request()->routeIs('messages.*') ? 'scale-x-100' : 'group-hover:scale-x-100' }} transition-transform origin-left"></span>
                @php $msgCount = \App\Models\Message::where('receiver_id', Auth::id())->whereNull('read_at')->count(); @endphp
                @if($msgCount > 0)
                    <span class="absolute -top-2 -right-4 bg-gradient-to-r from-blue-600 to-blue-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-tight shadow-sm">{{ $msgCount > 99 ? '99+' : $msgCount }}</span>
                @endif
            </a>
            <a href="{{ route('notifications.index') }}"
                class="text-sm text-gray-500 hover:text-gray-900 {{ request()->routeIs('notifications.*') ? 'font-semibold text-blue-600' : '' }} transition-colors relative group">
                Notifications
                <span class="absolute -bottom-1 left-0 right-0 h-0.5 bg-blue-600 rounded-full scale-x-0 {{ request()->routeIs('notifications.*') ? 'scale-x-100' : 'group-hover:scale-x-100' }} transition-transform origin-left"></span>
                @php $reqCount = \App\Models\ConnectRequest::where('receiver_id', Auth::id())->where('status', 'pending')->count(); @endphp
                @if($reqCount > 0)
                    <span class="absolute -top-2 -right-4 bg-gradient-to-r from-amber-500 to-amber-400 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-tight shadow-sm">{{ $reqCount > 99 ? '99+' : $reqCount }}</span>
                @endif
            </a>
            <a href="{{ route('study-wrapped') }}"
                class="text-sm text-gray-500 hover:text-gray-900 {{ request()->routeIs('study-wrapped') ? 'font-semibold text-blue-600' : '' }} transition-colors relative group">
                Study Wrapped
                <span class="absolute -bottom-1 left-0 right-0 h-0.5 bg-blue-600 rounded-full scale-x-0 {{ request()->routeIs('study-wrapped') ? 'scale-x-100' : 'group-hover:scale-x-100' }} transition-transform origin-left"></span>
            </a>
            <a href="{{ route('feedback.create') }}"
                class="text-sm text-gray-500 hover:text-gray-900 transition-colors relative group">
                Feedback
                <span class="absolute -bottom-1 left-0 right-0 h-0.5 bg-blue-600 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
            </a>
            <div class="h-5 w-px bg-gray-200"></div>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 transition-colors group">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center group-hover:shadow-sm transition-shadow">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span class="font-medium">{{ Auth::user()->name }}</span>
            </a>
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
            <a href="{{ route('notifications.index') }}"
                class="block py-2 text-sm {{ request()->routeIs('notifications.*') ? 'font-semibold text-blue-600' : 'text-gray-600' }}">
                Notifications
                @php $reqCount = \App\Models\ConnectRequest::where('receiver_id', Auth::id())->where('status', 'pending')->count(); @endphp
                @if($reqCount > 0)
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-500 text-white text-[10px] font-bold ml-1">{{ $reqCount > 99 ? '99+' : $reqCount }}</span>
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
