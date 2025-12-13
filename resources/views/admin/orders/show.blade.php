@extends('admin.layout')

@section('title', 'تفاصيل الطلب')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>تفاصيل الطلب #{{ $order->id }}</h5>
            </div>
            <div class="card-body">
                <h6>عناصر الطلب</h6>
                <table class="table">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>المجموع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->product_price, 2) }}</td>
                            <td>${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">المجموع النهائي:</th>
                            <th>${{ number_format($order->total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5>معلومات الطلب</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغى</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">عنوان التوصيل</label>
                        <textarea name="shipping_address" id="shipping_address" class="form-control" rows="3">{{ old('shipping_address', $order->shipping_address) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $order->phone) }}">
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">حفظ التغييرات</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <p><strong>المستخدم:</strong> {{ $order->user->name }}</p>
                <p><strong>البريد:</strong> {{ $order->user->email }}</p>
                <p><strong>التاريخ:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                <hr>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">رجوع</a>
            </div>
        </div>
    </div>
</div>
@endsection

