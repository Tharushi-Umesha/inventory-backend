<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Middleware\RoleMiddleware;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/test', function () {
    return response()->json(['message' => 'API routes working!']);
});

Route::middleware('auth:sanctum')->group(function () {

    // Auth endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());

    // Orders: accessible by all authenticated users
    Route::apiResource('orders', OrderController::class);

    // Products: only accessible by admin or manager
    Route::middleware([RoleMiddleware::class . ':admin,manager'])->group(function () {
        Route::apiResource('products', ProductController::class);
    });

    // Users — admin only
    Route::middleware([RoleMiddleware::class . ':admin'])->group(function () {
        Route::apiResource('users', UserController::class)->except(['create', 'edit']);
    });

    // Reports — admin and manager only
    Route::middleware([RoleMiddleware::class . ':admin,manager'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index']);
    });
});
