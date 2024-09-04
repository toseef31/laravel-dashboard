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

Route::middleware('auth:sanctum')->post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->post('/enable-two-factor', [\App\Http\Controllers\TwoFactorController::class, 'enableTwoFactor']);
Route::middleware('auth:sanctum')->post('/disable-two-factor', [\App\Http\Controllers\TwoFactorController::class, 'disableTwoFactor']);

