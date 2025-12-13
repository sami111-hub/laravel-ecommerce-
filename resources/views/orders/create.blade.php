@extends('layout')

@section('content')
<h1 class="mb-4">إتمام الطلب</h1>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <h5>عناصر الطلب</h5>
                <hr>
                @foreach($carts as $cart)
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ $cart->product->name }} (× {{ $cart->quantity }})</span>
                    <span>${{ number_format($cart->product->price * $cart->quantity, 2) }}</span>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>المجموع:</strong>
                    <strong class="text-success">${{ number_format($total, 2) }}</strong>
                </div>
            </div>
        </div>

        <form action="{{ route('orders.store') }}" method="POST" class="card">
            <div class="card-body">
                <h5>معلومات التوصيل</h5>
                @csrf
                <div class="mb-3">
                    <label for="shipping_address" class="form-label">عنوان التوصيل</label>
                    <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">رقم الهاتف</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات (اختياري)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="bi bi-check-circle"></i> تأكيد الطلب
                </button>
                <a href="{{ route('cart.index') }}" class="btn btn-secondary w-100 mt-2">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection



