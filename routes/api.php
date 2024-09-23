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

Route::middleware('auth:sanctum')->group(function () {
   

    # Auth Routes
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

    # Two-Factor Routes
    Route::post('/enable-two-factor', [\App\Http\Controllers\TwoFactorController::class, 'enableTwoFactor']);
    Route::post('/disable-two-factor', [\App\Http\Controllers\TwoFactorController::class, 'disableTwoFactor']);

    
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);


# User Routes
Route::post('/create-user', [\App\Http\Controllers\UserController::class, 'store']);
Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
Route::post('/user/delete', [\App\Http\Controllers\UserController::class, 'deleteUser']);
Route::post('/user/edit', [\App\Http\Controllers\UserController::class, 'editUser']);
Route::get('/user/{id}', [\App\Http\Controllers\UserController::class, 'show']);

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
Route::post('/books/compare', [\App\Http\Controllers\BookController::class, 'compareBooks']);
Route::get('/books/next', [\App\Http\Controllers\BookController::class, 'getNextBookId']);


# Ephemera Routes
Route::post('/create-ephemera', [\App\Http\Controllers\EphemeraController::class, 'store']);
Route::get('/ephemeras', [\App\Http\Controllers\EphemeraController::class, 'index']);
Route::post('/ephemera/delete', [\App\Http\Controllers\EphemeraController::class, 'deleteEphemera']);
Route::post('/ephemera/edit', [\App\Http\Controllers\EphemeraController::class, 'editEphemera']);
Route::get('/ephemera/{id}', [\App\Http\Controllers\EphemeraController::class, 'show']);
Route::get('/ephemeras/next', [\App\Http\Controllers\EphemeraController::class, 'getNextEphemeraId']);

# HardyReel Routes
Route::post('/create-hardyreel', [\App\Http\Controllers\HardyReelController::class, 'store']);
Route::get('/hardyreels', [\App\Http\Controllers\HardyReelController::class, 'index']);
Route::post('/hardyreel/delete', [\App\Http\Controllers\HardyReelController::class, 'deleteHardyReel']);
Route::post('/hardyreel/edit', [\App\Http\Controllers\HardyReelController::class, 'editHardyReel']);
Route::get('/hardyreel/{id}', [\App\Http\Controllers\HardyReelController::class, 'show']);
Route::get('/hardyreels/next', [\App\Http\Controllers\HardyReelController::class, 'getNextHardyReelId']);

# Book Media Routes
Route::post('/create-book-media', [\App\Http\Controllers\BookMediaController::class, 'store']);
Route::post('/book-media/delete', [\App\Http\Controllers\BookMediaController::class, 'deleteBookMedia']);
Route::get('/book-media/{id}', [\App\Http\Controllers\BookMediaController::class, 'show']);

# Ephemera Media Routes
Route::post('/create-ephemera-media', [\App\Http\Controllers\EphemeraMediaController::class, 'store']);
Route::post('/ephemera-media/delete', [\App\Http\Controllers\EphemeraMediaController::class, 'deleteEphemeraMedia']);
Route::get('/ephemera-media/{id}', [\App\Http\Controllers\EphemeraMediaController::class, 'show']);

# HardyReels Media Routes
Route::post('/create-hardyreel-media', [\App\Http\Controllers\HardyReelMediaController::class, 'store']);
Route::post('/hardyreel-media/delete', [\App\Http\Controllers\HardyReelMediaController::class, 'deleteHardyReelMedia']);
Route::get('/hardyreel-media/{id}', [\App\Http\Controllers\HardyReelMediaController::class, 'show']);




Route::get('/old-books-store', [\App\Http\Controllers\Data\BookController::class, 'storeBooksFromConfig']);
Route::get('/old-books-media-store', [\App\Http\Controllers\Data\BookController::class, 'insertMedia']);
Route::get('/old-hardy-media-store', [\App\Http\Controllers\Data\HardyAndEphemeraController::class, 'insertMediaHardy']);
Route::get('/old-ephemera-media-store', [\App\Http\Controllers\Data\HardyAndEphemeraController::class, 'insertMediaEphemera']);
Route::get('/old-ephemera-store', [\App\Http\Controllers\Data\HardyAndEphemeraController::class, 'storeEphemeraFromConfig']);
Route::get('/old-hardy-reels-store', [\App\Http\Controllers\Data\HardyAndEphemeraController::class, 'storeHardyReelsFromConfig']);