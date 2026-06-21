<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Employee') - FinTrack</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-gray-100 min-h-screen flex">

    <aside class="w-64 bg-gray-900 text-white min-h-screen flex flex-col fixed">
        <div class="p-5 border-b border-gray-800">
            <a href="/dashboard" class="text-xl font-bold text-indigo-400">FinTrack</a>
            <p class="text-xs text-gray-500 mt-1">Employee Portal</p>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                Dashboard
            </a>

            <div class="pt-4 pb-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">My Expenses</div>

            <a href="{{ route('employee.expenses.create') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm {{ request()->is('employee/expenses/create') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Submit Expense
            </a>

            <a href="{{ route('employee.expenses.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm {{ request()->is('employee/expenses') && !request()->is('*/create') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                My Expenses
            </a>
        </nav>

        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-sm font-bold">
                    {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-white">{{ $currentUser->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ $currentUser->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-sm text-gray-400 hover:text-white px-3 py-2 rounded-md hover:bg-gray-800 flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 ml-64">
        <header class="bg-white shadow h-16 flex items-center px-8">
            <h2 class="text-lg font-semibold text-gray-800">@yield('header', 'Employee')</h2>
        </header>

        <main class="p-8">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
