<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Register a new user with email and password
     * POST /api/v1/auth/signup
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone_number' => ['required', 'string', 'max:20', 'unique:users'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate email verification OTP
        $verificationCode = random_int(100000, 999999);
        $verificationExpiry = now()->addMinutes(15);

        // Create user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password, // auto-hashed by model cast
            'phone_number' => $request->phone_number,
            'auth_provider' => 'email',
            'is_guest' => false,
            'is_admin' => false,
            'email_verification_code' => $verificationCode,
            'email_verification_expires_at' => $verificationExpiry,
        ]);

        // Send verification email
        $this->sendVerificationEmail($user->email, $verificationCode);

        // Issue Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'email_verification_required' => true,
        ], 201);
    }

    /**
     * Login with phone number and password
     * POST /api/v1/auth/login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 422);
        }

        // Check if email is verified
        if (is_null($user->email_verified_at)) {
            return response()->json([
                'message' => 'Email not verified.',
                'email_verification_required' => true
            ], 403);
        }

        // Revoke old tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Google login
     * POST /api/v1/auth/google
     */
    public function googleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_token' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify Google token
        try {
            $response = Http::get('https://oauth2.googleapis.com/tokeninfo', [
                'id_token' => $request->id_token
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Invalid Google token.'
                ], 422);
            }

            $googleData = $response->json();

            // Extract required fields
            $email = $googleData['email'] ?? null;
            $googleId = $googleData['sub'] ?? null;
            $name = $googleData['name'] ?? '';

            if (!$email || !$googleId) {
                return response()->json([
                    'message' => 'Invalid Google token.'
                ], 422);
            }

            // Find or create user
            $user = User::where('email', $email)->first();

            if ($user) {
                // Update existing user
                if (is_null($user->google_id)) {
                    $user->google_id = $googleId;
                }
                if (is_null($user->auth_provider)) {
                    $user->auth_provider = 'google';
                }
                $user->save();
            } else {
                // Create new user
                $nameParts = explode(' ', trim($name), 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';

                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'password' => null,
                    'google_id' => $googleId,
                    'auth_provider' => 'google',
                    'is_guest' => false,
                    'email_verified_at' => now(), // Google implies verified email
                ]);
            }

            // Issue Sanctum token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to verify Google token.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    /**
     * Get authenticated user
     * GET /api/v1/auth/me
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ], 200);
    }

    /**
     * Update user profile and consumer information
     * PUT /api/v1/user/profile
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'phone_number' => ['sometimes', 'string', 'max:20', 'unique:users,phone_number,' . $request->user()->id],
            'year_of_birth' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'is_decision_maker' => ['nullable', 'boolean'],
            'has_medicare_part_b' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $user->update($request->only([
            'first_name',
            'last_name',
            'phone_number',
            'year_of_birth',
            'zip_code',
            'is_decision_maker',
            'has_medicare_part_b',
        ]));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh()
        ], 200);
    }

    /**
     * Request email verification OTP
     * POST /api/v1/auth/email/verify/request
     */
    public function requestEmailVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Generate new OTP
        $verificationCode = random_int(100000, 999999);
        $verificationExpiry = now()->addMinutes(15);

        $user->update([
            'email_verification_code' => $verificationCode,
            'email_verification_expires_at' => $verificationExpiry,
        ]);

        // Send email
        $this->sendVerificationEmail($user->email, $verificationCode);

        return response()->json([
            'message' => 'Verification code sent successfully'
        ], 200);
    }

    /**
     * Confirm email verification with OTP
     * POST /api/v1/auth/email/verify/confirm
     */
    public function confirmEmailVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Verify code
        if ($user->email_verification_code !== $request->code) {
            return response()->json([
                'message' => 'Invalid or expired verification code.'
            ], 422);
        }

        if ($user->email_verification_expires_at < now()) {
            return response()->json([
                'message' => 'Invalid or expired verification code.'
            ], 422);
        }

        // Mark email as verified
        $user->update([
            'email_verified_at' => now(),
            'email_verification_code' => null,
            'email_verification_expires_at' => null,
        ]);

        return response()->json([
            'message' => 'Email verified successfully',
            'user' => $user
        ], 200);
    }

    /**
     * Request password reset OTP
     * POST /api/v1/auth/password/forgot
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        // Generic success to avoid user enumeration
        if (!$user) {
            return response()->json([
                'message' => 'If the email exists, a password reset code has been sent.'
            ], 200);
        }

        // Generate password reset OTP
        $resetCode = random_int(100000, 999999);
        $resetExpiry = now()->addMinutes(15);

        $user->update([
            'password_reset_code' => $resetCode,
            'password_reset_expires_at' => $resetExpiry,
        ]);

        // Send email
        $this->sendPasswordResetEmail($user->email, $resetCode);

        return response()->json([
            'message' => 'If the email exists, a password reset code has been sent.'
        ], 200);
    }

    /**
     * Reset password using OTP
     * POST /api/v1/auth/password/reset
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid or expired reset code.'
            ], 422);
        }

        // Verify code
        if ($user->password_reset_code !== $request->code) {
            return response()->json([
                'message' => 'Invalid or expired reset code.'
            ], 422);
        }

        if ($user->password_reset_expires_at < now()) {
            return response()->json([
                'message' => 'Invalid or expired reset code.'
            ], 422);
        }

        // Update password
        $user->update([
            'password' => $request->password, // auto-hashed by model cast
            'password_reset_code' => null,
            'password_reset_expires_at' => null,
        ]);

        // Revoke all tokens (force re-login)
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Password reset successfully'
        ], 200);
    }

    /**
     * Send email verification OTP
     */
    private function sendVerificationEmail($email, $code)
    {
        Mail::raw(
            "Your Medicare account verification code is: {$code}\n\nThis code will expire in 15 minutes.",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Verify your Medicare account');
            }
        );
    }

    /**
     * Send password reset OTP
     */
    private function sendPasswordResetEmail($email, $code)
    {
        Mail::raw(
            "Your Medicare password reset code is: {$code}\n\nThis code will expire in 15 minutes.\n\nIf you did not request this, please ignore this email.",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Reset your Medicare password');
            }
        );
    }
}
