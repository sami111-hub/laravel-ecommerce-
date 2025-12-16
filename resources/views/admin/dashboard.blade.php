@extends('admin.layout')

@section('title', 'لوحة التحكم')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title mb-2">
                <i class="bi bi-speedometer2 text-primary"></i> لوحة التحكم
            </h1>
            <div class="page-breadcrumb">
                <i class="bi bi-house-door"></i>
                <span>الرئيسية</span>
                <i class="bi bi-chevron-left"></i>
                <span>لوحة التحكم</span>
            </div>
        </div>
        <div>
            <span class="text-muted">
                <i class="bi bi-calendar3"></i> {{ now()->format('Y-m-d H:i') }}
            </span>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- إحصائيات رئيسية -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-trend up">
                <i class="bi bi-arrow-up"></i>
                <span>{{ ($stats['users_count'] ?? 0) > 0 ? '+' . round((($stats['users_count'] ?? 0) / max(($stats['users_count'] ?? 0), 1)) * 10) . '%' : '0%' }}</span>
            </div>
        </div>
        <div class="stat-body">
            <h6>إجمالي المستخدمين</h6>
            <div class="stat-value">{{ number_format($stats['users_count'] ?? 0) }}</div>
            <a href="{{ route('admin.users.index') }}" class="stat-link">
                عرض التفاصيل <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div class="stat-trend up">
                <i class="bi bi-arrow-up"></i>
                <span>{{ ($stats['products_count'] ?? 0) > 0 ? '+' . round((($stats['products_count'] ?? 0) / max(($stats['products_count'] ?? 0), 1)) * 5) . '%' : '0%' }}</span>
            </div>
        </div>
        <div class="stat-body">
            <h6>المنتجات المتاحة</h6>
            <div class="stat-value">{{ number_format($stats['products_count'] ?? 0) }}</div>
            <a href="{{ route('admin.products.index') }}" class="stat-link">
                عرض التفاصيل <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="stat-card orange">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="bi bi-cart-check-fill"></i>
            </div>
            <div class="stat-trend up">
                <i class="bi bi-arrow-up"></i>
                <span>{{ ($stats['orders_count'] ?? 0) > 0 ? '+' . round((($stats['orders_count'] ?? 0) / max(($stats['orders_count'] ?? 0), 1)) * 15) . '%' : '0%' }}</span>
            </div>
        </div>
        <div class="stat-body">
            <h6>إجمالي الطلبات</h6>
            <div class="stat-value">{{ number_format($stats['orders_count'] ?? 0) }}</div>
            <a href="{{ route('admin.orders.index') }}" class="stat-link">
                عرض التفاصيل <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-trend {{ ($stats['pending_orders'] ?? 0) > 5 ? 'down' : 'up' }}">
                <i class="bi bi-arrow-{{ ($stats['pending_orders'] ?? 0) > 5 ? 'down' : 'up' }}"></i>
                <span>{{ ($stats['pending_orders'] ?? 0) > 5 ? '-' : '+' }}{{ round((($stats['pending_orders'] ?? 0) / max(($stats['orders_count'] ?? 0), 1)) * 100) }}%</span>
            </div>
        </div>
        <div class="stat-body">
            <h6>طلبات قيد الانتظار</h6>
            <div class="stat-value">{{ number_format($stats['pending_orders'] ?? 0) }}</div>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="stat-link">
                عرض التفاصيل <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- المستخدمين الجدد -->
    <div class="col-lg-6">
        <div class="modern-table">
            <div class="table-header">
                <h5><i class="bi bi-person-plus-fill text-primary"></i> المستخدمين الجدد</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>تاريخ التسجيل</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="width: 32px; height: 32px; font-size: 12px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->role)
                                    <br><small class="text-muted">{{ $user->role->name }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted">{{ $user->email }}</span>
                        </td>
                        <td>
                            <i class="bi bi-calendar3"></i>
                            {{ $user->created_at->format('Y-m-d') }}
                        </td>
                        <td>
                            <span class="modern-badge success">
                                <i class="bi bi-check-circle"></i> نشط
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 32px;"></i>
                            <p class="mb-0 mt-2">لا توجد مستخدمين جدد</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- آخر الطلبات -->
    <div class="col-lg-6">
        <div class="modern-table">
            <div class="table-header">
                <h5><i class="bi bi-bag-check-fill text-success"></i> آخر الطلبات</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-success">
                    عرض الكل
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                <strong class="text-primary">#{{ $order->id }}</strong>
                            </a>
                            @if($order->tracking_code)
                            <br><small class="text-muted">{{ $order->tracking_code }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-circle" style="font-size: 20px;"></i>
                                <div>
                                    {{ $order->user ? $order->user->name : 'مستخدم محذوف' }}
                                    <br><small class="text-muted">{{ $order->user ? $order->user->email : '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong style="color: #FF6B00;">
                                ${{ number_format($order->total ?? 0, 2) }}
                            </strong>
                            @if(isset($order->discount) && $order->discount > 0)
                            <br><small class="text-success">خصم: ${{ number_format($order->discount, 2) }}</small>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                $statusIcons = [
                                    'pending' => 'clock',
                                    'processing' => 'arrow-repeat',
                                    'completed' => 'check-circle',
                                    'cancelled' => 'x-circle'
                                ];
                                $statusTexts = [
                                    'pending' => 'قيد الانتظار',
                                    'processing' => 'قيد المعالجة',
                                    'completed' => 'مكتمل',
                                    'cancelled' => 'ملغي'
                                ];
                                $orderStatus = $order->status ?? 'pending';
                                $color = $statusColors[$orderStatus] ?? 'primary';
                                $icon = $statusIcons[$orderStatus] ?? 'info-circle';
                                $text = $statusTexts[$orderStatus] ?? $orderStatus;
                            @endphp
                            <span class="modern-badge {{ $color }}">
                                <i class="bi bi-{{ $icon }}"></i>
                                {{ $text }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 32px;"></i>
                            <p class="mb-0 mt-2">لا توجد طلبات حديثة</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- إحصائيات إضافية -->
<div class="row g-4 mt-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card info">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="bi bi-percent"></i>
                </div>
            </div>
            <div class="stat-body">
                <h6>العروض النشطة</h6>
                <div class="stat-value">{{ number_format($stats['active_offers'] ?? 0) }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stat-card danger">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
            <div class="stat-body">
                <h6>منتجات قاربت النفاذ</h6>
                <div class="stat-value">{{ number_format($stats['low_stock_products'] ?? 0) }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
            <div class="stat-body">
                <h6>إجمالي الإيرادات</h6>
                <div class="stat-value">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stat-card primary">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
            <div class="stat-body">
                <h6>الأدوار النشطة</h6>
                <div class="stat-value">{{ number_format($stats['roles_count'] ?? 0) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات الطلبات حسب الحالة -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="modern-table">
            <div class="table-header">
                <h5><i class="bi bi-pie-chart-fill text-info"></i> توزيع الطلبات حسب الحالة</h5>
            </div>
            <div class="row g-3 p-3">
                <div class="col-md-3">
                    <div class="card border-warning">
                        <div class="card-body text-center">
                            <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                            <h5 class="mt-2">{{ number_format($orders_by_status['pending'] ?? 0) }}</h5>
                            <p class="text-muted mb-0">قيد الانتظار</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body text-center">
                            <i class="bi bi-arrow-repeat text-info" style="font-size: 2rem;"></i>
                            <h5 class="mt-2">{{ number_format($orders_by_status['processing'] ?? 0) }}</h5>
                            <p class="text-muted mb-0">قيد المعالجة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                            <h5 class="mt-2">{{ number_format($orders_by_status['completed'] ?? 0) }}</h5>
                            <p class="text-muted mb-0">مكتمل</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-danger">
                        <div class="card-body text-center">
                            <i class="bi bi-x-circle text-danger" style="font-size: 2rem;"></i>
                            <h5 class="mt-2">{{ number_format($orders_by_status['cancelled'] ?? 0) }}</h5>
                            <p class="text-muted mb-0">ملغي</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-link {
    display: inline-block;
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: #667eea;
    text-decoration: none;
    transition: all 0.3s ease;
}

.stat-link:hover {
    color: #5568d3;
    text-decoration: underline;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
}

.modern-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.modern-badge.success {
    background: #d1fae5;
    color: #065f46;
}

.modern-badge.warning {
    background: #fef3c7;
    color: #92400e;
}

.modern-badge.info {
    background: #dbeafe;
    color: #1e40af;
}

.modern-badge.danger {
    background: #fee2e2;
    color: #991b1b;
}

.stat-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
</style>
@endsection
