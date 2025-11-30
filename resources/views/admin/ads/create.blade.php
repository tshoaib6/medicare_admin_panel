@extends('layouts.admin')

@section('title', 'Create New Advertisement')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bullhorn text-primary mr-2"></i>
            Create New Advertisement
        </h1>
        <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Ads
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Form Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Advertisement Details
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data" id="adForm">
                        @csrf
                        
                        <!-- Title -->
                        <div class="form-group">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading text-primary mr-1"></i>
                                Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Enter advertisement title..." required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left text-primary mr-1"></i>
                                Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Describe what this ad promotes..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ad Type and Status Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="form-label">
                                        <i class="fas fa-tag text-primary mr-1"></i>
                                        Advertisement Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Select ad type...</option>
                                        <option value="banner" {{ old('type') == 'banner' ? 'selected' : '' }}>
                                            Banner - Full width display
                                        </option>
                                        <option value="popup" {{ old('type') == 'popup' ? 'selected' : '' }}>
                                            Popup - Modal overlay
                                        </option>
                                        <option value="inline" {{ old('type') == 'inline' ? 'selected' : '' }}>
                                            Inline - Within content
                                        </option>
                                        <option value="sidebar" {{ old('type') == 'sidebar' ? 'selected' : '' }}>
                                            Sidebar - Side panel
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-toggle-on text-primary mr-1"></i>
                                        Status
                                    </label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', '1') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Active (ad will be displayed)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="form-group">
                            <label for="image" class="form-label">
                                <i class="fas fa-image text-primary mr-1"></i>
                                Advertisement Image
                            </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                <label class="custom-file-label" for="image">Choose image file...</label>
                            </div>
                            <small class="form-text text-muted">
                                Supported formats: JPEG, PNG, GIF, SVG. Maximum size: 2MB.
                            </small>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>

                        <!-- Target URL -->
                        <div class="form-group">
                            <label for="target_url" class="form-label">
                                <i class="fas fa-external-link-alt text-primary mr-1"></i>
                                Target URL
                            </label>
                            <input type="url" class="form-control @error('target_url') is-invalid @enderror" 
                                   id="target_url" name="target_url" value="{{ old('target_url') }}" 
                                   placeholder="https://example.com/landing-page">
                            <small class="form-text text-muted">
                                URL to redirect users when they click the ad.
                            </small>
                            @error('target_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Target Audience -->
                        <div class="form-group">
                            <label for="target_audience" class="form-label">
                                <i class="fas fa-users text-primary mr-1"></i>
                                Target Audience
                            </label>
                            <input type="text" class="form-control @error('target_audience') is-invalid @enderror" 
                                   id="target_audience" name="target_audience" value="{{ old('target_audience') }}" 
                                   placeholder="e.g., Medicare beneficiaries, seniors 65+, new enrollees...">
                            <small class="form-text text-muted">
                                Describe who should see this advertisement.
                            </small>
                            @error('target_audience')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Schedule Section -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar text-primary mr-1"></i>
                                Schedule (Optional)
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label small">Start Date</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" 
                                           min="{{ date('Y-m-d') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label small">End Date</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                Leave empty for ads that run indefinitely.
                            </small>
                        </div>

                        <!-- HTML Content (Advanced) -->
                        <div class="form-group">
                            <div class="d-flex align-items-center mb-2">
                                <label for="content_html" class="form-label mb-0">
                                    <i class="fas fa-code text-primary mr-1"></i>
                                    Custom HTML Content
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-info ml-2" 
                                        data-toggle="collapse" data-target="#htmlHelp">
                                    <i class="fas fa-question-circle"></i>
                                </button>
                            </div>
                            <div class="collapse" id="htmlHelp">
                                <div class="alert alert-info small">
                                    <strong>Advanced users:</strong> You can provide custom HTML for the ad display. 
                                    This will override the standard image + title display. Use Bootstrap classes for styling.
                                </div>
                            </div>
                            <textarea class="form-control @error('content_html') is-invalid @enderror" 
                                      id="content_html" name="content_html" rows="4" 
                                      placeholder="<div class='my-custom-ad'>...custom HTML...</div>">{{ old('content_html') }}</textarea>
                            @error('content_html')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Create Advertisement
                            </button>
                            <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Panel -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-eye mr-1"></i>
                        Live Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div id="adPreview">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-bullhorn fa-2x mb-3"></i>
                            <p>Fill in the form to see a preview of your advertisement.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-lightbulb mr-1"></i>
                        Ad Type Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="mb-2">
                            <strong class="text-primary">Banner:</strong> Best for promotions and announcements. Displayed at top/bottom of pages.
                        </div>
                        <div class="mb-2">
                            <strong class="text-info">Popup:</strong> High visibility for important messages. Use sparingly to avoid annoyance.
                        </div>
                        <div class="mb-2">
                            <strong class="text-success">Inline:</strong> Integrated within content. Less intrusive, good engagement.
                        </div>
                        <div class="mb-2">
                            <strong class="text-warning">Sidebar:</strong> Persistent visibility. Great for ongoing campaigns.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update file input label
    $('.custom-file-input').on('change', function() {
        var fileName = $(this)[0].files[0]?.name || 'Choose image file...';
        $(this).next('.custom-file-label').text(fileName);
    });

    // Form field watchers for live preview
    $('#title, #description, #type').on('input change', updatePreview);
    
    // Date validation
    $('#start_date').on('change', function() {
        var startDate = $(this).val();
        if (startDate) {
            $('#end_date').attr('min', startDate);
        }
    });
});

