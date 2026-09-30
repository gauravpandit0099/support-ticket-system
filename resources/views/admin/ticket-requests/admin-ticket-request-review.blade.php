@extends('layouts.app')

@section('dashboard')

<div class="min-h-screen bg-[#101010] text-white">
    <div class="flex min-h-screen">
        <!-- reusable admin sidebar -->
        @include('components.admin-sidebar')

        <!-- main content -->
        <main class="min-w-0 flex-1">
            <div class="mx-auto max-w-5xl px-6 py-8 lg:px-10">

                <!-- page header -->
                <div class="flex items-start justify-between gap-6 border-b border-white/10 pb-7">
                    <div>
                        <p class="mb-2 text-xs font-medium text-gray-500">
                            Ticket Requests
                        </p>
                        <h1 class="text-3xl font-bold tracking-tight text-white">
                            Review Ticket Request
                        </h1>
                        <p class="mt-2 text-sm text-gray-500">
                            Review the employee's request and decide whether it should be approved or rejected.
                        </p>
                    </div>

                    <a href="{{ route('admin.ticket-requests.index') }}"
                       class="rounded-lg border border-white/10 px-4 py-2 text-sm font-medium text-gray-300 transition hover:bg-white/5 hover:text-white">
                        Back to Requests
                    </a>
                </div>

                <div class="mt-8 grid gap-6 lg:grid-cols-5">

                    <!-- request details -->
                    <section class="lg:col-span-3">
                        <div class="rounded-xl border border-white/10 bg-[#151515]">
                            <div class="border-b border-white/10 px-6 py-5">
                                <h2 class="text-base font-semibold text-white">
                                    Request Details
                                </h2>
                                <p class="mt-1 text-xs text-gray-500">
                                    Information submitted by the employee.
                                </p>
                            </div>

                            <div class="space-y-6 p-6">

                                <!-- title -->
                                <div>
                                    <p class="text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                        Title
                                    </p>
                                    <p class="mt-2 text-sm font-semibold text-white">
                                        {{ $ticketRequest->title }}
                                    </p>
                                </div>

                                <!-- description -->
                                <div>
                                    <p class="text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                        Description
                                    </p>
                                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-300">
                                        {{ $ticketRequest->description }}
                                    </p>
                                </div>

                                <!-- employee -->
                                <div>
                                    <p class="text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                        Employee
                                    </p>
                                    <div class="mt-2 flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 text-xs font-semibold text-gray-300">
                                            {{ strtoupper(substr($ticketRequest->creator->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-white">
                                                {{ $ticketRequest->creator->name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $ticketRequest->creator->email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- priority -->
                                <div>
                                    <p class="text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                        Priority
                                    </p>
                                    @php
                                        $priorityClasses = match ($ticketRequest->priority) {
                                            'urgent' => 'border-red-500/20 bg-red-500/10 text-red-300',
                                            'high' => 'border-orange-500/20 bg-orange-500/10 text-orange-300',
                                            'medium' => 'border-yellow-500/20 bg-yellow-500/10 text-yellow-300',
                                            default => 'border-white/10 bg-white/5 text-gray-300',
                                        };
                                    @endphp
                                    <span class="{{ $priorityClasses }} mt-2 inline-flex rounded-full border px-2.5 py-1 text-[11px] font-medium capitalize">
                                        {{ $ticketRequest->priority }}
                                    </span>
                                </div>

                                <!-- submitted -->
                                <div>
                                    <p class="text-[11px] font-medium uppercase tracking-wider text-gray-500">
                                        Submitted
                                    </p>
                                    <p class="mt-2 text-sm text-gray-300">
                                        {{ $ticketRequest->created_at->format('d M Y, h:i A') }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    </section>

                    <!-- review form -->
                    <section class="lg:col-span-2">
                        <div class="rounded-xl border border-white/10 bg-[#151515]">
                            <div class="border-b border-white/10 px-6 py-5">
                                <h2 class="text-base font-semibold text-white">
                                    Review Decision
                                </h2>
                                <p class="mt-1 text-xs text-gray-500">
                                    Choose how to process this request.
                                </p>
                            </div>

                            <form id="review-ticket-form"
                                  method="POST"
                                  action="{{ route('admin.ticket-requests.review', $ticketRequest->id) }}"
                                  class="p-6">
                                @csrf
                                @method('PUT')

                                <!-- status -->
                                <div>
                                    <label for="status" class="mb-2 block text-sm font-medium text-gray-300">
                                        Decision
                                    </label>
                                    <select id="status"
                                            name="status"
                                            required
                                            class="w-full rounded-lg border border-white/10 bg-[#101010] px-4 py-3 text-sm text-white outline-none transition focus:border-white/30 focus:ring-2 focus:ring-white/10">
                                        <option value="approved" class="bg-[#101010]">Approve Request</option>
                                        <option value="rejected" class="bg-[#101010]">Reject Request</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- rejection reason -->
                                <div id="rejection-reason-wrapper" class="mt-6 hidden">
                                    <label for="rejection_reason" class="mb-2 block text-sm font-medium text-gray-300">
                                        Rejection Reason
                                    </label>
                                    <textarea id="rejection_reason"
                                              name="rejection_reason"
                                              rows="5"
                                              maxlength="5000"
                                              placeholder="Explain why this request is being rejected."
                                              class="w-full resize-y rounded-lg border border-white/10 bg-[#101010] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-600 focus:border-white/30 focus:ring-2 focus:ring-white/10">{{ old('rejection_reason') }}</textarea>
                                    @error('rejection_reason')
                                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- actions -->
                                <div class="mt-6 flex justify-end gap-3 border-t border-white/10 pt-6">
                                    <a href="{{ route('admin.ticket-requests.index') }}"
                                       class="rounded-lg border border-white/10 px-4 py-2.5 text-sm font-medium text-gray-300 transition hover:bg-white/5 hover:text-white">
                                        Cancel
                                    </a>
                                    <button id="review-ticket-button"
                                            type="submit"
                                            class="flex items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-white/20 disabled:cursor-not-allowed disabled:opacity-70">
                                        <svg id="review-ticket-loader"
                                             class="hidden h-4 w-4 animate-spin"
                                             xmlns="http://www.w3.org/2000/svg"
                                             fill="none"
                                             viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                        <span id="review-ticket-text">Submit Review</span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </section>

                </div>
            </div>
        </main>
    </div>
</div>

<script>
    /* toggle rejection reason according to review decision */
    const statusSelect = document.getElementById('status');
    const rejectionReasonWrapper = document.getElementById('rejection-reason-wrapper');
    const rejectionReason = document.getElementById('rejection_reason');

    function toggleRejectionReason() {
        if (statusSelect.value === 'rejected') {
            rejectionReasonWrapper.classList.remove('hidden');
            rejectionReason.required = true;
        } else {
            rejectionReasonWrapper.classList.add('hidden');
            rejectionReason.required = false;
            rejectionReason.value = '';
        }
    }

    statusSelect.addEventListener('change', toggleRejectionReason);
    toggleRejectionReason();

    /* show loader while review is being submitted */
    document.getElementById('review-ticket-form').addEventListener('submit', function () {
        const button = document.getElementById('review-ticket-button');
        const loader = document.getElementById('review-ticket-loader');
        const buttonText = document.getElementById('review-ticket-text');

        button.disabled = true;
        loader.classList.remove('hidden');
        buttonText.textContent = 'Submitting...';
    });
</script>

@endsection