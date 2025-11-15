<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BooksController;
use App\Http\Controllers\Api\UserController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Book routes


// Protected routes - require Sanctum token
Route::middleware('auth:sanctum')->group(function () {

    // Authenticated user's books
    Route::prefix('books')->group(function () {
        Route::get('/', [BooksController::class, 'allBooks']);
        Route::get('/{id}', [BooksController::class, 'show']);
        Route::post('/', [BooksController::class, 'store']);
        Route::put('/{id}', [BooksController::class, 'update']);
        Route::delete('/{id}', [BooksController::class, 'destroy']);
    });

    // User profile routes
    Route::prefix('user')->group(function () {
        Route::get('/profile', [UserController::class, 'profile']);
        Route::put('/profile', [UserController::class, 'updateProfile']);
    });
});
