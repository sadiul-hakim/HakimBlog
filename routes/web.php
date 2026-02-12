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
        Route::post("/login", "loginHandler")->name("login_handler");
        Route::get('/forgot-password', 'forgotPassword')->name('forgot_password');
        Route::post("/send-password-reset-link", "sendPasswordResetLink")->name("send_password_reset_link");
        Route::get("/password/reset/{token}", "resetPassword")->name("reset_password");
        Route::post("/reset-password-handler", "resetPasswordHandler")->name("reset_password_handler");
    });

    Route::middleware(['auth'])->controller(AdminController::class)->group(function () {
        Route::get('/dashboard', 'adminDashboard')->name('dashboard');
        Route::post("/logout", "logoutHandle")->name("logout");
    });
});

/**
 * TEST ROUTE
 */

Route::view('/example-page', 'example-page');
Route::view('/example-auth', 'example-auth');
