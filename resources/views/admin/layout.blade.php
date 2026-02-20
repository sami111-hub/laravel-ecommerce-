<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة الإدارة') - متجر Update Aden</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/enhancements.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Cairo', sans-serif; 
            background: linear-gradient(135deg, #5D4037 0%, #4E342E 100%);
            min-height: 100vh;
        }
        
        /* Modern Sidebar */
        .admin-sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #5D4037 0%, #4E342E 100%);
            box-shadow: -4px 0 20px rgba(0,0,0,0.1);
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
        }
        
        .sidebar-logo i {
            font-size: 32px;
            background: linear-gradient(135deg, #FF6B00 0%, #ff8533 100%);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
        
        .sidebar-logo-text h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }
        
        .sidebar-logo-text p {
            margin: 0;
            font-size: 12px;
            opacity: 0.7;
        }
        
        .sidebar-nav {
            padding: 15px 0;
        }
        
        .nav-section-title {
            padding: 20px 20px 10px;
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .sidebar-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
            font-size: 14px;
        }
        
        .sidebar-nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-right-color: #FF6B00;
        }
        
        .sidebar-nav-link.active {
            background: linear-gradient(90deg, rgba(255,107,0,0.2) 0%, rgba(255,107,0,0) 100%);
            color: white;
            border-right-color: #FF6B00;
            font-weight: 600;
        }
        
        .sidebar-nav-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        .sidebar-badge {
            margin-right: auto;
            background: #e74c3c;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }
        
        /* Main Content Area */
        .admin-main {
            margin-left: 0;
            margin-right: 280px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        /* Top Header */
        .admin-topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .topbar-search {
            flex: 1;
            max-width: 500px;
            position: relative;
        }
        
        .topbar-search input {
            width: 100%;
            padding: 10px 15px 10px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .topbar-search input:focus {
            outline: none;
            border-color: #FF6B00;
            box-shadow: 0 0 0 4px rgba(255,107,0,0.1);
        }
        
        .topbar-search i {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
        }
        
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .topbar-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fa;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #2c3e50;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .topbar-btn:hover {
            background: #FF6B00;
            color: white;
            transform: scale(1.1);
        }
        
        .topbar-badge {
            position: absolute;
            top: -5px;
            left: -5px;
            background: #e74c3c;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .topbar-user:hover {
            background: #e9ecef;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FF6B00 0%, #ff8533 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            line-height: 1;
            margin-bottom: 3px;
        }
        
        .user-role {
            font-size: 11px;
            color: #7f8c8d;
        }
        
        /* Content Area */
        .admin-content {
            padding: 30px;
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .page-breadcrumb a {
            color: #FF6B00;
            text-decoration: none;
        }
        
        .page-breadcrumb a:hover {
            text-decoration: underline;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.1;
            transform: translate(30%, -30%);
        }
        
        .stat-card.primary::before { background: #3498db; }
        .stat-card.success::before { background: #5D4037; }
        .stat-card.warning::before { background: #f39c12; }
        .stat-card.danger::before { background: #e74c3c; }
        .stat-card.info::before { background: #9b59b6; }
        .stat-card.orange::before { background: #FF6B00; }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        
        .stat-card.primary .stat-icon { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
        .stat-card.success .stat-icon { background: linear-gradient(135deg, #5D4037 0%, #4E342E 100%); }
        .stat-card.warning .stat-icon { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }
        .stat-card.danger .stat-icon { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }
        .stat-card.info .stat-icon { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); }
        .stat-card.orange .stat-icon { background: linear-gradient(135deg, #FF6B00 0%, #ff8533 100%); }
        
        .stat-trend {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 15px;
        }
        
        .stat-trend.up {
            background: rgba(39, 174, 96, 0.1);
            color: #27ae60;
        }
        
        .stat-trend.down {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }
        
        .stat-body h6 {
            font-size: 13px;
            color: #7f8c8d;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1;
        }
        
        /* Modern Table */
        .modern-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .table-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-header h5 {
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }
        
        .modern-table table {
            width: 100%;
            margin: 0;
        }
        
        .modern-table thead th {
            padding: 15px 25px;
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }
        
        .modern-table tbody td {
            padding: 15px 25px;
            border-top: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        
        .modern-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* Modern Badges */
        .modern-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .modern-badge.primary { background: rgba(52, 152, 219, 0.15); color: #3498db; }
        .modern-badge.success { background: rgba(93, 64, 55, 0.15); color: #5D4037; }
        .modern-badge.warning { background: rgba(243, 156, 18, 0.15); color: #f39c12; }
        .modern-badge.danger { background: rgba(231, 76, 60, 0.15); color: #e74c3c; }
        .modern-badge.info { background: rgba(155, 89, 182, 0.15); color: #9b59b6; }
        
        /* Alerts */
        .modern-alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: none;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modern-alert.success {
            background: rgba(39, 174, 96, 0.1);
            color: #27ae60;
        }
        
        .modern-alert.error {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }
        
        .modern-alert i {
            font-size: 20px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(100%);
            }
            
            .admin-sidebar.open {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-right: 0;
            }
            
            .topbar-search {
                display: none;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                <i class="bi bi-shop"></i>
                <div class="sidebar-logo-text">
                    <h4>متجر Update Aden</h4>
                    <p>لوحة الإدارة</p>
                </div>
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">القائمة الرئيسية</div>
            
            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>لوحة التحكم</span>
            </a>

            <div class="nav-section-title">المحتوى</div>
            
            <a href="{{ route('admin.products.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>المنتجات</span>
                @if(isset($stats) && $stats['products_count'] > 0)
                <span class="sidebar-badge">{{ $stats['products_count'] }}</span>
                @endif
            </a>
            
            <a href="{{ route('admin.categories.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap"></i>
                <span>التصنيفات</span>
            </a>
            
            <a href="{{ route('admin.offers.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}">
                <i class="bi bi-percent"></i>
                <span>العروض</span>
            </a>

            <div class="nav-section-title">الطلبات</div>
            
            <a href="{{ route('admin.orders.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart-check-fill"></i>
                <span>جميع الطلبات</span>
                @if(isset($stats) && $stats['pending_orders'] > 0)
                <span class="sidebar-badge">{{ $stats['pending_orders'] }}</span>
                @endif
            </a>

            <div class="nav-section-title">إدارة المستخدمين</div>
            
            <a href="{{ route('admin.users.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>المستخدمين</span>
            </a>
            
            <a href="{{ route('admin.roles.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <i class="bi bi-shield-check"></i>
                <span>الأدوار</span>
            </a>
            
            <a href="{{ route('admin.permissions.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                <i class="bi bi-key-fill"></i>
                <span>الصلاحيات</span>
            </a>

            <div class="nav-section-title">إعدادات</div>
            
            <a href="{{ route('admin.settings.promo-bar') }}" class="sidebar-nav-link {{ request()->routeIs('admin.settings.promo-bar*') ? 'active' : '' }}">
                <i class="bi bi-megaphone-fill"></i>
                <span>الشريط الترويجي</span>
            </a>
            
            <a href="{{ route('admin.settings.exchange-rates') }}" class="sidebar-nav-link {{ request()->routeIs('admin.settings.exchange-rates*') ? 'active' : '' }}">
                <i class="bi bi-currency-exchange"></i>
                <span>أسعار الصرف</span>
            </a>
            
            <a href="{{ route('admin.settings.hero-slider') }}" class="sidebar-nav-link {{ request()->routeIs('admin.settings.hero-slider*') ? 'active' : '' }}">
                <i class="bi bi-images"></i>
                <span>السلايدر الرئيسي</span>
            </a>
            
            <a href="{{ route('admin.flash-deals.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.flash-deals*') ? 'active' : '' }}">
                <i class="bi bi-lightning-charge-fill"></i>
                <span>عروض اليوم</span>
            </a>
            
            <a href="{{ route('home') }}" class="sidebar-nav-link">
                <i class="bi bi-house-door-fill"></i>
                <span>العودة للموقع</span>
            </a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-nav-link border-0 bg-transparent text-start w-100">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>تسجيل الخروج</span>
                </button>
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="admin-main">
        <!-- Top Bar -->
        <div class="admin-topbar">
            <div class="topbar-search">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="ابحث عن المنتجات، الطلبات، المستخدمين..." />
            </div>
            
            <div class="topbar-actions">
                <button class="topbar-btn" title="الإشعارات">
                    <i class="bi bi-bell-fill"></i>
                    <span class="topbar-badge">3</span>
                </button>
                
                <button class="topbar-btn" title="الرسائل">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span class="topbar-badge">5</span>
                </button>
                
                <div class="topbar-user">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">مدير النظام</div>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="admin-content">
            @if(session('success'))
                <div class="modern-alert success">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="modern-alert error">
                    <i class="bi bi-x-circle-fill"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/toast-notifications.js') }}"></script>
    
    <script>
        // عرض الإشعارات من Laravel
        @if(session('success'))
            showSuccess('{{ session('success') }}');
        @endif
        
        @if(session('error'))
            showError('{{ session('error') }}');
        @endif
    </script>
    
    @yield('scripts')
</body>
</html>
