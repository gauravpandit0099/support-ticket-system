@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#111111] text-white">

    <!-- reusable admin sidebar -->
    @include('components.admin-sidebar')

    <!-- main content -->
    <main class="ml-64 min-h-screen">

        <!-- header -->
        <div class="border-b border-[#252525] px-8 py-7">
            <div>
                <h2 class="text-2xl font-semibold text-white">
                    Edit Employee
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Update employee information.
                </p>
            </div>
        </div>

        <!-- form -->
        <div class="px-8 py-8">
            <div class="max-w-2xl rounded-xl border border-[#252525] bg-[#151515] p-7">

                @if($errors->any())
                    <div class="mb-6 rounded-lg border border-[#383838] bg-[#181818] px-5 py-4">
                        <ul class="space-y-1 text-sm text-slate-400">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- name -->
                    <div class="mb-5">
                        <label for="name" class="mb-2 block text-sm font-medium text-slate-300">
                            Name
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
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
                               value="{{ old('email', $user->email) }}"
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
                               value="{{ old('mobile', $user->mobile) }}"
                               required
                               maxlength="20"
                               class="w-full rounded-lg border border-[#383838] bg-[#101010] px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-slate-500"
                               placeholder="Enter mobile number">
                    </div>

                    <!-- password -->
                    <div class="mb-5">
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-300">
                            New Password
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               minlength="8"
                               maxlength="255"
                               class="w-full rounded-lg border border-[#383838] bg-[#101010] px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-slate-500"
                               placeholder="Leave blank to keep current password">
                        <p class="mt-2 text-xs text-slate-600">
                            Leave blank if you do not want to change the password.
                        </p>
                    </div>

                    <!-- role -->
                    <div class="mb-7">
                        <label for="role" class="mb-2 block text-sm font-medium text-slate-300">
                            Role
                        </label>
                        <select id="role"
                                name="role"
                                disabled
                                class="w-full cursor-not-allowed rounded-lg border border-[#383838] bg-[#202020] px-4 py-3 text-sm text-slate-500 outline-none">
                            <option value="employee" selected>
                                Employee
                            </option>
                        </select>
                        <p class="mt-2 text-xs text-slate-600">
                            Employee roles cannot be changed.
                        </p>
                    </div>

                    <!-- actions -->
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.users.index') }}"
                           class="rounded-lg border border-[#383838] px-5 py-2.5 text-sm font-medium text-slate-400 transition hover:bg-[#252525] hover:text-white">
                            Cancel
                        </a>
                        <button type="submit"
                                class="rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 transition hover:bg-slate-200">
                            Update Employee
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </main>

</div>
@endsection