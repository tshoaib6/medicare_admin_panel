<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiController;

/**
 * API v1 Routes
 * All endpoints are prefixed with /api/v1
 */
Route::prefix('v1')->group(function () {
    
    // API Documentation & Health
    Route::get('/', [ApiController::class, 'documentation']);
    Route::get('/health', [ApiController::class, 'health']);
    
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
    
    // Public Routes (no authentication required)
    Route::get('/companies', [App\Http\Controllers\Api\CompanyController::class, 'index']);
    Route::get('/companies/{company}', [App\Http\Controllers\Api\CompanyController::class, 'show']);
    Route::get('/plans', [App\Http\Controllers\Api\PlanController::class, 'index']);
    Route::get('/plans/{plan}', [App\Http\Controllers\Api\PlanController::class, 'show']);
    Route::get('/questionnaires', [App\Http\Controllers\Api\QuestionnaireController::class, 'index']);
    Route::get('/questionnaires/{questionnaire}', [App\Http\Controllers\Api\QuestionnaireController::class, 'show']);
    Route::get('/questionnaires/{questionnaire}/questions', [App\Http\Controllers\Api\QuestionnaireController::class, 'questions']);
    Route::get('/ads/active', [App\Http\Controllers\Api\AdController::class, 'active']);
    Route::post('/ads/{ad}/impression', [App\Http\Controllers\Api\AdController::class, 'trackImpression']);
    Route::post('/ads/{ad}/click', [App\Http\Controllers\Api\AdController::class, 'trackClick']);
    
    // Protected Routes (require auth:sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        // User Authentication & Profile
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::put('/user/profile', [AuthController::class, 'updateProfile']);
        
        // User Callback Requests
        Route::get('/my/callback-requests', [App\Http\Controllers\Api\CallbackRequestController::class, 'myRequests']);
        Route::post('/callback-requests', [App\Http\Controllers\Api\CallbackRequestController::class, 'store']);
        
        // User Activity Logs
        Route::get('/my/activities', [App\Http\Controllers\Api\ActivityLogController::class, 'myActivities']);
        Route::post('/activities/log', [App\Http\Controllers\Api\ActivityLogController::class, 'log']);
        
        // Admin Only Routes (require admin privileges)
        Route::middleware('admin')->group(function () {
            // Companies Management
            Route::apiResource('companies', App\Http\Controllers\Api\CompanyController::class)->except(['index', 'show']);
            Route::get('/companies/stats', [App\Http\Controllers\Api\CompanyController::class, 'stats']);
            
            // Plans Management  
            Route::apiResource('plans', App\Http\Controllers\Api\PlanController::class)->except(['index', 'show']);
            Route::patch('/plans/{plan}/toggle-availability', [App\Http\Controllers\Api\PlanController::class, 'toggleAvailability']);
            
            // Questionnaires Management
            Route::apiResource('questionnaires', App\Http\Controllers\Api\QuestionnaireController::class)->except(['index', 'show']);
            Route::post('/questionnaires/{questionnaire}/questions', [App\Http\Controllers\Api\QuestionnaireController::class, 'addQuestion']);
            Route::patch('/questionnaires/{questionnaire}/toggle-status', [App\Http\Controllers\Api\QuestionnaireController::class, 'toggleStatus']);
            
            // Questions Management (nested)
            Route::apiResource('questions', App\Http\Controllers\Api\QuestionController::class);
            Route::post('/questions/{question}/options', [App\Http\Controllers\Api\QuestionOptionController::class, 'store']);
            Route::put('/questions/{question}/options/{option}', [App\Http\Controllers\Api\QuestionOptionController::class, 'update']);
            Route::delete('/questions/{question}/options/{option}', [App\Http\Controllers\Api\QuestionOptionController::class, 'destroy']);
            
            // Callback Requests Management
            Route::apiResource('callback-requests', App\Http\Controllers\Api\CallbackRequestController::class)->except(['store']);
            Route::patch('/callback-requests/{callbackRequest}/status', [App\Http\Controllers\Api\CallbackRequestController::class, 'updateStatus']);
            Route::get('/callback-requests/stats', [App\Http\Controllers\Api\CallbackRequestController::class, 'stats']);
            Route::post('/callback-requests/bulk-action', [App\Http\Controllers\Api\CallbackRequestController::class, 'bulkAction']);
            
            // Ads Management
            Route::apiResource('ads', App\Http\Controllers\Api\AdController::class);
            Route::patch('/ads/{ad}/toggle-status', [App\Http\Controllers\Api\AdController::class, 'toggleStatus']);
            Route::post('/ads/bulk-action', [App\Http\Controllers\Api\AdController::class, 'bulkAction']);
            Route::get('/ads/analytics', [App\Http\Controllers\Api\AdController::class, 'analytics']);
            
            // Activity Logs (Admin View)
            Route::get('/activities', [App\Http\Controllers\Api\ActivityLogController::class, 'index']);
            Route::get('/activities/{activityLog}', [App\Http\Controllers\Api\ActivityLogController::class, 'show']);
            Route::get('/activities/stats', [App\Http\Controllers\Api\ActivityLogController::class, 'stats']);
            Route::get('/activities/export', [App\Http\Controllers\Api\ActivityLogController::class, 'export']);
            
            // Users Management (Admin)
            Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'index']);
            Route::get('/users/{user}', [App\Http\Controllers\Api\UserController::class, 'show']);
            Route::patch('/users/{user}/toggle-admin', [App\Http\Controllers\Api\UserController::class, 'toggleAdmin']);
            Route::delete('/users/{user}', [App\Http\Controllers\Api\UserController::class, 'destroy']);
        });
    });
});
