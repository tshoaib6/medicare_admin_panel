@extends('layouts.admin')

@section('title', 'Edit Questionnaire')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Questionnaire</h1>
    <div>
        <a href="{{ route('admin.questionnaires.show', $questionnaire) }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mr-2">
            <i class="fas fa-eye fa-sm text-white-50"></i> View Details
        </a>
        <a href="{{ route('admin.questionnaires.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Questionnaire Information</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.questionnaires.update', $questionnaire) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-lg-8">
                    <div class="form-group">
                        <label for="title" class="form-label font-weight-bold">
                            Questionnaire Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $questionnaire->title) }}" 
                               placeholder="Enter questionnaire title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label font-weight-bold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3"
                                  placeholder="Describe the purpose of this questionnaire">{{ old('description', $questionnaire->description) }}</textarea>
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
                                        <option value="{{ $plan->id }}" {{ old('plan_id', $questionnaire->plan_id) == $plan->id ? 'selected' : '' }}>
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
                                       id="estimated_time" name="estimated_time" value="{{ old('estimated_time', $questionnaire->estimated_time) }}" 
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
                                           {{ old('is_active', $questionnaire->is_active) ? 'checked' : '' }}>
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
                                           {{ old('is_required', $questionnaire->is_required) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_required">
                                        Required for Plan
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Users must complete this questionnaire
                                </small>
                            </div>

                            <hr>
                            <div class="text-sm">
                                <strong>Created:</strong><br>
                                {{ $questionnaire->created_at->format('M j, Y g:i A') }}
                                <br><br>
                                <strong>Last Updated:</strong><br>
                                {{ $questionnaire->updated_at->format('M j, Y g:i A') }}
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
                        <!-- Existing questions will be loaded here -->
                    </div>
                    <div id="noQuestionsMessage" class="text-center py-4" style="display: none;">
                        <i class="fas fa-question-circle fa-2x text-gray-400 mb-3"></i>
                        <p class="text-gray-500">No questions added yet. Click "Add Question" to start.</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-group mt-4">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.questionnaires.show', $questionnaire) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Questionnaire
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

    // Load existing questions
    loadExistingQuestions();

    addQuestionBtn.addEventListener('click', function() {
        addQuestion();
    });

    function loadExistingQuestions() {
        const existingQuestions = @json($questionnaire->questions->sortBy('order_index'));
        
        existingQuestions.forEach(function(question, index) {
            addQuestion(question, index);
        });

        if (existingQuestions.length === 0) {
            noQuestionsMessage.style.display = 'block';
        }
    }

    function addQuestion(existingQuestion = null, existingIndex = null) {
        const isExisting = existingQuestion !== null;
        const currentIndex = isExisting ? existingIndex : questionIndex;
        
        const questionHtml = `
            <div class="question-item border rounded p-3 mb-3" data-question-index="${currentIndex}">
                ${isExisting ? `<input type="hidden" name="questions[${currentIndex}][id]" value="${existingQuestion.id}">` : ''}
                
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="font-weight-bold text-primary">Question ${currentIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-question">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Question Text *</label>
                            <textarea class="form-control" name="questions[${currentIndex}][question_text]" 
                                    rows="2" placeholder="Enter your question" required>${isExisting ? existingQuestion.question_text : ''}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Question Type *</label>
                            <select class="form-control question-type" name="questions[${currentIndex}][question_type]" required>
                                <option value="">Select type...</option>
                                <option value="text" ${isExisting && existingQuestion.question_type === 'text' ? 'selected' : ''}>Text Input</option>
                                <option value="number" ${isExisting && existingQuestion.question_type === 'number' ? 'selected' : ''}>Number</option>
                                <option value="email" ${isExisting && existingQuestion.question_type === 'email' ? 'selected' : ''}>Email</option>
                                <option value="date" ${isExisting && existingQuestion.question_type === 'date' ? 'selected' : ''}>Date</option>
                                <option value="single_choice" ${isExisting && existingQuestion.question_type === 'single_choice' ? 'selected' : ''}>Single Choice</option>
                                <option value="multiple_choice" ${isExisting && existingQuestion.question_type === 'multiple_choice' ? 'selected' : ''}>Multiple Choice</option>
                                <option value="yes_no" ${isExisting && existingQuestion.question_type === 'yes_no' ? 'selected' : ''}>Yes/No</option>
                                <option value="textarea" ${isExisting && existingQuestion.question_type === 'textarea' ? 'selected' : ''}>Long Text</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" 
                                   id="required_${currentIndex}" name="questions[${currentIndex}][is_required]" value="1"
                                   ${isExisting && existingQuestion.is_required ? 'checked' : ''}>
                            <label class="custom-control-label" for="required_${currentIndex}">
                                Required Question
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="number" class="form-control form-control-sm" 
                                   name="questions[${currentIndex}][order_index]" 
                                   placeholder="Order" value="${isExisting ? existingQuestion.order_index : currentIndex + 1}" min="1">
                        </div>
                    </div>
                </div>

                <!-- Options Container -->
                <div class="options-container" style="display: ${isExisting && ['single_choice', 'multiple_choice'].includes(existingQuestion.question_type) ? 'block' : 'none'};">
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
        
        const questionElement = questionsContainer.lastElementChild;
        
        // Load existing options if any
        if (isExisting && existingQuestion.options && existingQuestion.options.length > 0) {
            existingQuestion.options.forEach(function(option) {
                addOption(questionElement, option.label, option.value, option.id);
            });
        }
        
        // Attach events to the new question
        attachQuestionEvents(questionElement);
        
        if (!isExisting) {
            questionIndex++;
            updateQuestionNumbers();
        }
    }

    function attachQuestionEvents(questionElement) {
        // Remove question
        const removeBtn = questionElement.querySelector('.remove-question');
        removeBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this question?')) {
                questionElement.remove();
                updateQuestionNumbers();
                
                if (questionsContainer.children.length === 0) {
                    noQuestionsMessage.style.display = 'block';
                }
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

    function addOption(questionElement, defaultLabel = '', defaultValue = '', optionId = null) {
        const questionIdx = questionElement.dataset.questionIndex;
        const optionsList = questionElement.querySelector('.options-list');
        const optionIndex = optionsList.children.length;
        
        const optionHtml = `
            <div class="option-item d-flex align-items-center mb-2">
                ${optionId ? `<input type="hidden" name="questions[${questionIdx}][options][${optionIndex}][id]" value="${optionId}">` : ''}
                <div class="flex-grow-1 mr-2">
                    <input type="text" class="form-control" 
                           name="questions[${questionIdx}][options][${optionIndex}][label]" 
                           placeholder="Option label" value="${defaultLabel}" required>
                </div>
                <div class="mr-2" style="width: 100px;">
                    <input type="text" class="form-control" 
                           name="questions[${questionIdx}][options][${optionIndex}][value]" 
                           placeholder="Value" value="${defaultValue || defaultLabel.toLowerCase().replace(/\s+/g, '_')}">
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
            if (confirm('Remove this option?')) {
                this.closest('.option-item').remove();
            }
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
});
</script>
@endsection