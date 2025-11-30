<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users for activities (create admin if none exist)
        $adminUser = User::where('is_admin', true)->first();
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@medicare.com',
                'password' => bcrypt('password'),
                'is_admin' => true,
                'email_verified_at' => now()
            ]);
        }
        
        $regularUser = User::where('is_admin', false)->first();
        if (!$regularUser) {
            $regularUser = User::create([
                'name' => 'John Medicare',
                'email' => 'john@example.com', 
                'password' => bcrypt('password'),
                'is_admin' => false,
                'email_verified_at' => now()
            ]);
        }

        $activities = [
            // Login Activities
            [
                'user_id' => $adminUser->id,
                'action' => 'login',
                'description' => 'Administrator logged into the system',
                'metadata' => [
                    'login_method' => 'email',
                    'remember_me' => false,
                    'session_id' => 'sess_' . bin2hex(random_bytes(16))
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(120),
            ],
            
            // Company Management
            [
                'user_id' => $adminUser->id,
                'action' => 'company_created',
                'description' => 'New Medicare insurance company "BlueCross BlueShield" added to system',
                'metadata' => [
                    'company_id' => 1,
                    'company_name' => 'BlueCross BlueShield',
                    'specialties_count' => 5,
                    'rating' => 4.5
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(115),
            ],
            
            [
                'user_id' => $adminUser->id,
                'action' => 'company_updated',
                'description' => 'Updated company information for "Aetna Medicare"',
                'metadata' => [
                    'company_id' => 2,
                    'changes' => [
                        'rating' => ['from' => 4.2, 'to' => 4.3],
                        'phone' => ['from' => '1-800-OLD-NUM', 'to' => '1-800-AETNA-12']
                    ]
                ],
                'ip_address' => '192.168.1.100', 
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(110),
            ],

            // Plan Management
            [
                'user_id' => $adminUser->id,
                'action' => 'plan_created',
                'description' => 'New Medicare Advantage plan "Complete Care Plus" created',
                'metadata' => [
                    'plan_id' => 1,
                    'plan_name' => 'Complete Care Plus',
                    'plan_type' => 'Medicare Advantage',
                    'monthly_premium' => 89.99,
                    'benefits_count' => 12
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(105),
            ],
            
            // User Activities
            [
                'user_id' => $regularUser->id,
                'action' => 'login',
                'description' => 'User logged into the Medicare portal',
                'metadata' => [
                    'login_method' => 'email',
                    'remember_me' => true,
                    'redirect_url' => '/dashboard'
                ],
                'ip_address' => '203.0.113.45',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(95),
            ],

            [
                'user_id' => $regularUser->id,
                'action' => 'profile_viewed',
                'description' => 'User viewed their profile information',
                'metadata' => [
                    'page' => 'profile',
                    'sections_viewed' => ['personal_info', 'contact_details'],
                    'time_spent' => 45
                ],
                'ip_address' => '203.0.113.45',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(90),
            ],

            // Questionnaire Activities
            [
                'user_id' => $adminUser->id,
                'action' => 'questionnaire_created',
                'description' => 'New questionnaire "Medicare Eligibility Assessment" created',
                'metadata' => [
                    'questionnaire_id' => 1,
                    'title' => 'Medicare Eligibility Assessment',
                    'questions_count' => 8,
                    'estimated_time' => 10
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(85),
            ],

            // Callback Request Activities
            [
                'user_id' => $regularUser->id,
                'action' => 'callback_requested',
                'description' => 'New callback request submitted for Medicare consultation',
                'metadata' => [
                    'callback_id' => 1,
                    'preferred_time' => 'Morning (9 AM - 12 PM)',
                    'topic' => 'Medicare Part D enrollment',
                    'priority' => 'medium'
                ],
                'ip_address' => '203.0.113.45',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(75),
            ],

            [
                'user_id' => $adminUser->id,
                'action' => 'callback_status_updated',
                'description' => 'Callback request status changed from pending to in_progress',
                'metadata' => [
                    'callback_id' => 1,
                    'status_from' => 'pending',
                    'status_to' => 'in_progress',
                    'assigned_agent' => 'Sarah Johnson'
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(70),
            ],

            // Advertisement Activities  
            [
                'user_id' => $adminUser->id,
                'action' => 'ad_created',
                'description' => 'New advertisement "Medicare Open Enrollment 2024" created',
                'metadata' => [
                    'ad_id' => 1,
                    'title' => 'Medicare Open Enrollment 2024',
                    'type' => 'banner',
                    'target_audience' => 'All Medicare beneficiaries',
                    'scheduled_start' => '2024-10-01'
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(60),
            ],

            [
                'user_id' => $adminUser->id,
                'action' => 'ad_status_changed',
                'description' => 'Advertisement "Free Medicare Plan Comparison" activated',
                'metadata' => [
                    'ad_id' => 2,
                    'status_from' => 'inactive',
                    'status_to' => 'active',
                    'activation_reason' => 'Campaign launch'
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(55),
            ],

            // System Activities
            [
                'user_id' => null,
                'action' => 'system_maintenance',
                'description' => 'Automated system maintenance completed successfully',
                'metadata' => [
                    'maintenance_type' => 'database_optimization',
                    'duration_seconds' => 180,
                    'tables_optimized' => 12,
                    'space_freed_mb' => 45.7
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Laravel/10.x Artisan Command',
                'created_at' => Carbon::now()->subMinutes(30),
            ],

            // Recent Activities
            [
                'user_id' => $adminUser->id,
                'action' => 'dashboard_viewed',
                'description' => 'Administrator accessed the main dashboard',
                'metadata' => [
                    'widgets_loaded' => ['stats', 'recent_activities', 'quick_actions'],
                    'load_time_ms' => 234,
                    'device_type' => 'desktop'
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(15),
            ],

            [
                'user_id' => $adminUser->id,
                'action' => 'export_requested',
                'description' => 'CSV export generated for activity logs',
                'metadata' => [
                    'export_type' => 'activity_logs',
                    'date_range' => ['from' => Carbon::now()->subDays(30)->toDateString(), 'to' => Carbon::now()->toDateString()],
                    'total_records' => 156,
                    'file_size_kb' => 23.4
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(5),
            ],

            [
                'user_id' => $adminUser->id,
                'action' => 'activity_log_viewed',
                'description' => 'Administrator viewed activity logs interface',
                'metadata' => [
                    'filters_applied' => ['date_range' => 'last_30_days'],
                    'total_displayed' => 25,
                    'page_number' => 1
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subMinutes(2),
            ]
        ];

        foreach ($activities as $activity) {
            ActivityLog::create($activity);
        }
    }
}
