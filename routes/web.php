<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'index']);
Route::post('login',[AuthController::class, 'login'])->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth', 'level:admin'])->group(function () {
    // Route::resource('user', UserController::class);
    // Route::resource('service', ServiceController::class);
});

Route::middleware(['auth', 'level:operator'])->group(function () {
    // Route::resource('order', OrderController::class);
});

Route::middleware(['auth', 'level:pimpinan'])->group(function () {
    // Route::get('/laporan', [ReportController::class, 'index']);
});
