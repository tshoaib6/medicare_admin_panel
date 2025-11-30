<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Company;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, get companies to associate plans with
        $companies = \App\Models\Company::all();
        $companyIds = $companies->pluck('id')->toArray();
        
        $plans = [
            [
                'slug' => 'medicare-advantage-premium',
                'title' => 'Medicare Advantage Premium',
                'description' => 'Comprehensive Medicare Advantage plan with $0 premium, prescription drug coverage, and additional benefits like dental and vision.',
                'company_id' => $companyIds[0] ?? 1,
                'benefits' => [
                    '$0 monthly premium',
                    'Prescription drug coverage included',
                    'Dental, vision, and hearing coverage',
                    'Wellness programs and gym membership',
                    'Transportation services',
                    'Over-the-counter allowance'
                ],
                'eligibility_criteria' => 'Must be enrolled in Medicare Part A and Part B, live in plan service area',
                'coverage_details' => 'Covers all Medicare Part A and Part B services plus additional benefits',
                'pricing_info' => '$0 monthly premium. Copays: $0 primary care, $10 specialist',
                'enrollment_period' => 'October 15 - December 7 (Annual Open Enrollment)',
                'contact_info' => 'Call 1-800-MEDICARE or visit our website to enroll',
                'is_active' => true
            ],
            [
                'slug' => 'medicare-supplement-plan-f',
                'title' => 'Medicare Supplement Plan F',
                'description' => 'The most comprehensive Medigap plan available, covering all Medicare deductibles, copays, and coinsurance.',
                'company_id' => $companyIds[1] ?? 1,
                'benefits' => [
                    'Medicare Part A deductible coverage',
                    'Medicare Part B deductible coverage',
                    'Medicare Part A and B coinsurance',
                    'First three pints of blood',
                    'Medicare Part B excess charges',
                    'Foreign travel emergency coverage'
                ],
                'eligibility_criteria' => 'Available only to those eligible for Medicare before January 1, 2020',
                'coverage_details' => 'Covers virtually all out-of-pocket costs not covered by Original Medicare',
                'pricing_info' => 'Monthly premiums vary by location, typically $150-$400 per month',
                'enrollment_period' => 'Six-month Medigap Open Enrollment period begins when you turn 65',
                'contact_info' => 'Contact licensed insurance agents for quotes and enrollment assistance',
                'is_active' => true
            ],
            [
                'slug' => 'prescription-drug-plan',
                'title' => 'Medicare Part D Prescription Drug Plan',
                'description' => 'Standalone prescription drug coverage to supplement Original Medicare or Medigap plans.',
                'company_id' => $companyIds[2] ?? 1,
                'benefits' => [
                    'Comprehensive drug formulary',
                    'Low monthly premiums',
                    'Mail-order pharmacy option',
                    'Generic drug preferred pricing',
                    'Coverage gap protection',
                    'Nationwide network'
                ],
                'eligibility_criteria' => 'Must have Medicare Part A or Part B',
                'coverage_details' => 'Covers prescription medications according to plan formulary',
                'pricing_info' => 'Low monthly premiums starting from $15/month',
                'enrollment_period' => 'October 15 - December 7 annually',
                'contact_info' => 'Enroll online or call customer service',
                'is_active' => true
            ],
            [
                'slug' => 'medicare-advantage-hmo',
                'title' => 'Medicare Advantage HMO',
                'description' => 'Health Maintenance Organization plan with integrated care coordination and focus on preventive services.',
                'company_id' => $companyIds[3] ?? 1,
                'benefits' => [
                    'Coordinated care through primary care physician',
                    'Prescription drug coverage',
                    'Preventive care at no cost',
                    'Specialist referrals managed',
                    'Dental and vision benefits',
                    'Health and wellness programs'
                ],
                'eligibility_criteria' => 'Must live in HMO service area and select PCP from network',
                'coverage_details' => 'All care coordinated through your primary care physician',
                'pricing_info' => 'Low monthly premium with affordable copays for services',
                'enrollment_period' => 'Annual Open Enrollment: October 15 - December 7',
                'contact_info' => 'Visit our provider directory online or call customer service',
                'is_active' => true
            ],
            [
                'slug' => 'medicare-advantage-ppo',
                'title' => 'Medicare Advantage PPO',
                'description' => 'Preferred Provider Organization plan offering flexibility to see any Medicare-approved provider.',
                'company_id' => $companyIds[4] ?? 1,
                'benefits' => [
                    'See any Medicare-approved doctor',
                    'No referrals needed for specialists',
                    'Out-of-network coverage available',
                    'Prescription drug coverage',
                    'Annual wellness visit',
                    'Fitness program benefits'
                ],
                'eligibility_criteria' => 'Available to Medicare beneficiaries in the plan service area',
                'coverage_details' => 'Lower costs when using in-network providers',
                'pricing_info' => 'Monthly premium applies. Lower copays for in-network services',
                'enrollment_period' => 'October 15 - December 7 annually',
                'contact_info' => 'Online enrollment available or call enrollment specialists',
                'is_active' => true
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
