@extends('layouts.app')

@section('dashboard')

<div class="min-h-screen bg-[#101010] text-white">
    <div class="flex min-h-screen">

        <!-- reusable admin sidebar -->
        @include('components.admin-sidebar')

        <!-- main content -->
        <main class="min-w-0 flex-1">
            <div class="mx-auto max-w-6xl px-6 py-8 lg:px-10">

                <!-- top section -->
                <div class="flex items-start justify-between gap-6 border-b border-white/10 pb-7">
                    <div>
                        <p class="mb-2 text-xs font-medium text-gray-500">
                            Admin portal
                        </p>
                        <h1 class="text-3xl font-bold tracking-tight text-white">
                            Welcome back, {{ $user->name }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-500">
                            Manage your organization's support requests and tickets.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-lg border border-white/10 px-4 py-2 text-sm font-medium text-gray-300 transition hover:bg-white/5 hover:text-white">
                            Logout
                        </button>
                    </form>
                </div>

                <!-- organization overview -->
                <section class="mt-8">
                    <div class="mb-4">
                        <h2 class="text-base font-semibold text-white">
                            Organization Overview
                        </h2>
                        <p class="mt-1 text-xs text-gray-500">
                            Your administrator account and organization details.
                        </p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">

                        <!-- administrator -->
                        <div class="rounded-xl border border-white/10 bg-[#151515] p-5">
                            <p class="text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                Administrator
                            </p>
                            <div class="mt-4 flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-xs font-bold text-gray-900">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Administrator
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- email -->
                        <div class="rounded-xl border border-white/10 bg-[#151515] p-5">
                            <p class="text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                Email
                            </p>
                            <p class="mt-4 break-all text-sm font-semibold text-white">
                                {{ $user->email }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                Administrator account
                            </p>
                        </div>

                        <!-- organization -->
                        <div class="rounded-xl border border-white/10 bg-[#151515] p-5">
                            <p class="text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                Organization
                            </p>
                            <p class="mt-4 text-sm font-semibold text-white">
                                {{ $user->tenant->name }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                Active organization
                            </p>
                        </div>

                    </div>
                </section>

                <!-- workspace -->
                <section class="mt-8">
                    <div class="mb-4">
                        <h2 class="text-base font-semibold text-white">
                            Workspace
                        </h2>
                        <p class="mt-1 text-xs text-gray-500">
                            Quickly access and manage your organization's support work.
                        </p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">

                        <!-- ticket requests -->
                        <a href="{{ route('admin.ticket-requests.index') }}"
                           class="group rounded-xl border border-white/10 bg-[#151515] p-5 transition hover:border-white/20 hover:bg-[#181818]">
                            <div class="flex items-start justify-between">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-500/20 text-sm text-purple-300">
                                    R
                                </div>
                                <span class="text-sm text-gray-600 transition group-hover:text-gray-300">
                                    →
                                </span>
                            </div>
                            <h3 class="mt-6 text-sm font-semibold text-white">
                                Ticket Requests
                            </h3>
                            <p class="mt-2 text-xs leading-5 text-gray-500">
                                Review employee support requests and take action on pending requests.
                            </p>
                            <p class="mt-5 text-xs font-medium text-gray-300">
                                View Requests →
                            </p>
                        </a>

                        <!-- support tickets -->
                        <a href="{{ route('admin.tickets.index') }}"
                           class="group rounded-xl border border-white/10 bg-[#151515] p-5 transition hover:border-white/20 hover:bg-[#181818]">
                            <div class="flex items-start justify-between">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500/20 text-sm text-emerald-300">
                                    T
                                </div>
                                <span class="text-sm text-gray-600">
                                    →
                                </span>
                            </div>
                            <h3 class="mt-6 text-sm font-semibold text-white">
                                Support Tickets
                            </h3>
                            <p class="mt-2 text-xs leading-5 text-gray-500">
                                Manage created tickets, assign employees, and track ticket progress.
                            </p>
                            <p class="mt-5 text-xs font-medium text-gray-600">
                                Coming next →
                            </p>
                        </a>

                    </div>
                </section>

            </div>
        </main>

    </div>

</div>

@endsection