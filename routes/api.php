<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/', function (Request $request) {
//     return response()->json([
//         'message' => 'hello world'
//     ], 200);
// });

Route::controller(AuthController::class)->as('auth.')->prefix('/auth')->group(function() {
    Route::post('/me', 'me')->name('me')->middleware(['auth:sanctum']);
    Route::post('/login', 'login')->name('login')->middleware(['guest']);
    Route::post('/register', 'register')->name('register')->middleware(['guest']);
    Route::post('/forgot-password', 'forgot')->name('forgot')->middleware('guest');
    Route::post('/reset-password', 'resetPassword')->name('reset-password')->middleware(['guest']);
    Route::post('/verify-email', 'verify')->name('verification.verify')->middleware('auth:sanctum');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth:sanctum');
});

Route::apiResource("articles", ArticleController::class);
// ->middleware(['auth:sanctum']);
