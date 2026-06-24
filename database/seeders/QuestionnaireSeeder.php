<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Plan;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planSlugs = [
            'medicare-advantage-premium',
            'medicare-supplement-plan-f',
            'prescription-drug-plan',
            'medicare-advantage-hmo',
            'medicare-advantage-ppo',
        ];

        $plansBySlug = Plan::query()
            ->whereIn('slug', $planSlugs)
            ->get()
            ->keyBy('slug');

        $questionnaireFlow = [
            [
                'plan_slug' => 'medicare-advantage-premium',
                'title' => 'Medicare Advantage Eligibility Assessment',
                'description' => 'Determine your eligibility and best options for Medicare Advantage plans.',
                'instructions' => 'Please answer all questions honestly to help us find the best Medicare Advantage plan for your needs.',
                'estimated_time' => 10,
                'is_active' => true,
                'questions' => [
                    [
                        'order_number' => 1,
                        'question_text' => 'What is your current age?',
                        'question_type' => 'single_choice',
                        'is_required' => true,
                        'options' => [
                            ['label' => 'Under 65', 'value' => 'under_65'],
                            ['label' => '65-70', 'value' => '65_70'],
                            ['label' => '71-75', 'value' => '71_75'],
                            ['label' => '76-80', 'value' => '76_80'],
                            ['label' => 'Over 80', 'value' => 'over_80'],
                        ],
                    ],
                    [
                        'order_number' => 2,
                        'question_text' => 'Are you currently enrolled in Medicare Part A and Part B?',
                        'question_type' => 'single_choice',
                        'is_required' => true,
                        'options' => [
                            ['label' => 'Yes, both Part A and Part B', 'value' => 'both'],
                            ['label' => 'Only Part A', 'value' => 'part_a_only'],
                            ['label' => 'Only Part B', 'value' => 'part_b_only'],
                            ['label' => 'Neither', 'value' => 'neither'],
                        ],
                    ],
                    [
                        'order_number' => 3,
                        'question_text' => 'Which additional benefits are most important to you? (Select all that apply)',
                        'question_type' => 'multiple_choice',
                        'is_required' => false,
                        'options' => [
                            ['label' => 'Prescription drug coverage', 'value' => 'prescription'],
                            ['label' => 'Dental coverage', 'value' => 'dental'],
                            ['label' => 'Vision coverage', 'value' => 'vision'],
                            ['label' => 'Hearing aids', 'value' => 'hearing'],
                            ['label' => 'Wellness programs', 'value' => 'wellness'],
                        ],
                    ],
                ],
            ],
            [
                'plan_slug' => 'medicare-supplement-plan-f',
                'title' => 'Medigap Coverage Assessment',
                'description' => 'Find the right Medicare Supplement plan to fill gaps in your Medicare coverage.',
                'instructions' => 'This assessment will help determine which Medigap plan best meets your healthcare needs and budget.',
                'estimated_time' => 8,
                'is_active' => true,
                'questions' => [
                    [
                        'order_number' => 1,
                        'question_text' => 'What is your main concern about Medicare coverage gaps?',
                        'question_type' => 'single_choice',
                        'is_required' => true,
                        'options' => [
                            ['label' => 'High deductibles', 'value' => 'deductibles'],
                            ['label' => 'Coinsurance costs', 'value' => 'coinsurance'],
                            ['label' => 'Copayments', 'value' => 'copayments'],
                            ['label' => 'All out-of-pocket costs', 'value' => 'all_costs'],
                        ],
                    ],
                    [
                        'order_number' => 2,
                        'question_text' => 'How often do you visit specialists in a typical year?',
                        'question_type' => 'single_choice',
                        'is_required' => true,
                        'options' => [
                            ['label' => 'Rarely', 'value' => 'rarely'],
                            ['label' => '1-3 times', 'value' => '1_3'],
                            ['label' => '4-6 times', 'value' => '4_6'],
                            ['label' => 'More than 6 times', 'value' => 'more_6'],
                        ],
                    ],
                    [
                        'order_number' => 3,
                        'question_text' => 'What monthly premium range are you most comfortable with?',
                        'question_type' => 'single_choice',
                        'is_required' => false,
                        'options' => [
                            ['label' => 'Under $100', 'value' => 'under_100'],
                            ['label' => '$100-$200', 'value' => '100_200'],
                            ['label' => '$201-$350', 'value' => '201_350'],
                            ['label' => 'Above $350', 'value' => 'above_350'],
                        ],
                    ],
                ],
            ],
            [
                'plan_slug' => 'prescription-drug-plan',
                'title' => 'Personal Health Assessment',
                'description' => 'Evaluate your health needs to recommend the most suitable Medicare plan options.',
                'instructions' => 'Please provide accurate information about your current health status and healthcare needs.',
                'estimated_time' => 15,
                'is_active' => true,
                'questions' => [
                    [
                        'order_number' => 1,
                        'question_text' => 'How would you rate your overall health?',
                        'question_type' => 'single_choice',
                        'is_required' => true,
                        'options' => [
                            ['label' => 'Excellent', 'value' => 'excellent'],
                            ['label' => 'Very Good', 'value' => 'very_good'],
                            ['label' => 'Good', 'value' => 'good'],
                            ['label' => 'Fair', 'value' => 'fair'],
                            ['label' => 'Poor', 'value' => 'poor'],
                        ],
                    ],
                    [
                        'order_number' => 2,
                        'question_text' => 'Do you currently take any prescription medications regularly?',
                        'question_type' => 'single_choice',
                        'is_required' => true,
                        'options' => [
                            ['label' => 'No medications', 'value' => 'none'],
                            ['label' => '1-2 medications', 'value' => '1_2'],
                            ['label' => '3-5 medications', 'value' => '3_5'],
                            ['label' => 'More than 5 medications', 'value' => 'more_5'],
                        ],
                    ],
                    [
                        'order_number' => 3,
                        'question_text' => 'How important is broad pharmacy network access for your prescriptions?',
                        'question_type' => 'single_choice',
                        'is_required' => false,
                        'options' => [
                            ['label' => 'Not important', 'value' => 'not_important'],
                            ['label' => 'Somewhat important', 'value' => 'somewhat_important'],
                            ['label' => 'Very important', 'value' => 'very_important'],
                            ['label' => 'Essential', 'value' => 'essential'],
                        ],
                    ],
                ],
            ],
            [
                'plan_slug' => 'medicare-advantage-hmo',
                'title' => 'Medicare Advantage HMO Fit Check',
                'description' => 'Assess whether coordinated care and in-network providers align with your healthcare preferences.',
                'instructions' => 'Answer the following questions to evaluate if an HMO structure is a good fit for your needs.',
                'estimated_time' => 7,
                'is_active' => true,
                'questions' => [
                    [
                        'order_number' => 1,
                        'question_text' => 'Are you comfortable choosing a primary care physician to coordinate your care?',
                        'question_type' => 'single_choice',
                        'is_required' => true,
                        'options' => [
                            ['label' => 'Yes, that works for me', 'value' => 'yes'],
                            ['label' => 'No, I prefer direct specialist access', 'value' => 'no'],
                            ['label' => 'Not sure', 'value' => 'not_sure'],
                        ],
                    ],
                    [
                        'order_number' => 2,
                        'question_text' => 'How often do you seek care outside your local provider network?',
                        'question_type' => 'single_choice',
                        'is_required' => true,
                        'options' => [
                            ['label' => 'Never', 'value' => 'never'],
                            ['label' => 'Occasionally', 'value' => 'occasionally'],
                            ['label' => 'Frequently', 'value' => 'frequently'],
                        ],
                    ],
                    [
                        'order_number' => 3,
                        'question_text' => 'What matters most for your plan choice?',
                        'question_type' => 'single_choice',
                        'is_required' => false,
                        'options' => [
                            ['label' => 'Lower monthly cost', 'value' => 'lower_cost'],
                            ['label' => 'Provider flexibility', 'value' => 'provider_flexibility'],
                            ['label' => 'Predictable copays', 'value' => 'predictable_copays'],
                        ],
                    ],
                ],
            ],
            [
                'plan_slug' => 'medicare-advantage-ppo',
                'title' => 'Medicare Advantage PPO Flexibility Assessment',
                'description' => 'Evaluate your need for out-of-network access and specialist flexibility with a PPO plan.',
                'instructions' => 'This short assessment helps determine if PPO flexibility is worth the potential additional cost for you.',
                'estimated_time' => 7,
                'is_active' => true,
                'questions' => [
                    [
                        'order_number' => 1,
                        'question_text' => 'How important is being able to see specialists without referrals?',
                        'question_type' => 'single_choice',
                        'is_required' => true,
                        'options' => [
                            ['label' => 'Not important', 'value' => 'not_important'],
                            ['label' => 'Somewhat important', 'value' => 'somewhat_important'],
                            ['label' => 'Very important', 'value' => 'very_important'],
                        ],
                    ],
                    [
                        'order_number' => 2,
                        'question_text' => 'Do you expect to use out-of-network providers in the next 12 months?',
                        'question_type' => 'single_choice',
                        'is_required' => true,
                        'options' => [
                            ['label' => 'No', 'value' => 'no'],
                            ['label' => 'Possibly', 'value' => 'possibly'],
                            ['label' => 'Yes', 'value' => 'yes'],
                        ],
                    ],
                    [
                        'order_number' => 3,
                        'question_text' => 'What is your preferred balance between cost and flexibility?',
                        'question_type' => 'single_choice',
                        'is_required' => false,
                        'options' => [
                            ['label' => 'Lowest cost', 'value' => 'lowest_cost'],
                            ['label' => 'Balanced', 'value' => 'balanced'],
                            ['label' => 'Maximum flexibility', 'value' => 'maximum_flexibility'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($questionnaireFlow as $flowItem) {
            $plan = $plansBySlug->get($flowItem['plan_slug']);

            if (!$plan) {
                continue;
            }

            $questionnaire = Questionnaire::updateOrCreate(
                [
                    'title' => $flowItem['title'],
                    'plan_id' => $plan->id,
                ],
                [
                    'description' => $flowItem['description'],
                    'instructions' => $flowItem['instructions'],
                    'estimated_time' => $flowItem['estimated_time'],
                    'is_active' => $flowItem['is_active'],
                ]
            );

            foreach ($flowItem['questions'] as $questionData) {
                $question = Question::updateOrCreate(
                    [
                        'questionnaire_id' => $questionnaire->id,
                        'order_number' => $questionData['order_number'],
                    ],
                    [
                        'question_text' => $questionData['question_text'],
                        'question_type' => $questionData['question_type'],
                        'is_required' => $questionData['is_required'],
                    ]
                );

                foreach ($questionData['options'] ?? [] as $optionData) {
                    QuestionOption::updateOrCreate(
                        [
                            'question_id' => $question->id,
                            'value' => $optionData['value'],
                        ],
                        [
                            'label' => $optionData['label'],
                        ]
                    );
                }
            }
        }
    }
}
