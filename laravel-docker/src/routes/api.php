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

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'E-Market API is running',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/auth/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    
    // Products API
    Route::apiResource('products', App\Http\Controllers\Api\ProductController::class);
    
    // Categories API
    Route::apiResource('categories', App\Http\Controllers\Api\CategoryController::class);
    
    // Orders API
    Route::apiResource('orders', App\Http\Controllers\Api\OrderController::class);
});
