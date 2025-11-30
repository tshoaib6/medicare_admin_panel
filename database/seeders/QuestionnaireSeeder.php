<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $plans = Plan::all();
        
        // Create Medicare Advantage Eligibility Questionnaire
        $questionnaire1 = Questionnaire::create([
            'title' => 'Medicare Advantage Eligibility Assessment',
            'description' => 'Determine your eligibility and best options for Medicare Advantage plans.',
            'plan_id' => $plans->first()->id,
            'instructions' => 'Please answer all questions honestly to help us find the best Medicare Advantage plan for your needs.',
            'estimated_time' => 10,
            'is_active' => true
        ]);

        // Questions for Medicare Advantage
        $question1 = Question::create([
            'questionnaire_id' => $questionnaire1->id,
            'question_text' => 'What is your current age?',
            'question_type' => 'single_choice',
            'is_required' => true,
            'order_number' => 1
        ]);

        QuestionOption::create(['question_id' => $question1->id, 'label' => 'Under 65', 'value' => 'under_65']);
        QuestionOption::create(['question_id' => $question1->id, 'label' => '65-70', 'value' => '65_70']);
        QuestionOption::create(['question_id' => $question1->id, 'label' => '71-75', 'value' => '71_75']);
        QuestionOption::create(['question_id' => $question1->id, 'label' => '76-80', 'value' => '76_80']);
        QuestionOption::create(['question_id' => $question1->id, 'label' => 'Over 80', 'value' => 'over_80']);

        $question2 = Question::create([
            'questionnaire_id' => $questionnaire1->id,
            'question_text' => 'Are you currently enrolled in Medicare Part A and Part B?',
            'question_type' => 'single_choice',
            'is_required' => true,
            'order_number' => 2
        ]);

        QuestionOption::create(['question_id' => $question2->id, 'label' => 'Yes, both Part A and Part B', 'value' => 'both']);
        QuestionOption::create(['question_id' => $question2->id, 'label' => 'Only Part A', 'value' => 'part_a_only']);
        QuestionOption::create(['question_id' => $question2->id, 'label' => 'Only Part B', 'value' => 'part_b_only']);
        QuestionOption::create(['question_id' => $question2->id, 'label' => 'Neither', 'value' => 'neither']);

        $question3 = Question::create([
            'questionnaire_id' => $questionnaire1->id,
            'question_text' => 'Which additional benefits are most important to you? (Select all that apply)',
            'question_type' => 'multiple_choice',
            'is_required' => false,
            'order_number' => 3
        ]);

        QuestionOption::create(['question_id' => $question3->id, 'label' => 'Prescription drug coverage', 'value' => 'prescription']);
        QuestionOption::create(['question_id' => $question3->id, 'label' => 'Dental coverage', 'value' => 'dental']);
        QuestionOption::create(['question_id' => $question3->id, 'label' => 'Vision coverage', 'value' => 'vision']);
        QuestionOption::create(['question_id' => $question3->id, 'label' => 'Hearing aids', 'value' => 'hearing']);
        QuestionOption::create(['question_id' => $question3->id, 'label' => 'Wellness programs', 'value' => 'wellness']);

        // Create Medigap Assessment Questionnaire
        $questionnaire2 = Questionnaire::create([
            'title' => 'Medigap Coverage Assessment',
            'description' => 'Find the right Medicare Supplement plan to fill gaps in your Medicare coverage.',
            'plan_id' => $plans->get(1)->id,
            'instructions' => 'This assessment will help determine which Medigap plan best meets your healthcare needs and budget.',
            'estimated_time' => 8,
            'is_active' => true
        ]);

        $question4 = Question::create([
            'questionnaire_id' => $questionnaire2->id,
            'question_text' => 'What is your main concern about Medicare coverage gaps?',
            'question_type' => 'single_choice',
            'is_required' => true,
            'order_number' => 1
        ]);

        QuestionOption::create(['question_id' => $question4->id, 'label' => 'High deductibles', 'value' => 'deductibles']);
        QuestionOption::create(['question_id' => $question4->id, 'label' => 'Coinsurance costs', 'value' => 'coinsurance']);
        QuestionOption::create(['question_id' => $question4->id, 'label' => 'Copayments', 'value' => 'copayments']);
        QuestionOption::create(['question_id' => $question4->id, 'label' => 'All out-of-pocket costs', 'value' => 'all_costs']);

        // Create Health Assessment Questionnaire
        $questionnaire3 = Questionnaire::create([
            'title' => 'Personal Health Assessment',
            'description' => 'Evaluate your health needs to recommend the most suitable Medicare plan options.',
            'plan_id' => $plans->get(2)->id,
            'instructions' => 'Please provide accurate information about your current health status and healthcare needs.',
            'estimated_time' => 15,
            'is_active' => true
        ]);

        $question5 = Question::create([
            'questionnaire_id' => $questionnaire3->id,
            'question_text' => 'How would you rate your overall health?',
            'question_type' => 'single_choice',
            'is_required' => true,
            'order_number' => 1
        ]);

        QuestionOption::create(['question_id' => $question5->id, 'label' => 'Excellent', 'value' => 'excellent']);
        QuestionOption::create(['question_id' => $question5->id, 'label' => 'Very Good', 'value' => 'very_good']);
        QuestionOption::create(['question_id' => $question5->id, 'label' => 'Good', 'value' => 'good']);
        QuestionOption::create(['question_id' => $question5->id, 'label' => 'Fair', 'value' => 'fair']);
        QuestionOption::create(['question_id' => $question5->id, 'label' => 'Poor', 'value' => 'poor']);

        $question6 = Question::create([
            'questionnaire_id' => $questionnaire3->id,
            'question_text' => 'Do you currently take any prescription medications regularly?',
            'question_type' => 'single_choice',
            'is_required' => true,
            'order_number' => 2
        ]);

        QuestionOption::create(['question_id' => $question6->id, 'label' => 'No medications', 'value' => 'none']);
        QuestionOption::create(['question_id' => $question6->id, 'label' => '1-2 medications', 'value' => '1_2']);
        QuestionOption::create(['question_id' => $question6->id, 'label' => '3-5 medications', 'value' => '3_5']);
        QuestionOption::create(['question_id' => $question6->id, 'label' => 'More than 5 medications', 'value' => 'more_5']);
    }
}
