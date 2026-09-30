@extends('layouts.app')

@section('dashboard')

<div class="h-screen overflow-hidden bg-[#111111] text-white">
    <div class="flex h-full">

        <!-- reusable user sidebar -->
        @include('components.user-sidebar')

        <!-- main content -->
        <main class="min-w-0 flex-1 overflow-y-auto">
            <div class="min-h-full">

                <!-- top section -->
                <div class="border-b border-[#292929] px-8 py-6 lg:px-10">
                    <div class="flex items-center justify-between gap-6">
                        <div class="min-w-0">
                            <p class="text-sm text-slate-500">
                                User portal
                            </p>
                            <h2 class="mt-1 text-3xl font-semibold tracking-tight text-white">
                                Ticket Details
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                View your assigned ticket and update its progress.
                            </p>
                        </div>

                        <!-- back button -->
                        <a href="{{ route('user.tickets.index') }}"
                           class="shrink-0 rounded-lg border border-[#303030] px-5 py-2.5 text-sm font-medium text-slate-300 transition hover:bg-[#222222] hover:text-white">
                            Back to Tickets
                        </a>
                    </div>
                </div>

                <!-- content -->
                <div class="px-8 py-6 lg:px-10">

                    <!-- flash messages -->
                    @if(session('success'))
                        <div class="mb-5 rounded-xl border border-emerald-900/50 bg-emerald-950/30 px-5 py-4 text-sm text-emerald-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-5 rounded-xl border border-red-900/50 bg-red-950/30 px-5 py-4 text-sm text-red-300">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 rounded-xl border border-red-900/50 bg-red-950/30 px-5 py-4 text-sm text-red-300">
                            <ul class="space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- ticket information -->
                    <div class="rounded-xl border border-[#303030] bg-[#151515]">

                        <!-- ticket header -->
                        <div class="border-b border-[#303030] px-6 py-5">
                            <div class="flex items-center justify-between gap-6">
                                <div class="min-w-0">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">
                                        Ticket
                                    </p>
                                    <h3 class="mt-2 truncate text-xl font-semibold text-white">
                                        {{ $ticket->title }}
                                    </h3>
                                </div>
                                <span class="shrink-0 inline-flex rounded-full border border-[#383838] bg-[#202020] px-3 py-1 text-xs text-slate-300">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                        </div>

                        <!-- ticket details -->
                        <div class="grid gap-6 px-6 py-6 md:grid-cols-2">

                            <!-- description -->
                            <div class="md:col-span-2">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                    Description
                                </p>
                                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-300">
                                    {{ $ticket->description }}
                                </p>
                            </div>

                            <!-- priority -->
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                    Priority
                                </p>
                                <span class="mt-2 inline-flex rounded-full border border-[#383838] bg-[#202020] px-3 py-1 text-xs text-slate-300">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </div>

                            <!-- created by -->
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                    Created By
                                </p>
                                <p class="mt-2 text-sm text-slate-300">
                                    {{ $ticket->creator?->name ?? 'N/A' }}
                                </p>
                            </div>

                            <!-- assigned to -->
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                    Assigned To
                                </p>
                                <p class="mt-2 text-sm text-slate-300">
                                    {{ $ticket->assignee?->name ?? 'N/A' }}
                                </p>
                            </div>

                            <!-- created date -->
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                    Created
                                </p>
                                <p class="mt-2 text-sm text-slate-300">
                                    {{ $ticket->created_at?->format('d M Y, h:i A') }}
                                </p>
                            </div>

                        </div>
                    </div>

                    <!-- status update -->
                    <div class="mt-6 rounded-xl border border-[#303030] bg-[#151515]">
                        <div class="border-b border-[#303030] px-6 py-5">
                            <h3 class="text-lg font-semibold text-white">
                                Update Status
                            </h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Update the current progress of this ticket.
                            </p>
                        </div>

                        @if($ticket->status === 'resolved')

                            <!-- resolved ticket status is locked -->
                            <div class="px-6 py-6">
                                <div class="max-w-md">
                                    <label class="mb-2 block text-sm font-medium text-slate-300">
                                        Status
                                    </label>
                                    <div class="w-full rounded-lg border border-[#383838] bg-[#202020] px-4 py-3 text-sm text-slate-500">
                                        Resolved
                                    </div>
                                </div>
                                <p class="mt-4 text-xs text-slate-600">
                                    This ticket has been resolved and can no longer be updated.
                                </p>
                            </div>

                        @else

                            <form method="POST"
                                  action="{{ route('user.tickets.status', $ticket->id) }}"
                                  class="px-6 py-6">
                                @csrf
                                @method('PUT')

                                <div class="max-w-md">
                                    <label for="status" class="mb-2 block text-sm font-medium text-slate-300">
                                        Status
                                    </label>
                                    <select name="status"
                                            id="status"
                                            required
                                            class="w-full rounded-lg border border-[#383838] bg-[#101010] px-4 py-3 text-sm text-white outline-none transition focus:border-slate-500">
                                        <option value="open" @selected($ticket->status === 'open')>
                                            Open
                                        </option>
                                        <option value="in_progress" @selected($ticket->status === 'in_progress')>
                                            In Progress
                                        </option>
                                        <option value="resolved" @selected($ticket->status === 'resolved')>
                                            Resolved
                                        </option>
                                    </select>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="submit"
                                            class="rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 transition hover:bg-slate-200">
                                        Update Status
                                    </button>
                                </div>

                            </form>

                        @endif

                    </div>

                    <!-- bottom spacing -->
                    <div class="h-8"></div>

                </div>

            </div>
        </main>

    </div>
</div>

@endsection