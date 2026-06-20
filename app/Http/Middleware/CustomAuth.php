<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomAuth
{
    public function handle(Request $request, Closure $next)
    {
        $userId = session('user_id');

        if (! $userId) {
            return redirect('/login');
        }

        $user = DB::selectOne('SELECT * FROM users WHERE id = :id AND is_active = 1', ['id' => $userId]);

        if (! $user) {
            session()->forget('user_id');
            return redirect('/login');
        }

        view()->share('currentUser', $user);

        return $next($request);
    }
}
