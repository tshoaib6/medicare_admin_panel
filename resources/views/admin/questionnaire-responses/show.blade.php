@extends('layouts.admin')

@section('title', 'Questionnaire Response Details')

@section('page-header')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-poll-h"></i> Response Details #{{ $questionnaireResponse->id }}
    </h1>
    <div class="d-flex">
        <a href="{{ route('admin.questionnaire-responses.index') }}" class="btn btn-secondary btn-sm mr-2">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <form method="POST" action="{{ route('admin.questionnaire-responses.destroy', $questionnaireResponse) }}" 
              style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this response?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i> Delete Response
            </button>
        </form>
    </div>
</div>
@endsection

@section('content')
<!-- Response Overview -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Response Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="font-weight-bold">Status:</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'completed' => 'success',
                                            'in_progress' => 'warning', 
                                            'abandoned' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $statusColors[$questionnaireResponse->status] ?? 'secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $questionnaireResponse->status)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Completion:</td>
                                <td>
                                    <div class="progress" style="width: 150px; height: 20px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $questionnaireResponse->completion_percentage }}%;" 
                                             aria-valuenow="{{ $questionnaireResponse->completion_percentage }}" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            {{ $questionnaireResponse->completion_percentage }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Started At:</td>
                                <td>
                                    @if($questionnaireResponse->started_at)
                                        {{ $questionnaireResponse->started_at->format('M d, Y H:i A') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="font-weight-bold">Completed At:</td>
                                <td>
                                    @if($questionnaireResponse->completed_at)
                                        {{ $questionnaireResponse->completed_at->format('M d, Y H:i A') }}
                                    @else
                                        <span class="text-muted">Not completed</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Time Taken:</td>
                                <td>
                                    @if($questionnaireResponse->time_taken)
                                        {{ $questionnaireResponse->time_taken }} minutes
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Answered:</td>
                                <td>
                                    {{ $questionnaireResponse->questionResponses->count() }} / {{ $questionnaireResponse->questionnaire->questions()->count() }} questions
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- User Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
            </div>
            <div class="card-body text-center">
                <div class="icon-circle bg-primary mb-3 mx-auto" style="width: 60px; height: 60px; line-height: 60px;">
                    <i class="fas fa-user text-white fa-2x"></i>
                </div>
                <h5 class="font-weight-bold">{{ $questionnaireResponse->user->first_name }} {{ $questionnaireResponse->user->last_name }}</h5>
                <p class="text-gray-600 mb-1">{{ $questionnaireResponse->user->email }}</p>
                <p class="text-gray-600 mb-3">{{ $questionnaireResponse->user->phone_number }}</p>
                <div class="text-xs text-gray-500">
                    Member since {{ $questionnaireResponse->user->created_at->format('M Y') }}
                </div>
            </div>
        </div>
        
        <!-- Questionnaire Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Questionnaire Details</h6>
            </div>
            <div class="card-body">
                <h6 class="font-weight-bold">{{ $questionnaireResponse->questionnaire->title }}</h6>
                @if($questionnaireResponse->questionnaire->description)
                    <p class="text-gray-600 small mb-3">{{ $questionnaireResponse->questionnaire->description }}</p>
                @endif
                
                @if($questionnaireResponse->questionnaire->plan)
                    <div class="mb-3">
                        <span class="text-xs font-weight-bold text-uppercase text-gray-600">Associated Plan:</span><br>
                        <span class="badge badge-info">{{ $questionnaireResponse->questionnaire->plan->name }}</span>
                    </div>
                @endif
                
                <div class="mb-2">
                    <span class="text-xs font-weight-bold text-uppercase text-gray-600">Company:</span><br>
                    {{ $questionnaireResponse->questionnaire->plan->company->name ?? 'N/A' }}
                </div>
                
                <div class="mb-2">
                    <span class="text-xs font-weight-bold text-uppercase text-gray-600">Total Questions:</span><br>
                    {{ $questionnaireResponse->questionnaire->questions()->count() }}
                </div>
                
                <div>
                    <span class="text-xs font-weight-bold text-uppercase text-gray-600">Status:</span><br>
                    <span class="badge badge-{{ $questionnaireResponse->questionnaire->is_active ? 'success' : 'secondary' }}">
                        {{ $questionnaireResponse->questionnaire->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Questions and Answers -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Questions & Answers</h6>
    </div>
    <div class="card-body">
        @if($questionnaireResponse->questionResponses->count() > 0)
            @foreach($questionnaireResponse->questionResponses as $index => $questionResponse)
                <div class="card mb-3 border-left-primary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="text-center">
                                    <div class="icon-circle bg-primary text-white font-weight-bold">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-11">
                                <h6 class="font-weight-bold mb-3">{{ $questionResponse->question->question_text }}</h6>
                                
                                @if($questionResponse->question->question_type === 'text' || $questionResponse->question->question_type === 'textarea')
                                    <div class="bg-light p-3 rounded">
                                        <strong>Answer:</strong><br>
                                        {{ $questionResponse->answer_text ?? 'No answer provided' }}
                                    </div>
                                @elseif(in_array($questionResponse->question->question_type, ['single_choice', 'multiple_choice']))
                                    <!-- Selected Answer(s) -->
                                    @if($questionResponse->formatted_answer)
                                        <div class="alert alert-success mb-3">
                                            <strong><i class="fas fa-check-circle mr-2"></i>Selected Answer(s):</strong><br>
                                            <span class="h6">{{ $questionResponse->formatted_answer }}</span>
                                        </div>
                                    @endif
                                    
                                    <!-- All Available Options -->
                                    <div class="mb-3">
                                        <strong>All Available Options:</strong>
                                        <div class="mt-2">
                                            @foreach($questionResponse->question->options as $option)
                                                <div class="d-flex align-items-center mb-2 p-2 border rounded {{ in_array($option->id, $questionResponse->answer_value ?? []) ? 'bg-success text-white' : 'bg-light' }}">
                                                    @if(in_array($option->id, $questionResponse->answer_value ?? []))
                                                        <i class="fas fa-check-circle text-white mr-3" style="font-size: 1.2em;"></i>
                                                        <strong>{{ $option->label }}</strong>
                                                    @else
                                                        <i class="far fa-circle text-muted mr-3" style="font-size: 1.2em;"></i>
                                                        {{ $option->label }}
                                                    @endif
                                                    @if($option->value)
                                                        <span class="badge badge-{{ in_array($option->id, $questionResponse->answer_value ?? []) ? 'light' : 'secondary' }} ml-auto">{{ $option->value }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="text-xs text-gray-500 mt-2">
                                    Question Type: <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $questionResponse->question->question_type)) }}</span>
                                    @if($questionResponse->question->is_required)
                                        <span class="badge badge-danger ml-1">Required</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-question-circle fa-3x text-gray-300 mb-3"></i>
                <h5 class="text-gray-600">No answers provided yet</h5>
                <p class="text-gray-500">This user hasn't answered any questions in this questionnaire.</p>
            </div>
        @endif
    </div>
</div>

<!-- Unanswered Questions -->
@php
    $answeredQuestionIds = $questionnaireResponse->questionResponses->pluck('question_id')->toArray();
    $unansweredQuestions = $questionnaireResponse->questionnaire->questions()
        ->whereNotIn('id', $answeredQuestionIds)->get();
@endphp

@if($unansweredQuestions->count() > 0)
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-warning text-white">
        <h6 class="m-0 font-weight-bold">Unanswered Questions ({{ $unansweredQuestions->count() }})</h6>
    </div>
    <div class="card-body">
        @foreach($unansweredQuestions as $index => $question)
            <div class="card mb-3 border-left-warning">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-1">
                            <div class="text-center">
                                <div class="icon-circle bg-warning text-white font-weight-bold">
                                    {{ count($answeredQuestionIds) + $index + 1 }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-11">
                            <h6 class="font-weight-bold mb-2">{{ $question->question_text }}</h6>
                            @if($question->description)
                                <p class="text-gray-600 small">{{ $question->description }}</p>
                            @endif
                            
                            <div class="text-xs text-gray-500">
                                Question Type: <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                                @if($question->is_required)
                                    <span class="badge badge-danger ml-1">Required</span>
                                @endif
                                <span class="badge badge-warning ml-1">Not Answered</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Metadata -->
@if($questionnaireResponse->metadata)
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Response Metadata</h6>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($questionnaireResponse->metadata as $key => $value)
                <div class="col-md-6 mb-2">
                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                    <span class="text-gray-600">{{ $value }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection