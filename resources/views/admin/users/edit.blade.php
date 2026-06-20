@extends('admin.layout')

@section('title', 'Edit User')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600">&larr; Back</a>
        <h2 class="text-2xl font-semibold text-gray-800">Edit User</h2>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-400">(leave blank to keep current)</span></label>
            <input type="password" id="password" name="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select id="role" name="role" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="employee" {{ old('role', $user->role) === 'employee' ? 'selected' : '' }}>Employee</option>
                <option value="accountant" {{ old('role', $user->role) === 'accountant' ? 'selected' : '' }}>Accountant</option>
                <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>Client</option>
            </select>
        </div>

        <div>
            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="is_active" name="is_active" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="1" {{ old('is_active', $user->is_active) == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active', $user->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-medium text-sm">
                Update User
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 font-medium text-sm">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
