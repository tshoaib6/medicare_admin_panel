@extends('layouts.admin')

@section('title', 'Edit Plan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Plan</h1>
    <a href="{{ route('admin.plans.show', $plan) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Details
    </a>
</div>

<form action="{{ route('admin.plans.update', $plan) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list mr-2"></i>Plan Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label font-weight-bold">
                                Plan Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $plan->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label font-weight-bold">
                                Slug <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" name="slug" value="{{ old('slug', $plan->slug) }}" required>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">URL-friendly version (auto-generated from title)</small>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label font-weight-bold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $plan->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="icon" class="form-label font-weight-bold">Icon (Font Awesome class)</label>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                   id="icon" name="icon" value="{{ old('icon', $plan->icon) }}">
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Example: fas fa-heart, fas fa-shield-alt</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label font-weight-bold">Plan Color</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                       id="color" name="color" value="{{ old('color', $plan->color ?? '#007bff') }}" 
                                       style="width: 60px; height: 38px;">
                                <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                       id="color_text" name="color_text" value="{{ old('color', $plan->color ?? '#007bff') }}" 
                                       placeholder="#007bff" readonly>
                            </div>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Color for plan theme and icons</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Benefits Section -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-heart mr-2"></i>Plan Benefits (dynamic add/remove)
                    </h6>
                </div>
                <div class="card-body">
                    <div id="benefits-container">
                        @php
                            $existingBenefits = old('benefits', $plan->benefits ?? []);
                            if (empty($existingBenefits)) $existingBenefits = [''];
                        @endphp
                        
                        @foreach($existingBenefits as $index => $benefit)
                        <div class="benefit-item mb-3">
                            <div class="row align-items-end">
                                <div class="col-md-10">
                                    <label class="form-label font-weight-bold">Benefit {{ $index + 1 }}</label>
                                    <input type="text" name="benefits[]" class="form-control" 
                                           placeholder="e.g., $0 annual deductible" value="{{ $benefit }}">
                                </div>
                                <div class="col-md-2">
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-success btn-sm add-benefit" title="Add Benefit">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm remove-benefit" title="Remove" 
                                                style="{{ count($existingBenefits) > 1 ? '' : 'display: none;' }}">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('benefits')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Availability -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs mr-2"></i>Availability Settings
                    </h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_available" id="is_available" 
                               {{ old('is_available', $plan->is_available) ? 'checked' : '' }}>
                        <label class="form-check-label font-weight-bold" for="is_available">
                            Plan is Available for Users
                        </label>
                        <div class="form-text text-muted">
                            Unchecked plans will not be visible to users during enrollment
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Action Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-save mr-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i> Update Plan
                        </button>
                        <a href="{{ route('admin.plans.show', $plan) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancel Changes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Guidelines -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle mr-2"></i>Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <small>Changing slug may affect existing URLs</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <small>Benefits help users compare plan options</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <small>Color changes affect plan branding</small>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <small>All changes will be logged for audit purposes</small>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Preview -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-eye mr-2"></i>Live Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div id="plan-preview" class="text-center p-3 border rounded" style="background-color: #f8f9fc;">
                        <div id="preview-icon">
                            <i class="{{ $plan->icon ?? 'fas fa-clipboard-list' }} fa-2x text-muted mb-2"></i>
                        </div>
                        <h6 id="preview-title" class="font-weight-bold">{{ $plan->title }}</h6>
                        <p id="preview-description" class="text-muted small">{{ $plan->description ?: 'Plan description will appear here' }}</p>
                        <div id="preview-benefits"></div>
                    </div>
                </div>
            </div>

            <!-- Plan Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-chart-bar mr-2"></i>Plan Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <h4 class="font-weight-bold text-primary">{{ $plan->questionnaires->count() }}</h4>
                                <small class="text-muted">Questionnaires</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="font-weight-bold text-info">{{ $plan->created_at->diffInDays() }}</h4>
                            <small class="text-muted">Days Old</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('benefits-container');
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const descriptionInput = document.getElementById('description');
    const iconInput = document.getElementById('icon');
    const colorInput = document.getElementById('color');
    const colorTextInput = document.getElementById('color_text');
    
    // Auto-generate slug from title
    titleInput.addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        slugInput.value = slug;
        updatePreview();
    });
    
    // Color picker synchronization
    colorInput.addEventListener('input', function() {
        colorTextInput.value = this.value;
        updatePreview();
    });
    
    colorTextInput.addEventListener('input', function() {
        if (this.value.match(/^#[0-9A-F]{6}$/i)) {
            colorInput.value = this.value;
            updatePreview();
        }
    });
    
    // Benefits management
    function updateBenefitsButtons() {
        const items = container.querySelectorAll('.benefit-item');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-benefit');
            if (items.length > 1) {
                removeBtn.style.display = 'inline-block';
            } else {
                removeBtn.style.display = 'none';
            }
            
            // Update labels
            const label = item.querySelector('label');
            if (label) {
                label.textContent = `Benefit ${index + 1}`;
            }
        });
    }
    
    function addBenefitItem() {
        const template = container.querySelector('.benefit-item').cloneNode(true);
        template.querySelector('input').value = '';
        container.appendChild(template);
        updateBenefitsButtons();
        updatePreview();
    }
    
    container.addEventListener('click', function(e) {
        if (e.target.closest('.add-benefit')) {
            e.preventDefault();
            addBenefitItem();
        } else if (e.target.closest('.remove-benefit')) {
            e.preventDefault();
            e.target.closest('.benefit-item').remove();
            updateBenefitsButtons();
            updatePreview();
        }
    });
    
    container.addEventListener('input', function(e) {
        if (e.target.type === 'text') {
            updatePreview();
        }
    });
    
    // Live preview update
    function updatePreview() {
        const previewIcon = document.getElementById('preview-icon');
        const previewTitle = document.getElementById('preview-title');
        const previewDescription = document.getElementById('preview-description');
        const previewBenefits = document.getElementById('preview-benefits');
        
        // Update icon
        const icon = iconInput.value || '{{ $plan->icon ?? "fas fa-clipboard-list" }}';
        const color = colorInput.value || '{{ $plan->color ?? "#007bff" }}';
        previewIcon.innerHTML = `<i class="${icon} fa-2x mb-2" style="color: ${color};"></i>`;
        
        // Update title
        previewTitle.textContent = titleInput.value || '{{ $plan->title }}';
        previewTitle.style.color = color;
        
        // Update description
        previewDescription.textContent = descriptionInput.value || 'Plan description will appear here';
        
        // Update benefits
        const benefits = Array.from(container.querySelectorAll('input[name="benefits[]"]'))
            .map(input => input.value.trim())
            .filter(benefit => benefit.length > 0);
        
        if (benefits.length > 0) {
            previewBenefits.innerHTML = benefits
                .map(benefit => `<small class="badge badge-light mr-1 mb-1">✓ ${benefit}</small>`)
                .join('');
        } else {
            previewBenefits.innerHTML = '<small class="text-muted">No benefits added yet</small>';
        }
    }
    
    // Add event listeners for preview updates
    [descriptionInput, iconInput].forEach(input => {
        input.addEventListener('input', updatePreview);
    });
    
    // Initial setup
    updateBenefitsButtons();
    updatePreview();
});
</script>
@endsection