<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ad;

class AdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ads = [
            [
                'title' => 'Medicare Open Enrollment 2024',
                'description' => 'Don\'t miss your chance to review and change your Medicare coverage during Open Enrollment.',
                'type' => 'banner',
                'content_html' => '<div class="bg-blue-600 text-white p-4 rounded-lg"><h3 class="text-xl font-bold">Medicare Open Enrollment</h3><p>October 15 - December 7, 2024</p><button class="bg-white text-blue-600 px-4 py-2 rounded mt-2">Learn More</button></div>',
                'image_url' => 'https://via.placeholder.com/728x90?text=Medicare+Open+Enrollment+2024',
                'target_url' => '/open-enrollment-guide',
                'target_audience' => 'All Medicare beneficiaries',
                'start_date' => '2024-10-01',
                'end_date' => '2024-12-07',
                'click_count' => 0,
                'impression_count' => 0,
                'is_active' => true
            ],
            [
                'title' => 'Free Medicare Plan Comparison',
                'description' => 'Compare Medicare Advantage and Medigap plans side-by-side to find the best coverage for your needs.',
                'type' => 'sidebar',
                'content_html' => '<div class="border border-gray-300 p-4 rounded"><h4 class="font-semibold">Compare Plans</h4><p class="text-sm text-gray-600">Find the perfect Medicare plan for you</p><a href="#" class="text-blue-600 hover:underline">Start Comparison →</a></div>',
                'image_url' => 'https://via.placeholder.com/300x250?text=Plan+Comparison+Tool',
                'target_url' => '/plan-comparison',
                'target_audience' => 'Users researching Medicare plans',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'click_count' => 0,
                'impression_count' => 0,
                'is_active' => true
            ],
            [
                'title' => 'Get Your Medicare Questions Answered',
                'description' => 'Schedule a free consultation with our Medicare experts to get personalized advice.',
                'type' => 'popup',
                'content_html' => '<div class="bg-white p-6 rounded-lg shadow-lg max-w-md"><h3 class="text-lg font-bold mb-4">Need Help with Medicare?</h3><p class="mb-4">Get free, personalized advice from our licensed agents.</p><button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Schedule Free Call</button><button class="ml-2 text-gray-500 hover:text-gray-700">Maybe Later</button></div>',
                'image_url' => 'https://via.placeholder.com/400x300?text=Medicare+Consultation',
                'target_url' => '/schedule-consultation',
                'target_audience' => 'New visitors, users spending >2 minutes on site',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'click_count' => 0,
                'impression_count' => 0,
                'is_active' => true
            ],
            [
                'title' => 'Medicare Supplement Special Offer',
                'description' => 'Save up to $50/month on Medigap Plan G with our exclusive partner rates.',
                'type' => 'inline',
                'content_html' => '<div class="bg-green-50 border border-green-200 p-4 rounded-lg"><div class="flex items-center"><div class="text-green-600 mr-3"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg></div><div><h4 class="font-semibold text-green-800">Special Medigap Offer</h4><p class="text-green-700">Save up to $50/month on comprehensive coverage</p><a href="#" class="text-green-600 font-medium hover:underline">Get Quote →</a></div></div></div>',
                'image_url' => 'https://via.placeholder.com/600x200?text=Medigap+Special+Offer',
                'target_url' => '/medigap-special-offer',
                'target_audience' => 'Users viewing Medigap plan pages',
                'start_date' => '2024-01-15',
                'end_date' => '2024-03-15',
                'click_count' => 0,
                'impression_count' => 0,
                'is_active' => true
            ],
            [
                'title' => 'Medicare Part D Enrollment Reminder',
                'description' => 'Don\'t forget to enroll in Medicare Part D prescription drug coverage to avoid late penalties.',
                'type' => 'banner',
                'content_html' => '<div class="bg-yellow-100 border-l-4 border-yellow-500 p-4"><div class="flex"><div class="ml-3"><p class="text-sm text-yellow-700"><strong>Important Reminder:</strong> Medicare Part D enrollment deadline approaching. Avoid late penalties by enrolling today.</p></div></div></div>',
                'image_url' => 'https://via.placeholder.com/728x90?text=Part+D+Enrollment+Reminder',
                'target_url' => '/part-d-enrollment',
                'target_audience' => 'Users without Part D coverage',
                'start_date' => '2024-11-01',
                'end_date' => '2024-12-07',
                'click_count' => 0,
                'impression_count' => 0,
                'is_active' => true
            ]
        ];

        foreach ($ads as $ad) {
            Ad::create($ad);
        }
    }
}
