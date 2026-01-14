<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SearchProductController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('search', [SearchProductController::class, 'search']);

Route::controller(AuthController::class)
    ->prefix('auths')
    ->middleware([])
    ->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/verify-otp', 'verifyOtp');
        Route::post('/forgot-password', 'forgotPassword');
        Route::post('/reset-password', 'resetPassword');
    });

Route::controller(AuthController::class)
    ->prefix('auths')
    ->middleware(['auth:sanctum'])
    ->group(function () {

        Route::post('/logout', 'logout');
    });



Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)
        ->prefix('users')
        ->middleware([])
        ->group(function () {
            // Route::get('/', 'index');
            // Route::get('{users}/', 'find');
            // Route::post('/', 'store');
        });
});
