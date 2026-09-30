<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Support Ticket System') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#111111] text-white antialiased">

    <!-- flash messages -->
    @if(session('success') || session('error'))
        <div
            id="flash-message"
            class="fixed right-5 top-5 z-50 max-w-sm rounded-xl border border-white/10 bg-[#1a1a1a] px-5 py-4 text-sm font-medium text-white shadow-xl"
        >
            @if(session('success'))
                <div class="text-green-400">
                    {{ session('success') }}
                </div>
            @else
                <div class="text-red-400">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    @hasSection('dashboard')
        @yield('dashboard')
    @else
        <!-- standard application header -->
        {{-- <header class="border-b border-white/10 bg-[#151515]">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

                <div>
                    <a
                        href="{{ auth()->check()
                            ? (auth()->user()->role === 'admin'
                                ? route('admin.dashboard')
                                : route('user.dashboard'))
                            : route('login') }}"
                        class="text-lg font-bold text-white"
                    >
                        Support Ticket System
                    </a>

                    @auth
                        <p class="mt-0.5 text-xs text-gray-500">
                            {{ auth()->user()->role === 'admin' ? 'Admin Portal' : 'Employee Portal' }}
                        </p>
                    @endauth
                </div>

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="rounded-lg border border-white/10 bg-transparent px-4 py-2 text-sm font-medium text-gray-300 transition hover:border-white/20 hover:bg-white/5 hover:text-white"
                        >
                            Logout
                        </button>
                    </form>
                @endauth

            </div>
        </header> --}}

        @yield('content')
    @endif

    <!-- auto hide flash message -->
    <script>
        setTimeout(() => {
            const flashMessage = document.getElementById('flash-message');

            if (flashMessage) {
                flashMessage.remove();
            }
        }, 3000);
    </script>

</body>
</html>