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

# Ephemera Types Routes
Route::post('/create-type', [\App\Http\Controllers\EphemeraTypesController::class, 'store']);
Route::get('/types', [\App\Http\Controllers\EphemeraTypesController::class, 'index']);
Route::post('/type/delete', [\App\Http\Controllers\EphemeraTypesController::class, 'deleteType']);
Route::post('/type/edit', [\App\Http\Controllers\EphemeraTypesController::class, 'editType']);
Route::get('/type/{id}', [\App\Http\Controllers\EphemeraTypesController::class, 'show']);

# HardyReel Routes
Route::post('/create-hardyreel', [\App\Http\Controllers\HardyReelController::class, 'store']);
Route::get('/hardyreels', [\App\Http\Controllers\HardyReelController::class, 'index']);
Route::post('/hardyreel/delete', [\App\Http\Controllers\HardyReelController::class, 'deleteHardyReel']);
Route::post('/hardyreel/edit', [\App\Http\Controllers\HardyReelController::class, 'editHardyReel']);
Route::get('/hardyreel/{id}', [\App\Http\Controllers\HardyReelController::class, 'show']);
Route::get('/hardyreels/next', [\App\Http\Controllers\HardyReelController::class, 'getNextHardyReelId']);

# OtherReel Routes
Route::post('/create-otherreel', [\App\Http\Controllers\OtherReelController::class, 'store']);
Route::get('/otherreels', [\App\Http\Controllers\OtherReelController::class, 'index']);
Route::post('/otherreel/delete', [\App\Http\Controllers\OtherReelController::class, 'deleteOtherReel']);
Route::post('/otherreel/edit', [\App\Http\Controllers\OtherReelController::class, 'editOtherReel']);
Route::get('/otherreel/{id}', [\App\Http\Controllers\OtherReelController::class, 'show']);
Route::get('/otherreels/next', [\App\Http\Controllers\OtherReelController::class, 'getNextOtherReelId']);

# Lures Routes
Route::post('/create-lures', [\App\Http\Controllers\LuresController::class, 'store']);
Route::get('/lures', [\App\Http\Controllers\LuresController::class, 'index']);
Route::post('/lures/delete', [\App\Http\Controllers\LuresController::class, 'deleteLures']);
Route::post('/lures/edit', [\App\Http\Controllers\LuresController::class, 'editLures']);
Route::get('/lure/{id}', [\App\Http\Controllers\LuresController::class, 'show']);
Route::get('/lures/next', [\App\Http\Controllers\LuresController::class, 'getNextLuresId']);

# Rods Routes
Route::post('/create-rods', [\App\Http\Controllers\RodsController::class, 'store']);
Route::get('/rods', [\App\Http\Controllers\RodsController::class, 'index']);
Route::post('/rods/delete', [\App\Http\Controllers\RodsController::class, 'deleteRods']);
Route::post('/rods/edit', [\App\Http\Controllers\RodsController::class, 'editRods']);
Route::get('/rod/{id}', [\App\Http\Controllers\RodsController::class, 'show']);
Route::get('/rods/next', [\App\Http\Controllers\RodsController::class, 'getNextRodsId']);

# OtherTackle Routes
Route::post('/create-othertackle', [\App\Http\Controllers\OtherTackleController::class, 'store']);
Route::get('/othertackles', [\App\Http\Controllers\OtherTackleController::class, 'index']);
Route::post('/othertackles/delete', [\App\Http\Controllers\OtherTackleController::class, 'deleteOtherTackle']);
Route::post('/othertackles/edit', [\App\Http\Controllers\OtherTackleController::class, 'editOtherTackle']);
Route::get('/othertackle/{id}', [\App\Http\Controllers\OtherTackleController::class, 'show']);
Route::get('/othertackles/next', [\App\Http\Controllers\OtherTackleController::class, 'getNextOtherTackleId']);

