<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/**
 * API v1 Routes
 * All endpoints are prefixed with /api/v1
 */
Route::prefix('v1')->group(function () {
    
    // Public Auth Routes
    Route::post('/auth/signup', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']); // Uses phone_number + password
    Route::post('/auth/google', [AuthController::class, 'googleLogin']);
    
    // Email Verification Routes
    Route::post('/auth/email/verify/request', [AuthController::class, 'requestEmailVerification']);
    Route::post('/auth/email/verify/confirm', [AuthController::class, 'confirmEmailVerification']);
    
    // Password Reset Routes
    Route::post('/auth/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/password/reset', [AuthController::class, 'resetPassword']);
    
    // Protected Routes (require auth:sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    });
});
