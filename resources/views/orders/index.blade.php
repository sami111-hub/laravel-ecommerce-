@extends('layout')

@section('content')
<h1 class="mb-4">طلباتي</h1>
@if($orders->count() > 0)
<div class="table-responsive">
    <table class="table table-striped">
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
@else
<div class="text-center py-5">
    <i class="bi bi-inbox" style="font-size: 5rem; color: #ddd;"></i>
    <h3 class="mt-3">لا توجد طلبات</h3>
    <p class="text-muted">لم تقم بإنشاء أي طلبات بعد</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary">تصفح المنتجات</a>
</div>
@endif
@endsection



