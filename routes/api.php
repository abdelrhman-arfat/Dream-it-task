<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\BooksController;
use App\Http\Controllers\Api\UserController;


// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Book routes
Route::prefix('book')->group(function () {
    Route::get('/', [BooksController::class, 'index']);       // List all books
    Route::get('/{id}', [BooksController::class, 'show']);    // Get single book
});
// Protected routes - require Sanctum token
Route::middleware('auth:sanctum')->group(function () {

    // User routes
    Route::prefix('user')->group(function () {
        Route::get("/profile", [UserController::class, 'profile']);
        Route::put("/profile", [UserController::class, 'updateProfile']);
        Route::prefix('book')->group(function () {
            Route::post('/', [BooksController::class, 'store']);      // Create book
            Route::put('/{id}', [BooksController::class, 'update']);  // Update book
            Route::delete('/{id}', [BooksController::class, 'destroy']); // Delete book
        });
    });
});
