@extends('layouts.app')

@section('title', 'Submit Expense')
@section('header', 'Submit Expense')

@section('content')
<div class="max-w-2xl">
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('employee.expenses.store') }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select id="category" name="category" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    onchange="toggleCustom(this)">
                    <option value="">-- Select --</option>
                    <option value="Travel" {{ old('category') === 'Travel' ? 'selected' : '' }}>Travel</option>
                    <option value="Food" {{ old('category') === 'Food' ? 'selected' : '' }}>Food</option>
                    <option value="Office Supplies" {{ old('category') === 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                    <option value="Utilities" {{ old('category') === 'Utilities' ? 'selected' : '' }}>Utilities</option>
                    <option value="Software" {{ old('category') === 'Software' ? 'selected' : '' }}>Software</option>
                    <option value="Training" {{ old('category') === 'Training' ? 'selected' : '' }}>Training</option>
                    <option value="__custom__" {{ old('category') && !in_array(old('category'), ['Travel','Food','Office Supplies','Utilities','Software','Training','Other']) ? 'selected' : '' }}>Other (Custom)</option>
                </select>
            </div>
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                <input type="number" id="amount" name="amount" value="{{ old('amount') }}" required step="0.01" min="0.01"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="0.00">
            </div>
        </div>

        <div id="customCategoryDiv" class="{{ old('category') && !in_array(old('category'), ['Travel','Food','Office Supplies','Utilities','Software','Training','Other']) ? '' : 'hidden' }}">
            <label for="custom_category" class="block text-sm font-medium text-gray-700 mb-1">Custom Category</label>
            <input type="text" id="custom_category" name="custom_category" value="{{ old('custom_category') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="e.g. Medical, Transportation, Internet">
        </div>

        <div>
            <label for="vendor" class="block text-sm font-medium text-gray-700 mb-1">Vendor <span class="text-gray-400">(optional)</span></label>
            <input type="text" id="vendor" name="vendor" value="{{ old('vendor') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="e.g. Uber, Amazon">
        </div>

        <div>
            <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-1">Expense Date</label>
            <input type="date" id="expense_date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-medium text-sm">
                Submit Expense
            </button>
            <a href="{{ route('employee.expenses.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 font-medium text-sm">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function toggleCustom(select) {
    const div = document.getElementById('customCategoryDiv');
    const input = document.getElementById('custom_category');
    if (select.value === '__custom__') {
        div.classList.remove('hidden');
        input.required = true;
    } else {
        div.classList.add('hidden');
        input.required = false;
        input.value = '';
    }
}
</script>
@endsection
