@extends('layouts.admin')

@section('title', 'Add New Company')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Add New Company</h1>
    <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Companies
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Company Information</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="website" class="form-label">Website URL</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                   id="website" name="website" value="{{ old('website') }}">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Company Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="rating" class="form-label">Company Rating</label>
                            <select class="form-control @error('rating') is-invalid @enderror" 
                                    id="rating" name="rating">
                                <option value="">Select Rating</option>
                                <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                                <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                                <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                                <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                                <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="image_url" class="form-label">Image URL (Optional)</label>
                            <input type="url" class="form-control @error('image_url') is-invalid @enderror" 
                                   id="image_url" name="image_url" value="{{ old('image_url') }}" 
                                   placeholder="https://example.com/logo.png">
                            @error('image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Alternative to file upload</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Specialties <small class="text-muted">(dynamic add/remove)</small></label>
                        <div id="specialties-container">
                            <div class="specialty-item d-flex mb-2">
                                <select name="specialties[]" class="form-control mr-2">
                                    <option value="">Select Specialty</option>
                                    <option value="medicare_advantage">Medicare Advantage</option>
                                    <option value="supplement">Medicare Supplement</option>
                                    <option value="prescription_drugs">Prescription Drug Plans</option>
                                    <option value="dental">Dental Plans</option>
                                    <option value="vision">Vision Plans</option>
                                    <option value="long_term_care">Long-term Care</option>
                                    <option value="life_insurance">Life Insurance</option>
                                </select>
                                <button type="button" class="btn btn-success btn-sm add-specialty mr-1" title="Add Specialty">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm remove-specialty" title="Remove" style="display: none;">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        @error('specialties')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="logo" class="form-label">Company Logo (Upload)</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF (max 2MB)</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-control @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Company
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Guidelines</h6>
            </div>
            <div class="card-body">
                <h6 class="text-primary">Company Information</h6>
                <ul class="text-sm text-muted mb-3">
                    <li>Company name should be unique</li>
                    <li>Email address will be used for notifications</li>
                    <li>Phone number should include country code</li>
                </ul>
                
                <h6 class="text-primary">Logo Requirements</h6>
                <ul class="text-sm text-muted">
                    <li>Maximum file size: 2MB</li>
                    <li>Recommended dimensions: 300x300px</li>
                    <li>Supported formats: JPG, PNG, GIF</li>
                    <li>Logo will be resized automatically</li>
                </ul>
            </div>
        </div>
    </div>
</div>
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
    
    // Add event listeners to initial buttons
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