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
                    <span class="font-semibold">
                        Support
                    </span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-400">
                        Logout
                    </button>
                </form>

            </div>
        </div>

        <!-- main -->
        <main class="min-w-0 flex-1">
            <div class="mx-auto max-w-7xl px-5 pb-10 pt-24 sm:px-8 lg:px-10 lg:pt-10">

                <!-- page heading -->
                <div class="flex flex-col gap-5 border-b border-white/10 pb-8 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Support requests
                        </p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            My Ticket Requests
                        </h1>
                        <p class="mt-2 text-sm text-gray-500">
                            Track the support requests you have submitted.
                        </p>
                    </div>

                    <div class="flex w-full gap-3 sm:w-auto">
                        <a href="{{ route('user.ticket-requests.create') }}"
                           class="flex-1 rounded-xl bg-white px-5 py-3 text-center text-sm font-bold text-black transition hover:bg-gray-200 sm:flex-none">
                            + New Ticket
                        </a>
                    </div>
                </div>

                @if($ticketRequests->isEmpty())

                    <!-- empty state -->
                    <div class="mt-8 rounded-2xl border border-white/10 bg-[#151515] px-6 py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#24195c] text-sm font-semibold text-purple-300">
                            R
                        </div>
                        <h2 class="mt-6 text-xl font-semibold text-white">
                            No ticket requests yet
                        </h2>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                            You haven't submitted any support requests. Create your first request and your administrator can review it.
                        </p>
                        <a href="{{ route('user.ticket-requests.create') }}"
                           class="mt-7 inline-flex rounded-xl bg-white px-5 py-3 text-sm font-bold text-black transition hover:bg-gray-200">
                            + Create Ticket Request
                        </a>
                    </div>

                @else

                    <!-- requests -->
                    <div class="mt-8">
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-white">
                                Your Requests
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Review the status and details of your submitted requests.
                            </p>
                        </div>

                        <!-- desktop table -->
                        <div class="hidden overflow-hidden rounded-2xl border border-white/10 bg-[#151515] md:block">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="border-b border-white/10 bg-white/[0.02]">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Request
                                            </th>
                                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Priority
                                            </th>
                                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Status
                                            </th>
                                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Submitted
                                            </th>
                                            <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-white/5">
                                        @foreach($ticketRequests as $ticketRequest)
                                            <tr class="transition hover:bg-white/[0.02]">

                                                <td class="px-6 py-5">
                                                    <p class="text-sm font-semibold text-white">
                                                        {{ $ticketRequest->title }}
                                                    </p>
                                                    <p class="mt-1 max-w-md truncate text-sm text-gray-600">
                                                        {{ $ticketRequest->description }}
                                                    </p>
                                                </td>

                                                <td class="px-6 py-5">
                                                    @php
                                                        $priorityClasses = match($ticketRequest->priority) {
                                                            'urgent' => 'bg-red-500/10 text-red-300 border-red-500/20',
                                                            'high' => 'bg-orange-500/10 text-orange-300 border-orange-500/20',
                                                            'medium' => 'bg-yellow-500/10 text-yellow-300 border-yellow-500/20',
                                                            default => 'bg-gray-500/10 text-gray-300 border-gray-500/20',
                                                        };
                                                    @endphp
                                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold capitalize {{ $priorityClasses }}">
                                                        {{ $ticketRequest->priority }}
                                                    </span>
                                                </td>

                                                <td class="px-6 py-5">
                                                    @php
                                                        $statusClasses = match($ticketRequest->status) {
                                                            'approved' => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20',
                                                            'rejected' => 'bg-red-500/10 text-red-300 border-red-500/20',
                                                            default => 'bg-yellow-500/10 text-yellow-300 border-yellow-500/20',
                                                        };
                                                    @endphp
                                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold capitalize {{ $statusClasses }}">
                                                        {{ $ticketRequest->status }}
                                                    </span>
                                                </td>

                                                <td class="whitespace-nowrap px-6 py-5 text-sm text-gray-500">
                                                    {{ $ticketRequest->created_at->format('d M Y, h:i A') }}
                                                </td>

                                                <td class="whitespace-nowrap px-6 py-5 text-right">
                                                    @if($ticketRequest->status === 'pending')
                                                        <a href="{{ route('user.ticket-requests.edit', $ticketRequest->id) }}"
                                                           class="inline-flex rounded-lg border border-white/10 px-3 py-2 text-xs font-semibold text-gray-300 transition hover:border-white/20 hover:bg-white/5 hover:text-white">
                                                            Edit
                                                        </a>
                                                    @else
                                                        <span class="text-xs text-gray-700">
                                                            Locked
                                                        </span>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- mobile cards -->
                        <div class="space-y-4 md:hidden">
                            @foreach($ticketRequests as $ticketRequest)
                                <div class="rounded-2xl border border-white/10 bg-[#151515] p-5">

                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <h3 class="truncate text-sm font-semibold text-white">
                                                {{ $ticketRequest->title }}
                                            </h3>
                                            <p class="mt-1 text-xs text-gray-600">
                                                {{ $ticketRequest->created_at->format('d M Y, h:i A') }}
                                            </p>
                                        </div>
                                        <span class="shrink-0 rounded-full border px-2.5 py-1 text-xs font-semibold capitalize {{ match($ticketRequest->status) {
                                            'approved' => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20',
                                            'rejected' => 'bg-red-500/10 text-red-300 border-red-500/20',
                                            default => 'bg-yellow-500/10 text-yellow-300 border-yellow-500/20',
                                        } }}">
                                            {{ $ticketRequest->status }}
                                        </span>
                                    </div>

                                    <p class="mt-4 text-sm leading-6 text-gray-500">
                                        {{ $ticketRequest->description }}
                                    </p>

                                    <div class="mt-5 flex items-center justify-between border-t border-white/5 pt-4">
                                        <span class="text-xs font-semibold capitalize text-gray-500">
                                            Priority: {{ $ticketRequest->priority }}
                                        </span>
                                        @if($ticketRequest->status === 'pending')
                                            <a href="{{ route('user.ticket-requests.edit', $ticketRequest->id) }}"
                                               class="rounded-lg border border-white/10 px-3 py-2 text-xs font-semibold text-gray-300 transition hover:bg-white/5 hover:text-white">
                                                Edit
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-700">
                                                Locked
                                            </span>
                                        @endif
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