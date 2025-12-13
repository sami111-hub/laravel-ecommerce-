@extends('admin.layout')

@section('title', 'إدارة الطلبات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>الطلبات</h4>
</div>

<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6>إجمالي الطلبات</h6>
                <h3>{{ $orderStats['total'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6>قيد الانتظار</h6>
                <h3>{{ $orderStats['pending'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h6>قيد المعالجة</h6>
                <h3>{{ $orderStats['processing'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6>مكتملة</h6>
                <h3>{{ $orderStats['completed'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="بحث بالاسم أو البريد..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">كل الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغى</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">بحث</button>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>المستخدم</th>
                    <th>الحالة</th>
                    <th>المجموع</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>#{{ $order->id }}</strong></td>
                    <td>
                        {{ $order->user->name }}<br>
                        <small class="text-muted">{{ $order->user->email }}</small>
                    </td>
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
                    <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> عرض
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">لا توجد طلبات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</div>
@endsection

