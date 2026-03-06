<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuestionnaireResponse;
use App\Models\QuestionResponse;
use App\Models\Questionnaire;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class QuestionnaireResponseController extends Controller
{
    /**
     * Get all questionnaire responses (Admin only)
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $questionnaire = $request->get('questionnaire_id');
        $user = $request->get('user_id');
        $status = $request->get('status');
        $perPage = $request->get('per_page', 20);
        
        $responses = QuestionnaireResponse::with(['user', 'questionnaire.plan', 'questionResponses'])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($questionnaire, function ($query, $questionnaire) {
                $query->where('questionnaire_id', $questionnaire);
            })
            ->when($user, function ($query, $user) {
                $query->where('user_id', $user);
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $responses,
            'message' => 'Questionnaire responses retrieved successfully'
        ]);
    }

    /**
     * Start a questionnaire (create response record)
     */
    public function start(Request $request, Questionnaire $questionnaire): JsonResponse
    {
        $user = $request->user();
        
        // Check if user already has a response for this questionnaire
        $existingResponse = QuestionnaireResponse::where('user_id', $user->id)
            ->where('questionnaire_id', $questionnaire->id)
            ->first();
            
        if ($existingResponse) {
            return response()->json([
                'success' => true,
                'data' => $existingResponse->load(['questionnaire', 'questionResponses.question']),
                'message' => 'Questionnaire response already exists'
            ]);
        }
        
        // Create new response
        $response = QuestionnaireResponse::create([
            'user_id' => $user->id,
            'questionnaire_id' => $questionnaire->id,
            'status' => 'in_progress',
            'started_at' => now(),
            'metadata' => [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        ]);
        
        $response->load(['questionnaire', 'questionResponses.question']);
        
        return response()->json([
            'success' => true,
            'data' => $response,
            'message' => 'Questionnaire started successfully'
        ], 201);
    }

    /**
     * Submit answers for questions
     */
    public function submitAnswers(Request $request, QuestionnaireResponse $questionnaireResponse): JsonResponse
    {
        // Ensure user owns this response
        if ($questionnaireResponse->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to questionnaire response'
            ], 403);
        }
        
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer_value' => 'nullable|array',
            'answers.*.answer_text' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            foreach ($validated['answers'] as $answerData) {
                QuestionResponse::updateOrCreate(
                    [
                        'questionnaire_response_id' => $questionnaireResponse->id,
                        'question_id' => $answerData['question_id']
                    ],
                    [
                        'answer_value' => $answerData['answer_value'] ?? null,
                        'answer_text' => $answerData['answer_text'] ?? null,
                    ]
                );
            }
            
            DB::commit();
            
            $questionnaireResponse->load(['questionResponses.question.options']);
            
            return response()->json([
                'success' => true,
                'data' => $questionnaireResponse,
                'message' => 'Answers submitted successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error submitting answers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete questionnaire
     */
    public function complete(QuestionnaireResponse $questionnaireResponse, Request $request): JsonResponse
    {
        // Ensure user owns this response
        if ($questionnaireResponse->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to questionnaire response'
            ], 403);
        }
        
        $questionnaireResponse->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        
        $questionnaireResponse->load(['questionnaire', 'questionResponses.question.options']);
        
        return response()->json([
            'success' => true,
            'data' => $questionnaireResponse,
            'message' => 'Questionnaire completed successfully'
        ]);
    }

    /**
     * Get user's questionnaire responses
     */
    public function myResponses(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = $request->get('per_page', 15);
        
        $responses = QuestionnaireResponse::where('user_id', $user->id)
            ->with(['questionnaire.plan', 'questionResponses'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $responses,
            'message' => 'Your questionnaire responses retrieved successfully'
        ]);
    }

    /**
     * Get specific questionnaire response with all answers
     */
    public function show(QuestionnaireResponse $questionnaireResponse, Request $request): JsonResponse
    {
        // Admin can view any response, users can only view their own
        $user = $request->user();
        if (!$user->is_admin && $questionnaireResponse->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        $questionnaireResponse->load([
            'user',
            'questionnaire.plan.company',
            'questionResponses.question.options'
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $questionnaireResponse,
            'message' => 'Questionnaire response retrieved successfully'
        ]);
    }

    /**
     * Get questionnaire response statistics (Admin only)
     */
    public function stats(Request $request): JsonResponse
    {
        $stats = [
            'total_responses' => QuestionnaireResponse::count(),
            'completed_responses' => QuestionnaireResponse::where('status', 'completed')->count(),
            'in_progress_responses' => QuestionnaireResponse::where('status', 'in_progress')->count(),
            'abandoned_responses' => QuestionnaireResponse::where('status', 'abandoned')->count(),
            'average_completion_time' => QuestionnaireResponse::where('status', 'completed')
                ->whereNotNull('started_at')
                ->whereNotNull('completed_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, started_at, completed_at)) as avg_time')
                ->value('avg_time'),
            'responses_by_questionnaire' => QuestionnaireResponse::with('questionnaire')
                ->selectRaw('questionnaire_id, COUNT(*) as count')
                ->groupBy('questionnaire_id')
                ->orderByDesc('count')
                ->get(),
            'recent_responses' => QuestionnaireResponse::with(['user', 'questionnaire'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Questionnaire response statistics retrieved successfully'
        ]);
    }
}
