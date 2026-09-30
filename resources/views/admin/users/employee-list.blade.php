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
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-slate-500">
                            Admin portal
                        </p>
                        <h2 class="mt-2 text-3xl font-semibold tracking-tight text-white">
                            Users
                        </h2>
                        <p class="mt-2 text-sm text-slate-500">
                            Manage Users in your company.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- add employee -->
                        <a href="{{ route('admin.users.create') }}"
                           class="rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 transition hover:bg-slate-200">
                            Add User
                        </a>
                    </div>
                </div>
            </div>

            <!-- page content -->
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
                        Users Directory
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        View and manage Users in your organization.
                    </p>
                </div>

                <!-- employee table -->
                <div class="overflow-hidden rounded-xl border border-[#303030] bg-[#151515]">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-[#303030]">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Employee
                                    </th>
                                    <th class="px-6 py-4 text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Email
                                    </th>
                                    <th class="px-6 py-4 text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Mobile
                                    </th>
                                    <th class="px-6 py-4 text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Role
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-[#292929]">
                                @forelse($users as $user)
                                    <tr class="transition hover:bg-[#1d1d1d]">

                                        <!-- employee -->
                                        <td class="px-6 py-5">
                                            <div>
                                                <p class="font-medium text-white">
                                                    {{ $user->name }}
                                                </p>
                                                <p class="mt-1 text-xs text-slate-500">
                                                    #{{ $user->id }}
                                                </p>
                                            </div>
                                        </td>

                                        <!-- email -->
                                        <td class="px-6 py-5">
                                            <p class="text-sm text-slate-400">
                                                {{ $user->email }}
                                            </p>
                                        </td>

                                        <!-- mobile -->
                                        <td class="px-6 py-5">
                                            <p class="text-sm text-slate-400">
                                                {{ $user->mobile }}
                                            </p>
                                        </td>

                                        <!-- role -->
                                        <td class="px-6 py-5">
                                            <span class="inline-flex rounded-full border border-[#383838] bg-[#202020] px-3 py-1 text-xs text-slate-300">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>

                                        <!-- action -->
                                        <td class="px-6 py-5 text-right">
                                            @if($user->role === 'employee')
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                   class="rounded-lg border border-[#383838] px-4 py-2 text-xs font-semibold text-slate-300 transition hover:bg-[#252525] hover:text-white">
                                                    Edit
                                                </a>
                                            @else
                                                <span class="text-xs text-slate-600">
                                                    Admin
                                                </span>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <p class="text-sm font-medium text-slate-400">
                                                No Users found.
                                            </p>
                                            <p class="mt-1 text-xs text-slate-600">
                                                Users added to your company will appear here.
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

@endsection