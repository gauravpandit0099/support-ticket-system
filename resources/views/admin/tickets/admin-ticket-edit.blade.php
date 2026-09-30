@extends('layouts.app')

@section('dashboard')
    <div class="min-h-screen bg-[#111111] text-white">
        <div class="flex min-h-screen">

            <!-- reusable admin sidebar -->
            @include('components.admin-sidebar')

            {{-- Main content --}}
            <main class="min-w-0 flex-1">

                {{-- Header --}}
                <header class="flex items-center justify-between border-b border-white/10 px-6 py-5 sm:px-8">
                    <div>
                        <h2 class="text-xl font-semibold tracking-tight">
                            Edit Ticket
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Update ticket information
                        </p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-lg border border-white/10 px-4 py-2 text-sm font-medium text-gray-300 transition hover:bg-white/5 hover:text-white">
                            Logout
                        </button>
                    </form>
                </header>

                {{-- Content --}}
                <div class="px-6 py-8 sm:px-8">

                    {{-- Flash messages --}}
                    @if(session('success'))
                        <div class="mb-6 rounded-lg border border-green-500/20 bg-green-500/10 px-4 py-3 text-sm text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-400">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-400">
                            <ul class="space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="max-w-3xl">

                        {{-- Back --}}
                        <a href="{{ route('admin.tickets.index') }}"
                           class="mb-6 inline-flex items-center text-sm text-gray-400 transition hover:text-white">
                            ← Back to Support Tickets
                        </a>

                        {{-- Ticket information --}}
                        <div class="rounded-xl border border-white/10 bg-[#151515] p-6 sm:p-8">

                            <div class="mb-7">
                                <p class="text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Ticket #{{ $ticket->id }}
                                </p>
                                <h3 class="mt-2 text-lg font-semibold text-white">
                                    Update Ticket
                                </h3>
                            </div>

                            <form method="POST"
                                  action="{{ route('admin.tickets.update', $ticket->id) }}"
                                  class="space-y-6">
                                @csrf
                                @method('PUT')

                                {{-- Title --}}
                                <div>
                                    <label for="title" class="mb-2 block text-sm font-medium text-gray-300">
                                        Title
                                    </label>
                                    <input id="title"
                                           type="text"
                                           name="title"
                                           value="{{ old('title', $ticket->title) }}"
                                           maxlength="255"
                                           required
                                           class="w-full rounded-lg border border-white/10 bg-[#111111] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-600 focus:border-white/30"
                                           placeholder="Enter ticket title">
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
                                              class="w-full resize-none rounded-lg border border-white/10 bg-[#111111] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-600 focus:border-white/30"
                                              placeholder="Enter ticket description">{{ old('description', $ticket->description) }}</textarea>
                                </div>

                                {{-- Priority --}}
                                <div>
                                    <label for="priority" class="mb-2 block text-sm font-medium text-gray-300">
                                        Priority
                                    </label>
                                    <select id="priority"
                                            name="priority"
                                            required
                                            class="w-full rounded-lg border border-white/10 bg-[#111111] px-4 py-3 text-sm text-white outline-none transition focus:border-white/30">
                                        <option value="low" @selected(old('priority', $ticket->priority) === 'low')>
                                            Low
                                        </option>
                                        <option value="medium" @selected(old('priority', $ticket->priority) === 'medium')>
                                            Medium
                                        </option>
                                        <option value="high" @selected(old('priority', $ticket->priority) === 'high')>
                                            High
                                        </option>
                                        <option value="urgent" @selected(old('priority', $ticket->priority) === 'urgent')>
                                            Urgent
                                        </option>
                                    </select>
                                </div>

                                {{-- Current status --}}
                                <div class="rounded-lg border border-white/10 bg-white/[0.02] px-4 py-3">
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="text-sm text-gray-400">
                                            Current Status
                                        </span>
                                        <span class="text-sm font-medium text-white">
                                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                                    <a href="{{ route('admin.tickets.index') }}"
                                       class="rounded-lg border border-white/10 px-5 py-3 text-center text-sm font-medium text-gray-300 transition hover:bg-white/5 hover:text-white">
                                        Cancel
                                    </a>
                                    <button type="submit"
                                            class="rounded-lg bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-200">
                                        Save Changes
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection