<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = DB::selectOne(
            'SELECT id, password, is_active FROM users WHERE email = :email',
            ['email' => $request->email]
        );

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account has been deactivated.'],
            ]);
        }

        $request->session()->put('user_id', $user->id);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'email', 'max:150'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $exists = DB::selectOne(
            'SELECT COUNT(*) AS cnt FROM users WHERE email = :email',
            ['email' => $request->email]
        );

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
                'role'      => 'employee',
                'is_active' => 1,
            ]
        );

        $request->session()->put('user_id', $id);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
