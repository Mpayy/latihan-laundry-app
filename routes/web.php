<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'index']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth', 'level:admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');


    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/edit/{id}', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('customers/update/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('customers/destroy/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('services/store', [ServiceController::class, 'store'])->name('services.store');
    Route::get('services/edit/{id}', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('services/update/{id}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('services/destroy/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');
});

Route::middleware(['auth', 'level:operator'])->group(function () {
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders/store', [OrderController::class, 'store'])->name('orders.store');

    Route::put('orders/pickup/{id}', [OrderController::class, 'pickup'])->name('orders.pickup');

    Route::get('orders/{id}/bayar', [OrderController::class, 'bayar'])->name('orders.bayar');
    Route::put('orders/{id}/bayar', [OrderController::class, 'bayarStore'])->name('orders.bayarStore');
});

Route::middleware(['auth', 'level:pimpinan'])->group(function () {
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
});
