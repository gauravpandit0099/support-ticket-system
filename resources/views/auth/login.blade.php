@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#101010] px-6 py-12 text-white">
    <div class="mx-auto flex min-h-[calc(100vh-6rem)] max-w-md items-center justify-center">
        <div class="w-full">

            {{-- logo / heading --}}
            <div class="mb-8 text-center">
                <div class="mx-auto mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-white text-sm font-bold text-gray-900">
                    ST
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-white">
                    Support Ticket System
                </h1>
                <p class="mt-2 text-sm text-gray-400">
                    Sign in to manage your support requests
                </p>
            </div>

            {{-- login form --}}
            <div class="rounded-2xl border border-white/10 bg-[#151515] p-7 shadow-2xl">

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-white">
                        Welcome back
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Enter your credentials to continue.
                    </p>
                </div>

                <form id="login-form"
                      method="POST"
                      action="{{ route('login.authenticate') }}"
                      class="space-y-5">
                    @csrf

                    {{-- email --}}
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-300">
                            Email Address
                        </label>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               autocomplete="email"
                               autofocus
                               placeholder="Enter your email"
                               class="w-full rounded-lg border border-white/10 bg-[#101010] px-4 py-3 text-sm text-white placeholder-gray-600 outline-none transition focus:border-white/30 focus:ring-2 focus:ring-white/5">
                        @error('email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- password --}}
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-300">
                            Password
                        </label>
                        <input id="password"
                               type="password"
                               name="password"
                               autocomplete="current-password"
                               placeholder="Enter your password"
                               class="w-full rounded-lg border border-white/10 bg-[#101010] px-4 py-3 text-sm text-white placeholder-gray-600 outline-none transition focus:border-white/30 focus:ring-2 focus:ring-white/5">
                        @error('password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- submit --}}
                    <button id="login-button"
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-semibold text-gray-900 transition hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-white/30 disabled:cursor-not-allowed disabled:opacity-60">
                        <svg id="login-loader"
                             class="hidden h-4 w-4 animate-spin"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span id="login-button-text">Sign In</span>
                    </button>

                </form>
            </div>

            {{-- footer --}}
            <p class="mt-6 text-center text-xs text-gray-600">
                Secure employee and admin support portal
            </p>

        </div>
    </div>
</div>

<script>
    /* show loader while login request is being submitted */
    document.getElementById('login-form').addEventListener('submit', function () {
        const button = document.getElementById('login-button');
        const loader = document.getElementById('login-loader');
        const buttonText = document.getElementById('login-button-text');

        button.disabled = true;
        loader.classList.remove('hidden');
        buttonText.textContent = 'Signing in...';
    });
</script>

@endsection