<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function create()
    {
        return view('employee.expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'        => ['required', 'string', 'max:100'],
            'custom_category' => ['required_if:category,__custom__', 'nullable', 'string', 'max:100'],
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'vendor'          => ['nullable', 'string', 'max:150'],
            'expense_date'    => ['required', 'date'],
        ]);

        $category = $request->category === '__custom__' ? $request->custom_category : $request->category;

        $maxId = DB::selectOne('SELECT NVL(MAX(id), 0) AS max_id FROM expenses');
        $id = ($maxId->max_id ?? 0) + 1;

        DB::statement(
            'INSERT INTO expenses (id, user_id, category, amount, vendor, expense_date, status)
             VALUES (:p_id, :p_user_id, :p_category, :p_amount, :p_vendor, TO_DATE(:p_expense_date, \'YYYY-MM-DD\'), \'pending\')',
            [
                'p_id'           => $id,
                'p_user_id'      => session('user_id'),
                'p_category'     => $category,
                'p_amount'       => $request->amount,
                'p_vendor'       => $request->vendor,
                'p_expense_date' => $request->expense_date,
            ]
        );

        return redirect('/employee/expenses')->with('success', 'Expense submitted successfully.');
    }

    public function edit($id)
    {
        $userId = session('user_id');
        $expense = DB::selectOne(
            'SELECT * FROM expenses WHERE id = ' . intval($id) . ' AND user_id = ' . $userId . ' AND status = \'pending\''
        );

        if (! $expense) {
            return redirect('/employee/expenses')->with('error', 'Expense not found or already processed.');
        }

        return view('employee.expenses.edit', compact('expense'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category'        => ['required', 'string', 'max:100'],
            'custom_category' => ['required_if:category,__custom__', 'nullable', 'string', 'max:100'],
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'vendor'          => ['nullable', 'string', 'max:150'],
            'expense_date'    => ['required', 'date'],
        ]);

        $userId = session('user_id');
        $category = $request->category === '__custom__' ? $request->custom_category : $request->category;

        DB::statement(
            "UPDATE expenses SET category = '{$category}', amount = {$request->amount},
             vendor = " . ($request->vendor ? "'{$request->vendor}'" : "NULL") . ",
             expense_date = TO_DATE('{$request->expense_date}', 'YYYY-MM-DD')
             WHERE id = " . intval($id) . " AND user_id = {$userId} AND status = 'pending'"
        );

        return redirect('/employee/expenses')->with('success', 'Expense updated successfully.');
    }

    public function myExpenses()
    {
        $userId = session('user_id');
        $expenses = DB::select('
            SELECT e.id, e.category, e.amount, e.vendor, e.expense_date, e.status, e.approved_by,
                   u.name AS approver_name
            FROM expenses e
            LEFT JOIN users u ON e.approved_by = u.id
            WHERE e.user_id = ' . $userId . '
            ORDER BY e.expense_date DESC
        ');

        return view('employee.expenses.index', compact('expenses'));
    }

    public function adminIndex(Request $request)
    {
        $search   = trim((string) $request->input('search', ''));
        $category = trim((string) $request->input('category', ''));
        $status   = trim((string) $request->input('status', ''));
        $dateFrom = trim((string) $request->input('date_from', ''));
        $dateTo   = trim((string) $request->input('date_to', ''));

        $where  = [];
        $params = [];

        if ($search !== '') {
            $where[]           = 'e.user_id = :user_id';
            $params['user_id'] = (int) $search;
        }
        if ($category !== '') {
            $where[]            = 'e.category = :category';
            $params['category'] = $category;
        }
        if ($status !== '') {
            $where[]          = 'e.status = :status';
            $params['status'] = $status;
        }
        if ($dateFrom !== '') {
            $where[]             = "e.expense_date >= TO_DATE(:date_from, 'YYYY-MM-DD')";
            $params['date_from'] = $dateFrom;
        }
        if ($dateTo !== '') {
            $where[]           = "e.expense_date <= TO_DATE(:date_to, 'YYYY-MM-DD')";
            $params['date_to'] = $dateTo;
        }

        $sql = 'SELECT e.id, e.category, e.amount, e.vendor, e.expense_date, e.status, e.approved_by,
                       u.name AS employee_name, a.name AS approver_name
                FROM expenses e
                JOIN users u ON e.user_id = u.id
                LEFT JOIN users a ON e.approved_by = a.id';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY e.expense_date DESC';

        $expenses = DB::select($sql, $params);

        $employees  = DB::select('SELECT DISTINCT u.id, u.name FROM expenses e JOIN users u ON e.user_id = u.id ORDER BY u.name ASC');
        $categories = DB::select('SELECT DISTINCT category FROM expenses ORDER BY category ASC');

        return view('admin.expenses.index', compact(
            'expenses', 'search', 'category', 'status', 'dateFrom', 'dateTo',
            'employees', 'categories'
        ));
    }

    public function approve($id)
    {
        $userId = session('user_id');
        DB::statement(
            "UPDATE expenses SET status = 'approved', approved_by = {$userId} WHERE id = {$id} AND status = 'pending'"
        );

        return redirect('/admin/expenses')->with('success', 'Expense approved.');
    }

    public function reject($id)
    {
        $userId = session('user_id');
        DB::statement(
            "UPDATE expenses SET status = 'rejected', approved_by = {$userId} WHERE id = {$id} AND status = 'pending'"
        );

        return redirect('/admin/expenses')->with('success', 'Expense rejected.');
    }
}
