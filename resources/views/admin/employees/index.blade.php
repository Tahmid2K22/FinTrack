@extends('layouts.app')

@section('title', 'Employee Management')
@section('header', 'Employee Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Employee Management</h2>
    <a href="{{ route('admin.employees.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium">
        + Add Employee
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Salary</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hire Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($employees as $emp)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $emp->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $emp->user_name }}</div>
                        <div class="text-xs text-gray-500">{{ $emp->user_email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $emp->department }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $emp->position }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${{ number_format($emp->base_salary, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($emp->hire_date)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $emp->status === 'active' ? 'bg-green-100 text-green-800' :
                               ($emp->status === 'inactive' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($emp->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('admin.employees.edit', $emp->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <form method="POST" action="{{ route('admin.employees.destroy', $emp->id) }}" class="inline" onsubmit="return confirm('Remove this employee?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">No employees found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
