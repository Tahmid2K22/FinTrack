<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - FinTrack</title>
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
                    @if ($currentUser->role === 'admin')
                        <a href="{{ route('admin.users.index') }}" class="text-sm bg-red-100 text-red-700 px-3 py-1 rounded-md hover:bg-red-200 font-medium">Admin Panel</a>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-700">{{ $currentUser->name }}</span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded capitalize">{{ $currentUser->role }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Dashboard</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Role</p>
                <p class="text-2xl font-bold text-gray-800 capitalize">{{ $currentUser->role }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Status</p>
                <p class="text-2xl font-bold {{ $currentUser->is_active ? 'text-green-600' : 'text-red-600' }}">
                    {{ $currentUser->is_active ? 'Active' : 'Inactive' }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Welcome, {{ $currentUser->name }}!</h3>
            <p class="text-gray-600">You are logged in as <strong class="capitalize">{{ $currentUser->role }}</strong>.</p>
        </div>
    </div>
</body>
</html>
