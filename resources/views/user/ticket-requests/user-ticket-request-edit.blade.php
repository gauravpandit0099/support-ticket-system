@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#101010] text-white">
    <div class="flex min-h-screen">

        <!-- reusable user sidebar -->
        @include('components.user-sidebar')

        {{-- Main content --}}
        <main class="flex-1">
            <div class="mx-auto max-w-5xl px-6 py-10 lg:px-10">

                {{-- Page header --}}
                <div class="mb-8 flex flex-col gap-5 border-b border-white/10 pb-8 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="mb-2 text-sm text-purple-400">
                            My requests
                        </p>
                        <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            Edit ticket request
                        </h1>
                        <p class="mt-2 text-sm text-gray-500">
                            Update your pending support request before it is reviewed.
                        </p>
                    </div>

                    <a href="{{ route('user.ticket-requests.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-white/10 px-5 py-3 text-sm font-semibold text-gray-300 transition hover:border-white/20 hover:bg-white/5 hover:text-white">
                        Back to requests
                    </a>
                </div>

                {{-- Form --}}
                <div class="max-w-3xl">
                    <div class="rounded-2xl border border-white/10 bg-[#151515] p-6 shadow-2xl sm:p-8">

                        <div class="mb-8">
                            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-purple-500/10 text-sm text-purple-400">
                                R
                            </div>
                            <h2 class="text-lg font-semibold text-white">
                                Request details
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Make the required changes to your support request.
                            </p>
                        </div>

                        <form id="ticket-request-edit-form"
                              method="POST"
                              action="{{ route('user.ticket-requests.update', $ticketRequest->id) }}"
                              class="space-y-7">
                            @csrf
                            @method('PUT')

                            {{-- Title --}}
                            <div>
                                <label for="title" class="mb-2 block text-sm font-medium text-gray-300">
                                    Request title
                                </label>
                                <input id="title"
                                       type="text"
                                       name="title"
                                       value="{{ old('title', $ticketRequest->title) }}"
                                       maxlength="255"
                                       required
                                       placeholder="Enter a short description of the issue"
                                       class="w-full rounded-xl border border-white/10 bg-[#101010] px-4 py-3.5 text-sm text-white placeholder-gray-600 outline-none transition focus:border-purple-500/60 focus:ring-2 focus:ring-purple-500/10">
                                @error('title')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="description" class="mb-2 block text-sm font-medium text-gray-300">
                                    Description
                                </label>
                                <textarea id="description"
                                          name="description"
                                          rows="7"
                                          maxlength="5000"
                                          required
                                          placeholder="Describe the issue in detail..."
                                          class="w-full resize-y rounded-xl border border-white/10 bg-[#101010] px-4 py-3.5 text-sm leading-6 text-white placeholder-gray-600 outline-none transition focus:border-purple-500/60 focus:ring-2 focus:ring-purple-500/10">{{ old('description', $ticketRequest->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Priority --}}
                            <div>
                                <label for="priority" class="mb-2 block text-sm font-medium text-gray-300">
                                    Priority
                                </label>
                                <select id="priority"
                                        name="priority"
                                        required
                                        class="w-full rounded-xl border border-white/10 bg-[#101010] px-4 py-3.5 text-sm text-white outline-none transition focus:border-purple-500/60 focus:ring-2 focus:ring-purple-500/10">
                                    <option value="low" @selected(old('priority', $ticketRequest->priority) === 'low') class="bg-[#151515]">
                                        Low
                                    </option>
                                    <option value="medium" @selected(old('priority', $ticketRequest->priority) === 'medium') class="bg-[#151515]">
                                        Medium
                                    </option>
                                    <option value="high" @selected(old('priority', $ticketRequest->priority) === 'high') class="bg-[#151515]">
                                        High
                                    </option>
                                    <option value="urgent" @selected(old('priority', $ticketRequest->priority) === 'urgent') class="bg-[#151515]">
                                        Urgent
                                    </option>
                                </select>
                                <p class="mt-2 text-xs text-gray-600">
                                    You can change the priority while the request is still pending.
                                </p>
                                @error('priority')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-col-reverse gap-3 border-t border-white/10 pt-7 sm:flex-row sm:justify-end">
                                <a href="{{ route('user.ticket-requests.index') }}"
                                   class="inline-flex items-center justify-center rounded-xl border border-white/10 px-5 py-3 text-sm font-semibold text-gray-400 transition hover:bg-white/5 hover:text-white">
                                    Cancel
                                </a>
                                <button id="update-ticket-button"
                                        type="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-bold text-black transition hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-white/30 disabled:cursor-not-allowed disabled:opacity-60">
                                    <svg id="update-ticket-loader"
                                         class="hidden h-4 w-4 animate-spin"
                                         xmlns="http://www.w3.org/2000/svg"
                                         fill="none"
                                         viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    <span id="update-ticket-text">Save changes</span>
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </main>

    </div>
</div>

<script>
    /* show loader while ticket request is being updated */
    document.getElementById('ticket-request-edit-form').addEventListener('submit', function () {
        const button = document.getElementById('update-ticket-button');
        const loader = document.getElementById('update-ticket-loader');
        const buttonText = document.getElementById('update-ticket-text');

        button.disabled = true;
        loader.classList.remove('hidden');
        buttonText.textContent = 'Saving...';
    });
</script>

@endsection