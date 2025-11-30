<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Medicare Advantage Plus',
                'description' => 'Leading provider of comprehensive Medicare Advantage plans with extensive network coverage and additional benefits.',
                'image_url' => 'https://via.placeholder.com/300x200?text=Medicare+Advantage+Plus',
                'rating' => 4.8,
                'phone' => '+1-800-MEDICARE',
                'specialties' => ['medicare_advantage', 'prescription_drugs', 'dental', 'vision']
            ],
            [
                'name' => 'Golden Years Insurance',
                'description' => 'Specialized insurance company focusing on Medicare supplements and long-term care solutions for seniors.',
                'image_url' => 'https://via.placeholder.com/300x200?text=Golden+Years+Insurance',
                'rating' => 4.6,
                'phone' => '+1-855-GOLDEN-1',
                'specialties' => ['supplement', 'long_term_care', 'life_insurance']
            ],
            [
                'name' => 'HealthFirst Medicare Solutions',
                'description' => 'Innovative healthcare solutions provider offering personalized Medicare plans with technology-driven health management.',
                'image_url' => 'https://via.placeholder.com/300x200?text=HealthFirst+Medicare',
                'rating' => 4.7,
                'phone' => '+1-888-HEALTH-1',
                'specialties' => ['medicare_advantage', 'prescription_drugs', 'dental']
            ],
            [
                'name' => 'Senior Care Networks',
                'description' => 'Comprehensive network of healthcare providers dedicated to serving Medicare beneficiaries with quality care.',
                'image_url' => 'https://via.placeholder.com/300x200?text=Senior+Care+Networks',
                'rating' => 4.5,
                'phone' => '+1-877-SENIOR-1',
                'specialties' => ['medicare_advantage', 'supplement', 'long_term_care']
            ],
            [
                'name' => 'All-American Medicare',
                'description' => 'Trusted Medicare insurance provider with over 30 years of experience serving seniors across the United States.',
                'image_url' => 'https://via.placeholder.com/300x200?text=All-American+Medicare',
                'rating' => 4.4,
                'phone' => '+1-800-ALL-AMER',
                'specialties' => ['supplement', 'prescription_drugs', 'medicare_advantage']
            ],
            [
                'name' => 'Blue Shield Medicare',
                'description' => 'Trusted healthcare coverage with extensive provider networks and comprehensive Medicare Advantage plans.',
                'image_url' => 'https://via.placeholder.com/300x200?text=Blue+Shield+Medicare',
                'rating' => 4.6,
                'phone' => '+1-800-BLUE-MED',
                'specialties' => ['medicare_advantage', 'supplement', 'prescription_drugs', 'dental', 'vision']
            ],
            [
                'name' => 'United Medicare Solutions',
                'description' => 'National leader in Medicare coverage with innovative plans and excellent customer service.',
                'image_url' => 'https://via.placeholder.com/300x200?text=United+Medicare',
                'rating' => 4.7,
                'phone' => '+1-888-UNITED-M',
                'specialties' => ['medicare_advantage', 'prescription_drugs', 'supplement']
            ],
            [
                'name' => 'Humana Medicare Care',
                'description' => 'Comprehensive Medicare solutions with focus on preventive care and wellness programs.',
                'image_url' => 'https://via.placeholder.com/300x200?text=Humana+Medicare',
                'rating' => 4.5,
                'phone' => '+1-877-HUMANA-1',
                'specialties' => ['medicare_advantage', 'prescription_drugs', 'dental', 'vision', 'long_term_care']
            ]
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
