<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::findByEmail($request->email);

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
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if (User::emailExists($request->email)) {
            throw ValidationException::withMessages([
                'email' => ['The email has already been taken.'],
            ]);
        }

        $user = User::createUser([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'employee',
            'is_active' => 1,
        ]);

        $request->session()->put('user_id', $user->id);

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
