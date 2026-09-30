@extends('layouts.app')

@section('dashboard')

<div class="min-h-screen bg-[#111111] text-white">

    <!-- page layout -->
    <div class="flex min-h-screen">

        <!-- admin sidebar -->
        @include('components.admin-sidebar')

        <!-- main content -->
        <main class="min-w-0 flex-1">

            <!-- top section -->
            <div class="border-b border-[#292929] px-10 py-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-slate-500">
                            Admin portal
                        </p>
                        <h2 class="mt-2 text-3xl font-semibold tracking-tight text-white">
                            Support Tickets
                        </h2>
                        <p class="mt-2 text-sm text-slate-500">
                            Manage created tickets, assign employees, and track ticket progress.
                        </p>
                    </div>
                </div>
            </div>

            <!-- ticket content -->
            <div class="px-10 py-8">

                <!-- flash messages -->
                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-900/50 bg-emerald-950/30 px-5 py-4 text-sm text-emerald-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-xl border border-red-900/50 bg-red-950/30 px-5 py-4 text-sm text-red-300">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-red-900/50 bg-red-950/30 px-5 py-4 text-sm text-red-300">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- section heading -->
                <div class="mb-5">
                    <h3 class="text-lg font-semibold text-white">
                        Ticket Overview
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Review tickets and assign them to employees in your organization.
                    </p>
                </div>

                <!-- ticket table -->
                <div class="overflow-hidden rounded-xl border border-[#303030] bg-[#151515]">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-[#303030]">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Ticket
                                    </th>
                                    <th class="px-6 py-4 text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Created By
                                    </th>
                                    <th class="px-6 py-4 text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Priority
                                    </th>
                                    <th class="px-6 py-4 text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Status
                                    </th>
                                    <th class="px-6 py-4 text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Assigned To
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-[#292929]">
                                @forelse($tickets as $ticket)
                                    <tr class="transition hover:bg-[#1d1d1d]">

                                        <!-- ticket -->
                                        <td class="px-6 py-5">
                                            <div class="max-w-sm">
                                                <p class="font-medium text-white">
                                                    {{ $ticket->title }}
                                                </p>
                                                <p class="mt-1 truncate text-xs text-slate-500">
                                                    {{ $ticket->description }}
                                                </p>
                                            </div>
                                        </td>

                                        <!-- created by -->
                                        <td class="px-6 py-5">
                                            <p class="text-sm text-slate-300">
                                                {{ $ticket->creator?->name ?? 'N/A' }}
                                            </p>
                                        </td>

                                        <!-- priority -->
                                        <td class="px-6 py-5">
                                            <span class="inline-flex rounded-full border border-[#383838] bg-[#202020] px-3 py-1 text-xs text-slate-300">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                        </td>

                                        <!-- status -->
                                        <td class="px-6 py-5">
                                            <span class="inline-flex rounded-full border border-[#383838] bg-[#202020] px-3 py-1 text-xs text-slate-300">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </td>

                                        <!-- assigned to -->
                                        <td class="px-6 py-5">
                                            <p class="text-sm text-slate-300">
                                                {{ $ticket->assignee?->name ?? 'Unassigned' }}
                                            </p>
                                        </td>

                                        <!-- action -->
                                        <td class="px-6 py-5">
                                            <div class="flex items-center justify-end gap-2">
                                                @if($ticket->status === 'resolved')
                                                    <!-- resolved ticket edit is locked -->
                                                    <button type="button"
                                                            disabled
                                                            title="Resolved tickets cannot be edited"
                                                            class="cursor-not-allowed rounded-lg border border-[#383838] bg-[#202020] px-4 py-2 text-xs font-semibold text-slate-600">
                                                        Edit
                                                    </button>
                                                @else
                                                    <!-- edit ticket -->
                                                    <a href="{{ route('admin.tickets.edit', $ticket->id) }}"
                                                       class="rounded-lg border border-[#383838] px-4 py-2 text-xs font-semibold text-slate-300 transition hover:bg-[#252525] hover:text-white">
                                                        Edit
                                                    </a>
                                                @endif

                                                @if($ticket->assigned_to)
                                                    <!-- assigned ticket is locked -->
                                                    <button type="button"
                                                            disabled
                                                            title="Ticket already assigned to {{ $ticket->assignee?->name ?? 'an employee' }}"
                                                            class="cursor-not-allowed rounded-lg border border-[#383838] bg-[#202020] px-4 py-2 text-xs font-semibold text-slate-600">
                                                        Assigned
                                                    </button>
                                                @else
                                                    <!-- assign ticket -->
                                                    <button type="button"
                                                            onclick="openAssignModal({{ $ticket->id }}, '')"
                                                            class="rounded-lg bg-white px-4 py-2 text-xs font-semibold text-slate-900 transition hover:bg-slate-200">
                                                        Assign Ticket
                                                    </button>
                                                @endif
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-16 text-center">
                                            <p class="text-sm font-medium text-slate-400">
                                                No tickets found.
                                            </p>
                                            <p class="mt-1 text-xs text-slate-600">
                                                Created tickets will appear here.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </main>

    </div>

</div>

<!-- assignment modal -->
<div id="assignModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 px-4">
    <div class="w-full max-w-md rounded-xl border border-[#303030] bg-[#181818] p-6">

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-white">
                Assign Ticket
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Select an employee for this ticket.
            </p>
        </div>

        <form id="assignForm" method="POST">
            @csrf
            @method('PUT')

            <label for="assignedTo" class="mb-2 block text-sm font-medium text-slate-300">
                Employee
            </label>

            <select name="assigned_to"
                    id="assignedTo"
                    required
                    class="w-full rounded-lg border border-[#383838] bg-[#101010] px-4 py-3 text-sm text-white outline-none transition focus:border-slate-500">
                <option value="">
                    Select employee
                </option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">
                        {{ $employee->name }} — {{ $employee->email }}
                    </option>
                @endforeach
            </select>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button"
                        onclick="closeAssignModal()"
                        class="rounded-lg border border-[#383838] px-4 py-2 text-sm text-slate-400 transition hover:bg-[#252525] hover:text-white">
                    Cancel
                </button>
                <button type="submit"
                        class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-200">
                    Assign Ticket
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    /* open ticket assignment modal */
    function openAssignModal(ticketId, assignedTo) {
        const modal = document.getElementById('assignModal');
        const form = document.getElementById('assignForm');
        const employeeSelect = document.getElementById('assignedTo');

        form.action = "{{ url('/admin/tickets') }}/" + ticketId + "/assign";
        employeeSelect.value = assignedTo;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    /* close ticket assignment modal */
    function closeAssignModal() {
        const modal = document.getElementById('assignModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

@endsection