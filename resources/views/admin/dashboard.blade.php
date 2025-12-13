@extends('admin.layout')

@section('title', 'لوحة التحكم')

@section('content')
<div class="page-header">
    <h1 class="page-title">لوحة التحكم</h1>
    <div class="page-breadcrumb">
        <i class="bi bi-house-door"></i>
        <span>الرئيسية</span>
        <i class="bi bi-chevron-left"></i>
        <span>لوحة التحكم</span>
    </div>
</div>

<!-- إحصائيات رئيسية -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-trend up">
                <i class="bi bi-arrow-up"></i>
                <span>+12%</span>
            </div>
        </div>
        <div class="stat-body">
            <h6>إجمالي المستخدمين</h6>
            <div class="stat-value">{{ number_format($stats['users_count']) }}</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div class="stat-trend up">
                <i class="bi bi-arrow-up"></i>
                <span>+8%</span>
            </div>
        </div>
        <div class="stat-body">
            <h6>المنتجات المتاحة</h6>
            <div class="stat-value">{{ number_format($stats['products_count']) }}</div>
        </div>
    </div>

    <div class="stat-card orange">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="bi bi-cart-check-fill"></i>
            </div>
            <div class="stat-trend up">
                <i class="bi bi-arrow-up"></i>
                <span>+24%</span>
            </div>
        </div>
        <div class="stat-body">
            <h6>إجمالي الطلبات</h6>
            <div class="stat-value">{{ number_format($stats['orders_count']) }}</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-trend down">
                <i class="bi bi-arrow-down"></i>
                <span>-5%</span>
            </div>
        </div>
        <div class="stat-body">
            <h6>طلبات قيد الانتظار</h6>
            <div class="stat-value">{{ number_format($stats['pending_orders']) }}</div>
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
                                <strong>{{ $user->name }}</strong>
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
                            <strong class="text-primary">#{{ $order->id }}</strong>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-circle" style="font-size: 20px;"></i>
                                {{ $order->user->name }}
                            </div>
                        </td>
                        <td>
                            <strong style="color: #FF6B00;">
                                ${{ number_format($order->total, 2) }}
                            </strong>
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
                                $color = $statusColors[$order->status] ?? 'primary';
                                $icon = $statusIcons[$order->status] ?? 'info-circle';
                                $text = $statusTexts[$order->status] ?? $order->status;
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
                <div class="stat-value">{{ rand(5, 15) }}</div>
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
                <div class="stat-value">{{ rand(3, 8) }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="bi bi-star-fill"></i>
                </div>
            </div>
            <div class="stat-body">
                <h6>متوسط التقييم</h6>
                <div class="stat-value">4.8</div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stat-card primary">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="bi bi-eye-fill"></i>
                </div>
            </div>
            <div class="stat-body">
                <h6>الزيارات اليوم</h6>
                <div class="stat-value">{{ number_format(rand(500, 2000)) }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
