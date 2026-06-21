@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Your Role</p>
        <p class="text-2xl font-bold text-gray-800 capitalize mt-1">{{ $currentUser->role }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Status</p>
        <p class="text-2xl font-bold {{ $currentUser->is_active ? 'text-green-600' : 'text-red-600' }} mt-1">
            {{ $currentUser->is_active ? 'Active' : 'Inactive' }}
        </p>
    </div>

    @if ($currentUser->role === 'admin')
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Total Users</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Pending Expenses</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending_expenses'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Approved Expenses</p>
            <p class="text-2xl font-bold text-green-600 mt-1">${{ number_format($stats['approved_total'], 2) }}</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">My Expenses</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $stats['my_expenses'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending_expenses'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Approved Total</p>
            <p class="text-2xl font-bold text-green-600 mt-1">${{ number_format($stats['approved_total'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Rejected</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['rejected_count'] }}</p>
        </div>
    @endif
</div>

<div class="bg-white rounded-xl shadow p-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-2">Welcome, {{ $currentUser->name }}!</h3>
    @if ($currentUser->role === 'admin')
        <p class="text-gray-500 text-sm">You have full access to the system. Use the sidebar to manage expenses, users, and more.</p>
    @else
        <p class="text-gray-500 text-sm">You can submit and track your expenses from the sidebar.</p>
        <a href="{{ route('employee.expenses.create') }}"
           class="inline-block mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
            Submit an Expense
        </a>
    @endif
</div>
@endsection
