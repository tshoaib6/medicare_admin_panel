<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuestionOption;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QuestionOptionController extends Controller
{
    /**
     * Store a newly created question option
     */
    public function store(Request $request, Question $question): JsonResponse
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'nullable|string|max:255',
        ]);

        $validated['value'] = $validated['value'] ?? $validated['label'];

        $option = $question->options()->create($validated);

        return response()->json([
            'success' => true,
            'data' => $option,
            'message' => 'Question option created successfully'
        ], 201);
    }

    /**
     * Update the specified question option
     */
    public function update(Request $request, Question $question, QuestionOption $option): JsonResponse
    {
        // Ensure the option belongs to the question
        if ($option->question_id !== $question->id) {
            return response()->json([
                'success' => false,
                'message' => 'Option does not belong to this question'
            ], 422);
        }

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'nullable|string|max:255',
        ]);

        $validated['value'] = $validated['value'] ?? $validated['label'];

        $option->update($validated);

        return response()->json([
            'success' => true,
            'data' => $option,
            'message' => 'Question option updated successfully'
        ]);
    }

    /**
     * Remove the specified question option
     */
    public function destroy(Question $question, QuestionOption $option): JsonResponse
    {
        // Ensure the option belongs to the question
        if ($option->question_id !== $question->id) {
            return response()->json([
                'success' => false,
                'message' => 'Option does not belong to this question'
            ], 422);
        }

        $option->delete();

        return response()->json([
            'success' => true,
            'message' => 'Question option deleted successfully'
        ]);
    }
}