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
            // Real Medicare Companies
            [
                'name' => 'Aetna Medicare',
                'description' => 'Comprehensive Medicare Advantage plans with extensive provider networks and additional benefits including prescription drugs, dental, and vision coverage.',
                'image_url' => '/storage/company-logos/aetna.svg',
                'rating' => 4.8,
                'phone' => '+1-800-123-4567',
                'specialties' => ['medicare_advantage', 'prescription_drugs', 'dental', 'vision']
            ],
            [
                'name' => 'Blue Cross Blue Shield',
                'description' => 'Trusted healthcare coverage with extensive provider networks and comprehensive Medicare Advantage plans serving millions of seniors.',
                'image_url' => '/storage/company-logos/bcbs.svg',
                'rating' => 4.7,
                'phone' => '+1-800-345-6789',
                'specialties' => ['medicare_advantage', 'supplement', 'prescription_drugs', 'dental', 'vision']
            ],
            [
                'name' => 'Humana',
                'description' => 'Comprehensive Medicare solutions with focus on preventive care, wellness programs, and personalized health management.',
                'image_url' => '/storage/company-logos/humana.svg',
                'rating' => 4.6,
                'phone' => '+1-877-246-8262',
                'specialties' => ['medicare_advantage', 'prescription_drugs', 'dental', 'vision', 'long_term_care']
            ],
            [
                'name' => 'SelectQuote',
                'description' => 'Insurance brokerage specializing in Medicare plans, helping seniors compare and find the best Medicare Advantage and Supplement plans.',
                'image_url' => '/storage/company-logos/selectquote.svg',
                'rating' => 4.5,
                'phone' => '+1-800-959-6043',
                'specialties' => ['medicare_advantage', 'supplement', 'prescription_drugs']
            ],
            [
                'name' => 'Get Me Healthcare',
                'description' => 'Dedicated health insurance broker offering Medicare supplement insurance, ACA plans, dental, and life insurance solutions.',
                'image_url' => '/storage/company-logos/gmhc.svg',
                'rating' => 4.4,
                'phone' => '+1-866-611-0519',
                'specialties' => ['supplement', 'medicare_advantage', 'dental', 'life_insurance']
            ],
            [
                'name' => 'iHealth Plans',
                'description' => 'Online health insurance marketplace providing quotes and enrollment for Medicare Advantage, Medicare Supplement, and other health coverage options.',
                'image_url' => '/storage/company-logos/ihealth.svg',
                'rating' => 4.5,
                'phone' => '+1-888-334-4339',
                'specialties' => ['medicare_advantage', 'supplement', 'prescription_drugs']
            ],
            [
                'name' => 'eHealth Insurance',
                'description' => 'Online health insurance exchange offering comprehensive Medicare plans, ACA coverage, and supplemental insurance options.',
                'image_url' => '/storage/company-logos/ehealth.svg',
                'rating' => 4.6,
                'phone' => '+1-800-356-4043',
                'specialties' => ['medicare_advantage', 'supplement', 'prescription_drugs', 'dental']
            ],
            [
                'name' => 'Your Way Insurance',
                'description' => 'Licensed Medicare consultants helping seniors navigate Medicare complexity and find the best plans across 25+ states.',
                'image_url' => '/storage/company-logos/yourway.svg',
                'rating' => 4.5,
                'phone' => '+1-773-200-0003',
                'specialties' => ['medicare_advantage', 'supplement', 'prescription_drugs']
            ],
            [
                'name' => 'FamFirst Insurance',
                'description' => 'Family-focused insurance brokerage offering Medicare Advantage plans, supplements, and comprehensive coverage solutions.',
                'image_url' => '/storage/company-logos/famfirst.svg',
                'rating' => 4.4,
                'phone' => '+1-800-574-5590',
                'specialties' => ['medicare_advantage', 'supplement', 'prescription_drugs', 'dental']
            ],
            // Additional popular Medicare companies
            [
                'name' => 'United Healthcare',
                'description' => 'National leader in Medicare coverage with innovative plans and excellent customer service for over 20 million members.',
                'image_url' => '/storage/company-logos/unitedhealthcare.svg',
                'rating' => 4.7,
                'phone' => '+1-888-627-8862',
                'specialties' => ['medicare_advantage', 'prescription_drugs', 'supplement']
            ],
            [
                'name' => 'Cigna Medicare',
                'description' => 'Comprehensive Medicare solutions with focus on preventive care, wellness programs, and extensive provider networks.',
                'image_url' => '/storage/company-logos/cigna.svg',
                'rating' => 4.6,
                'phone' => '+1-800-668-3755',
                'specialties' => ['medicare_advantage', 'prescription_drugs', 'dental', 'vision']
            ],
            [
                'name' => 'Kaiser Permanente',
                'description' => 'Integrated healthcare system providing comprehensive Medicare Advantage plans with preventive care and wellness focus.',
                'image_url' => '/storage/company-logos/kaiser.svg',
                'rating' => 4.5,
                'phone' => '+1-800-456-7890',
                'specialties' => ['medicare_advantage', 'prescription_drugs', 'dental', 'vision']
            ],
            [
                'name' => 'Anthem Medicare',
                'description' => 'One of the nation\'s largest Medicare Advantage providers with innovative plans and comprehensive coverage options.',
                'image_url' => '/storage/company-logos/anthem.svg',
                'rating' => 4.5,
                'phone' => '+1-844-663-2273',
                'specialties' => ['medicare_advantage', 'supplement', 'prescription_drugs']
            ]
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(
                ['name' => $company['name']],
                $company
            );
        }
    }
}
