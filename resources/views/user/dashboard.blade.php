@extends('layouts.app')

@section('dashboard')

<div class="h-screen overflow-hidden bg-[#111111] text-white">
    <div class="flex h-screen overflow-hidden">

        <!-- reusable user sidebar -->
        @include('components.user-sidebar')

        <!-- main content -->
        <main class="h-screen min-w-0 flex-1 overflow-hidden">
            <div class="mx-auto h-full max-w-7xl overflow-hidden px-5 pt-24 sm:px-8 lg:px-10 lg:pt-7">

                <!-- heading -->
                <div class="flex items-start justify-between border-b border-white/10 pb-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Employee portal
                        </p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            Welcome back, {{ auth()->user()->name }}
                        </h1>
                        <p class="mt-1.5 text-sm text-gray-500">
                            Manage your support requests and assigned tickets from one place.
                        </p>
                    </div>

                    <!-- logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg border border-white/10 px-5 py-3 text-sm font-semibold text-gray-300 transition hover:bg-white/5 hover:text-white">
                            Logout
                        </button>
                    </form>
                </div>

                <!-- statistics -->
                <div class="mt-6 grid gap-4 md:grid-cols-3">

                    <div class="rounded-2xl border border-white/5 bg-[#161616] p-5">
                        <p class="text-sm font-medium text-gray-500">
                            Open Requests
                        </p>
                        <p class="mt-2 text-3xl font-bold text-white">
                            {{ $openRequests }}
                        </p>
                        <p class="mt-1 text-xs text-gray-600">
                            Your pending requests
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/5 bg-[#161616] p-5">
                        <p class="text-sm font-medium text-gray-500">
                            Assigned to You
                        </p>
                        <p class="mt-2 text-3xl font-bold text-white">
                            {{ $assignedTickets }}
                        </p>
                        <p class="mt-1 text-xs text-gray-600">
                            Tickets currently assigned
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/5 bg-[#161616] p-5">
                        <p class="text-sm font-medium text-gray-500">
                            Resolved
                        </p>
                        <p class="mt-2 text-3xl font-bold text-white">
                            {{ $resolvedTickets }}
                        </p>
                        <p class="mt-1 text-xs text-gray-600">
                            Your resolved tickets
                        </p>
                    </div>

                </div>

                <!-- workspace -->
                <div class="mt-6">
                    <div class="mb-3">
                        <p class="text-sm font-semibold text-gray-400">
                            Workspace
                        </p>
                        <p class="mt-1 text-sm text-gray-600">
                            Quickly access your support work.
                        </p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">

                        <!-- requests -->
                        <a href="{{ route('user.ticket-requests.index') }}"
                           class="group rounded-2xl border border-white/10 bg-[#151515] p-6 transition hover:border-white/20 hover:bg-[#181818]">
                            <div class="flex items-start justify-between">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#24195c] text-sm font-semibold text-purple-300">
                                    R
                                </div>
                                <span class="text-xl text-gray-600 transition group-hover:translate-x-1 group-hover:text-white">
                                    →
                                </span>
                            </div>
                            <h2 class="mt-5 text-xl font-semibold text-white">
                                My Ticket Requests
                            </h2>
                            <p class="mt-1.5 max-w-md text-sm leading-6 text-gray-500">
                                Track the support requests you have submitted and edit requests that are still pending.
                            </p>
                            <p class="mt-4 text-sm font-semibold text-gray-300">
                                View Requests →
                            </p>
                        </a>

                        <!-- assigned tickets -->
                        <a href="{{ route('user.tickets.index') }}"
                           class="group rounded-2xl border border-white/10 bg-[#151515] p-6 transition hover:border-white/20 hover:bg-[#181818]">
                            <div class="flex items-start justify-between">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#003c32] text-sm font-semibold text-emerald-300">
                                    T
                                </div>
                                <span class="text-xl text-gray-600 transition group-hover:translate-x-1 group-hover:text-white">
                                    →
                                </span>
                            </div>
                            <h2 class="mt-5 text-xl font-semibold text-white">
                                Assigned Tickets
                            </h2>
                            <p class="mt-1.5 max-w-md text-sm leading-6 text-gray-500">
                                View tickets assigned to you and update their progress.
                            </p>
                            <p class="mt-4 text-sm font-semibold text-gray-300">
                                View Assigned Tickets →
                            </p>
                        </a>

                    </div>
                </div>

            </div>
        </main>

    </div>
</div>

@endsection