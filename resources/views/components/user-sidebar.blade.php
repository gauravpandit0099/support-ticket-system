<!-- user sidebar -->
<aside class="sticky top-0 hidden h-screen w-64 shrink-0 border-r border-white/10 bg-[#151515] lg:flex lg:flex-col">

    <!-- brand -->
    <div class="border-b border-white/10 px-6 py-5">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-sm font-bold text-gray-900">
                ST
            </div>
            <div>
                <p class="text-sm font-semibold text-white">
                    Support
                </p>
                <p class="text-xs text-gray-500">
                    Employee Portal
                </p>
            </div>
        </div>
    </div>

    <!-- navigation -->
    <nav class="flex-1 px-4 py-6">

        <p class="mb-4 px-3 text-[11px] font-medium uppercase tracking-wider text-gray-500">
            Workspace
        </p>

        <div class="space-y-2">

            <!-- dashboard -->
            <a href="{{ route('user.dashboard') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('user.dashboard') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg text-xs {{ request()->routeIs('user.dashboard') ? 'bg-white/10 text-white' : 'bg-white/5 text-gray-400' }}">
                    D
                </span>
                Dashboard
            </a>

            <!-- ticket requests -->
            <a href="{{ route('user.ticket-requests.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('user.ticket-requests.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg text-xs {{ request()->routeIs('user.ticket-requests.*') ? 'bg-purple-500/20 text-purple-300' : 'bg-white/5 text-gray-400' }}">
                    R
                </span>
                Raise Ticket Request
            </a>

            <!-- assigned tickets -->
            <a href="{{ route('user.tickets.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('user.tickets.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg text-xs {{ request()->routeIs('user.tickets.*') ? 'bg-emerald-500/20 text-emerald-300' : 'bg-white/5 text-gray-400' }}">
                    T
                </span>
                Assigned Tickets
            </a>

            <!-- logout -->
            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left text-sm font-medium text-gray-400 transition hover:bg-white/5 hover:text-white">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-xs text-gray-400">
                        L
                    </span>
                    Logout
                </button>
            </form>

        </div>

    </nav>

    <!-- employee profile -->
    <div class="border-t border-white/10 p-4">
        <div class="flex items-center gap-3 rounded-xl bg-white/5 px-3 py-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-800 text-sm font-semibold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-white">
                    {{ auth()->user()->name }}
                </p>
                <p class="truncate text-xs text-gray-500">
                    {{ auth()->user()->email }}
                </p>
            </div>
        </div>
    </div>

</aside>

<!-- mobile top bar -->
<div class="fixed left-0 right-0 top-0 z-40 border-b border-white/10 bg-[#151515] px-4 py-4 lg:hidden">
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-xs font-bold text-black">
                ST
            </div>
            <div>
                <p class="font-semibold text-white">
                    Support
                </p>
                <p class="text-[11px] text-gray-500">
                    Employee Portal
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-400 transition hover:text-white">
                Logout
            </button>
        </form>

    </div>
</div>