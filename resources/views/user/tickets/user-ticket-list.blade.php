@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#111111] text-white">
    <div class="flex min-h-screen">

        <!-- reusable user sidebar -->
        @include('components.user-sidebar')

        <!-- mobile top bar -->
        <div class="fixed left-0 right-0 top-0 z-40 border-b border-white/10 bg-[#151515] px-4 py-4 lg:hidden">
            <div class="flex items-center justify-between">

                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3">
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
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-400 transition hover:text-white">
                        Logout
                    </button>
                </form>

            </div>
        </div>

        <!-- main content -->
        <main class="min-w-0 flex-1">
            <div class="mx-auto max-w-7xl px-5 pb-10 pt-24 sm:px-8 lg:px-10 lg:pt-10">

                <!-- page heading -->
                <div class="flex flex-col gap-5 border-b border-white/10 pb-8 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            User portal
                        </p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            Assigned Tickets
                        </h1>
                        <p class="mt-2 text-sm text-gray-500">
                            View tickets assigned to you and track their progress.
                        </p>
                    </div>
                </div>

                <!-- flash messages -->
                @if(session('success'))
                    <div class="mt-6 rounded-xl border border-emerald-900/50 bg-emerald-950/30 px-5 py-4 text-sm text-emerald-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mt-6 rounded-xl border border-red-900/50 bg-red-950/30 px-5 py-4 text-sm text-red-300">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mt-6 rounded-xl border border-red-900/50 bg-red-950/30 px-5 py-4 text-sm text-red-300">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($tickets->isEmpty())

                    <!-- empty state -->
                    <div class="mt-8 rounded-2xl border border-white/10 bg-[#151515] px-6 py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#003c32] text-sm font-semibold text-emerald-300">
                            T
                        </div>
                        <h2 class="mt-6 text-xl font-semibold text-white">
                            No assigned tickets
                        </h2>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                            Tickets assigned to you by your administrator will appear here.
                        </p>
                    </div>

                @else

                    <!-- tickets -->
                    <div class="mt-8">
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-white">
                                Your Assigned Tickets
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Review the status and details of tickets assigned to you.
                            </p>
                        </div>

                        <!-- desktop table -->
                        <div class="hidden overflow-hidden rounded-2xl border border-white/10 bg-[#151515] md:block">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="border-b border-white/10 bg-white/[0.02]">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Ticket
                                            </th>
                                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Priority
                                            </th>
                                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Status
                                            </th>
                                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Created
                                            </th>
                                            <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-white/5">
                                        @foreach($tickets as $ticket)
                                            <tr class="transition hover:bg-white/[0.02]">

                                                <!-- ticket -->
                                                <td class="px-6 py-5">
                                                    <div class="max-w-sm">
                                                        <p class="text-sm font-semibold text-white">
                                                            {{ $ticket->title }}
                                                        </p>
                                                        <p class="mt-1 max-w-md truncate text-sm text-gray-600">
                                                            {{ $ticket->description }}
                                                        </p>
                                                    </div>
                                                </td>

                                                <!-- priority -->
                                                <td class="px-6 py-5">
                                                    @php
                                                        $priorityClasses = match($ticket->priority) {
                                                            'urgent' => 'bg-red-500/10 text-red-300 border-red-500/20',
                                                            'high' => 'bg-orange-500/10 text-orange-300 border-orange-500/20',
                                                            'medium' => 'bg-yellow-500/10 text-yellow-300 border-yellow-500/20',
                                                            default => 'bg-gray-500/10 text-gray-300 border-gray-500/20',
                                                        };
                                                    @endphp
                                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold capitalize {{ $priorityClasses }}">
                                                        {{ $ticket->priority }}
                                                    </span>
                                                </td>

                                                <!-- status -->
                                                <td class="px-6 py-5">
                                                    @php
                                                        $statusClasses = match($ticket->status) {
                                                            'resolved' => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20',
                                                            'in_progress' => 'bg-blue-500/10 text-blue-300 border-blue-500/20',
                                                            default => 'bg-yellow-500/10 text-yellow-300 border-yellow-500/20',
                                                        };
                                                    @endphp
                                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold capitalize {{ $statusClasses }}">
                                                        {{ str_replace('_', ' ', $ticket->status) }}
                                                    </span>
                                                </td>

                                                <!-- created -->
                                                <td class="whitespace-nowrap px-6 py-5 text-sm text-gray-500">
                                                    {{ $ticket->created_at?->format('d M Y, h:i A') }}
                                                </td>

                                                <!-- action -->
                                                <td class="whitespace-nowrap px-6 py-5 text-right">
                                                    <a href="{{ route('user.tickets.show', $ticket->id) }}"
                                                       class="inline-flex rounded-lg bg-white px-4 py-2 text-xs font-semibold text-black transition hover:bg-gray-200">
                                                        View Ticket
                                                    </a>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- mobile cards -->
                        <div class="space-y-4 md:hidden">
                            @foreach($tickets as $ticket)
                                @php
                                    $priorityClasses = match($ticket->priority) {
                                        'urgent' => 'bg-red-500/10 text-red-300 border-red-500/20',
                                        'high' => 'bg-orange-500/10 text-orange-300 border-orange-500/20',
                                        'medium' => 'bg-yellow-500/10 text-yellow-300 border-yellow-500/20',
                                        default => 'bg-gray-500/10 text-gray-300 border-gray-500/20',
                                    };

                                    $statusClasses = match($ticket->status) {
                                        'resolved' => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20',
                                        'in_progress' => 'bg-blue-500/10 text-blue-300 border-blue-500/20',
                                        default => 'bg-yellow-500/10 text-yellow-300 border-yellow-500/20',
                                    };
                                @endphp

                                <div class="rounded-2xl border border-white/10 bg-[#151515] p-5">

                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <h3 class="truncate text-sm font-semibold text-white">
                                                {{ $ticket->title }}
                                            </h3>
                                            <p class="mt-1 text-xs text-gray-600">
                                                {{ $ticket->created_at?->format('d M Y, h:i A') }}
                                            </p>
                                        </div>
                                        <span class="shrink-0 rounded-full border px-2.5 py-1 text-xs font-semibold capitalize {{ $statusClasses }}">
                                            {{ str_replace('_', ' ', $ticket->status) }}
                                        </span>
                                    </div>

                                    <p class="mt-4 text-sm leading-6 text-gray-500">
                                        {{ $ticket->description }}
                                    </p>

                                    <div class="mt-5 flex items-center justify-between border-t border-white/5 pt-4">
                                        <span class="text-xs font-semibold capitalize text-gray-500">
                                            Priority: {{ $ticket->priority }}
                                        </span>
                                        <a href="{{ route('user.tickets.show', $ticket->id) }}"
                                           class="rounded-lg bg-white px-3 py-2 text-xs font-semibold text-black transition hover:bg-gray-200">
                                            View Ticket
                                        </a>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                    </div>

                @endif

            </div>
        </main>

    </div>
</div>

@endsection