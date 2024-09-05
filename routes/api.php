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

# Protected Routes
Route::middleware('auth:sanctum')->post('/create-user', [\App\Http\Controllers\UserController::class, 'store']);
Route::middleware('auth:sanctum')->get('/users', [\App\Http\Controllers\UserController::class, 'index']);
Route::middleware('auth:sanctum')->post('/user/delete', [\App\Http\Controllers\UserController::class, 'deleteUser']);
Route::middleware('auth:sanctum')->post('/user/edit', [\App\Http\Controllers\UserController::class, 'editUser']);

Route::middleware('auth:sanctum')->post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->post('/enable-two-factor', [\App\Http\Controllers\TwoFactorController::class, 'enableTwoFactor']);
Route::middleware('auth:sanctum')->post('/disable-two-factor', [\App\Http\Controllers\TwoFactorController::class, 'disableTwoFactor']);

Route::middleware('auth:sanctum')->post('/create-size', [\App\Http\Controllers\BookSizeController::class, 'store']);
Route::middleware('auth:sanctum')->get('/sizes', [\App\Http\Controllers\BookSizeController::class, 'index']);
Route::middleware('auth:sanctum')->post('/size/delete', [\App\Http\Controllers\BookSizeController::class, 'deleteSize']);
Route::middleware('auth:sanctum')->post('/size/edit', [\App\Http\Controllers\BookSizeController::class, 'editSize']);

Route::middleware('auth:sanctum')->post('/create-book', [\App\Http\Controllers\BookController::class, 'store']);
Route::middleware('auth:sanctum')->get('/books', [\App\Http\Controllers\BookController::class, 'index']);
Route::middleware('auth:sanctum')->post('/book/delete', [\App\Http\Controllers\BookController::class, 'deleteBook']);
Route::middleware('auth:sanctum')->post('/book/edit', [\App\Http\Controllers\BookController::class, 'editBook']);

Route::middleware('auth:sanctum')->post('/create-book-media', [\App\Http\Controllers\BookMediaController::class, 'store']);
Route::middleware('auth:sanctum')->post('/book-media/delete', [\App\Http\Controllers\BookMediaController::class, 'deleteBookMedia']);