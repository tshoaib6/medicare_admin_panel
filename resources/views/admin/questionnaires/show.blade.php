@extends('layouts.admin')

@section('title', 'Questionnaire Details')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Questionnaire Details</h1>
    <div>
        <a href="{{ route('admin.questionnaires.edit', $questionnaire) }}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm mr-2">
            <i class="fas fa-edit fa-sm text-white-50"></i> Edit Questionnaire
        </a>
        <a href="{{ route('admin.questionnaires.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <!-- Basic Information -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">{{ $questionnaire->title }}</h6>
                <span class="badge badge-{{ $questionnaire->is_active ? 'success' : 'danger' }} badge-lg">
                    {{ $questionnaire->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="card-body">
                @if($questionnaire->description)
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-gray-800">Description</h6>
                        <p class="text-gray-700">{{ $questionnaire->description }}</p>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-gray-800">Associated Plan</h6>
                        @if($questionnaire->plan)
                            <div class="d-flex align-items-center">
                                <span class="badge badge-info mr-2">{{ $questionnaire->plan->title }}</span>
                                @if($questionnaire->plan->company)
                                    <small class="text-muted">by {{ $questionnaire->plan->company->name }}</small>
                                @endif
                            </div>
                        @else
                            <span class="text-muted">No plan associated</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-gray-800">Estimated Time</h6>
                        @if($questionnaire->estimated_time)
                            <span class="text-info">
                                <i class="fas fa-clock"></i> {{ $questionnaire->estimated_time }} minutes
                            </span>
                        @else
                            <span class="text-muted">Not specified</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-gray-800">Required</h6>
                        <span class="badge badge-{{ $questionnaire->is_required ? 'warning' : 'secondary' }}">
                            {{ $questionnaire->is_required ? 'Required for Plan' : 'Optional' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-gray-800">Created</h6>
                        <span class="text-gray-700">{{ $questionnaire->created_at->format('F j, Y \a\t g:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Questions ({{ $questionnaire->questions->count() }})
                </h6>
            </div>
            <div class="card-body">
                @if($questionnaire->questions->count() > 0)
                    @foreach($questionnaire->questions->sortBy('order_index') as $index => $question)
                        <div class="question-preview border rounded p-3 mb-3 {{ $question->is_required ? 'border-warning' : 'border-light' }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="font-weight-bold text-primary mb-0">
                                    Question {{ $question->order_index ?? $index + 1 }}
                                    @if($question->is_required)
                                        <span class="text-danger">*</span>
                                    @endif
                                </h6>
                                <div>
                                    <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                                    @if($question->is_required)
                                        <span class="badge badge-warning">Required</span>
                                    @endif
                                </div>
                            </div>
                            
                            <p class="mb-3 text-gray-800">{{ $question->question_text }}</p>

                            @if(in_array($question->question_type, ['single_choice', 'multiple_choice']) && $question->options->count() > 0)
                                <div class="mt-3">
                                    <h6 class="font-weight-bold text-gray-700 mb-2">Options:</h6>
                                    <div class="ml-3">
                                        @foreach($question->options as $option)
                                            <div class="form-check {{ $question->question_type === 'multiple_choice' ? 'form-check' : 'form-check' }} mb-1">
                                                @if($question->question_type === 'single_choice')
                                                    <input class="form-check-input" type="radio" disabled>
                                                @else
                                                    <input class="form-check-input" type="checkbox" disabled>
                                                @endif
                                                <label class="form-check-label text-gray-700">
                                                    {{ $option->label }}
                                                    @if($option->value !== $option->label)
                                                        <small class="text-muted">({{ $option->value }})</small>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif($question->question_type === 'yes_no')
                                <div class="mt-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" disabled>
                                        <label class="form-check-label text-gray-700">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" disabled>
                                        <label class="form-check-label text-gray-700">No</label>
                                    </div>
                                </div>
                            @else
                                <div class="mt-3">
                                    @switch($question->question_type)
                                        @case('text')
                                        @case('email')
                                            <input type="{{ $question->question_type }}" class="form-control" placeholder="User input will appear here" disabled>
                                            @break
                                        @case('number')
                                            <input type="number" class="form-control" placeholder="Enter number" disabled>
                                            @break
                                        @case('date')
                                            <input type="date" class="form-control" disabled>
                                            @break
                                        @case('textarea')
                                            <textarea class="form-control" rows="3" placeholder="User's long text response will appear here" disabled></textarea>
                                            @break
                                    @endswitch
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-question-circle fa-2x text-gray-400 mb-3"></i>
                        <h6 class="text-gray-600">No Questions Added</h6>
                        <p class="text-gray-500">This questionnaire doesn't have any questions yet.</p>
                        <a href="{{ route('admin.questionnaires.edit', $questionnaire) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Questions
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar Information -->
    <div class="col-lg-4">
        <!-- Quick Stats -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 border-right">
                        <div class="h4 font-weight-bold text-primary">{{ $questionnaire->questions->count() }}</div>
                        <div class="text-xs text-gray-600">Total Questions</div>
                    </div>
                    <div class="col-6">
                        <div class="h4 font-weight-bold text-warning">{{ $questionnaire->questions->where('is_required', true)->count() }}</div>
                        <div class="text-xs text-gray-600">Required Questions</div>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6 border-right">
                        <div class="h4 font-weight-bold text-info">{{ $questionnaire->questions->whereIn('question_type', ['single_choice', 'multiple_choice'])->count() }}</div>
                        <div class="text-xs text-gray-600">Choice Questions</div>
                    </div>
                    <div class="col-6">
                        <div class="h4 font-weight-bold text-success">{{ $questionnaire->questions->whereIn('question_type', ['text', 'textarea', 'number', 'email', 'date'])->count() }}</div>
                        <div class="text-xs text-gray-600">Input Questions</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Types Breakdown -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Question Types</h6>
            </div>
            <div class="card-body">
                @php
                    $questionTypes = $questionnaire->questions->groupBy('question_type');
                    $typeIcons = [
                        'text' => 'fas fa-font',
                        'textarea' => 'fas fa-align-left',
                        'number' => 'fas fa-hashtag',
                        'email' => 'fas fa-envelope',
                        'date' => 'fas fa-calendar',
                        'single_choice' => 'fas fa-dot-circle',
                        'multiple_choice' => 'fas fa-check-square',
                        'yes_no' => 'fas fa-toggle-on'
                    ];
                @endphp

                @if($questionTypes->count() > 0)
                    @foreach($questionTypes as $type => $questions)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <i class="{{ $typeIcons[$type] ?? 'fas fa-question' }} text-gray-500 mr-2"></i>
                                <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $type)) }}</span>
                            </div>
                            <span class="badge badge-secondary">{{ $questions->count() }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">No questions to analyze</p>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.questionnaires.edit', $questionnaire) }}" class="btn btn-warning btn-block">
                        <i class="fas fa-edit"></i> Edit Questionnaire
                    </a>
                    
                    @if($questionnaire->is_active)
                        <button class="btn btn-outline-secondary btn-block" onclick="toggleStatus(false)">
                            <i class="fas fa-eye-slash"></i> Deactivate
                        </button>
                    @else
                        <button class="btn btn-outline-success btn-block" onclick="toggleStatus(true)">
                            <i class="fas fa-eye"></i> Activate
                        </button>
                    @endif
                    
                    <hr>
                    <form action="{{ route('admin.questionnaires.destroy', $questionnaire) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this questionnaire? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i> Delete Questionnaire
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleStatus(newStatus) {
    const action = newStatus ? 'activate' : 'deactivate';
    const confirmMessage = `Are you sure you want to ${action} this questionnaire?`;
    
    if (confirm(confirmMessage)) {
        // Create a form to submit the status change
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.questionnaires.update", $questionnaire) }}';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Add method override
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);
        
        // Add status input
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'is_active';
        statusInput.value = newStatus ? '1' : '0';
        form.appendChild(statusInput);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection