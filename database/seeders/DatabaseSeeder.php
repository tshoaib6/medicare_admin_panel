<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@medicare.com',
            'phone_number' => '+1-555-0001',
            'password' => 'admin123',
            'auth_provider' => 'email',
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        // Create Regular Users
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone_number' => '+1-555-1001',
            'password' => 'password123',
            'auth_provider' => 'email',
            'email_verified_at' => now(),
            'year_of_birth' => 1980,
            'zip_code' => '12345',
            'is_decision_maker' => true,
            'has_medicare_part_b' => true,
        ]);

        User::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone_number' => '+1-555-1002',
            'password' => 'password123',
            'auth_provider' => 'email',
            'email_verified_at' => now(),
            'year_of_birth' => 1975,
            'zip_code' => '67890',
            'is_decision_maker' => false,
            'has_medicare_part_b' => true,
        ]);

        User::create([
            'first_name' => 'Michael',
            'last_name' => 'Johnson',
            'email' => 'michael.johnson@example.com',
            'phone_number' => '+1-555-1003',
            'password' => 'password123',
            'auth_provider' => 'email',
            'email_verified_at' => null, // Not verified
            'year_of_birth' => 1965,
            'zip_code' => '54321',
            'is_decision_maker' => true,
            'has_medicare_part_b' => false,
        ]);

        User::create([
            'first_name' => 'Sarah',
            'last_name' => 'Williams',
            'email' => 'sarah.williams@gmail.com',
            'phone_number' => '+1-555-1004',
            'password' => null, // Google user
            'google_id' => '1234567890',
            'auth_provider' => 'google',
            'email_verified_at' => now(),
            'year_of_birth' => 1990,
            'zip_code' => '98765',
            'is_decision_maker' => false,
            'has_medicare_part_b' => false,
        ]);

        User::create([
            'first_name' => 'Robert',
            'last_name' => 'Brown',
            'email' => 'robert.brown@example.com',
            'phone_number' => '+1-555-1005',
            'password' => 'password123',
            'auth_provider' => 'email',
            'email_verified_at' => now(),
            'year_of_birth' => 1955,
            'zip_code' => '11111',
            'is_decision_maker' => true,
            'has_medicare_part_b' => true,
        ]);

        // Seed Phase 2 data
        $this->call([
            CompanySeeder::class,
            PlanSeeder::class,
            QuestionnaireSeeder::class,
            AdSeeder::class,
            ActivityLogSeeder::class,
        ]);
    }
}
