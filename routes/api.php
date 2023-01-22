<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SocialRegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Public routes
Route::prefix('/')->middleware('guest')->group(function () {
	Route::post('login', [SessionController::class, 'login']);
	Route::post('register', [SessionController::class, 'register'])->name('register');
	Route::get('verify-account', [SessionController::class, 'verify'])->name('verification.verify');
	Route::post('reset-password', [PasswordController::class, 'send'])->name('reset.password');
	Route::get('reset-check', [PasswordController::class, 'check'])->name('check');
	Route::post('update-password', [PasswordController::class, 'update'])->name('update.password');
	Route::get('google-register/{locale}/{type}', [SocialRegisterController::class, 'redirectToProvider'])->name('google.register');
	Route::get('auth/google/{locale}/{type}/callback', [SocialRegisterController::class, 'handleCallBack'])->name('google.register.callback');
});
//Auth routes
Route::prefix('/auth')->middleware(['auth:sanctum'])->group(function () {
	Route::get('/user', [UserController::class, 'me']);
	Route::get('/movie-tags', [MovieController::class, 'tags']);
	Route::post('/movie-upload', [MovieController::class, 'store']);
	Route::get('/user-movies', [MovieController::class, 'userMovies']);
	Route::get('/movie/{id}', [MovieController::class, 'userMovie']);
	Route::put('/movie/{id}', [MovieController::class, 'update']);
});
