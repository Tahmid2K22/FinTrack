@extends('layouts.app')

@section('title', 'Expense Management')
@section('header', 'Expense Management')

@section('content')
<div class="mb-6">
    <p class="text-sm text-gray-500">Review and manage employee expense submissions</p>
</div>

<form method="GET" action="{{ route('admin.expenses.index') }}" class="bg-white rounded-xl shadow p-4 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">

        <div>
            <label for="exp-employee" class="block text-xs font-medium text-gray-500 mb-1">Employee</label>
            <select id="exp-employee" name="search"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                <option value="">— All Employees —</option>
                @foreach ($employees as $emp)
                    <option value="{{ $emp->id }}" {{ $search == $emp->id ? 'selected' : '' }}>
                        {{ $emp->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="exp-category" class="block text-xs font-medium text-gray-500 mb-1">Category</label>
            <select id="exp-category" name="category"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                <option value="">— All Categories —</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->category }}" {{ $category === $cat->category ? 'selected' : '' }}>
                        {{ $cat->category }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="exp-status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select id="exp-status" name="status"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                <option value="">— All Statuses —</option>
                <option value="pending"  {{ $status === 'pending'  ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div>
            <label for="exp-date-from" class="block text-xs font-medium text-gray-500 mb-1">Date From</label>
            <input id="exp-date-from" type="date" name="date_from" value="{{ $dateFrom }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div>
            <label for="exp-date-to" class="block text-xs font-medium text-gray-500 mb-1">Date To</label>
            <input id="exp-date-to" type="date" name="date_to" value="{{ $dateTo }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
    </div>

    <div class="flex items-center gap-2 mt-3">
        <button type="submit"
            class="bg-indigo-600 text-white py-2 px-5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
            Filter
        </button>
        @if ($search !== '' || $category !== '' || $status !== '' || $dateFrom !== '' || $dateTo !== '')
            <a href="{{ route('admin.expenses.index') }}"
                class="bg-gray-100 text-gray-600 py-2 px-4 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                Clear
            </a>
        @endif
        <span class="ml-auto text-xs text-gray-400">
            <strong>{{ count($expenses) }}</strong> result{{ count($expenses) !== 1 ? 's' : '' }}
            @if ($search !== '' || $category !== '' || $status !== '' || $dateFrom !== '' || $dateTo !== '')
                &nbsp;<span class="text-indigo-500 font-medium">— filtered</span>
            @endif
        </span>
    </div>
</form>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approved By</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($expenses as $exp)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $exp->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $exp->employee_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ \Carbon\Carbon::parse($exp->expense_date)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $exp->category }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $exp->vendor ?: '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($exp->amount, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $exp->status === 'approved' ? 'bg-green-100 text-green-800' :
                               ($exp->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($exp->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $exp->approver_name ?: '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        @if ($exp->status === 'pending')
                            <form method="POST" action="{{ route('admin.expenses.approve', $exp->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 mr-2 font-medium">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.expenses.reject', $exp->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Reject</button>
                            </form>
                        @else
                            <span class="text-gray-400 text-xs">Already {{ $exp->status }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-10 text-center text-sm text-gray-400">
                        @if ($search !== '' || $category !== '' || $status !== '' || $dateFrom !== '' || $dateTo !== '')
                            No expenses match your filters.
                            <a href="{{ route('admin.expenses.index') }}" class="text-indigo-500 hover:underline">Clear filters</a>
                        @else
                            No expenses found.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
