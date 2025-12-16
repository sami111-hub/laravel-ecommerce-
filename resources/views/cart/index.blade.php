@extends('layout')

@section('title', 'سلة التسوق - Update Aden')
@section('description', 'مراجعة سلة التسوق وإتمام الطلب - Update Aden')

@section('content')
<h1 class="mb-4">السلة</h1>
@if($carts->count() > 0)
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                @foreach($carts as $cart)
                <div class="cart-row border-bottom">
                    <div style="flex:0 0 60px;">
                        @if($cart->product->image)
                            <img src="{{ $cart->product->image }}" class="cart-img" alt="{{ $cart->product->name }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width:50px;height:50px;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div style="flex:1;">
                        <div class="cart-item-name">{{ $cart->product->name }}</div>
                        <div class="cart-price">${{ number_format($cart->product->price, 2) }}</div>
                    </div>
                    <div style="flex:0 0 160px; text-align:right;">
                        <form action="{{ route('cart.update', $cart) }}" method="POST" class="d-inline-block me-2" id="form-qty-{{ $cart->id }}">
                            @csrf
                            @method('PUT')
                            <div class="qty-controls">
                                <button type="button" class="btn-decrease" data-target="#qty-{{ $cart->id }}">-</button>
                                <input type="number" id="qty-{{ $cart->id }}" name="quantity" value="{{ $cart->quantity }}" min="1" max="{{ $cart->product->stock }}" style="width:60px; text-align:center;" />
                                <button type="button" class="btn-increase" data-target="#qty-{{ $cart->id }}">+</button>
                            </div>
                        </form>
                        <form action="{{ route('cart.destroy', $cart) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذا المنتج؟')" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="order-summary">
            <h5>ملخص الطلب</h5>
            <hr>
            <div class="d-flex justify-content-between mb-3">
                <span><strong>المجموع:</strong></span>
                <span class="order-total">${{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="d-flex flex-column align-items-center gap-2">
                <a href="{{ route('orders.create') }}" class="btn btn-checkout">إتمام الطلب</a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-2">مواصلة التسوق</a>
            </div>
        </div>
    </div>
</div>
@else
<div class="text-center py-5">
    <i class="bi bi-cart-x" style="font-size: 5rem; color: #ddd;"></i>
    <h3 class="mt-3">السلة فارغة</h3>
    <p class="text-muted">لم تقم بإضافة أي منتجات بعد</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary">تصفح المنتجات</a>
</div>
@endif

@section('scripts')
<script>
document.addEventListener('click', function(e){
    if(e.target.matches('.btn-decrease')){
        const input = document.querySelector(e.target.getAttribute('data-target'));
        if(!input) return;
        let v = parseInt(input.value || 0, 10);
        if(v>1) input.value = v-1;
        // submit parent form
        input.closest('form')?.submit();
    }
    if(e.target.matches('.btn-increase')){
        const input = document.querySelector(e.target.getAttribute('data-target'));
        if(!input) return;
        let v = parseInt(input.value || 0, 10);
        input.value = v+1;
        input.closest('form')?.submit();
    }
});
</script>
@endsection

@endsection



