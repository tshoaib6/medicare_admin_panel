@extends('layouts.admin')

@section('title', 'Questionnaires Management')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Questionnaires Management</h1>
    <a href="{{ route('admin.questionnaires.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Questionnaire
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Questionnaires</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $questionnaires->total() ?? $questionnaires->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-question-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Active Questionnaires</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $questionnaires->where('is_active', true)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Questions</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $questionnaires->sum('questions_count') ?? $questionnaires->sum(function($q) { return $q->questions->count(); }) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Plan Categories</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $questionnaires->pluck('plan_id')->unique()->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Questionnaires Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Questionnaires List</h6>
    </div>
    <div class="card-body">
        @if($questionnaires->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Plan Category</th>
                            <th>Questions</th>
                            <th>Estimated Time</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questionnaires as $questionnaire)
                        <tr>
                            <td>{{ $questionnaire->id }}</td>
                            <td>
                                <div class="font-weight-bold">{{ $questionnaire->title }}</div>
                                @if($questionnaire->description)
                                    <small class="text-muted">{{ Str::limit($questionnaire->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($questionnaire->plan)
                                    <span class="badge badge-info">{{ $questionnaire->plan->title }}</span>
                                @else
                                    <span class="text-muted">No Plan</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $questionnaire->questions_count ?? $questionnaire->questions->count() }} questions</span>
                            </td>
                            <td>
                                @if($questionnaire->estimated_time)
                                    <small class="text-muted">{{ $questionnaire->estimated_time }} min</small>
                                @else
                                    <small class="text-muted">Not set</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $questionnaire->is_active ? 'success' : 'danger' }}">
                                    {{ $questionnaire->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $questionnaire->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.questionnaires.show', $questionnaire) }}" 
                                       class="btn btn-info btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.questionnaires.edit', $questionnaire) }}" 
                                       class="btn btn-warning btn-sm" title="Edit Questionnaire">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.questionnaires.destroy', $questionnaire) }}" method="POST" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this questionnaire and all its questions?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Questionnaire">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $questionnaires->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-question-circle fa-3x text-gray-400 mb-3"></i>
                <h5 class="text-gray-600">No questionnaires found</h5>
                <p class="text-gray-500">Create your first questionnaire to start collecting user information.</p>
                <a href="{{ route('admin.questionnaires.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Questionnaire
                </a>
            </div>
        @endif
    </div>
</div>
@endsection