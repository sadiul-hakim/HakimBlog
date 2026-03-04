<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/**
 * ADMIN ROUTE
 */

Route::prefix("admin")->name("admin.")->group(function () {
    Route::middleware(['guest', 'preventBackHistory'])->controller(AuthController::class)->group(function () {
        Route::get('/login', 'loginForm')->name('login');
        Route::post("/login", "loginHandler")->name("login_handler");
        Route::get('/forgot-password', 'forgotPassword')->name('forgot_password');
        Route::post("/send-password-reset-link", "sendPasswordResetLink")->name("send_password_reset_link");
        Route::get("/password/reset/{token}", "resetPassword")->name("reset_password");
        Route::post("/reset-password-handler", "resetPasswordHandler")->name("reset_password_handler");
    });

    Route::middleware(['auth', 'preventBackHistory'])->group(function () {

        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'adminDashboard')->name('dashboard');
            Route::get('/profile', 'profileView')->name('profile');
            Route::post('/update-profile-picture', 'updateProfilePicture')->name('update_profile_picture');
            Route::post("/logout", "logoutHandle")->name("logout");

            Route::middleware(["superAdminMiddleware"])->group(function () {
                Route::get('/settings', 'generalSettings')->name('settings');
                Route::post('/update-logo', 'updateLogo')->name('update_logo');
                Route::post('/update-favicon', 'updateFavicon')->name('update_favicon');
                Route::get('/categories', 'categoriesPage')->name('categories');
            });
        });

        Route::controller(PostController::class)->group(function () {
            Route::get("/post/new", "addPost")->name("add_post");
            Route::post("/post/create", "createPost")->name("create_post");
            Route::get("/posts", "allPosts")->name("posts");
        });
    });
});

/**
 * TEST ROUTE
 */

Route::view('/example-page', 'example-page');
Route::view('/example-auth', 'example-auth');
