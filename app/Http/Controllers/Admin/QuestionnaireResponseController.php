<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionnaireResponse;
use App\Models\Questionnaire;
use App\Models\User;
use Illuminate\Http\Request;

class QuestionnaireResponseController extends Controller
{
    /**
     * Display a listing of questionnaire responses
     */
    public function index(Request $request)
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
        
        // Get filter options
        $questionnaires = Questionnaire::with('plan')->orderBy('title')->get();
        $users = User::orderBy('first_name')->get();
        $statuses = ['in_progress', 'completed', 'abandoned'];
        
        return view('admin.questionnaire-responses.index', compact('responses', 'questionnaires', 'users', 'statuses'));
    }

    /**
     * Display the specified questionnaire response
     */
    public function show(QuestionnaireResponse $questionnaireResponse)
    {
        $questionnaireResponse->load([
            'user',
            'questionnaire.plan.company',
            'questionResponses.question.options'
        ]);
        
        return view('admin.questionnaire-responses.show', compact('questionnaireResponse'));
    }

    /**
     * Delete the specified questionnaire response
     */
    public function destroy(QuestionnaireResponse $questionnaireResponse)
    {
        try {
            $questionnaireResponse->delete();
            
            return redirect()->route('admin.questionnaire-responses.index')
                ->with('success', 'Questionnaire response deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.questionnaire-responses.index')
                ->with('error', 'Error deleting questionnaire response: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for questionnaire responses
     */
    public function stats()
    {
        $stats = [
            'total' => QuestionnaireResponse::count(),
            'completed' => QuestionnaireResponse::where('status', 'completed')->count(),
            'in_progress' => QuestionnaireResponse::where('status', 'in_progress')->count(),
            'abandoned' => QuestionnaireResponse::where('status', 'abandoned')->count(),
        ];
        
        $stats['completion_rate'] = $stats['total'] > 0 ? 
            round(($stats['completed'] / $stats['total']) * 100, 2) : 0;
            
        $stats['average_time'] = QuestionnaireResponse::where('status', 'completed')
            ->whereNotNull('started_at')
            ->whereNotNull('completed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, started_at, completed_at)) as avg_time')
            ->value('avg_time') ?? 0;
        
        return response()->json($stats);
    }

    /**
     * Export questionnaire responses to CSV
     */
    public function export(Request $request)
    {
        $responses = QuestionnaireResponse::with(['user', 'questionnaire.plan', 'questionResponses.question'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'questionnaire_responses_' . date('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        return response()->streamDownload(function () use ($responses) {
            $handle = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($handle, [
                'ID',
                'User Name',
                'User Email', 
                'User Phone',
                'Questionnaire',
                'Plan',
                'Status',
                'Completion %',
                'Started At',
                'Completed At',
                'Time Taken (minutes)',
                'Total Questions',
                'Answered Questions'
            ]);
            
            foreach ($responses as $response) {
                fputcsv($handle, [
                    $response->id,
                    $response->user->first_name . ' ' . $response->user->last_name,
                    $response->user->email,
                    $response->user->phone_number,
                    $response->questionnaire->title,
                    $response->questionnaire->plan->name ?? 'N/A',
                    ucfirst($response->status),
                    $response->completion_percentage . '%',
                    $response->started_at?->format('Y-m-d H:i:s'),
                    $response->completed_at?->format('Y-m-d H:i:s'),
                    $response->time_taken ?? 'N/A',
                    $response->questionnaire->questions()->count(),
                    $response->questionResponses()->count()
                ]);
            }
            
            fclose($handle);
        }, $filename, $headers);
    }
}