function previewImage(event) {
    var file = event.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#previewImg').attr('src', e.target.result);
            $('#imagePreview').show();
            updatePreview();
        };
        reader.readAsDataURL(file);
    } else {
        $('#imagePreview').hide();
    }
}

function updatePreview() {
    var title = $('#title').val();
    var description = $('#description').val();
    var type = $('#type').val();
    var imageSrc = $('#previewImg').attr('src');
    
    if (!title && !description && !type) {
        $('#adPreview').html(`
            <div class="text-center text-muted py-4">
                <i class="fas fa-bullhorn fa-2x mb-3"></i>
                <p>Fill in the form to see a preview of your advertisement.</p>
            </div>
        `);
        return;
    }
    
    var previewHtml = '';
    var typeClass = {
        'banner': 'alert alert-primary',
        'popup': 'card border-primary',
        'inline': 'card border-success',
        'sidebar': 'card border-warning'
    };
    
    var typeIcon = {
        'banner': 'fas fa-flag',
        'popup': 'fas fa-window-maximize',
        'inline': 'fas fa-align-center',
        'sidebar': 'fas fa-columns'
    };
    
    if (type) {
        previewHtml = `
            <div class="${typeClass[type] || 'card'} mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start">
                        ${imageSrc ? `<img src="${imageSrc}" alt="Ad" class="mr-3" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">` : `<div class="mr-3 bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 4px;"><i class="fas fa-image text-muted"></i></div>`}
                        <div class="flex-grow-1">
                            <h6 class="mb-1 font-weight-bold">${title || 'Ad Title'}</h6>
                            <p class="mb-2 small text-muted">${description || 'Ad description will appear here...'}</p>
                            <small class="text-primary">
                                <i class="${typeIcon[type]} mr-1"></i>
                                ${type.charAt(0).toUpperCase() + type.slice(1)} Ad
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    $('#adPreview').html(previewHtml);
}

// Form validation
$('#adForm').on('submit', function(e) {
    var startDate = $('#start_date').val();
    var endDate = $('#end_date').val();
    
    if (startDate && endDate && new Date(endDate) <= new Date(startDate)) {
        e.preventDefault();
        alert('End date must be after start date.');
        return false;
    }
});
</script>
@endpush