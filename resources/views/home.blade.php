@extends('layout')

@section('content')
{{-- Trust Badges Section --}}
<div class="trust-badges fade-in-on-scroll">
    <div class="trust-badge">
        <i class="bi bi-shield-check"></i>
        <div class="trust-badge-text">
            <span class="trust-badge-title">منتجات أصلية</span>
            <span class="trust-badge-subtitle">ضمان الجودة 100%</span>
        </div>
    </div>
    <div class="trust-badge">
        <i class="bi bi-truck"></i>
        <div class="trust-badge-text">
            <span class="trust-badge-title">التوصيل إلى جميع أنحاء عدن</span>
            <span class="trust-badge-subtitle">خلال 24-48 ساعة</span>
        </div>
    </div>
    <div class="trust-badge">
        <i class="bi bi-arrow-repeat"></i>
        <div class="trust-badge-text">
            <span class="trust-badge-title">إرجاع مجاني</span>
            <span class="trust-badge-subtitle">خلال 7 أيام</span>
        </div>
    </div>
    <div class="trust-badge">
        <i class="bi bi-headset"></i>
        <div class="trust-badge-text">
            <span class="trust-badge-title">دعم 24/7</span>
            <span class="trust-badge-subtitle">نحن هنا لمساعدتك</span>
        </div>
    </div>
</div>

{{-- Categories Section --}}
<div class="mt-3 page-transition">
    <div class="text-center mb-4">
        <h2 class="fw-bold gradient-text mb-2" style="font-size: 2rem;">
            <i class="bi bi-stars icon-bounce"></i> 
            تسوق حسب الأقسام
            <i class="bi bi-stars icon-bounce"></i>
        </h2>
        <p class="text-muted">اختر من بين مجموعة واسعة من المنتجات المميزة</p>
    </div>
    @includeWhen(isset($categories) && $categories->count(), 'components.category-chips', ['categories' => $categories])
    <div class="categories-grid fade-in-on-scroll">
        @foreach($categories as $category)
        <div class="category-card">
            <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                <div class="card text-center shadow-soft border-gradient">
                    <div class="card-body">
                        <i class="bi bi-{{ $category->icon ?? 'box' }}" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                        <h5 class="mt-3 mb-0 fw-bold">{{ $category->name }}</h5>
                        <small class="text-muted">استكشف المزيد</small>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

{{-- Latest Products Section --}}
@php
    try {
        $latestProducts = \App\Models\Product::latest()->take(8)->get();
    } catch (\Throwable $e) {
        $latestProducts = collect();
    }
@endphp

@if($latestProducts->count() > 0)
<div class="mt-5 fade-in-on-scroll">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-lightning-fill text-warning"></i> 
                أحدث المنتجات
            </h3>
            <p class="text-muted mb-0">تصفح أحدث إضافاتنا</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary rounded-pill">
            عرض الكل <i class="bi bi-arrow-left"></i>
        </a>
    </div>
    
    <div class="row g-3">
        @foreach($latestProducts as $product)
        <div class="col-lg-3 col-md-4 col-sm-6 col-6">
            @include('components.product-card-hybrid', ['product' => $product])
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Call to Action --}}
<div class="mt-5 mb-4 fade-in-on-scroll">
    <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, #2BB673 0%, #1ea05e 100%);">
        <div class="card-body text-center py-5 text-white">
            <i class="bi bi-gift-fill" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
            <h3 class="fw-bold mb-3">احصل على خصم 20% على أول طلب!</h3>
            <p class="mb-4" style="font-size: 1.1rem;">سجل الآن واستمتع بعروض حصرية</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg rounded-pill px-5">
                <i class="bi bi-person-plus"></i> سجل الآن
            </a>
        </div>
    </div>
</div>

{{-- Newsletter Section --}}
<div class="newsletter-premium text-center">
    <div class="newsletter-content">
        <i class="bi bi-envelope-heart-fill" style="font-size: 3rem; color: white; margin-bottom: 1rem; display: block;"></i>
        <h3 class="newsletter-title">اشترك في نشرتنا البريدية</h3>
        <p class="newsletter-subtitle">احصل على آخر العروض والمنتجات الجديدة مباشرة في بريدك</p>
        <form class="newsletter-form" onsubmit="handleNewsletterSubmit(event)">
            <input type="email" class="newsletter-input" placeholder="أدخل بريدك الإلكتروني" required>
            <button type="submit" class="newsletter-btn">
                <i class="bi bi-send-fill"></i> اشترك الآن
            </button>
        </form>
    </div>
</div>
@endsection