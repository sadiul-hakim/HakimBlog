<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/**
 * ADMIN ROUTE
 */

Route::prefix("admin")->name("admin.")->group(function () {
    Route::middleware(['guest'])->controller(AuthController::class)->group(function () {
        Route::get('/login', 'loginForm')->name('login');
        Route::get('/forgot-password', 'forgotPassword')->name('forgot-password');
    });

    Route::middleware(['auth'])->controller(AdminController::class)->group(function () {
        Route::get('/dashboard', 'adminDashboard')->name('dashboard');
    });
});
