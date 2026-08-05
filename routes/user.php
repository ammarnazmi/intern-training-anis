<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\User;
use App\Http\Controllers\User\Profile;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Route;
use Onpay\Core\Http\Middleware\IsUserBanned;
use Onpay\Core\Http\Middleware\SetUserLocale;

Route::middleware('guest:user')->group(function () {
    // Login
    Route::get('login', [Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [Auth\AuthenticatedSessionController::class, 'store']);

    // Forgot password
    Route::get('forgot-password', [Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [Auth\PasswordResetLinkController::class, 'store'])->name('password.email');

    // Reset password
    Route::get('reset-password/{token}', [Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [Auth\NewPasswordController::class, 'store'])->name('password.update');

    // Register
    Route::get('register', [Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [Auth\RegisteredUserController::class, 'storeUser']);
});

Route::middleware(['auth:user', SetUserLocale::class])->group(function () {
    // Email verification
    Route::get('verify-email', Auth\EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', Auth\VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('email/verification-notification', [Auth\EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

    // Confirm password
    Route::get('confirm-password', [Auth\ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [Auth\ConfirmablePasswordController::class, 'store']);

    // Logout
    Route::post('logout', [Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware([
    Authenticate::using('user'),
    EnsureEmailIsVerified::redirectTo('user.verification.notice'),
    SetUserLocale::class,
    IsUserBanned::using('user.login'),
])->group(function () {
    // Main
    Route::get('/', [User\MainController::class, 'index'])->name('main');

    // Profile
    Route::name('profile.')->prefix('profile')->group(function () {
        Route::singleton('user', Profile\UserController::class);
        Route::singleton('password', Profile\PasswordController::class);
        Route::put('locale', LocaleController::class)->name('locale');
    });
});
