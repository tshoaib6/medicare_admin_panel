<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class QuestionnaireController extends Controller
{

    /**
     * Display a listing of questionnaires.
     */
    public function index(Request $request)
    {
        $query = Questionnaire::with(['plan', 'questions'])
            ->withCount('questions');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by plan
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->get('plan_id'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $active = $request->get('status') === 'active';
            $query->where('is_active', $active);
        }

        $questionnaires = $query->orderBy('created_at', 'desc')->paginate(15);
        $plans = Plan::orderBy('title')->get();

        return view('admin.questionnaires.index', compact('questionnaires', 'plans'));
    }

    /**
     * Show the form for creating a new questionnaire.
     */
    public function create()
    {
        $plans = Plan::orderBy('title')->get();
        return view('admin.questionnaires.create', compact('plans'));
    }

    /**
     * Store a newly created questionnaire.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'plan_id' => 'required|exists:plans,id',
            'estimated_time' => 'nullable|integer|min:1|max:120',
            'is_active' => 'boolean',
            'is_required' => 'boolean',
            'questions' => 'sometimes|array',
            'questions.*.question_text' => 'required_with:questions|string|max:500',
            'questions.*.question_type' => [
                'required_with:questions',
                Rule::in(['text', 'textarea', 'number', 'email', 'date', 'single_choice', 'multiple_choice', 'yes_no'])
            ],
            'questions.*.is_required' => 'boolean',
            'questions.*.order_index' => 'nullable|integer|min:1',
            'questions.*.options' => 'sometimes|array',
            'questions.*.options.*.label' => 'required_with:questions.*.options|string|max:255',
            'questions.*.options.*.value' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Create questionnaire
            $questionnaire = Questionnaire::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'plan_id' => $validated['plan_id'],
                'estimated_time' => $validated['estimated_time'] ?? null,
                'is_active' => $request->boolean('is_active', false),
                'is_required' => $request->boolean('is_required', false),
            ]);

            // Create questions if provided
            if (isset($validated['questions'])) {
                $this->createQuestions($questionnaire, $validated['questions']);
            }

            DB::commit();

            return redirect()
                ->route('admin.questionnaires.show', $questionnaire)
                ->with('success', 'Questionnaire created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Error creating questionnaire: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified questionnaire.
     */
    public function show(Questionnaire $questionnaire)
    {
        $questionnaire->load(['plan.company', 'questions.options']);
        return view('admin.questionnaires.show', compact('questionnaire'));
    }

    /**
     * Show the form for editing the specified questionnaire.
     */
    public function edit(Questionnaire $questionnaire)
    {
        $questionnaire->load(['questions.options']);
        $plans = Plan::orderBy('title')->get();
        return view('admin.questionnaires.edit', compact('questionnaire', 'plans'));
    }

    /**
     * Update the specified questionnaire.
     */
    public function update(Request $request, Questionnaire $questionnaire)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'plan_id' => 'required|exists:plans,id',
            'estimated_time' => 'nullable|integer|min:1|max:120',
            'is_active' => 'boolean',
            'is_required' => 'boolean',
            'questions' => 'sometimes|array',
            'questions.*.id' => 'nullable|exists:questions,id',
            'questions.*.question_text' => 'required_with:questions|string|max:500',
            'questions.*.question_type' => [
                'required_with:questions',
                Rule::in(['text', 'textarea', 'number', 'email', 'date', 'single_choice', 'multiple_choice', 'yes_no'])
            ],
            'questions.*.is_required' => 'boolean',
            'questions.*.order_index' => 'nullable|integer|min:1',
            'questions.*.options' => 'sometimes|array',
            'questions.*.options.*.id' => 'nullable|exists:question_options,id',
            'questions.*.options.*.label' => 'required_with:questions.*.options|string|max:255',
            'questions.*.options.*.value' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Update questionnaire
            $questionnaire->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'plan_id' => $validated['plan_id'],
                'estimated_time' => $validated['estimated_time'] ?? null,
                'is_active' => $request->boolean('is_active', false),
                'is_required' => $request->boolean('is_required', false),
            ]);

            // Handle questions update
            $this->updateQuestions($questionnaire, $validated['questions'] ?? []);

            DB::commit();

            return redirect()
                ->route('admin.questionnaires.show', $questionnaire)
                ->with('success', 'Questionnaire updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Error updating questionnaire: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified questionnaire.
     */
    public function destroy(Questionnaire $questionnaire)
    {
        DB::beginTransaction();

        try {
            // Delete related question options first
            foreach ($questionnaire->questions as $question) {
                $question->options()->delete();
            }
            
            // Delete questions
            $questionnaire->questions()->delete();
            
            // Delete questionnaire
            $questionnaire->delete();

            DB::commit();

            return redirect()
                ->route('admin.questionnaires.index')
                ->with('success', 'Questionnaire deleted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error deleting questionnaire: ' . $e->getMessage());
        }
    }

    /**
     * Create questions for a questionnaire.
     */
    private function createQuestions(Questionnaire $questionnaire, array $questionsData)
    {
        foreach ($questionsData as $questionData) {
            $question = $questionnaire->questions()->create([
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'is_required' => isset($questionData['is_required']) && $questionData['is_required'],
                'order_index' => $questionData['order_index'] ?? 1,
            ]);

            // Create options if question type supports them
            if (in_array($questionData['question_type'], ['single_choice', 'multiple_choice']) 
                && isset($questionData['options'])) {
                $this->createQuestionOptions($question, $questionData['options']);
            }
        }
    }

    /**
     * Update questions for a questionnaire.
     */
    private function updateQuestions(Questionnaire $questionnaire, array $questionsData)
    {
        $existingQuestionIds = [];
        
        foreach ($questionsData as $questionData) {
            if (isset($questionData['id']) && $questionData['id']) {
                // Update existing question
                $question = Question::find($questionData['id']);
                if ($question && $question->questionnaire_id === $questionnaire->id) {
                    $question->update([
                        'question_text' => $questionData['question_text'],
                        'question_type' => $questionData['question_type'],
                        'is_required' => isset($questionData['is_required']) && $questionData['is_required'],
                        'order_index' => $questionData['order_index'] ?? 1,
                    ]);

                    // Handle options update
                    $this->updateQuestionOptions($question, $questionData['options'] ?? []);
                    
                    $existingQuestionIds[] = $question->id;
                }
            } else {
                // Create new question
                $question = $questionnaire->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'question_type' => $questionData['question_type'],
                    'is_required' => isset($questionData['is_required']) && $questionData['is_required'],
                    'order_index' => $questionData['order_index'] ?? 1,
                ]);

                // Create options if needed
                if (in_array($questionData['question_type'], ['single_choice', 'multiple_choice']) 
                    && isset($questionData['options'])) {
                    $this->createQuestionOptions($question, $questionData['options']);
                }
                
                $existingQuestionIds[] = $question->id;
            }
        }

        // Delete questions that were removed
        $questionnaire->questions()
            ->whereNotIn('id', $existingQuestionIds)
            ->each(function ($question) {
                $question->options()->delete();
                $question->delete();
            });
    }

    /**
     * Create options for a question.
     */
    private function createQuestionOptions(Question $question, array $optionsData)
    {
        foreach ($optionsData as $optionData) {
            $question->options()->create([
                'label' => $optionData['label'],
                'value' => $optionData['value'] ?? $optionData['label'],
            ]);
        }
    }

    /**
     * Update options for a question.
     */
    private function updateQuestionOptions(Question $question, array $optionsData)
    {
        $existingOptionIds = [];

        // Only handle options for choice questions
        if (in_array($question->question_type, ['single_choice', 'multiple_choice'])) {
            foreach ($optionsData as $optionData) {
                if (isset($optionData['id']) && $optionData['id']) {
                    // Update existing option
                    $option = QuestionOption::find($optionData['id']);
                    if ($option && $option->question_id === $question->id) {
                        $option->update([
                            'label' => $optionData['label'],
                            'value' => $optionData['value'] ?? $optionData['label'],
                        ]);
                        $existingOptionIds[] = $option->id;
                    }
                } else {
                    // Create new option
                    $option = $question->options()->create([
                        'label' => $optionData['label'],
                        'value' => $optionData['value'] ?? $optionData['label'],
                    ]);
                    $existingOptionIds[] = $option->id;
                }
            }
        }

        // Delete options that were removed or if question type changed
        $question->options()
            ->whereNotIn('id', $existingOptionIds)
            ->delete();
    }
}
