<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminAuth;
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

Route::middleware([AdminAuth::class])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});
