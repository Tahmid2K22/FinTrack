<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\CustomAuth;
use Illuminate\Support\Facades\DB;
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
        $userId = session('user_id');
        $currentUser = DB::selectOne("SELECT * FROM users WHERE id = :id AND is_active = 1", ['id' => $userId]);
        $role = $currentUser->role ?? 'employee';
        $stats = [];

        if ($role === 'admin') {
            $totalUsers = DB::selectOne("SELECT COUNT(*) AS cnt FROM users");
            $pendingExpenses = DB::selectOne("SELECT COUNT(*) AS cnt FROM expenses WHERE status = 'pending'");
            $totalEmployees = DB::selectOne("SELECT COUNT(*) AS cnt FROM employees");
            $approvedExpenses = DB::selectOne("SELECT COALESCE(SUM(amount),0) AS total FROM expenses WHERE status = 'approved'");

            $stats['total_users'] = $totalUsers->cnt ?? 0;
            $stats['pending_expenses'] = $pendingExpenses->cnt ?? 0;
            $stats['total_employees'] = $totalEmployees->cnt ?? 0;
            $stats['approved_total'] = $approvedExpenses->total ?? 0;
        } else {
            $myExpenses = DB::selectOne("SELECT COUNT(*) AS cnt FROM expenses WHERE user_id = :p_uid", ['p_uid' => $userId]);
            $pendingExpenses = DB::selectOne("SELECT COUNT(*) AS cnt FROM expenses WHERE user_id = :p_uid AND status = 'pending'", ['p_uid' => $userId]);
            $approvedExpenses = DB::selectOne("SELECT COALESCE(SUM(amount),0) AS total FROM expenses WHERE user_id = :p_uid AND status = 'approved'", ['p_uid' => $userId]);
            $rejectedExpenses = DB::selectOne("SELECT COUNT(*) AS cnt FROM expenses WHERE user_id = :p_uid AND status = 'rejected'", ['p_uid' => $userId]);

            $stats['my_expenses'] = $myExpenses->cnt ?? 0;
            $stats['pending_expenses'] = $pendingExpenses->cnt ?? 0;
            $stats['approved_total'] = $approvedExpenses->total ?? 0;
            $stats['rejected_count'] = $rejectedExpenses->cnt ?? 0;
        }

        return view('dashboard', ['currentUser' => $currentUser, 'stats' => $stats]);
    })->name('dashboard');

    // Employee expense routes
    Route::get('/employee/expenses', [ExpenseController::class, 'myExpenses'])->name('employee.expenses.index');
    Route::get('/employee/expenses/create', [ExpenseController::class, 'create'])->name('employee.expenses.create');
    Route::post('/employee/expenses', [ExpenseController::class, 'store'])->name('employee.expenses.store');
    Route::get('/employee/expenses/{id}/edit', [ExpenseController::class, 'edit'])->name('employee.expenses.edit');
    Route::put('/employee/expenses/{id}', [ExpenseController::class, 'update'])->name('employee.expenses.update');
});

Route::middleware([AdminAuth::class])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('admin.employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
    Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');

    // Admin expense routes
    Route::get('/expenses', [ExpenseController::class, 'adminIndex'])->name('admin.expenses.index');
    Route::post('/expenses/{id}/approve', [ExpenseController::class, 'approve'])->name('admin.expenses.approve');
    Route::post('/expenses/{id}/reject', [ExpenseController::class, 'reject'])->name('admin.expenses.reject');
});
