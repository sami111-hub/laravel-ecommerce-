@extends('layout')

@section('title', 'Update Aden - أفضل متجر إلكترونيات في عدن')
@section('description', 'تسوق أحدث الأجهزة الإلكترونية والتقنية من Update Aden - هواتف ذكية، لابتوبات، ساعات، إكسسوارات بأفضل الأسعار - توصيل إلى جميع أنحاء عدن')

@section('content')

{{-- Hero Slider المحسّن - Full Width --}}
{{-- Fixed: Hero slider outside container for full-width effect --}}
@include('components.hero-slider-enhanced')

{{-- Main Content Container --}}
<div class="container">

{{-- قسم وصل حديثاً --}}
{{-- Fixed: Removed inline margin-top to use CSS spacing --}}
<div style="margin-top: 30px; margin-bottom: 20px;">
    <div class="section-header-deal">
        <div class="deal-title-group">
            <i class="bi bi-star-fill" style="color: #fbbf24;"></i>
            <h2 class="section-title-deal">وصل حديثاً</h2>
            <span class="deal-subtitle">أحدث المنتجات</span>
        </div>
        <a href="{{ route('products.index', ['filter' => 'new']) }}" class="view-all-link">
            عرض الكل
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>

    <div class="products-horizontal-scroll">
        <button class="scroll-btn scroll-left" onclick="scrollProducts('left', 'newArrivals')">
            <i class="bi bi-chevron-right"></i>
        </button>

        <div class="products-scroll-container" id="newArrivals">
            @php
                try {
                    $newProducts = \App\Models\Product::with(['category', 'brand'])
                        ->latest()
                        ->take(10)
                        ->get()
                        ->map(function($product) {
                            $product->is_new = true;
                            $product->delivery_days = 2;
                            $product->stock = rand(10, 100);
                            return $product;
                        });
                } catch (\Throwable $e) {
                    $newProducts = collect();
                }
            @endphp

            @forelse($newProducts as $product)
                @include('components.product-card-hybrid', ['product' => $product])
            @empty
                <div class="no-products">
                    <i class="bi bi-inbox"></i>
                    <p>لا توجد منتجات جديدة</p>
                </div>
            @endforelse
        </div>

        <button class="scroll-btn scroll-right" onclick="scrollProducts('right', 'newArrivals')">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>
</div>

{{-- قسم تسوق حسب الفئة --}}
{{-- Fixed: Reduced top margin for consistent spacing --}}
<div style="margin-top: 30px; margin-bottom: 30px;">
    <div class="section-header-deal">
        <div class="deal-title-group">
            <i class="bi bi-grid-3x3-gap-fill" style="color: #667eea;"></i>
            <h2 class="section-title-deal">تسوق حسب الفئة</h2>
            <span class="deal-subtitle">جميع الأقسام</span>
        </div>
    </div>

    <div class="row g-4">
        @php
            $mainCategories = [
                ['name' => 'الهواتف الذكية', 'icon' => 'phone', 'color' => '#667eea', 'slug' => 'smartphones'],
                ['name' => 'اللابتوبات', 'icon' => 'laptop', 'color' => '#764ba2', 'slug' => 'laptops'],
                ['name' => 'الساعات الذكية', 'icon' => 'smartwatch', 'color' => '#f093fb', 'slug' => 'watches'],
                ['name' => 'الطابعات', 'icon' => 'printer', 'color' => '#4facfe', 'slug' => 'printers'],
                ['name' => 'السماعات', 'icon' => 'headphones', 'color' => '#43e97b', 'slug' => 'headphones'],
                ['name' => 'الشواحن', 'icon' => 'battery-charging', 'color' => '#fa709a', 'slug' => 'chargers'],
                ['name' => 'الإكسسوارات', 'icon' => 'box-seam', 'color' => '#fee140', 'slug' => 'accessories'],
                ['name' => 'الكاميرات', 'icon' => 'camera', 'color' => '#30cfd0', 'slug' => 'cameras'],
            ];
        @endphp

        @foreach($mainCategories as $cat)
            <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                <a href="{{ route('products.index', ['category' => $cat['slug']]) }}" class="text-decoration-none">
                    <div class="tech-category-card" data-color="{{ $cat['color'] }}">
                        <div class="tech-category-icon" style="background: linear-gradient(135deg, {{ $cat['color'] }} 0%, {{ $cat['color'] }}cc 100%);">
                            <i class="bi bi-{{ $cat['icon'] }}"></i>
                        </div>
                        <h6 class="tech-category-name">{{ $cat['name'] }}</h6>
                        <span class="tech-category-count">
                            {{ \App\Models\Product::whereHas('categories', function($q) use ($cat) {
                                $q->where('slug', $cat['slug']);
                            })->count() }} منتج
                        </span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<style>
.tech-category-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
    border: 2px solid transparent;
}

.tech-category-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    border-color: var(--primary-color);
}

.tech-category-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.tech-category-card:hover .tech-category-icon {
    transform: scale(1.15) rotate(10deg);
}

