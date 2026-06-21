@extends('admin.layout')

@section('title', 'Edit Employee')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.employees.index') }}" class="text-gray-400 hover:text-gray-600">&larr; Back</a>
        <h2 class="text-2xl font-semibold text-gray-800">Edit Employee — {{ $employee->user_name }}</h2>
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

    <form method="POST" action="{{ route('admin.employees.update', $employee->id) }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
            <select id="user_id" name="user_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $employee->user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <input type="text" id="department" name="department" value="{{ old('department', $employee->department) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                <input type="text" id="position" name="position" value="{{ old('position', $employee->position) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="base_salary" class="block text-sm font-medium text-gray-700 mb-1">Base Salary</label>
                <input type="number" id="base_salary" name="base_salary" value="{{ old('base_salary', $employee->base_salary) }}" required step="0.01" min="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-1">Hire Date</label>
                <input type="date" id="hire_date" name="hire_date" value="{{ old('hire_date', \Carbon\Carbon::parse($employee->hire_date)->format('Y-m-d')) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="status" name="status" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="terminated" {{ old('status', $employee->status) === 'terminated' ? 'selected' : '' }}>Terminated</option>
            </select>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-medium text-sm">
                Update Employee
            </button>
            <a href="{{ route('admin.employees.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 font-medium text-sm">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
