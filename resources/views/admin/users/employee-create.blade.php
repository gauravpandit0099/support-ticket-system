@extends('layouts.app')

@section('dashboard')

<div class="min-h-screen bg-[#111111] text-white">
    <div class="flex min-h-screen">

        <!-- reusable admin sidebar -->
        @include('components.admin-sidebar')

        <!-- main content -->
        <main class="min-w-0 flex-1">

            <!-- header -->
            <div class="border-b border-[#292929] px-10 py-8">
                <div class="flex items-start justify-between gap-6">
                    <div>
                        <p class="text-sm text-slate-500">
                            Admin portal
                        </p>
                        <h2 class="mt-2 text-3xl font-semibold tracking-tight text-white">
                            Add Employee
                        </h2>
                        <p class="mt-2 text-sm text-slate-500">
                            Create a new employee for your company.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- back -->
                        <a href="{{ route('admin.users.index') }}"
                           class="rounded-lg border border-[#303030] px-5 py-2.5 text-sm font-medium text-slate-300 transition hover:bg-[#222222] hover:text-white">
                            Back to Employees
                        </a>
                    </div>
                </div>
            </div>

            <!-- form content -->
            <div class="px-10 py-8">

                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-red-900/50 bg-red-950/30 px-5 py-4 text-sm text-red-300">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="max-w-2xl rounded-xl border border-[#303030] bg-[#151515] p-7">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <!-- name -->
                        <div class="mb-5">
                            <label for="name" class="mb-2 block text-sm font-medium text-slate-300">
                                Name
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   maxlength="255"
                                   class="w-full rounded-lg border border-[#383838] bg-[#101010] px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-slate-500"
                                   placeholder="Enter employee name">
                        </div>

                        <!-- email -->
                        <div class="mb-5">
                            <label for="email" class="mb-2 block text-sm font-medium text-slate-300">
                                Email
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   maxlength="255"
                                   class="w-full rounded-lg border border-[#383838] bg-[#101010] px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-slate-500"
                                   placeholder="Enter employee email">
                        </div>

                        <!-- mobile -->
                        <div class="mb-5">
                            <label for="mobile" class="mb-2 block text-sm font-medium text-slate-300">
                                Mobile
                            </label>
                            <input type="text"
                                   id="mobile"
                                   name="mobile"
                                   value="{{ old('mobile') }}"
                                   required
                                   maxlength="20"
                                   class="w-full rounded-lg border border-[#383838] bg-[#101010] px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-slate-500"
                                   placeholder="Enter mobile number">
                        </div>

                        <!-- password -->
                        <div class="mb-5">
                            <label for="password" class="mb-2 block text-sm font-medium text-slate-300">
                                Password
                            </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   minlength="8"
                                   maxlength="255"
                                   class="w-full rounded-lg border border-[#383838] bg-[#101010] px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-slate-500"
                                   placeholder="Enter employee password">
                            <p class="mt-2 text-xs text-slate-600">
                                Password must be at least 8 characters.
                            </p>
                        </div>

                        <!-- role -->
                        <div class="mb-7">
                            <label for="role" class="mb-2 block text-sm font-medium text-slate-300">
                                Role
                            </label>
                            <select id="role"
                                    name="role"
                                    required
                                    class="w-full rounded-lg border border-[#383838] bg-[#101010] px-4 py-3 text-sm text-white outline-none transition focus:border-slate-500">
                                <option value="employee" {{ old('role', 'employee') === 'employee' ? 'selected' : '' }}>
                                    Employee
                                </option>
                            </select>
                        </div>

                        <!-- actions -->
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.users.index') }}"
                               class="rounded-lg border border-[#383838] px-5 py-2.5 text-sm font-medium text-slate-400 transition hover:bg-[#252525] hover:text-white">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 transition hover:bg-slate-200">
                                Create Employee
                            </button>
                        </div>

                    </form>
                </div>

            </div>

        </main>

    </div>

</div>

@endsection