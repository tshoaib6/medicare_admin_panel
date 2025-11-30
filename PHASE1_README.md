# Medicare App - Phase 1 Authentication System

## Overview
This is the Phase 1 implementation of the Medicare App authentication system. It provides a complete API-based authentication with email/password and Google OAuth, email verification via OTP, password reset via OTP, and an admin panel for user management.

## Database Schema

### Users Table
The `users` table contains all user information including consumer data:

```sql
- id (bigint, primary)
- first_name (string)
- last_name (string) 
- email (string, unique)
- password (string, nullable for Google-only accounts)
- phone_number (string, nullable)
- year_of_birth (integer, nullable)
- zip_code (string, nullable)
- is_decision_maker (boolean, default false)
- has_medicare_part_b (boolean, default false)
- google_id (string, nullable)
- auth_provider (string, nullable) // "email" or "google"
- is_guest (boolean, default false)
- is_admin (boolean, default false)
- email_verified_at (timestamp, nullable)
- email_verification_code (string, nullable)
- email_verification_expires_at (timestamp, nullable)
- password_reset_code (string, nullable)
- password_reset_expires_at (timestamp, nullable)
- remember_token
- created_at
- updated_at
```

## Email Configuration

Add these settings to your `.env` file for Gmail SMTP:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail_address@gmail.com
MAIL_PASSWORD=your_gmail_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Note**: You'll need to generate an App Password from your Google Account settings to use Gmail SMTP.

## API Endpoints

All API endpoints are prefixed with `/api/v1`

### Public Endpoints

#### Authentication
- **POST** `/api/v1/auth/signup` - Register new user
- **POST** `/api/v1/auth/login` - Email/password login
- **POST** `/api/v1/auth/google` - Google OAuth login

#### Email Verification
- **POST** `/api/v1/auth/email/verify/request` - Send/resend verification OTP
- **POST** `/api/v1/auth/email/verify/confirm` - Verify email with OTP

#### Password Reset
- **POST** `/api/v1/auth/password/forgot` - Send password reset OTP
- **POST** `/api/v1/auth/password/reset` - Reset password with OTP

### Protected Endpoints (require `auth:sanctum`)
- **POST** `/api/v1/auth/logout` - Logout user
- **GET** `/api/v1/auth/me` - Get authenticated user data
- **PUT** `/api/v1/user/profile` - Update user profile

## API Usage Examples

### 1. User Registration
```bash
POST /api/v1/auth/signup
Content-Type: application/json

{
    "first_name": "John",
    "last_name": "Doe", 
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone_number": "555-1234" // required and unique
}
```

Response:
```json
{
    "user": { ...user_object... },
    "token": "sanctum_token_here",
    "email_verification_required": true
}
```

### 2. Email Verification
```bash
POST /api/v1/auth/email/verify/request
Content-Type: application/json

{
    "email": "john@example.com"
}
```

Then confirm with:
```bash
POST /api/v1/auth/email/verify/confirm
Content-Type: application/json

{
    "email": "john@example.com",
    "code": "123456"
}
```

### 3. Login
```bash
POST /api/v1/auth/login
Content-Type: application/json

{
    "phone_number": "555-1234",
    "password": "password123"
}
```

Response (if email verified):
```json
{
    "user": { ...user_object... },
    "token": "sanctum_token_here"
}
```

Response (if email not verified):
```json
{
    "message": "Email not verified.",
    "email_verification_required": true
}
```

### 4. Google Login
```bash
POST /api/v1/auth/google
Content-Type: application/json

{
    "id_token": "google_id_token_from_frontend"
}
```

### 5. Update Profile
```bash
PUT /api/v1/user/profile
Authorization: Bearer {token}
Content-Type: application/json

{
    "first_name": "John",
    "last_name": "Smith",
    "phone_number": "555-9999",
    "year_of_birth": 1985,
    "zip_code": "12345",
    "is_decision_maker": true,
    "has_medicare_part_b": true
}
```

## Admin Panel

Access the admin panel at `/admin/dashboard` (requires login + `is_admin = true`)

### Admin Routes
- **GET** `/admin/dashboard` - Statistics overview
- **GET** `/admin/users` - Users list with search
- **GET** `/admin/users/{id}` - User details

### Creating Admin User

To make a user an admin, update the database directly:
```sql
UPDATE users SET is_admin = 1 WHERE email = 'admin@example.com';
```

Or use tinker:
```bash
php artisan tinker
User::where('email', 'admin@example.com')->update(['is_admin' => true]);
```

## Setup Instructions

1. **Run migrations:**
   ```bash
   php artisan migrate
   ```

2. **Configure email in `.env`** (see Email Configuration section above)

3. **Install Sanctum** (if not already installed):
   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```

4. **Create admin user:**
   ```bash
   php artisan tinker
   User::create([
       'first_name' => 'Admin',
       'last_name' => 'User',
       'email' => 'admin@example.com',
       'password' => 'password',
       'auth_provider' => 'email',
       'email_verified_at' => now(),
       'is_admin' => true
   ]);
   ```

5. **Test the endpoints** using Postman or similar API client

## Security Features

- Sanctum token-based authentication for API
- Email verification required before login (configurable)
- OTP codes expire after 15 minutes
- Password reset tokens are single-use
- Admin panel access restricted to `is_admin` users
- Google OAuth token verification via Google's API
- Passwords are automatically hashed

## Key Files Created/Modified

- `database/migrations/0001_01_01_000000_create_users_table.php` - Updated users schema
- `app/Models/User.php` - Updated with all fields and casts
- `app/Http/Controllers/Api/AuthController.php` - Complete API auth controller
- `app/Http/Middleware/AdminMiddleware.php` - Admin access middleware
- `app/Http/Controllers/Admin/AdminUserController.php` - Admin panel controller
- `routes/api.php` - All API routes
- `routes/web.php` - Admin panel routes
- `resources/views/admin/` - Admin panel Blade views
- `bootstrap/app.php` - Middleware registration

## Notes

- No separate consumer table - all data is in the users table
- OTP codes are 6-digit random numbers
- Google login automatically marks email as verified
- Email verification is required for email/password logins
- Admin panel uses simple HTML/CSS (no external frameworks)
- All validation follows Laravel best practices