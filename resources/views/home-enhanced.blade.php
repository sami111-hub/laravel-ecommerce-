@extends('layout')

@section('content')

{{-- Hero Slider الخرافي --}}
@include('components.hero-slider-enhanced')

{{-- قسم تسوق حسب الفئة --}}
@php
    $categoryCounts = [
        'electronics' => \App\Models\Product::whereHas('category', function($q) { 
            $q->where('name', 'LIKE', '%إلكترونيات%')->orWhere('name', 'LIKE', '%electronics%'); 
        })->count(),
        'supermarket' => \App\Models\Product::whereHas('category', function($q) { 
            $q->where('name', 'LIKE', '%سوبر ماركت%')->orWhere('name', 'LIKE', '%supermarket%'); 
        })->count(),
        'home' => \App\Models\Product::whereHas('category', function($q) { 
            $q->where('name', 'LIKE', '%منزل%')->orWhere('name', 'LIKE', '%home%')->orWhere('name', 'LIKE', '%مطبخ%'); 
        })->count(),
        'beauty' => \App\Models\Product::whereHas('category', function($q) { 
            $q->where('name', 'LIKE', '%جمال%')->orWhere('name', 'LIKE', '%beauty%')->orWhere('name', 'LIKE', '%صحة%'); 
        })->count(),
        'fashion' => \App\Models\Product::whereHas('category', function($q) { 
            $q->where('name', 'LIKE', '%أزياء%')->orWhere('name', 'LIKE', '%fashion%')->orWhere('name', 'LIKE', '%ملابس%'); 
        })->count(),
        'sports' => \App\Models\Product::whereHas('category', function($q) { 
            $q->where('name', 'LIKE', '%رياضة%')->orWhere('name', 'LIKE', '%sports%'); 
        })->count(),
        'toys' => \App\Models\Product::whereHas('category', function($q) { 
            $q->where('name', 'LIKE', '%ألعاب%')->orWhere('name', 'LIKE', '%toys%'); 
        })->count(),
        'books' => \App\Models\Product::whereHas('category', function($q) { 
            $q->where('name', 'LIKE', '%كتب%')->orWhere('name', 'LIKE', '%books%'); 
        })->count(),
    ];
@endphp
@include('components.shop-by-category', ['categoryCounts' => $categoryCounts])

{{-- قسم المنتجات المميزة --}}
<div class="container my-5">
    <div class="section-header text-center mb-4">
        <h2 class="section-title">
            <i class="bi bi-star-fill text-warning"></i>
            المنتجات المميزة
        </h2>
        <p class="section-subtitle">اكتشف أفضل العروض والمنتجات الأكثر مبيعاً</p>
    </div>

    @php
        try {
            $featuredProducts = \App\Models\Product::with(['category', 'brand'])
                ->inRandomOrder()
                ->take(8)
                ->get()
                ->map(function($product) {
                    // Add discount and other fields for display
                    $product->discount = rand(10, 40);
                    $product->old_price = $product->price * (1 + $product->discount / 100);
                    $product->is_new = rand(0, 1);
                    $product->rating = rand(3, 5);
                    $product->reviews_count = rand(5, 150);
                    $product->stock = rand(0, 50);
                    return $product;
                });
        } catch (\Throwable $e) {
            $featuredProducts = collect();
        }
    @endphp

    @if($featuredProducts->count())
    <div class="products-grid">
        @foreach($featuredProducts as $product)
            @include('components.product-card-enhanced', ['product' => $product])
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-box-seam" style="font-size: 64px; color: #d1d5db;"></i>
        <h4 class="mt-3 text-muted">لا توجد منتجات حالياً</h4>
        <p class="text-muted">سنضيف منتجات جديدة قريباً</p>
    </div>
    @endif
    
    <div class="text-center mt-4">
        <a href="{{ route('products.index') }}" class="hero-btn hero-btn-primary" style="display: inline-flex;">
            <span>عرض جميع المنتجات</span>
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>
</div>

{{-- قسم العروض الحصرية --}}
<div class="container my-5">
    <div class="row align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 24px; padding: 60px 40px; color: white;">
        <div class="col-md-8">
            <h2 style="font-size: 42px; font-weight: 900; margin-bottom: 20px;">
                عروض حصرية لفترة محدودة!
            </h2>
            <p style="font-size: 20px; margin-bottom: 30px; opacity: 0.95;">
                اشترك في نشرتنا البريدية واحصل على خصم <strong>15%</strong> على أول طلب
            </p>
            <div class="d-flex gap-2" style="max-width: 500px;">
                <input type="email" class="form-control" placeholder="أدخل بريدك الإلكتروني" style="padding: 14px 20px; border-radius: 50px; border: none; font-size: 15px;">
                <button class="btn" style="background: white; color: #667eea; padding: 14px 32px; border-radius: 50px; font-weight: 700; white-space: nowrap;">
                    اشترك الآن
                </button>
            </div>
        </div>
        <div class="col-md-4 text-center d-none d-md-block">
            <i class="bi bi-gift-fill" style="font-size: 120px; opacity: 0.3;"></i>
        </div>
    </div>
</div>

{{-- قسم المزايا --}}
<div class="container my-5">
    <div class="row g-4">
        <div class="col-md-3 col-6">
            <div class="text-center p-4" style="background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                <div style="width: 70px; height: 70px; margin: 0 auto 15px; background: linear-gradient(135deg, #2BB673 0%, #249e60 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-truck" style="font-size: 32px; color: white;"></i>
                </div>
                <h5 style="font-weight: 700; margin-bottom: 8px;">شحن مجاني</h5>
                <p style="font-size: 14px; color: #6b7280; margin: 0;">للطلبات فوق $50</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="text-center p-4" style="background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                <div style="width: 70px; height: 70px; margin: 0 auto 15px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-shield-check" style="font-size: 32px; color: white;"></i>
                </div>
                <h5 style="font-weight: 700; margin-bottom: 8px;">ضمان الجودة</h5>
                <p style="font-size: 14px; color: #6b7280; margin: 0;">منتجات أصلية 100%</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="text-center p-4" style="background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                <div style="width: 70px; height: 70px; margin: 0 auto 15px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-arrow-repeat" style="font-size: 32px; color: white;"></i>
                </div>
                <h5 style="font-weight: 700; margin-bottom: 8px;">إرجاع سهل</h5>
                <p style="font-size: 14px; color: #6b7280; margin: 0;">خلال 14 يوم</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="text-center p-4" style="background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                <div style="width: 70px; height: 70px; margin: 0 auto 15px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-headset" style="font-size: 32px; color: white;"></i>
                </div>
                <h5 style="font-weight: 700; margin-bottom: 8px;">دعم 24/7</h5>
                <p style="font-size: 14px; color: #6b7280; margin: 0;">نحن هنا لخدمتك</p>
            </div>
        </div>
    </div>
</div>

@endsection
