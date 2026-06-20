<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - FinTrack</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-6">
                    <a href="/dashboard" class="text-xl font-bold text-indigo-600">FinTrack</a>
                    <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded font-medium">ADMIN</span>
                    <div class="hidden sm:flex items-center gap-4">
                        <a href="{{ route('admin.users.index') }}" class="text-sm {{ request()->is('admin/users*') ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-gray-800' }}">Users</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="/dashboard" class="text-sm text-gray-500 hover:text-gray-700">Dashboard</a>
                    <span class="text-sm text-gray-700">{{ $currentUser->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
