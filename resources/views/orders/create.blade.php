@extends('layout')

@section('title', 'إتمام الطلب - Update Aden')
@section('description', 'إتمام طلبك من Update Aden')

@section('content')
<div class="container py-4">
    <h1 class="mb-4"><i class="bi bi-cart-check"></i> إتمام الطلب</h1>
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-box-seam"></i> عناصر الطلب</h5>
                </div>
                <div class="card-body">
                    @foreach($carts as $cart)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            @if($cart->product->image)
                                <img src="{{ $cart->product->image }}" alt="{{ $cart->product->name }}" 
                                     class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            @endif
                            <div>
                                <strong>{{ $cart->product->name }}</strong>
                                <div class="text-muted small">الكمية: {{ $cart->quantity }} × 
                                    <x-multi-currency-price :price="$cart->product->price" size="small" :showAll="false" />
                                </div>
                            </div>
                        </div>
                        <strong class="text-primary">
                            <x-multi-currency-price :price="$cart->product->price * $cart->quantity" size="small" />
                        </strong>
                    </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>المجموع الفرعي:</strong>
                        <strong><x-multi-currency-price :price="$subtotal" size="small" /></strong>
                    </div>
                </div>
            </div>

            <form action="{{ route('orders.store') }}" method="POST" class="card shadow-sm" id="orderForm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-truck"></i> معلومات التوصيل</h5>
                </div>
                <div class="card-body">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label"><i class="bi bi-geo-alt"></i> عنوان التوصيل</label>
                        <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                  id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label"><i class="bi bi-telephone"></i> رقم الهاتف</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label"><i class="bi bi-sticky"></i> ملاحظات (اختياري)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-lg w-100" id="submitBtn">
                        <i class="bi bi-check-circle"></i> تأكيد الطلب
                    </button>
                    <a href="{{ route('cart.index') }}" class="btn btn-secondary w-100 mt-2">
                        <i class="bi bi-arrow-right"></i> العودة للسلة
                    </a>
                </div>
            </form>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> ملخص الطلب</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>المجموع الفرعي:</span>
                            <span><x-multi-currency-price :price="$subtotal" size="small" /></span>
                        </div>
                        
                        <div id="couponSection" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="coupon_code" 
                                       name="coupon_code" placeholder="كود الخصم">
                                <button type="button" class="btn btn-outline-primary" id="applyCouponBtn">
                                    <i class="bi bi-ticket-perforated"></i> تطبيق
                                </button>
                            </div>
                            <div id="couponMessage" class="mt-2 small"></div>
                        </div>
                        
                        <div id="discountRow" class="d-none mb-2">
                            <div class="d-flex justify-content-between text-success">
                                <span>الخصم:</span>
                                <span id="discountAmount">$0.00</span>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>المجموع الكلي:</strong>
                            <strong class="text-success fs-5" id="totalAmount"><x-multi-currency-price :price="$subtotal" size="small" /></strong>
                        </div>
                    </div>
                    
                    <div class="alert alert-info small mb-0">
                        <i class="bi bi-info-circle"></i> 
                        <strong>ملاحظة:</strong> سيتم التواصل معك لتأكيد الطلب خلال 24 ساعة
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const couponInput = document.getElementById('coupon_code');
    const applyBtn = document.getElementById('applyCouponBtn');
    const couponMessage = document.getElementById('couponMessage');
    const discountRow = document.getElementById('discountRow');
    const discountAmount = document.getElementById('discountAmount');
    const totalAmount = document.getElementById('totalAmount');
    const orderForm = document.getElementById('orderForm');
    const submitBtn = document.getElementById('submitBtn');
    
    let appliedCoupon = null;
    const subtotal = {{ $subtotal }};
    
    applyBtn.addEventListener('click', function() {
        const code = couponInput.value.trim();
        if (!code) {
            showCouponMessage('يرجى إدخال كود الخصم', 'danger');
            return;
        }
        
        fetch('{{ route("coupons.validate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ code: code, amount: subtotal })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                appliedCoupon = code;
                const discount = data.discount;
                const total = subtotal - discount;
                
                discountRow.classList.remove('d-none');
                discountAmount.textContent = '-$' + discount.toFixed(2);
                totalAmount.textContent = '$' + total.toFixed(2);
                
                showCouponMessage('تم تطبيق الكوبون بنجاح!', 'success');
                
                // إضافة حقل مخفي للكوبون في النموذج
                let couponField = orderForm.querySelector('input[name="coupon_code"]');
                if (!couponField) {
                    couponField = document.createElement('input');
                    couponField.type = 'hidden';
                    couponField.name = 'coupon_code';
                    orderForm.appendChild(couponField);
                }
                couponField.value = code;
            } else {
                showCouponMessage(data.message || 'كود الخصم غير صحيح', 'danger');
                discountRow.classList.add('d-none');
                totalAmount.textContent = '$' + subtotal.toFixed(2);
            }
        })
        .catch(error => {
            showCouponMessage('حدث خطأ. يرجى المحاولة مرة أخرى', 'danger');
        });
    });
    
    function showCouponMessage(message, type) {
        couponMessage.textContent = message;
        couponMessage.className = 'mt-2 small text-' + type;
    }
    
    // منع إرسال النموذج بالضغط على Enter في حقل الكوبون
    couponInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyBtn.click();
        }
    });
});
</script>
@endsection
@endsection



