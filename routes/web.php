<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\CustomAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (session('user_id')) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware([CustomAuth::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
