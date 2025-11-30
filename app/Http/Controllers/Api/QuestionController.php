<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions
     */
    public function index(Request $request): JsonResponse
    {
        $questionnaire = $request->get('questionnaire_id');
        $search = $request->get('search');
        $perPage = $request->get('per_page', 20);
        
        $questions = Question::with(['questionnaire', 'options'])
            ->when($questionnaire, function ($query, $questionnaire) {
                $query->where('questionnaire_id', $questionnaire);
            })
            ->when($search, function ($query, $search) {
                $query->where('question_text', 'like', "%{$search}%");
            })
            ->orderBy('questionnaire_id')
            ->orderBy('order_index')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $questions,
            'message' => 'Questions retrieved successfully'
        ]);
    }

    /**
     * Store a newly created question
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'question_text' => 'required|string|max:500',
            'question_type' => [
                'required',
                Rule::in(['text', 'textarea', 'number', 'email', 'date', 'single_choice', 'multiple_choice', 'yes_no'])
            ],
            'is_required' => 'boolean',
            'order_index' => 'nullable|integer|min:1',
            'options' => 'required_if:question_type,single_choice,multiple_choice|array',
            'options.*.label' => 'required_with:options|string|max:255',
            'options.*.value' => 'nullable|string|max:255',
        ]);

        // Set order index if not provided
        if (!isset($validated['order_index'])) {
            $validated['order_index'] = Question::where('questionnaire_id', $validated['questionnaire_id'])
                ->max('order_index') + 1;
        }

        $question = Question::create($validated);

        // Create options if provided
        if (isset($validated['options'])) {
            foreach ($validated['options'] as $optionData) {
                $question->options()->create([
                    'label' => $optionData['label'],
                    'value' => $optionData['value'] ?? $optionData['label'],
                ]);
            }
        }

        $question->load(['questionnaire', 'options']);

        return response()->json([
            'success' => true,
            'data' => $question,
            'message' => 'Question created successfully'
        ], 201);
    }

    /**
     * Display the specified question
     */
    public function show(Question $question): JsonResponse
    {
        $question->load(['questionnaire', 'options']);
        
        return response()->json([
            'success' => true,
            'data' => $question,
            'message' => 'Question retrieved successfully'
        ]);
    }

    /**
     * Update the specified question
     */
    public function update(Request $request, Question $question): JsonResponse
    {
        $validated = $request->validate([
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'question_text' => 'required|string|max:500',
            'question_type' => [
                'required',
                Rule::in(['text', 'textarea', 'number', 'email', 'date', 'single_choice', 'multiple_choice', 'yes_no'])
            ],
            'is_required' => 'boolean',
            'order_index' => 'nullable|integer|min:1',
        ]);

        $question->update($validated);
        $question->load(['questionnaire', 'options']);

        return response()->json([
            'success' => true,
            'data' => $question,
            'message' => 'Question updated successfully'
        ]);
    }

    /**
     * Remove the specified question
     */
    public function destroy(Question $question): JsonResponse
    {
        // Delete associated options
        $question->options()->delete();
        
        // Delete the question
        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully'
        ]);
    }
}