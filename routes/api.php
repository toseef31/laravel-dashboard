<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

# Public Routes
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/forgot-password', [\App\Http\Controllers\AuthController::class, 'forgotPassword']);
Route::post('/resend-forgot-password-email', [\App\Http\Controllers\AuthController::class, 'forgotPassword']);
Route::post('/reset-password/{resetToken}', [\App\Http\Controllers\AuthController::class, 'resetPassword']);
Route::post('/select-two-factor/{signature}/{type}', [\App\Http\Controllers\AuthController::class, 'generateTwoFactorTimeline']);
Route::post('/submit-two-factor/{signature}', [\App\Http\Controllers\TwoFactorController::class, 'submitTwoFactor']);
Route::post('/resend-otp/{signature}', [\App\Http\Controllers\TwoFactorController::class, 'resendOTP']);

//Route::middleware('auth:sanctum')->group(function () {
    # User Routes
    Route::post('/create-user', [\App\Http\Controllers\UserController::class, 'store']);
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
    Route::post('/user/delete', [\App\Http\Controllers\UserController::class, 'deleteUser']);
    Route::post('/user/edit', [\App\Http\Controllers\UserController::class, 'editUser']);

    # Auth Routes
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

    # Two-Factor Routes
    Route::post('/enable-two-factor', [\App\Http\Controllers\TwoFactorController::class, 'enableTwoFactor']);
    Route::post('/disable-two-factor', [\App\Http\Controllers\TwoFactorController::class, 'disableTwoFactor']);

    # Book Size Routes
    Route::post('/create-size', [\App\Http\Controllers\BookSizeController::class, 'store']);
    Route::get('/sizes', [\App\Http\Controllers\BookSizeController::class, 'index']);
    Route::post('/size/delete', [\App\Http\Controllers\BookSizeController::class, 'deleteSize']);
    Route::post('/size/edit', [\App\Http\Controllers\BookSizeController::class, 'editSize']);
    Route::get('/size/{id}', [\App\Http\Controllers\BookSizeController::class, 'show']);

    # Book Routes
    Route::post('/create-book', [\App\Http\Controllers\BookController::class, 'store']);
    Route::get('/books', [\App\Http\Controllers\BookController::class, 'index']);
    Route::post('/book/delete', [\App\Http\Controllers\BookController::class, 'deleteBook']);
    Route::post('/book/edit', [\App\Http\Controllers\BookController::class, 'editBook']);
    Route::get('/book/{id}', [\App\Http\Controllers\BookController::class, 'show']);
    Route::post('/book/duplicate/{id}', [\App\Http\Controllers\BookController::class, 'duplicateBook']);

    # Book Media Routes
    Route::post('/create-book-media', [\App\Http\Controllers\BookMediaController::class, 'store']);
    Route::post('/book-media/delete', [\App\Http\Controllers\BookMediaController::class, 'deleteBookMedia']);
    Route::get('/book-media/{id}', [\App\Http\Controllers\BookMediaController::class, 'show']);
//});
