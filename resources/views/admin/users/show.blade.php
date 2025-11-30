<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Admin</title>
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
        .breadcrumb {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
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
        .profile-container {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 30px;
        }
        .profile-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            height: fit-content;
        }
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .avatar {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 15px;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        .profile-name {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .profile-email {
            font-size: 14px;
            opacity: 0.9;
        }
        .profile-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px 30px;
        }
        .stat {
            text-align: center;
        }
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .details-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .details-header {
            padding: 25px 30px;
            border-bottom: 1px solid #f0f0f0;
        }
        .details-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
        }
        .details-title i {
            margin-right: 10px;
            color: #667eea;
        }
        .section {
            padding: 25px 30px;
            border-bottom: 1px solid #f0f0f0;
        }
        .section:last-child {
            border-bottom: none;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .section-title i {
            margin-right: 10px;
            color: #667eea;
            width: 20px;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .detail-item {
            background: #f8f9ff;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        .detail-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .detail-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
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
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }
        .badge-secondary {
            background: #e2e3e5;
            color: #383d41;
        }
        .back-btn {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }
        .timestamp {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
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
                <div>
                    <div class="breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a> / 
                        <a href="{{ route('admin.users.index') }}">Users</a> / 
                        User Profile
                    </div>
                    <h1 class="page-title">User Profile</h1>
                </div>
                <div class="user-info">
                    <a href="{{ route('admin.users.index') }}" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <div class="profile-container">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="profile-name">{{ $user->first_name }} {{ $user->last_name }}</div>
                        <div class="profile-email">{{ $user->email }}</div>
                    </div>
                    <div class="profile-stats">
                        <div class="stat">
                            <div class="stat-value">{{ $user->id }}</div>
                            <div class="stat-label">User ID</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value">{{ $user->created_at->diffForHumans() }}</div>
                            <div class="stat-label">Member Since</div>
                        </div>
                    </div>
                </div>

                <div class="details-card">
                    <div class="details-header">
                        <div class="details-title">
                            <i class="fas fa-info-circle"></i>
                            Complete Profile Information
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">
                            <i class="fas fa-user"></i>
                            Personal Information
                        </div>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label">First Name</div>
                                <div class="detail-value">{{ $user->first_name }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Last Name</div>
                                <div class="detail-value">{{ $user->last_name }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Email Address</div>
                                <div class="detail-value">{{ $user->email }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Phone Number</div>
                                <div class="detail-value">{{ $user->phone_number ?: 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">
                            <i class="fas fa-heart"></i>
                            Healthcare Information
                        </div>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label">Year of Birth</div>
                                <div class="detail-value">
                                    @if($user->year_of_birth)
                                        {{ $user->year_of_birth }} <small>(Age: {{ date('Y') - $user->year_of_birth }})</small>
                                    @else
                                        Not provided
                                    @endif
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Zip Code</div>
                                <div class="detail-value">{{ $user->zip_code ?: 'Not provided' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Decision Maker</div>
                                <div class="detail-value">
                                    @if($user->is_decision_maker)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Yes
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-times"></i> No
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Medicare Part B</div>
                                <div class="detail-value">
                                    @if($user->has_medicare_part_b)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Yes
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-times"></i> No
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">
                            <i class="fas fa-shield-alt"></i>
                            Account Security & Status
                        </div>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label">Authentication Provider</div>
                                <div class="detail-value">
                                    @if($user->auth_provider === 'google')
                                        <span class="badge badge-info">
                                            <i class="fab fa-google"></i> Google OAuth
                                        </span>
                                    @elseif($user->auth_provider === 'email')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-envelope"></i> Email & Password
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">{{ $user->auth_provider ?: 'Unknown' }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Email Verification</div>
                                <div class="detail-value">
                                    @if($user->email_verified_at)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Verified
                                        </span>
                                        <div class="timestamp">{{ $user->email_verified_at->format('M d, Y H:i:s') }}</div>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-exclamation-circle"></i> Not Verified
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Account Type</div>
                                <div class="detail-value">
                                    @if($user->is_admin)
                                        <span class="badge badge-danger">
                                            <i class="fas fa-crown"></i> Administrator
                                        </span>
                                    @elseif($user->is_guest)
                                        <span class="badge badge-info">
                                            <i class="fas fa-user-clock"></i> Guest User
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-user"></i> Regular User
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Google ID</div>
                                <div class="detail-value">{{ $user->google_id ?: 'Not linked' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">
                            <i class="fas fa-clock"></i>
                            Account Timeline
                        </div>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label">Registration Date</div>
                                <div class="detail-value">
                                    {{ $user->created_at->format('M d, Y') }}
                                    <div class="timestamp">{{ $user->created_at->format('H:i:s') }} ({{ $user->created_at->diffForHumans() }})</div>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Last Profile Update</div>
                                <div class="detail-value">
                                    {{ $user->updated_at->format('M d, Y') }}
                                    <div class="timestamp">{{ $user->updated_at->format('H:i:s') }} ({{ $user->updated_at->diffForHumans() }})</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
