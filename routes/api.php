<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
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


// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // URL Management
    Route::get('/urls', [ApiController::class, 'index']);
    Route::post('/urls', [ApiController::class, 'store']);
    Route::get('/urls/{id}', [ApiController::class, 'show']);
    Route::put('/urls/{id}', [ApiController::class, 'update']);
    Route::delete('/urls/{id}', [ApiController::class, 'destroy']);
    Route::post('/urls/{id}/disable', [ApiController::class, 'disable']);

    // URL Analytics
    Route::get('/urls/{id}/stats', [ApiController::class, 'stats']);
});

// Redirect exit page (public)
Route::get('/r/{short_code}', [RedirectController::class, 'exitPage']);


