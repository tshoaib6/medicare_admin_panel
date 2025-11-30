<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * Get API documentation and available endpoints
     */
    public function documentation(): JsonResponse
    {
        $documentation = [
            'api_version' => 'v1',
            'base_url' => url('/api/v1'),
            'authentication' => [
                'type' => 'Bearer Token (Laravel Sanctum)',
                'header' => 'Authorization: Bearer {token}'
            ],
            'endpoints' => [
                'authentication' => [
                    'POST /auth/signup' => 'Register new user',
                    'POST /auth/login' => 'Login with phone_number and password', 
                    'POST /auth/google' => 'Login with Google OAuth',
                    'POST /auth/email/verify/request' => 'Request email verification OTP',
                    'POST /auth/email/verify/confirm' => 'Confirm email verification with OTP',
                    'POST /auth/password/forgot' => 'Request password reset OTP',
                    'POST /auth/password/reset' => 'Reset password with OTP',
                    'POST /auth/logout' => 'Logout (requires auth)',
                    'GET /auth/me' => 'Get authenticated user profile (requires auth)',
                    'PUT /user/profile' => 'Update user profile (requires auth)'
                ],
                'companies' => [
                    'GET /companies' => 'List all companies (public)',
                    'GET /companies/{id}' => 'Get company details (public)',
                    'POST /companies' => 'Create company (admin only)',
                    'PUT /companies/{id}' => 'Update company (admin only)',
                    'DELETE /companies/{id}' => 'Delete company (admin only)'
                ],
                'plans' => [
                    'GET /plans' => 'List all plans (public)',
                    'GET /plans/{id}' => 'Get plan details (public)',
                    'POST /plans' => 'Create plan (admin only)',
                    'PUT /plans/{id}' => 'Update plan (admin only)',
                    'DELETE /plans/{id}' => 'Delete plan (admin only)'
                ],
                'questionnaires' => [
                    'GET /questionnaires' => 'List all questionnaires (public)',
                    'GET /questionnaires/{id}' => 'Get questionnaire with questions (public)',
                    'GET /questionnaires/{id}/questions' => 'Get questions for questionnaire (public)',
                    'POST /questionnaires' => 'Create questionnaire (admin only)',
                    'PUT /questionnaires/{id}' => 'Update questionnaire (admin only)',
                    'DELETE /questionnaires/{id}' => 'Delete questionnaire (admin only)',
                    'POST /questionnaires/{id}/questions' => 'Add question to questionnaire (admin only)'
                ],
                'callback_requests' => [
                    'GET /my/callback-requests' => 'Get user\'s callback requests (requires auth)',
                    'POST /callback-requests' => 'Create callback request (requires auth)',
                    'GET /callback-requests' => 'List all callback requests (admin only)',
                    'GET /callback-requests/{id}' => 'Get callback request details (admin only)',
                    'PUT /callback-requests/{id}' => 'Update callback request (admin only)',
                    'DELETE /callback-requests/{id}' => 'Delete callback request (admin only)',
                    'PATCH /callback-requests/{id}/status' => 'Update callback request status (admin only)'
                ],
                'ads' => [
                    'GET /ads/active' => 'Get active ads for display (public)',
                    'POST /ads/{id}/impression' => 'Track ad impression (public)',
                    'POST /ads/{id}/click' => 'Track ad click (public)',
                    'GET /ads' => 'List all ads (admin only)',
                    'POST /ads' => 'Create ad (admin only)',
                    'PUT /ads/{id}' => 'Update ad (admin only)',
                    'DELETE /ads/{id}' => 'Delete ad (admin only)'
                ],
                'activity_logs' => [
                    'GET /my/activities' => 'Get user\'s activities (requires auth)',
                    'POST /activities/log' => 'Log user activity (requires auth)',
                    'GET /activities' => 'List all activities (admin only)',
                    'GET /activities/{id}' => 'Get activity details (admin only)',
                    'GET /activities/stats' => 'Get activity statistics (admin only)'
                ]
            ],
            'query_parameters' => [
                'pagination' => 'per_page (default: 15), page (default: 1)',
                'search' => 'search (string)',
                'filters' => 'Various filters available per endpoint',
                'dates' => 'date_from, date_to (YYYY-MM-DD format)'
            ],
            'response_format' => [
                'success' => true,
                'data' => '{}|[]',
                'message' => 'Success message'
            ],
            'error_format' => [
                'success' => false,
                'message' => 'Error message',
                'errors' => 'Validation errors (if applicable)'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $documentation,
            'message' => 'API documentation retrieved successfully'
        ]);
    }

    /**
     * Get API health status
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'status' => 'healthy',
                'timestamp' => now()->toISOString(),
                'version' => 'v1.0.0'
            ],
            'message' => 'API is healthy'
        ]);
    }
}