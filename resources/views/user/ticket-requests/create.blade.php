@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#101010] text-white">
    <div class="flex min-h-screen">

        <!-- reusable user sidebar -->
        @include('components.user-sidebar')

        {{-- Main content --}}
        <main class="flex-1">
            <div class="mx-auto max-w-6xl px-6 py-10 lg:px-10">

                {{-- Page header --}}
                <div class="mb-8 flex flex-col gap-5 border-b border-white/10 pb-8 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="mb-2 text-sm text-blue-400">
                            New support request
                        </p>
                        <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            Submit a ticket
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-500">
                            Tell your administrator what you need help with. Provide enough detail so the issue can be reviewed quickly.
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
                            <h2 class="text-lg font-semibold text-white">
                                Request details
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Complete the information below to submit your support request.
                            </p>
                        </div>

                        <form id="ticket-request-form"
                              method="POST"
                              action="{{ route('user.ticket-requests.store') }}"
                              class="space-y-7">
                            @csrf

                            {{-- Title --}}
                            <div>
                                <label for="title" class="mb-2 block text-sm font-medium text-gray-300">
                                    Request title
                                </label>
                                <input id="title"
                                       type="text"
                                       name="title"
                                       value="{{ old('title') }}"
                                       maxlength="255"
                                       required
                                       placeholder="e.g. Laptop is unable to connect to Wi-Fi"
                                       class="w-full rounded-xl border border-white/10 bg-[#101010] px-4 py-3.5 text-sm text-white placeholder-gray-600 outline-none transition focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/10">
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
                                          placeholder="Describe the issue, what you have already tried, and any useful details..."
                                          class="w-full resize-y rounded-xl border border-white/10 bg-[#101010] px-4 py-3.5 text-sm leading-6 text-white placeholder-gray-600 outline-none transition focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/10">{{ old('description') }}</textarea>
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
                                        class="w-full rounded-xl border border-white/10 bg-[#101010] px-4 py-3.5 text-sm text-white outline-none transition focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/10">
                                    <option value="" class="bg-[#151515]">
                                        Select priority
                                    </option>
                                    <option value="low" @selected(old('priority') === 'low') class="bg-[#151515]">
                                        Low
                                    </option>
                                    <option value="medium" @selected(old('priority') === 'medium') class="bg-[#151515]">
                                        Medium
                                    </option>
                                    <option value="high" @selected(old('priority') === 'high') class="bg-[#151515]">
                                        High
                                    </option>
                                    <option value="urgent" @selected(old('priority') === 'urgent') class="bg-[#151515]">
                                        Urgent
                                    </option>
                                </select>
                                <p class="mt-2 text-xs text-gray-600">
                                    Choose the priority based on how urgently you need assistance.
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
                                <button id="submit-ticket-button"
                                        type="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-bold text-black transition hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-white/30 disabled:cursor-not-allowed disabled:opacity-60">
                                    <svg id="submit-ticket-loader"
                                         class="hidden h-4 w-4 animate-spin"
                                         xmlns="http://www.w3.org/2000/svg"
                                         fill="none"
                                         viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    <span id="submit-ticket-text">Submit request</span>
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
    /* show loader while ticket request is being submitted */
    document.getElementById('ticket-request-form').addEventListener('submit', function () {
        const button = document.getElementById('submit-ticket-button');
        const loader = document.getElementById('submit-ticket-loader');
        const buttonText = document.getElementById('submit-ticket-text');

        button.disabled = true;
        loader.classList.remove('hidden');
        buttonText.textContent = 'Submitting...';
    });
</script>

@endsection