<!-- admin sidebar -->
<aside class="sticky top-0 hidden h-screen w-64 shrink-0 border-r border-white/10 bg-[#151515] lg:flex lg:flex-col">

    <!-- brand -->
    <div class="shrink-0 border-b border-white/10 px-6 py-5">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-sm font-bold text-gray-900">
                ST
            </div>
            <div>
                <p class="text-sm font-semibold text-white">
                    Support
                </p>
                <p class="text-xs text-gray-500">
                    Admin Portal
                </p>
            </div>
        </div>
    </div>

    <!-- navigation -->
    <nav class="min-h-0 flex-1 overflow-y-auto px-4 py-6">

        <p class="mb-4 px-3 text-[11px] font-medium uppercase tracking-wider text-gray-500">
            Workspace
        </p>

        <div class="space-y-2">

            <!-- dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/10 text-xs">
                    D
                </span>
                Dashboard
            </a>

            <!-- ticket requests -->
            <a href="{{ route('admin.ticket-requests.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('admin.ticket-requests.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-500/20 text-xs text-purple-300">
                    R
                </span>
                Ticket Requests
            </a>

            <!-- support tickets -->
            <a href="{{ route('admin.tickets.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('admin.tickets.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-500/20 text-xs text-emerald-300">
                    T
                </span>
                Support Tickets
            </a>

            <!-- user management -->
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-500/20 text-xs text-blue-300">
                    U
                </span>
                User Management
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

    <!-- admin profile -->
    <div class="shrink-0 border-t border-white/10 p-4">
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
                    Admin Portal
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