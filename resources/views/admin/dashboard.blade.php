<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Medicare</title>
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card:nth-child(2)::before {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card:nth-child(3)::before {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .stat-card:nth-child(4)::before {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        .stat-card:nth-child(5)::before {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .stat-title {
            font-size: 16px;
            color: #666;
            font-weight: 500;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }
        .stat-card:nth-child(1) .stat-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card:nth-child(2) .stat-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card:nth-child(3) .stat-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .stat-card:nth-child(4) .stat-icon {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        .stat-card:nth-child(5) .stat-icon {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #333;
        }
        .stat-change {
            font-size: 14px;
            color: #28a745;
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
                    <a href="{{ route('admin.dashboard') }}" class="active">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i>
                        Users
                    </a>
                </div>
            </nav>
        </div>

        <div class="main-content">
            <div class="top-bar">
                <h1 class="page-title">Dashboard Overview</h1>
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

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-title">Total Users</div>
                            <div class="stat-value">{{ $totalUsers }}</div>
                            <div class="stat-change">+12% from last month</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-title">Verified Users</div>
                            <div class="stat-value">{{ $verifiedUsers }}</div>
                            <div class="stat-change">+8% from last month</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-title">Admin Users</div>
                            <div class="stat-value">{{ $totalAdmins }}</div>
                            <div class="stat-change">No change</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-title">Google Users</div>
                            <div class="stat-value">{{ $googleUsers }}</div>
                            <div class="stat-change">+15% from last month</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fab fa-google"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-title">Email Users</div>
                            <div class="stat-value">{{ $emailUsers }}</div>
                            <div class="stat-change">+5% from last month</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
