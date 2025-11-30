<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QuestionnaireController extends Controller
{
    /**
     * Display a listing of questionnaires
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $plan = $request->get('plan_id');
        $status = $request->get('status');
        $perPage = $request->get('per_page', 15);
        
        $questionnaires = Questionnaire::with('plan')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($plan, function ($query, $plan) {
                $query->where('plan_id', $plan);
            })
            ->when($status !== null, function ($query) use ($status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $questionnaires,
            'message' => 'Questionnaires retrieved successfully'
        ]);
    }

    /**
     * Store a newly created questionnaire
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'plan_id' => 'required|exists:plans,id',
            'instructions' => 'nullable|string',
            'estimated_time' => 'nullable|integer|min:1',
            'is_active' => 'boolean'
        ]);
        
        $questionnaire = Questionnaire::create($validated);
        $questionnaire->load('plan');
        
        return response()->json([
            'success' => true,
            'data' => $questionnaire,
            'message' => 'Questionnaire created successfully'
        ], 201);
    }

    /**
     * Display the specified questionnaire with questions
     */
    public function show(Questionnaire $questionnaire): JsonResponse
    {
        $questionnaire->load(['plan', 'questions.options']);
        
        return response()->json([
            'success' => true,
            'data' => $questionnaire,
            'message' => 'Questionnaire retrieved successfully'
        ]);
    }

    /**
     * Update the specified questionnaire
     */
    public function update(Request $request, Questionnaire $questionnaire): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'estimated_time' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'is_required' => 'boolean'
        ]);
        
        $questionnaire->update($validated);
        $questionnaire->load('plan');
        
        return response()->json([
            'success' => true,
            'data' => $questionnaire->fresh(['plan']),
            'message' => 'Questionnaire updated successfully'
        ]);
    }

    /**
     * Remove the specified questionnaire
     */
    public function destroy(Questionnaire $questionnaire): JsonResponse
    {
        // Check if questionnaire has associated questions
        if ($questionnaire->questions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete questionnaire that has questions. Please delete all questions first.'
            ], 422);
        }
        
        $questionnaire->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Questionnaire deleted successfully'
        ]);
    }

    /**
     * Get questions for a specific questionnaire
     */
    public function questions(Questionnaire $questionnaire): JsonResponse
    {
        $questions = $questionnaire->questions()
            ->with('options')
            ->orderBy('order_index')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $questions,
            'message' => 'Questions retrieved successfully'
        ]);
    }

    /**
     * Add a question to a questionnaire
     */
    public function addQuestion(Request $request, Questionnaire $questionnaire): JsonResponse
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:single_choice,multiple_choice,text,textarea,number,email,date,yes_no',
            'is_required' => 'boolean',
            'order_index' => 'nullable|integer|min:1',
            'options' => 'required_if:question_type,single_choice,multiple_choice|array',
            'options.*.label' => 'required_with:options|string',
            'options.*.value' => 'nullable|string'
        ]);
        
        // Set order index if not provided
        if (!isset($validated['order_index'])) {
            $validated['order_index'] = $questionnaire->questions()->max('order_index') + 1;
        }
        
        $question = $questionnaire->questions()->create($validated);
        
        // Add options if provided
        if (isset($validated['options'])) {
            foreach ($validated['options'] as $option) {
                $question->options()->create($option);
            }
        }
        
        $question->load('options');
        
        return response()->json([
            'success' => true,
            'data' => $question,
            'message' => 'Question added successfully'
        ], 201);
    }

    /**
     * Toggle questionnaire active status
     */
    public function toggleStatus(Questionnaire $questionnaire): JsonResponse
    {
        $questionnaire->update(['is_active' => !$questionnaire->is_active]);
        
        return response()->json([
            'success' => true,
            'data' => $questionnaire->fresh(),
            'message' => $questionnaire->is_active ? 'Questionnaire activated' : 'Questionnaire deactivated'
        ]);
    }
}