# InUseTackles Routes
Route::post('/create-inusetackle', [\App\Http\Controllers\InUseTackleController::class, 'store']);
Route::get('/inusetackles', [\App\Http\Controllers\InUseTackleController::class, 'index']);
Route::post('/inusetackles/delete', [\App\Http\Controllers\InUseTackleController::class, 'deleteInUseTackle']);
Route::post('/inusetackles/edit', [\App\Http\Controllers\InUseTackleController::class, 'editInUseTackle']);
Route::get('/inusetackle/{id}', [\App\Http\Controllers\InUseTackleController::class, 'show']);
Route::get('/inusetackles/next', [\App\Http\Controllers\InUseTackleController::class, 'getNextInUseTackleId']);

# Penn Catalogues Routes
Route::post('/create-penn-catalogues', [\App\Http\Controllers\PennCatalogueController::class, 'store']);
Route::get('/penn-catalogues', [\App\Http\Controllers\PennCatalogueController::class, 'index']);
Route::post('/penn-catalogues/delete', [\App\Http\Controllers\PennCatalogueController::class, 'deletePennCatalogue']);
Route::post('/penn-catalogues/edit', [\App\Http\Controllers\PennCatalogueController::class, 'editPennCatalogue']);
Route::get('/penn-catalogue/{id}', [\App\Http\Controllers\PennCatalogueController::class, 'show']);

# Book Media Routes
Route::post('/create-book-media', [\App\Http\Controllers\BookMediaController::class, 'store']);
Route::post('/book-media/delete', [\App\Http\Controllers\BookMediaController::class, 'deleteBookMedia']);
Route::get('/book-media/{id}', [\App\Http\Controllers\BookMediaController::class, 'show']);
Route::post('/book-media/set-thumbnail', [\App\Http\Controllers\BookMediaController::class, 'setThumbnail']);

# Ephemera Media Routes
Route::post('/create-ephemera-media', [\App\Http\Controllers\EphemeraMediaController::class, 'store']);
Route::post('/ephemera-media/delete', [\App\Http\Controllers\EphemeraMediaController::class, 'deleteEphemeraMedia']);
Route::get('/ephemera-media/{id}', [\App\Http\Controllers\EphemeraMediaController::class, 'show']);
Route::post('/ephemera-media/set-thumbnail', [\App\Http\Controllers\EphemeraMediaController::class, 'setThumbnail']);

# HardyReels Media Routes
Route::post('/create-hardyreel-media', [\App\Http\Controllers\HardyReelMediaController::class, 'store']);
Route::post('/hardyreel-media/delete', [\App\Http\Controllers\HardyReelMediaController::class, 'deleteHardyReelMedia']);
Route::get('/hardyreel-media/{id}', [\App\Http\Controllers\HardyReelMediaController::class, 'show']);
Route::post('/hardyreel-media/set-thumbnail', [\App\Http\Controllers\HardyReelMediaController::class, 'setThumbnail']);

# OtherReels Media Routes
Route::post('/create-otherreel-media', [\App\Http\Controllers\OtherReelMediaController::class, 'store']);
Route::post('/otherreel-media/delete', [\App\Http\Controllers\OtherReelMediaController::class, 'deleteOtherReelMedia']);
Route::get('/otherreel-media/{id}', [\App\Http\Controllers\OtherReelMediaController::class, 'show']);
Route::post('/otherreel-media/set-thumbnail', [\App\Http\Controllers\OtherReelMediaController::class, 'setThumbnail']);

# Lures Media Routes
Route::post('/create-lures-media', [\App\Http\Controllers\LuresMediaController::class, 'store']);
Route::post('/lures-media/delete', [\App\Http\Controllers\LuresMediaController::class, 'deleteLuresMedia']);
Route::get('/lures-media/{id}', [\App\Http\Controllers\LuresMediaController::class, 'show']);
Route::post('/lures-media/set-thumbnail', [\App\Http\Controllers\LuresMediaController::class, 'setThumbnail']);

# Rods Media Routes
Route::post('/create-rods-media', [\App\Http\Controllers\RodsMediaController::class, 'store']);
Route::post('/rods-media/delete', [\App\Http\Controllers\RodsMediaController::class, 'deleteRodsMedia']);
Route::get('/rods-media/{id}', [\App\Http\Controllers\RodsMediaController::class, 'show']);
Route::post('/rods-media/set-thumbnail', [\App\Http\Controllers\RodsMediaController::class, 'setThumbnail']);

