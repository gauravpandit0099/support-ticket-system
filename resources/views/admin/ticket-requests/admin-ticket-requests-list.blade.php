@extends('layouts.app')

@section('dashboard')

<div class="min-h-screen bg-[#101010] text-white">
    <div class="flex min-h-screen">
        <!-- reusable admin sidebar -->
        @include('components.admin-sidebar')

        <!-- main content -->
        <main class="min-w-0 flex-1">
            <div class="mx-auto max-w-7xl px-6 py-8 lg:px-10">

                <!-- page header -->
                <div class="flex items-start justify-between gap-6 border-b border-white/10 pb-7">
                    <div>
                        <p class="mb-2 text-xs font-medium text-gray-500">
                            Support requests
                        </p>
                        <h1 class="text-3xl font-bold tracking-tight text-white">
                            Ticket Requests
                        </h1>
                        <p class="mt-2 text-sm text-gray-500">
                            Review support requests submitted by employees.
                        </p>
                    </div>
                </div>

                <!-- requests section -->
                <section class="mt-8">
                    <div class="mb-4">
                        <h2 class="text-base font-semibold text-white">
                            Employee Requests
                        </h2>
                        <p class="mt-1 text-xs text-gray-500">
                            Review and manage requests from employees in your organization.
                        </p>
                    </div>

                    @if($ticketRequests->isEmpty())
                        <div class="rounded-xl border border-white/10 bg-[#151515] px-6 py-12 text-center">
                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-sm text-gray-400">
                                R
                            </div>
                            <p class="mt-4 text-sm font-medium text-white">
                                No ticket requests found
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                Employee support requests will appear here.
                            </p>
                        </div>
                    @else
                        <div class="overflow-hidden rounded-xl border border-white/10 bg-[#151515]">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="border-b border-white/10 bg-white/[0.02]">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                                Request
                                            </th>
                                            <th class="px-6 py-4 text-left text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                                Employee
                                            </th>
                                            <th class="px-6 py-4 text-left text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                                Priority
                                            </th>
                                            <th class="px-6 py-4 text-left text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                                Status
                                            </th>
                                            <th class="px-6 py-4 text-left text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                                Submitted
                                            </th>
                                            <th class="px-6 py-4 text-right text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-white/10">
                                        @foreach($ticketRequests as $ticketRequest)
                                            <tr class="transition hover:bg-white/[0.025]">

                                                <!-- request -->
                                                <td class="px-6 py-5">
                                                    <div class="max-w-xs">
                                                        <p class="truncate text-sm font-semibold text-white">
                                                            {{ $ticketRequest->title }}
                                                        </p>
                                                        <p class="mt-1 truncate text-xs text-gray-500">
                                                            {{ $ticketRequest->description }}
                                                        </p>
                                                    </div>
                                                </td>

                                                <!-- employee -->
                                                <td class="px-6 py-5">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/10 text-[10px] font-semibold text-gray-300">
                                                            {{ strtoupper(substr($ticketRequest->creator->name, 0, 2)) }}
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="truncate text-sm font-medium text-white">
                                                                {{ $ticketRequest->creator->name }}
                                                            </p>
                                                            <p class="truncate text-xs text-gray-500">
                                                                {{ $ticketRequest->creator->email }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- priority -->
                                                <td class="px-6 py-5">
                                                    @php
                                                        $priorityClasses = match ($ticketRequest->priority) {
                                                            'urgent' => 'border-red-500/20 bg-red-500/10 text-red-300',
                                                            'high' => 'border-orange-500/20 bg-orange-500/10 text-orange-300',
                                                            'medium' => 'border-yellow-500/20 bg-yellow-500/10 text-yellow-300',
                                                            default => 'border-white/10 bg-white/5 text-gray-300',
                                                        };
                                                    @endphp
                                                    <span class="{{ $priorityClasses }} inline-flex rounded-full border px-2.5 py-1 text-[11px] font-medium capitalize">
                                                        {{ $ticketRequest->priority }}
                                                    </span>
                                                </td>

                                                <!-- status -->
                                                <td class="px-6 py-5">
                                                    @php
                                                        $statusClasses = match ($ticketRequest->status) {
                                                            'approved' => 'border-emerald-500/20 bg-emerald-500/10 text-emerald-300',
                                                            'rejected' => 'border-red-500/20 bg-red-500/10 text-red-300',
                                                            default => 'border-yellow-500/20 bg-yellow-500/10 text-yellow-300',
                                                        };
                                                    @endphp
                                                    <span class="{{ $statusClasses }} inline-flex rounded-full border px-2.5 py-1 text-[11px] font-medium capitalize">
                                                        {{ $ticketRequest->status }}
                                                    </span>
                                                </td>

                                                <!-- submitted -->
                                                <td class="whitespace-nowrap px-6 py-5">
                                                    <p class="text-xs text-gray-400">
                                                        {{ $ticketRequest->created_at->format('d M Y') }}
                                                    </p>
                                                    <p class="mt-1 text-[11px] text-gray-600">
                                                        {{ $ticketRequest->created_at->format('h:i A') }}
                                                    </p>
                                                </td>

                                                <!-- action -->
                                                <td class="whitespace-nowrap px-6 py-5 text-right">
                                                    @if($ticketRequest->status === 'pending')
                                                        <a href="{{ route('admin.ticket-requests.show', $ticketRequest->id) }}"
                                                           class="inline-flex items-center rounded-lg border border-white/10 px-3 py-2 text-xs font-medium text-gray-300 transition hover:bg-white/10 hover:text-white">
                                                            Review
                                                        </a>
                                                    @else
                                                        <span class="text-xs text-gray-600">
                                                            Reviewed
                                                        </span>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                </section>

            </div>
        </main>
    </div>
</div>

@endsection