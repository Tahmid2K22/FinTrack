<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function index()
    {
        $users = DB::select('SELECT * FROM users ORDER BY id ASC');
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150'],
            'password' => ['required', Password::min(8)],
            'role' => ['required', 'in:admin,accountant,manager,employee'],
            'is_active' => ['required', 'in:0,1'],
        ]);

        $exists = DB::selectOne('SELECT COUNT(*) AS cnt FROM users WHERE email = :email', ['email' => $request->email]);
        if ($exists->cnt > 0) {
            throw ValidationException::withMessages([
                'email' => ['The email has already been taken.'],
            ]);
        }

        $maxId = DB::selectOne('SELECT NVL(MAX(id), 0) AS max_id FROM users');
        $id = ($maxId->max_id ?? 0) + 1;

        DB::statement(
            'INSERT INTO users (id, name, email, password, role, is_active, created_at, updated_at)
             VALUES (:id, :name, :email, :password, :role, :is_active, SYSTIMESTAMP, SYSTIMESTAMP)',
            [
                'id'        => $id,
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => $request->role,
                'is_active' => $request->is_active,
            ]
        );

        return redirect('/admin/users')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = DB::selectOne('SELECT * FROM users WHERE id = :id', ['id' => $id]);

        if (! $user) {
            return redirect('/admin/users');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150'],
            'role' => ['required', 'in:admin,accountant,manager,employee'],
            'is_active' => ['required', 'in:0,1'],
        ]);

        $exists = DB::selectOne(
            'SELECT COUNT(*) AS cnt FROM users WHERE email = :email AND id != :id',
            ['email' => $request->email, 'id' => $id]
        );
        if ($exists->cnt > 0) {
            throw ValidationException::withMessages([
                'email' => ['The email has already been taken.'],
            ]);
        }

        $sql = 'UPDATE users SET name = :name, email = :email, role = :role, is_active = :is_active, updated_at = SYSTIMESTAMP';
        $params = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'is_active' => $request->is_active,
            'id'        => $id,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => Password::min(8)]);
            $sql .= ', password = :password';
            $params['password'] = Hash::make($request->password);
        }

        $sql .= ' WHERE id = :id';
        DB::statement($sql, $params);

        return redirect('/admin/users')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $currentUser = session('user_id');

        if ($currentUser == $id) {
            return redirect('/admin/users')->with('error', 'You cannot delete your own account.');
        }

        DB::statement('DELETE FROM users WHERE id = :id', ['id' => $id]);

        return redirect('/admin/users')->with('success', 'User deleted successfully.');
    }
}
