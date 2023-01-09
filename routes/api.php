<?php

use App\Http\Controllers\PasswordController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

//Public routes
Route::prefix('/')->middleware('guest')->group(function () {
	Route::post('/login', [SessionController::class, 'login']);
	Route::post('register', [SessionController::class, 'register'])->name('register');
	Route::get('/verify-account', [SessionController::class, 'verify'])->name('verification.verify');
	Route::post('reset-password', [PasswordController::class, 'send'])->name('reset.password');
	Route::get('reset-check', [PasswordController::class, 'check'])->name('check');
	Route::post('update-password', [PasswordController::class, 'update'])->name('update.password');
});
