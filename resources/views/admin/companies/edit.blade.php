@extends('layouts.admin')

@section('title', 'Edit Company')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Company</h1>
    <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Details
    </a>
</div>

<form action="{{ route('admin.companies.update', $company) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-building mr-2"></i>Company Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label font-weight-bold">
                                Company Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $company->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label font-weight-bold">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $company->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label font-weight-bold">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $company->phone) }}" 
                                   placeholder="(123) 456-7890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="website" class="form-label font-weight-bold">Website URL</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                   id="website" name="website" value="{{ old('website', $company->website) }}" 
                                   placeholder="https://example.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="address" class="form-label font-weight-bold">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2" 
                                      placeholder="Company headquarters address">{{ old('address', $company->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label font-weight-bold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Brief description about the company">{{ old('description', $company->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="rating" class="form-label font-weight-bold">Company Rating</label>
                            <select class="form-control @error('rating') is-invalid @enderror" 
                                    id="rating" name="rating">
                                <option value="">Select Rating</option>
                                <option value="1" {{ old('rating', $company->rating) == '1' ? 'selected' : '' }}>1 Star</option>
                                <option value="2" {{ old('rating', $company->rating) == '2' ? 'selected' : '' }}>2 Stars</option>
                                <option value="3" {{ old('rating', $company->rating) == '3' ? 'selected' : '' }}>3 Stars</option>
                                <option value="4" {{ old('rating', $company->rating) == '4' ? 'selected' : '' }}>4 Stars</option>
                                <option value="5" {{ old('rating', $company->rating) == '5' ? 'selected' : '' }}>5 Stars</option>
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="image_url" class="form-label font-weight-bold">Image URL (Optional)</label>
                            <input type="url" class="form-control @error('image_url') is-invalid @enderror" 
                                   id="image_url" name="image_url" value="{{ old('image_url', $company->image_url) }}" 
                                   placeholder="https://example.com/logo.png">
                            @error('image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Alternative to file upload</small>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="form-label font-weight-bold">Specialties <small class="text-muted">(dynamic add/remove)</small></label>
                            <div id="specialties-container">
                                @php
                                    $existingSpecialties = old('specialties', 
                                        is_array($company->specialties) ? $company->specialties : 
                                        (is_string($company->specialties) ? json_decode($company->specialties, true) : [])
                                    ) ?? [''];
                                    if (empty($existingSpecialties)) $existingSpecialties = [''];
                                @endphp
                                
                                @foreach($existingSpecialties as $index => $specialty)
                                <div class="specialty-item d-flex mb-2">
                                    <select name="specialties[]" class="form-control mr-2">
                                        <option value="">Select Specialty</option>
                                        <option value="medicare_advantage" {{ $specialty == 'medicare_advantage' ? 'selected' : '' }}>Medicare Advantage</option>
                                        <option value="supplement" {{ $specialty == 'supplement' ? 'selected' : '' }}>Medicare Supplement</option>
                                        <option value="prescription_drugs" {{ $specialty == 'prescription_drugs' ? 'selected' : '' }}>Prescription Drug Plans</option>
                                        <option value="dental" {{ $specialty == 'dental' ? 'selected' : '' }}>Dental Plans</option>
                                        <option value="vision" {{ $specialty == 'vision' ? 'selected' : '' }}>Vision Plans</option>
                                        <option value="long_term_care" {{ $specialty == 'long_term_care' ? 'selected' : '' }}>Long-term Care</option>
                                        <option value="life_insurance" {{ $specialty == 'life_insurance' ? 'selected' : '' }}>Life Insurance</option>
                                    </select>
                                    <button type="button" class="btn btn-success btn-sm add-specialty mr-1" title="Add Specialty">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm remove-specialty" title="Remove" style="{{ count($existingSpecialties) > 1 ? '' : 'display: none;' }}">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            @error('specialties')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Logo -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-image mr-2"></i>Company Logo
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Current Logo Display -->
                    @if($company->logo)
                    <div class="current-logo mb-3">
                        <label class="form-label font-weight-bold">Current Logo:</label>
                        <div class="d-flex align-items-center">
                            <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }} Logo" 
                                 class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover;">
                            <div>
                                <p class="mb-1"><strong>{{ basename($company->logo) }}</strong></p>
                                <small class="text-muted">
                                    Uploaded {{ \Carbon\Carbon::parse(Storage::lastModified($company->logo))->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Logo Upload -->
                    <div class="row">
                        <div class="col-md-8">
                            <label for="logo" class="form-label font-weight-bold">
                                {{ $company->logo ? 'Replace Logo' : 'Upload Logo' }}
                            </label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" name="logo" accept="image/*">
                            <small class="form-text text-muted">
                                Supported formats: JPG, PNG, GIF. Maximum size: 2MB. Recommended: 200x200px square.
                            </small>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if($company->logo)
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Remove Current Logo</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo">
                                <label class="form-check-label text-danger" for="remove_logo">
                                    Remove existing logo
                                </label>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status & Settings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs mr-2"></i>Status & Settings
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                       {{ old('is_active', $company->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label font-weight-bold" for="is_active">
                                    Company Status
                                </label>
                                <div class="form-text text-muted">
                                    Active companies can create and manage insurance plans
                                </div>
                            </div>
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
                            <i class="fas fa-save"></i> Update Company
                        </button>
                        <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-outline-secondary">
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
                            <small>Company name must be unique and descriptive</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <small>Email will be used for official communication</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <small>Logo should be square format for best display</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <small>Inactive companies cannot create new plans</small>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <small>All changes will be logged for audit purposes</small>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Company Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-chart-bar mr-2"></i>Company Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <h4 class="font-weight-bold text-primary">{{ $company->plans->count() ?? 0 }}</h4>
                                <small class="text-muted">Active Plans</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="font-weight-bold text-info">{{ $company->created_at->diffInDays() }}</h4>
                            <small class="text-muted">Days Old</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.form-switch .form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-lg {
    font-size: 1.1rem;
    padding: 12px 24px;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('specialties-container');
    
    function updateButtons() {
        const items = container.querySelectorAll('.specialty-item');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-specialty');
            if (items.length > 1) {
                removeBtn.style.display = 'inline-block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }
    
    function addSpecialtyItem() {
        const template = container.querySelector('.specialty-item').cloneNode(true);
        template.querySelector('select').value = '';
        container.appendChild(template);
        updateButtons();
        
        // Add event listeners to new buttons
        template.querySelector('.add-specialty').addEventListener('click', addSpecialtyItem);
        template.querySelector('.remove-specialty').addEventListener('click', function() {
            template.remove();
            updateButtons();
        });
    }
    
    // Add event listeners to existing buttons
    container.addEventListener('click', function(e) {
        if (e.target.closest('.add-specialty')) {
            e.preventDefault();
            addSpecialtyItem();
        } else if (e.target.closest('.remove-specialty')) {
            e.preventDefault();
            e.target.closest('.specialty-item').remove();
            updateButtons();
        }
    });
    
    // Initial button state
    updateButtons();
});
</script>
@endsection