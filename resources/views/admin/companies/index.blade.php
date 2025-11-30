@extends('layouts.admin')

@section('title', 'Companies Management')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Companies Management</h1>
    <a href="{{ route('admin.companies.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Company
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Companies Overview Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Companies</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $companies->total() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
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
                            Active Companies</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $companies->where('is_active', true)->count() ?? $companies->filter(function($c) { return $c->is_active ?? true; })->count() }}</div>
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
                            Top Rated (4+ Stars)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $companies->where('rating', '>=', 4)->count() ?? $companies->filter(function($c) { return ($c->rating ?? 0) >= 4; })->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-gray-300"></i>
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
                            With Specialties</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $companies->whereNotNull('specialties')->count() ?? $companies->filter(function($c) { return !empty($c->specialties); })->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Search & Filters</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.companies.index') }}" class="row">
            <div class="col-md-4 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Search companies..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2 mb-2">
                <select name="rating" class="form-control">
                    <option value="">All Ratings</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <select name="specialty" class="form-control">
                    <option value="">All Specialties</option>
                    <option value="medicare_advantage" {{ request('specialty') == 'medicare_advantage' ? 'selected' : '' }}>Medicare Advantage</option>
                    <option value="supplement" {{ request('specialty') == 'supplement' ? 'selected' : '' }}>Supplement Plans</option>
                    <option value="prescription_drugs" {{ request('specialty') == 'prescription_drugs' ? 'selected' : '' }}>Prescription Drugs</option>
                    <option value="dental" {{ request('specialty') == 'dental' ? 'selected' : '' }}>Dental</option>
                    <option value="vision" {{ request('specialty') == 'vision' ? 'selected' : '' }}>Vision</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <div class="btn-group w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Companies Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Companies List</h6>
    </div>
    <div class="card-body">
        @if($companies->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Rating</th>
                            <th>Phone</th>
                            <th>Specialties (as tags)</th>
                            <th>Actions (Edit/Delete)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($companies as $company)
                        <tr>
                            <td>{{ $company->id }}</td>
                            <td>
                                @if($company->image_url || $company->logo)
                                    <img src="{{ $company->image_url ?? Storage::url($company->logo) }}" 
                                         alt="{{ $company->name }}" 
                                         class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-building text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $company->name }}</div>
                                @if($company->description)
                                    <small class="text-muted">{{ Str::limit($company->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($company->rating)
                                    <div class="d-flex align-items-center">
                                        <span class="font-weight-bold mr-1">{{ number_format($company->rating, 1) }}</span>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $company->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">No rating</span>
                                @endif
                            </td>
                            <td>
                                @if($company->phone)
                                    <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </td>
                            <td>
                                @if($company->specialties && is_array($company->specialties))
                                    @foreach($company->specialties as $specialty)
                                        <span class="badge badge-info mr-1 mb-1">{{ ucfirst(str_replace('_', ' ', $specialty)) }}</span>
                                    @endforeach
                                @elseif($company->specialties && is_string($company->specialties))
                                    @foreach(json_decode($company->specialties, true) ?? [] as $specialty)
                                        <span class="badge badge-info mr-1 mb-1">{{ ucfirst(str_replace('_', ' ', $specialty)) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No specialties</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.companies.show', $company) }}" 
                                       class="btn btn-info btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.companies.edit', $company) }}" 
                                       class="btn btn-warning btn-sm" title="Edit Company">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this company?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Company">
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
                {{ $companies->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-building fa-3x text-gray-400 mb-3"></i>
                <h5 class="text-gray-600">No companies found</h5>
                <p class="text-gray-500">Get started by adding your first insurance company.</p>
                <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Company
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-submit form on filter change
    document.querySelectorAll('select[name="rating"], select[name="specialty"]').forEach(function(select) {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Enhanced search with debouncing
    let searchTimeout;
    document.querySelector('input[name="search"]').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
</script>
@endsection