# Rods Media Routes
Route::post('/create-penn-catalogues-media', [\App\Http\Controllers\PennCatalogueMediaController::class, 'store']);
Route::post('/penn-catalogues-media/delete', [\App\Http\Controllers\PennCatalogueMediaController::class, 'deletePennCatalogueMedia']);
Route::get('/penn-catalogues-media/{id}', [\App\Http\Controllers\PennCatalogueMediaController::class, 'show']);
Route::post('/penn-catalogues-media/set-thumbnail', [\App\Http\Controllers\PennCatalogueMediaController::class, 'setThumbnail']);

# OtherTackle Media Routes
Route::post('/create-othertackle-media', [\App\Http\Controllers\OtherTackleMediaController::class, 'store']);
Route::post('/othertackle-media/delete', [\App\Http\Controllers\OtherTackleMediaController::class, 'deleteOtherTackleMedia']);
Route::get('/othertackle-media/{id}', [\App\Http\Controllers\OtherTackleMediaController::class, 'show']);
Route::post('/othertackle-media/set-thumbnail', [\App\Http\Controllers\OtherTackleMediaController::class, 'setThumbnail']);

# OtherTackle Media Routes
Route::post('/create-inusetackle-media', [\App\Http\Controllers\InUseTackleMediaController::class, 'store']);
Route::post('/inusetackle-media/delete', [\App\Http\Controllers\InUseTackleMediaController::class, 'deleteInUseTackleMedia']);
Route::get('/inusetackle-media/{id}', [\App\Http\Controllers\InUseTackleMediaController::class, 'show']);
Route::post('/inusetackle-media/set-thumbnail', [\App\Http\Controllers\InUseTackleMediaController::class, 'setThumbnail']);



Route::get('/old-books-store', [\App\Http\Controllers\Data\BookController::class, 'storeBooksFromConfig']);
Route::get('/old-books-media-store', [\App\Http\Controllers\Data\BookController::class, 'insertMedia']);
Route::get('/old-hardy-media-store', [\App\Http\Controllers\Data\HardyAndEphemeraController::class, 'insertMediaHardy']);
Route::get('/old-ephemera-media-store', [\App\Http\Controllers\Data\HardyAndEphemeraController::class, 'insertMediaEphemera']);
Route::get('/old-ephemera-store', [\App\Http\Controllers\Data\HardyAndEphemeraController::class, 'storeEphemeraFromConfig']);
Route::get('/old-other-reels-store', [\App\Http\Controllers\Data\OtherReelsOtherTacklesInUseTacklesController::class, 'storeoTHERReelsFromConfig']);
Route::get('/old-other-tackles-store', [\App\Http\Controllers\Data\OtherReelsOtherTacklesInUseTacklesController::class, 'storeOtherTackleFromConfig']);
Route::get('/old-inuse-tackles-store', [\App\Http\Controllers\Data\OtherReelsOtherTacklesInUseTacklesController::class, 'storeInUseTackleFromConfig']);
Route::get('/old-hardy-reels-store', [\App\Http\Controllers\Data\HardyAndEphemeraController::class, 'storeHardyReelsFromConfig']);
Route::get('/old-lures-store', [\App\Http\Controllers\Data\LuresRodsPennController::class, 'storeLuresFromConfig']);
Route::get('/old-rods-store', [\App\Http\Controllers\Data\LuresRodsPennController::class, 'storeRodsFromConfig']);
Route::get('/old-penn-store', [\App\Http\Controllers\Data\LuresRodsPennController::class, 'storePennFromConfig']);
Route::get('/old-lures-media-store', [\App\Http\Controllers\Data\LuresRodsPennController::class, 'insertMediaLures']);
Route::get('/old-rods-media-store', [\App\Http\Controllers\Data\LuresRodsPennController::class, 'insertMediaRods']);
Route::get('/old-penn-media-store', [\App\Http\Controllers\Data\LuresRodsPennController::class, 'insertMediaPenn']);
