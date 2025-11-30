@extends('layouts.admin')

@section('title', 'Plan Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Plan Details</h1>
    <div>
        <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit Plan
        </a>
        <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Plans
        </a>
    </div>
</div>

<div class="row">
    <!-- Plan Profile Card -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header text-white text-center" style="background-color: {{ $plan->color ?? '#007bff' }};">
                <div class="plan-icon mb-3">
                    @if($plan->icon)
                        <i class="{{ $plan->icon }} fa-4x"></i>
                    @else
                        <i class="fas fa-clipboard-list fa-4x"></i>
                    @endif
                </div>
                <h4 class="mb-1">{{ $plan->title }}</h4>
                <p class="mb-0">
                    <span class="badge badge-{{ $plan->is_available ? 'light' : 'dark' }}">
                        {{ $plan->is_available ? 'Available' : 'Unavailable' }}
                    </span>
                </p>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-right">
                            <h5 class="font-weight-bold mb-1">{{ $plan->benefits ? count($plan->benefits) : 0 }}</h5>
                            <small class="text-muted text-uppercase">Benefits</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="font-weight-bold mb-1">{{ $plan->questionnaires_count ?? $plan->questionnaires->count() }}</h5>
                        <small class="text-muted text-uppercase">Questionnaires</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Plan Details -->
    <div class="col-lg-8">
        <!-- Basic Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle mr-2"></i>Basic Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Plan Title</label>
                        <p class="mb-0">{{ $plan->title }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Slug</label>
                        <p class="mb-0"><code>{{ $plan->slug }}</code></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Icon Class</label>
                        <p class="mb-0">
                            @if($plan->icon)
                                <i class="{{ $plan->icon }} mr-2" style="color: {{ $plan->color ?? '#007bff' }};"></i>
                                <code>{{ $plan->icon }}</code>
                            @else
                                <span class="text-muted">No icon set</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Plan Color</label>
                        <p class="mb-0">
                            @if($plan->color)
                                <div class="d-flex align-items-center">
                                    <div class="color-preview" style="width: 20px; height: 20px; background-color: {{ $plan->color }}; border: 1px solid #ccc; border-radius: 3px; margin-right: 8px;"></div>
                                    <code>{{ $plan->color }}</code>
                                </div>
                            @else
                                <span class="text-muted">No color set</span>
                            @endif
                        </p>
                    </div>
                    @if($plan->description)
                    <div class="col-12 mb-3">
                        <label class="font-weight-bold text-muted">Description</label>
                        <p class="mb-0">{{ $plan->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Benefits Information -->
        @if($plan->benefits && count($plan->benefits) > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-heart mr-2"></i>Plan Benefits ({{ count($plan->benefits) }})
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($plan->benefits as $benefit)
                    <div class="col-md-6 mb-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <span>{{ $benefit }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-heart mr-2"></i>Plan Benefits
                </h6>
            </div>
            <div class="card-body text-center py-4">
                <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                <h5>No Benefits Listed</h5>
                <p class="text-muted">This plan doesn't have any benefits listed yet.</p>
                <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Benefits
                </a>
            </div>
        </div>
        @endif

        <!-- Questionnaires Information -->
        @if($plan->questionnaires && $plan->questionnaires->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-question-circle mr-2"></i>Related Questionnaires ({{ $plan->questionnaires->count() }})
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Questions Count</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plan->questionnaires as $questionnaire)
                            <tr>
                                <td>{{ $questionnaire->title }}</td>
                                <td>{{ $questionnaire->questions_count ?? $questionnaire->questions->count() }}</td>
                                <td>{{ $questionnaire->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.questionnaires.show', $questionnaire) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-question-circle mr-2"></i>Related Questionnaires
                </h6>
            </div>
            <div class="card-body text-center py-4">
                <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                <h5>No Questionnaires</h5>
                <p class="text-muted">No questionnaires are associated with this plan yet.</p>
                <a href="{{ route('admin.questionnaires.create', ['category_id' => $plan->id]) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Questionnaire
                </a>
            </div>
        </div>
        @endif

        <!-- System Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clock mr-2"></i>System Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Created Date</label>
                        <p class="mb-0">{{ $plan->created_at->format('M d, Y') }}</p>
                        <small class="text-muted">{{ $plan->created_at->format('H:i:s') }} ({{ $plan->created_at->diffForHumans() }})</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Last Updated</label>
                        <p class="mb-0">{{ $plan->updated_at->format('M d, Y') }}</p>
                        <small class="text-muted">{{ $plan->updated_at->format('H:i:s') }} ({{ $plan->updated_at->diffForHumans() }})</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
</style>
@endsection