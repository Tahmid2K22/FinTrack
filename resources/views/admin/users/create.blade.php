@extends('layouts.app')

@section('title', 'Create User')
@section('header', 'Create User')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">&larr; Back to Users</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" id="password" name="password" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select id="role" name="role" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <option value="employee"   {{ old('role') === 'employee'   ? 'selected' : '' }}>Employee</option>
                <option value="accountant" {{ old('role') === 'accountant' ? 'selected' : '' }}>Accountant</option>
                <option value="manager"    {{ old('role') === 'manager'    ? 'selected' : '' }}>Manager</option>
                <option value="admin"      {{ old('role') === 'admin'      ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div>
            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="is_active" name="is_active" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium text-sm">
                Create User
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-medium text-sm">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
