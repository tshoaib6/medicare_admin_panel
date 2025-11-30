@extends('layouts.admin')

@section('title', 'Create New Questionnaire')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create New Questionnaire</h1>
    <a href="{{ route('admin.questionnaires.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Questionnaire Information</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.questionnaires.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-lg-8">
                    <div class="form-group">
                        <label for="title" class="form-label font-weight-bold">
                            Questionnaire Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" 
                               placeholder="Enter questionnaire title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label font-weight-bold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3"
                                  placeholder="Describe the purpose of this questionnaire">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="plan_id" class="form-label font-weight-bold">
                                    Associated Plan <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('plan_id') is-invalid @enderror" 
                                        id="plan_id" name="plan_id" required>
                                    <option value="">Select a plan...</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estimated_time" class="form-label font-weight-bold">Estimated Time (minutes)</label>
                                <input type="number" class="form-control @error('estimated_time') is-invalid @enderror" 
                                       id="estimated_time" name="estimated_time" value="{{ old('estimated_time') }}" 
                                       min="1" max="120" placeholder="e.g., 5">
                                @error('estimated_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Settings -->
                <div class="col-lg-4">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" 
                                           id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        Active Questionnaire
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Active questionnaires are available to users
                                </small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" 
                                           id="is_required" name="is_required" value="1" 
                                           {{ old('is_required') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_required">
                                        Required for Plan
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Users must complete this questionnaire
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions Section -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Questions</h6>
                    <button type="button" class="btn btn-sm btn-success" id="addQuestion">
                        <i class="fas fa-plus"></i> Add Question
                    </button>
                </div>
                <div class="card-body">
                    <div id="questionsContainer">
                        <!-- Dynamic questions will be added here -->
                    </div>
                    <div id="noQuestionsMessage" class="text-center py-4">
                        <i class="fas fa-question-circle fa-2x text-gray-400 mb-3"></i>
                        <p class="text-gray-500">No questions added yet. Click "Add Question" to start.</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-group mt-4">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.questionnaires.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Questionnaire
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let questionIndex = 0;

document.addEventListener('DOMContentLoaded', function() {
    const addQuestionBtn = document.getElementById('addQuestion');
    const questionsContainer = document.getElementById('questionsContainer');
    const noQuestionsMessage = document.getElementById('noQuestionsMessage');

    addQuestionBtn.addEventListener('click', function() {
        addQuestion();
    });

    function addQuestion() {
        const questionHtml = `
            <div class="question-item border rounded p-3 mb-3" data-question-index="${questionIndex}">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="font-weight-bold text-primary">Question ${questionIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-question">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Question Text *</label>
                            <textarea class="form-control" name="questions[${questionIndex}][question_text]" 
                                    rows="2" placeholder="Enter your question" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Question Type *</label>
                            <select class="form-control question-type" name="questions[${questionIndex}][question_type]" required>
                                <option value="">Select type...</option>
                                <option value="text">Text Input</option>
                                <option value="number">Number</option>
                                <option value="email">Email</option>
                                <option value="date">Date</option>
                                <option value="single_choice">Single Choice</option>
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="yes_no">Yes/No</option>
                                <option value="textarea">Long Text</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" 
                                   id="required_${questionIndex}" name="questions[${questionIndex}][is_required]" value="1">
                            <label class="custom-control-label" for="required_${questionIndex}">
                                Required Question
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="number" class="form-control form-control-sm" 
                                   name="questions[${questionIndex}][order_index]" 
                                   placeholder="Order" value="${questionIndex + 1}" min="1">
                        </div>
                    </div>
                </div>

                <!-- Options Container (Hidden by default) -->
                <div class="options-container" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label font-weight-bold">Answer Options</label>
                        <button type="button" class="btn btn-sm btn-outline-primary add-option">
                            <i class="fas fa-plus"></i> Add Option
                        </button>
                    </div>
                    <div class="options-list">
                        <!-- Dynamic options will be added here -->
                    </div>
                </div>
            </div>
        `;

        questionsContainer.insertAdjacentHTML('beforeend', questionHtml);
        noQuestionsMessage.style.display = 'none';
        
        // Attach events to the new question
        attachQuestionEvents(questionsContainer.lastElementChild);
        questionIndex++;
        updateQuestionNumbers();
    }

    function attachQuestionEvents(questionElement) {
        // Remove question
        const removeBtn = questionElement.querySelector('.remove-question');
        removeBtn.addEventListener('click', function() {
            questionElement.remove();
            updateQuestionNumbers();
            
            if (questionsContainer.children.length === 0) {
                noQuestionsMessage.style.display = 'block';
            }
        });

        // Question type change
        const typeSelect = questionElement.querySelector('.question-type');
        typeSelect.addEventListener('change', function() {
            const optionsContainer = questionElement.querySelector('.options-container');
            const hasOptions = ['single_choice', 'multiple_choice'].includes(this.value);
            
            optionsContainer.style.display = hasOptions ? 'block' : 'none';
            
            if (hasOptions && optionsContainer.querySelector('.option-item') === null) {
                // Add two default options
                addOption(questionElement, 'Option 1');
                addOption(questionElement, 'Option 2');
            }
        });

        // Add option button
        const addOptionBtn = questionElement.querySelector('.add-option');
        addOptionBtn.addEventListener('click', function() {
            const optionsCount = questionElement.querySelectorAll('.option-item').length;
            addOption(questionElement, `Option ${optionsCount + 1}`);
        });
    }

    function addOption(questionElement, defaultLabel = '') {
        const questionIdx = questionElement.dataset.questionIndex;
        const optionsList = questionElement.querySelector('.options-list');
        const optionIndex = optionsList.children.length;
        
        const optionHtml = `
            <div class="option-item d-flex align-items-center mb-2">
                <div class="flex-grow-1 mr-2">
                    <input type="text" class="form-control" 
                           name="questions[${questionIdx}][options][${optionIndex}][label]" 
                           placeholder="Option label" value="${defaultLabel}" required>
                </div>
                <div class="mr-2" style="width: 100px;">
                    <input type="text" class="form-control" 
                           name="questions[${questionIdx}][options][${optionIndex}][value]" 
                           placeholder="Value" value="${defaultLabel.toLowerCase().replace(/\s+/g, '_')}">
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger remove-option">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        optionsList.insertAdjacentHTML('beforeend', optionHtml);
        
        // Attach remove event
        const removeBtn = optionsList.lastElementChild.querySelector('.remove-option');
        removeBtn.addEventListener('click', function() {
            this.closest('.option-item').remove();
        });
    }

    function updateQuestionNumbers() {
        const questions = questionsContainer.querySelectorAll('.question-item');
        questions.forEach((question, index) => {
            const header = question.querySelector('h6');
            header.textContent = `Question ${index + 1}`;
            
            const orderInput = question.querySelector('input[name*="[order_index]"]');
            if (orderInput) {
                orderInput.value = index + 1;
            }
        });
    }

    // Load old input data if validation fails
    @if(old('questions'))
        @foreach(old('questions') as $index => $question)
            addQuestion();
            const questionElement = questionsContainer.lastElementChild;
            
            // Set question data
            questionElement.querySelector('textarea[name*="[question_text]"]').value = '{{ $question["question_text"] ?? "" }}';
            questionElement.querySelector('select[name*="[question_type]"]').value = '{{ $question["question_type"] ?? "" }}';
            questionElement.querySelector('input[name*="[is_required]"]').checked = {{ isset($question['is_required']) ? 'true' : 'false' }};
            questionElement.querySelector('input[name*="[order_index]"]').value = '{{ $question["order_index"] ?? $index + 1 }}';
            
            // Trigger type change to show/hide options
            const typeSelect = questionElement.querySelector('.question-type');
            typeSelect.dispatchEvent(new Event('change'));
            
            // Add options if any
            @if(isset($question['options']))
                @foreach($question['options'] as $optionIndex => $option)
                    addOption(questionElement, '{{ $option["label"] ?? "" }}');
                    const optionElement = questionElement.querySelector('.options-list').lastElementChild;
                    optionElement.querySelector('input[name*="[value]"]').value = '{{ $option["value"] ?? "" }}';
                @endforeach
            @endif
        @endforeach
    @endif
});
</script>
@endsection