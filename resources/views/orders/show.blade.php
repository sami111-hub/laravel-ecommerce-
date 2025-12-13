@extends('layout')

@section('content')
<h1 class="mb-4">تفاصيل الطلب #{{ $order->id }}</h1>
<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-body">
                <h5>عناصر الطلب</h5>
                <hr>
                @foreach($order->items as $item)
                <div class="border-bottom pb-3 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            @if($item->product->image)
                                <img src="{{ $item->product->image }}" class="img-fluid rounded" alt="{{ $item->product_name }}">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 80px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>{{ $item->product_name }}</h6>
                            <p class="text-muted">الكمية: {{ $item->quantity }}</p>
                        </div>
                        <div class="col-md-3 text-end">
                            <p class="fw-bold">${{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>المجموع النهائي:</strong>
                    <strong class="text-success fs-5">${{ number_format($order->total, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>معلومات الطلب</h5>
                <hr>
                <p><strong>رقم الطلب:</strong> #{{ $order->id }}</p>
                <p><strong>التاريخ:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                <p><strong>الحالة:</strong> 
                    @if($order->status == 'pending')
                        <span class="badge bg-warning">قيد الانتظار</span>
                    @elseif($order->status == 'processing')
                        <span class="badge bg-info">قيد المعالجة</span>
                    @elseif($order->status == 'completed')
                        <span class="badge bg-success">مكتمل</span>
                    @elseif($order->status == 'cancelled')
                        <span class="badge bg-danger">ملغى</span>
                    @endif
                </p>
                <hr>
                <p><strong>عنوان التوصيل:</strong><br>{{ $order->shipping_address }}</p>
                <p><strong>رقم الهاتف:</strong> {{ $order->phone }}</p>
                @if($order->notes)
                    <p><strong>ملاحظات:</strong><br>{{ $order->notes }}</p>
                @endif
                <hr>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100">
                    <i class="bi bi-arrow-right"></i> رجوع إلى الطلبات
                </a>
            </div>
        </div>
    </div>
</div>
@endsection



