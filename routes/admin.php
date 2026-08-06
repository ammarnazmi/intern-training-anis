<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Profile;
use App\Http\Controllers\Auth;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;
use Onpay\Core\Http\Middleware\SetUserLocale;

Route::middleware('guest:admin')->group(function () {
    // Login
    Route::get('login', [Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [Auth\AuthenticatedSessionController::class, 'store']);

    // Forgot password
    Route::get('forgot-password', [Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [Auth\PasswordResetLinkController::class, 'store'])->name('password.email');

    // Reset password
    Route::get('reset-password/{token}', [Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [Auth\NewPasswordController::class, 'store'])->name('password.update');
});

Route::middleware(['auth:admin', SetUserLocale::class])->group(function () {
    // Confirm password
    Route::get('confirm-password', [Auth\ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [Auth\ConfirmablePasswordController::class, 'store']);

    // Logout
    Route::post('logout', [Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth:admin', SetUserLocale::class])->group(function () {
    // Main
    Route::get('/', [Admin\MainController::class, 'index'])->name('main');

    Route::name('main.')->prefix('main')->group(function () {
        Route::get('counts', [Admin\MainController::class, 'counts'])->name('counts');
        Route::get('online-users', [Admin\MainController::class, 'onlineUsers'])->name('online_users');
    });

    // Users
    Route::name('users.')->prefix('users')->group(function () {
        Route::put('{user}/activate', [Admin\UserController::class, 'activate'])->name('activate');
        Route::put('{user}/ban', [Admin\UserController::class, 'ban'])->name('ban');
        Route::put('{user}/unban', [Admin\UserController::class, 'unban'])->name('unban');
    });

    Route::resource('users', Admin\UserController::class);

    //prducts
    Route::resource('products', Admin\ProductController::class);
    // Subproducts routes (nested under products)
    Route::name('products.')->prefix('products')->group(function () {
        Route::get('{product}/subproducts', [Admin\SubproductController::class, 'index'])->name('subproducts.index');
        Route::get('{product}/subproducts/create', [Admin\SubproductController::class, 'create'])->name('subproducts.create');
        Route::post('{product}/subproducts', [Admin\SubproductController::class, 'store'])->name('subproducts.store');
    });

    // Standalone subproduct routes for edit/update/delete
    Route::name('subproducts.')->prefix('subproducts')->group(function () {
        Route::get('{subproduct}/edit', [Admin\SubproductController::class, 'edit'])->name('edit');
        Route::put('{subproduct}', [Admin\SubproductController::class, 'update'])->name('update');
        Route::delete('{subproduct}', [Admin\SubproductController::class, 'destroy'])->name('destroy');
    });


    // Profile
    Route::name('profile.')->prefix('profile')->group(function () {
        Route::singleton('user', Profile\UserController::class);
        Route::singleton('password', Profile\PasswordController::class);
        Route::put('locale', LocaleController::class)->name('locale');
    });
});
