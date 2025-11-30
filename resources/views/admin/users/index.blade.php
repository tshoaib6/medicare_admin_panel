<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-header {
            padding: 30px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
        }
        .sidebar-header h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .sidebar-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .sidebar-nav {
            padding: 20px 0;
        }
        .nav-item {
            margin: 5px 20px;
        }
        .nav-item a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            text-decoration: none;
            color: #555;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .nav-item a:hover, .nav-item a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(5px);
        }
        .nav-item i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }
        .top-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .page-title {
            font-size: 28px;
            color: #333;
            font-weight: 600;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }
        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .filters-section {
            padding: 25px 30px;
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f0fe 100%);
            border-bottom: 1px solid #e0e6ff;
        }
        .filters-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .filters-title i {
            margin-right: 10px;
            color: #667eea;
        }
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        .filter-group label {
            font-size: 14px;
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }
        .filter-group input, .filter-group select {
            padding: 10px 12px;
            border: 2px solid #e0e6ff;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }
        .filter-group input:focus, .filter-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .filter-actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .table-section {
            padding: 0;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        th {
            background: #f8f9ff;
            font-weight: 600;
            color: #333;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tr {
            transition: all 0.3s ease;
        }
        tr:hover {
            background: #f8f9ff;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        .badge-secondary {
            background: #e2e3e5;
            color: #383d41;
        }
        .btn-view {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
        }
        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(23, 162, 184, 0.3);
        }
        .pagination {
            padding: 25px 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f8f9ff;
        }
        .pagination nav {
            display: flex;
            gap: 8px;
        }
        .pagination a, .pagination span {
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .pagination a {
            background: white;
            color: #667eea;
            border: 2px solid #e0e6ff;
        }
        .pagination a:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        .pagination .active span {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 60px 30px;
            color: #666;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #ccc;
        }
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        .active-filter {
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .active-filter a {
            color: white;
            text-decoration: none;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Medicare Admin</h2>
                <p>Management Panel</p>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="active">
                        <i class="fas fa-users"></i>
                        Users
                    </a>
                </div>
            </nav>
        </div>

        <div class="main-content">
            <div class="top-bar">
                <h1 class="page-title">Users Management</h1>
                <div class="user-info">
                    <span>Welcome, {{ auth()->user()->first_name }}!</span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <div class="content-card">
                <div class="filters-section">
                    <div class="filters-title">
                        <i class="fas fa-filter"></i>
                        Filter Users
                    </div>
                    <form method="GET" action="{{ route('admin.users.index') }}">
                        <div class="filters-grid">
                            <div class="filter-group">
                                <label for="search">Search</label>
                                <input type="text" id="search" name="search" placeholder="Name, email, phone..." value="{{ $search ?? '' }}">
                            </div>
                            <div class="filter-group">
                                <label for="verified">Email Status</label>
                                <select id="verified" name="verified">
                                    <option value="">All Users</option>
                                    <option value="yes" {{ ($verified ?? '') == 'yes' ? 'selected' : '' }}>Verified</option>
                                    <option value="no" {{ ($verified ?? '') == 'no' ? 'selected' : '' }}>Not Verified</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="auth_provider">Auth Provider</label>
                                <select id="auth_provider" name="auth_provider">
                                    <option value="">All Providers</option>
                                    <option value="email" {{ ($authProvider ?? '') == 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="google" {{ ($authProvider ?? '') == 'google' ? 'selected' : '' }}>Google</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="is_admin">User Type</label>
                                <select id="is_admin" name="is_admin">
                                    <option value="">All Types</option>
                                    <option value="yes" {{ ($isAdmin ?? '') == 'yes' ? 'selected' : '' }}>Admin</option>
                                    <option value="no" {{ ($isAdmin ?? '') == 'no' ? 'selected' : '' }}>Regular User</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="has_medicare">Medicare Part B</label>
                                <select id="has_medicare" name="has_medicare">
                                    <option value="">All Users</option>
                                    <option value="yes" {{ ($hasMedicare ?? '') == 'yes' ? 'selected' : '' }}>Has Medicare Part B</option>
                                    <option value="no" {{ ($hasMedicare ?? '') == 'no' ? 'selected' : '' }}>No Medicare Part B</option>
                                </select>
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Clear All
                            </a>
                        </div>
                    </form>
                    
                    @if($search || $verified || $authProvider || $isAdmin || $hasMedicare)
                        <div class="active-filters">
                            <strong>Active Filters:</strong>
                            @if($search)
                                <span class="active-filter">
                                    Search: "{{ $search }}"
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}">×</a>
                                </span>
                            @endif
                            @if($verified)
                                <span class="active-filter">
                                    Verified: {{ $verified == 'yes' ? 'Yes' : 'No' }}
                                    <a href="{{ request()->fullUrlWithQuery(['verified' => null]) }}">×</a>
                                </span>
                            @endif
                            @if($authProvider)
                                <span class="active-filter">
                                    Provider: {{ ucfirst($authProvider) }}
                                    <a href="{{ request()->fullUrlWithQuery(['auth_provider' => null]) }}">×</a>
                                </span>
                            @endif
                            @if($isAdmin)
                                <span class="active-filter">
                                    Type: {{ $isAdmin == 'yes' ? 'Admin' : 'User' }}
                                    <a href="{{ request()->fullUrlWithQuery(['is_admin' => null]) }}">×</a>
                                </span>
                            @endif
                            @if($hasMedicare)
                                <span class="active-filter">
                                    Medicare: {{ $hasMedicare == 'yes' ? 'Yes' : 'No' }}
                                    <a href="{{ request()->fullUrlWithQuery(['has_medicare' => null]) }}">×</a>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="table-section">
                    @if($users->count() > 0)
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-hashtag"></i> ID</th>
                                        <th><i class="fas fa-user"></i> Name</th>
                                        <th><i class="fas fa-envelope"></i> Email</th>
                                        <th><i class="fas fa-phone"></i> Phone</th>
                                        <th><i class="fas fa-shield-alt"></i> Provider</th>
                                        <th><i class="fas fa-check-circle"></i> Verified</th>
                                        <th><i class="fas fa-user-shield"></i> Type</th>
                                        <th><i class="fas fa-calendar"></i> Registered</th>
                                        <th><i class="fas fa-cog"></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td><strong>#{{ $user->id }}</strong></td>
                                        <td>
                                            <div style="font-weight: 600;">{{ $user->first_name }} {{ $user->last_name }}</div>
                                            @if($user->year_of_birth)
                                                <div style="font-size: 12px; color: #666;">Age: {{ date('Y') - $user->year_of_birth }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td>
                                            @if($user->auth_provider === 'google')
                                                <span class="badge badge-info">
                                                    <i class="fab fa-google"></i> Google
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-envelope"></i> Email
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Verified
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times"></i> Not Verified
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->is_admin)
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-crown"></i> Admin
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-user"></i> User
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn-view">
                                                <i class="fas fa-eye"></i> View Profile
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <h3>No Users Found</h3>
                            <p>Try adjusting your search criteria or filters.</p>
                        </div>
                    @endif
                </div>

                @if($users->hasPages())
                    <div class="pagination">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
