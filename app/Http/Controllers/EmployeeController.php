<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = DB::select('
            SELECT e.id, e.department, e.position, e.base_salary, e.hire_date, e.status,
                   u.name AS user_name, u.email AS user_email, u.id AS user_id
            FROM employees e
            JOIN users u ON e.user_id = u.id
            ORDER BY e.id ASC
        ');

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $users = DB::select('
            SELECT u.id, u.name, u.email
            FROM users u
            WHERE u.id NOT IN (SELECT user_id FROM employees)
            ORDER BY u.name ASC
        ');

        return view('admin.employees.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'      => ['required', 'integer'],
            'department'   => ['required', 'string', 'max:100'],
            'position'     => ['required', 'string', 'max:100'],
            'base_salary'  => ['required', 'numeric', 'min:0'],
            'hire_date'    => ['required', 'date'],
            'status'       => ['required', 'in:active,inactive,terminated'],
        ]);

        $exists = DB::selectOne('SELECT COUNT(*) AS cnt FROM employees WHERE user_id = :p_uid', ['p_uid' => $request->user_id]);
        if ($exists->cnt > 0) {
            throw ValidationException::withMessages([
                'user_id' => ['This user is already an employee.'],
            ]);
        }

        $maxId = DB::selectOne('SELECT NVL(MAX(id), 0) AS max_id FROM employees');
        $id = ($maxId->max_id ?? 0) + 1;

        DB::statement(
            'INSERT INTO employees (id, user_id, department, position, base_salary, hire_date, status)
             VALUES (:id, :user_id, :department, :position, :base_salary, TO_DATE(:hire_date, \'YYYY-MM-DD\'), :status)',
            [
                'id'           => $id,
                'user_id'      => $request->user_id,
                'department'   => $request->department,
                'position'     => $request->position,
                'base_salary'  => $request->base_salary,
                'hire_date'    => $request->hire_date,
                'status'       => $request->status,
            ]
        );

        return redirect('/admin/employees')->with('success', 'Employee added successfully.');
    }

    public function edit($id)
    {
        $employee = DB::selectOne('
            SELECT e.id, e.user_id, e.department, e.position, e.base_salary, e.hire_date, e.status,
                   u.name AS user_name, u.email AS user_email
            FROM employees e
            JOIN users u ON e.user_id = u.id
            WHERE e.id = :id
        ', ['id' => $id]);

        if (! $employee) {
            return redirect('/admin/employees');
        }

        $users = DB::select('SELECT id, name, email FROM users ORDER BY name ASC');

        return view('admin.employees.edit', compact('employee', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'      => ['required', 'integer'],
            'department'   => ['required', 'string', 'max:100'],
            'position'     => ['required', 'string', 'max:100'],
            'base_salary'  => ['required', 'numeric', 'min:0'],
            'hire_date'    => ['required', 'date'],
            'status'       => ['required', 'in:active,inactive,terminated'],
        ]);

        $exists = DB::selectOne(
            'SELECT COUNT(*) AS cnt FROM employees WHERE user_id = :p_uid AND id != :p_id',
            ['p_uid' => $request->user_id, 'p_id' => $id]
        );
        if ($exists->cnt > 0) {
            throw ValidationException::withMessages([
                'user_id' => ['This user is already an employee.'],
            ]);
        }

        DB::statement(
            'UPDATE employees SET user_id = :user_id, department = :department, position = :position,
             base_salary = :base_salary, hire_date = TO_DATE(:hire_date, \'YYYY-MM-DD\'), status = :status
             WHERE id = :id',
            [
                'id'           => $id,
                'user_id'      => $request->user_id,
                'department'   => $request->department,
                'position'     => $request->position,
                'base_salary'  => $request->base_salary,
                'hire_date'    => $request->hire_date,
                'status'       => $request->status,
            ]
        );

        return redirect('/admin/employees')->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        DB::statement('DELETE FROM employees WHERE id = :id', ['id' => $id]);
        return redirect('/admin/employees')->with('success', 'Employee removed successfully.');
    }
}
