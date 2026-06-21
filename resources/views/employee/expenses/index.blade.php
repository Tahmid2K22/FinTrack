@extends('layouts.app')

@section('title', 'My Expenses')
@section('header', 'My Expenses')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">All your submitted expenses</p>
    <a href="{{ route('employee.expenses.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium">
        + Submit Expense
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
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
                <tr>
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
                            <a href="{{ route('employee.expenses.edit', $exp->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No expenses submitted yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
