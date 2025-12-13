@extends('layout')

@section('content')
<h1 class="mb-4">لوحة التحكم</h1>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-cart" style="font-size: 3rem; color: #0d6efd;"></i>
                <h3 class="mt-3">{{ $cartCount }}</h3>
                <p class="text-muted">المجموع في السلة</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-bag-check" style="font-size: 3rem; color: #28a745;"></i>
                <h3 class="mt-3">{{ $orders->count() }}</h3>
                <p class="text-muted">الطلبات الأخيرة</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-person-circle" style="font-size: 3rem; color: #6c757d;"></i>
                <h3 class="mt-3">{{ Auth::user()->name }}</h3>
                <p class="text-muted">مرحباً بك</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5>الطلبات الأخيرة</h5>
                <hr>
                @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>المجموع</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning">قيد الانتظار</span>
                                    @elseif($order->status == 'processing')
                                        <span class="badge bg-info">قيد المعالجة</span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge bg-success">مكتمل</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge bg-danger">ملغى</span>
                                    @endif
                                </td>
                                <td class="text-success fw-bold">${{ number_format($order->total, 2) }}</td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> عرض
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('orders.index') }}" class="btn btn-primary mt-3">عرض جميع الطلبات</a>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">لا توجد طلبات بعد</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">تصفح المنتجات</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection



