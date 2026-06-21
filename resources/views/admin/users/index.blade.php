@extends('layouts.app')

@section('title', 'User Management')
@section('header', 'User Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">Manage system users and their roles</p>
    <a href="{{ route('admin.users.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
        + Add User
    </a>
</div>

<form method="GET" action="{{ route('admin.users.index') }}" class="bg-white rounded-xl shadow p-4 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">

        <div>
            <label for="user-search" class="block text-xs font-medium text-gray-500 mb-1">Search Name / Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </span>
                <input id="user-search" type="text" name="search" value="{{ $search }}"
                    placeholder="Name or email…"
                    class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
        </div>

        <div>
            <label for="user-role" class="block text-xs font-medium text-gray-500 mb-1">Role</label>
            <select id="user-role" name="role"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                <option value="">— All Roles —</option>
                @foreach ($roles as $r)
                    <option value="{{ $r->role }}" {{ $role === $r->role ? 'selected' : '' }}>
                        {{ ucfirst($r->role) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="user-status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select id="user-status" name="status"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                <option value="">— All Statuses —</option>
                <option value="1" {{ $status === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $status === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                class="flex-1 bg-indigo-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                Filter
            </button>
            @if ($search !== '' || $role !== '' || $status !== '')
                <a href="{{ route('admin.users.index') }}"
                    class="flex-shrink-0 bg-gray-100 text-gray-600 py-2 px-3 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                    Clear
                </a>
            @endif
        </div>
    </div>
    <p class="mt-3 text-xs text-gray-400">
        Showing <strong>{{ count($users) }}</strong> user{{ count($users) !== 1 ? 's' : '' }}
        @if ($search !== '' || $role !== '' || $status !== '')
            &nbsp;<span class="text-indigo-500 font-medium">— filtered</span>
        @endif
    </p>
</form>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $user->role === 'admin'      ? 'bg-red-100 text-red-800' :
                               ($user->role === 'accountant' ? 'bg-blue-100 text-blue-800' :
                               ($user->role === 'manager'    ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ is_string($user->created_at)
                            ? \Carbon\Carbon::parse($user->created_at)->format('M d, Y')
                            : ($user->created_at ? $user->created_at->format('M d, Y') : '-') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                            class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        @if (session('user_id') != $user->id)
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                class="inline"
                                onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-400">
                        @if ($search !== '' || $role !== '' || $status !== '')
                            No users match your filters.
                            <a href="{{ route('admin.users.index') }}" class="text-indigo-500 hover:underline">Clear filters</a>
                        @else
                            No users found.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
