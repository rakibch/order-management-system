<?php

use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ---------------------------
    // Public routes
    // ---------------------------
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // ---------------------------
    // Authenticated routes
    // ---------------------------
    Route::middleware('auth:api')->group(function () {

        // User profile & token
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);

        // ---------------------------
        // Product routes (Admin & Vendor only)
        // ---------------------------
        Route::middleware('role:admin,vendor')->group(function () {
            Route::post('products', [ProductController::class, 'store']);
            Route::put('products/{product}', [ProductController::class, 'update']);
            Route::delete('products/{product}', [ProductController::class, 'destroy']);
        });

        // ---------------------------
        // Order creation (Customer only)
        // ---------------------------
        Route::middleware('role:customer')->group(function () {
            Route::post('orders', [OrderController::class, 'store']);
        });

        // ---------------------------
        // Read routes accessible by all roles (Admin, Vendor, Customer)
        // ---------------------------
        Route::middleware('role:admin,vendor,customer')->group(function () {
            // Products
            Route::get('products', [ProductController::class, 'index']);
            Route::get('products/{product}', [ProductController::class, 'show']);

            // Orders
            Route::get('orders', [OrderController::class, 'index']);
            Route::get('orders/{order}', [OrderController::class, 'show']);
            Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);
        });
    });
});