.tech-category-icon i {
    font-size: 32px;
    color: white;
}

.tech-category-name {
    font-size: 16px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 8px;
}

.tech-category-count {
    font-size: 13px;
    color: #7f8c8d;
    display: block;
}

@media (max-width: 576px) {
    .tech-category-icon {
        width: 55px;
        height: 55px;
    }
    .tech-category-icon i {
        font-size: 26px;
    }
    .tech-category-name {
        font-size: 14px;
    }
}
</style>

{{-- قسم الأكثر مبيعاً --}}
{{-- Fixed: Removed container class --}}
<div style="margin-top: 20px; margin-bottom: 20px;">
    <div class="section-header-deal">
        <div class="deal-title-group">
            <i class="bi bi-trophy-fill" style="color: #fbbf24;"></i>
            <h2 class="section-title-deal">الأكثر مبيعاً</h2>
            <span class="deal-subtitle">منتجات رائجة</span>
        </div>
        <a href="{{ route('products.index', ['sort' => 'best-selling']) }}" class="view-all-link">
            عرض الكل
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>

    <div class="row g-4">
        @php
            try {
                $bestSellers = \App\Models\Product::with(['category', 'brand'])
                    ->inRandomOrder()
                    ->take(8)
                    ->get()
                    ->map(function($product) {
                        $product->delivery_days = 3;
                        $product->stock = rand(5, 50);
                        return $product;
                    });
            } catch (\Throwable $e) {
                $bestSellers = collect();
            }
        @endphp

        @forelse($bestSellers as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                @include('components.product-card-hybrid', ['product' => $product])
            </div>
        @empty
            <div class="col-12">
                <div class="no-products">
                    <i class="bi bi-inbox"></i>
                    <p>لا توجد منتجات</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- قسم عروض التكنولوجيا --}}
{{-- Fixed: Removed container class --}}
<div style="margin-top: 20px; margin-bottom: 20px;">
    <div class="section-header-deal">
        <div class="deal-title-group">
            <i class="bi bi-lightning-charge-fill" style="color: #667eea;"></i>
            <h2 class="section-title-deal">عروض التكنولوجيا</h2>
            <span class="deal-subtitle" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">خصومات حصرية</span>
        </div>
        <a href="{{ route('products.index') }}" class="view-all-link">
            عرض الكل
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>

    <div class="products-horizontal-scroll">
        <button class="scroll-btn scroll-left" onclick="scrollProducts('left', 'techDeals')">
            <i class="bi bi-chevron-right"></i>
        </button>

        <div class="products-scroll-container" id="techDeals">
            @php
                try {
                    $techDeals = \App\Models\Product::with(['category', 'brand'])
                        ->inRandomOrder()
                        ->take(8)
                        ->get()
                        ->map(function($product) {
                            $product->discount = rand(15, 35);
                            $product->old_price = $product->price * (1 + $product->discount / 100);
                            $product->delivery_days = 2;
                            $product->stock = rand(3, 30);
                            return $product;
                        });
                } catch (\Throwable $e) {
                    $techDeals = collect();
                }
            @endphp

            @forelse($techDeals as $product)
                @include('components.product-card-hybrid', ['product' => $product])
            @empty
                <div class="no-products">
                    <i class="bi bi-inbox"></i>
                    <p>لا توجد منتجات</p>
                </div>
            @endforelse
        </div>

        <button class="scroll-btn scroll-right" onclick="scrollProducts('right', 'techDeals')">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>
</div>

{{-- قسم المزايا --}}
{{-- Fixed: Consistent spacing --}}
<div style="margin-top: 30px; margin-bottom: 30px;">
    <div class="row g-4">
        <div class="col-md-3 col-sm-6">
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #FF6B00 0%, #ff8533 100%);">
                    <i class="bi bi-truck"></i>
                </div>
                <h5 class="feature-title">شحن سريع</h5>
                <p class="feature-desc">توصيل خلال 2-4 أيام لجميع المدن</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <h5 class="feature-title">ضمان أصلي</h5>
                <p class="feature-desc">جميع المنتجات بضمان الوكيل</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="bi bi-credit-card"></i>
                </div>
                <h5 class="feature-title">دفع آمن</h5>
                <p class="feature-desc">الدفع عند الاستلام متاح</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="bi bi-headset"></i>
                </div>
                <h5 class="feature-title">دعم فني</h5>
                <p class="feature-desc">فريقنا جاهز لمساعدتك دائماً</p>
            </div>
        </div>
    </div>
</div>

<style>
.feature-card {
    background: white;
    border-radius: 16px;
    padding: 30px 20px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.feature-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.feature-card:hover .feature-icon {
    transform: scale(1.1) rotate(5deg);
}

.feature-icon i {
    font-size: 36px;
    color: white;
}

.feature-title {
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 8px;
}

.feature-desc {
    font-size: 14px;
    color: #7f8c8d;
    margin: 0;
}
</style>

</div> {{-- Close Main Content Container --}}

@endsection
