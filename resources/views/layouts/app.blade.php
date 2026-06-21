<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FinTrack') - FinTrack</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <style>
        .nav-link { transition: background-color 0.15s, color 0.15s; }
        .nav-link.active { background-color: #4f46e5; color: #fff; }
        #sidebar-nav { overflow-y: auto; scrollbar-width: thin; scrollbar-color: #374151 transparent; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <aside id="sidebar" class="w-64 bg-gray-900 text-white min-h-screen flex flex-col fixed top-0 left-0 z-30 shadow-xl">

        <div class="flex items-center gap-3 px-5 py-5 border-b border-gray-800 flex-shrink-0">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center font-bold text-sm">FT</div>
            <div>
                <a href="/dashboard" class="text-base font-bold text-white leading-none">FinTrack</a>
                <p class="text-xs text-gray-500 mt-0.5">
                    @if(isset($currentUser) && $currentUser->role === 'admin')
                        Admin Panel
                    @else
                        Employee Portal
                    @endif
                </p>
            </div>
        </div>

        <nav id="sidebar-nav" class="flex-1 px-3 py-4 space-y-0.5">

            <a href="/dashboard"
               class="nav-link {{ request()->is('dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/>
                </svg>
                Dashboard
            </a>

            @if(isset($currentUser))

                @if($currentUser->role === 'admin')

                    <div class="pt-5 pb-1.5 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Finance</div>

                    <a href="{{ route('admin.expenses.index') }}"
                       class="nav-link {{ request()->is('admin/expenses*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Expenses
                    </a>

                    <a href="#"
                       class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Transactions
                    </a>

                    <a href="#"
                       class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Invoices
                    </a>

                    <a href="#"
                       class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Clients
                    </a>

                    <a href="#"
                       class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Payroll
                    </a>

                    <div class="pt-5 pb-1.5 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Reports</div>

                    <a href="#"
                       class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Reports
                    </a>

                    <div class="pt-5 pb-1.5 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin</div>

                    <a href="{{ route('admin.users.index') }}"
                       class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Manage Users
                    </a>

                @else

                    <div class="pt-5 pb-1.5 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">My Expenses</div>

                    <a href="{{ route('employee.expenses.create') }}"
                       class="nav-link {{ request()->is('employee/expenses/create') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Submit Expense
                    </a>

                    <a href="{{ route('employee.expenses.index') }}"
                       class="nav-link {{ request()->is('employee/expenses') && !request()->is('*/create') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        My Expenses
                    </a>

                @endif
            @endif
        </nav>

        <div class="flex-shrink-0 px-4 py-4 border-t border-gray-800">
            @if(isset($currentUser))
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 bg-indigo-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                        {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ $currentUser->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ $currentUser->role }}</p>
                    </div>
                </div>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="nav-link w-full text-left text-sm text-gray-400 hover:text-white px-3 py-2 rounded-lg hover:bg-gray-800 flex items-center gap-3">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 ml-64 min-h-screen flex flex-col">

        <header class="bg-white shadow-sm h-16 flex items-center px-8 flex-shrink-0 sticky top-0 z-20">
            <h1 class="text-lg font-semibold text-gray-800">@yield('header', 'FinTrack')</h1>
            <div class="ml-auto flex items-center gap-3">
                @if(isset($currentUser))
                    <span class="text-sm text-gray-500">
                        Welcome, <strong class="text-gray-700">{{ $currentUser->name }}</strong>
                    </span>
                    @if($currentUser->role === 'admin')
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded font-semibold uppercase tracking-wide">Admin</span>
                    @endif
                @endif
            </div>
        </header>

        <main class="flex-1 p-8">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
