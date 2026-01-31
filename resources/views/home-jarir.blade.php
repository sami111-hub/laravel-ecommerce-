@extends('layout')

@section('title', 'Update Aden - متجرك الإلكتروني الأول')
@section('description', 'تسوق أحدث الأجهزة الإلكترونية من Update Aden - هواتف، لابتوبات، ساعات ذكية بأفضل الأسعار')

@section('content')
<div class="jarir-home">
    {{-- Hero Section with Sidebar --}}
    <div class="container-fluid px-3 px-lg-5 mt-3">
        <div class="row g-3">
            {{-- Categories Sidebar --}}
            <div class="col-lg-3 d-none d-lg-block">
                <div class="categories-sidebar">
                    <div class="sidebar-header">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                        <span>تسوق حسب الفئة</span>
                    </div>
                    <ul class="categories-list">
                        @php
                            $sidebarCategories = [
                                ['name' => 'الهواتف الذكية', 'icon' => 'phone', 'slug' => 'smartphones', 'color' => '#e74c3c'],
                                ['name' => 'اللابتوبات', 'icon' => 'laptop', 'slug' => 'laptops', 'color' => '#3498db'],
                                ['name' => 'الأجهزة اللوحية', 'icon' => 'tablet', 'slug' => 'tablets', 'color' => '#9b59b6'],
                                ['name' => 'الساعات الذكية', 'icon' => 'smartwatch', 'slug' => 'watches', 'color' => '#e67e22'],
                                ['name' => 'السماعات', 'icon' => 'headphones', 'slug' => 'headphones', 'color' => '#1abc9c'],
                                ['name' => 'الطابعات', 'icon' => 'printer', 'slug' => 'printers', 'color' => '#34495e'],
                                ['name' => 'الكاميرات', 'icon' => 'camera', 'slug' => 'cameras', 'color' => '#f39c12'],
                                ['name' => 'الشواحن والكابلات', 'icon' => 'battery-charging', 'slug' => 'chargers', 'color' => '#27ae60'],
                                ['name' => 'الإكسسوارات', 'icon' => 'box-seam', 'slug' => 'accessories', 'color' => '#8e44ad'],
                                ['name' => 'ألعاب الفيديو', 'icon' => 'controller', 'slug' => 'gaming', 'color' => '#c0392b'],
                            ];
                        @endphp
                        @foreach($sidebarCategories as $cat)
                            <li>
                                <a href="{{ route('products.index', ['category' => $cat['slug']]) }}">
                                    <i class="bi bi-{{ $cat['icon'] }}" style="color: {{ $cat['color'] }}"></i>
                                    <span>{{ $cat['name'] }}</span>
                                    <i class="bi bi-chevron-left arrow"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('categories.index') }}" class="view-all-cats">
                        عرض جميع الفئات
                        <i class="bi bi-arrow-left"></i>
                    </a>
                </div>
            </div>

            {{-- Main Hero Slider --}}
            <div class="col-lg-9 col-12">
                <div class="hero-slider-jarir">
                    <div id="jarirCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#jarirCarousel" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#jarirCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#jarirCarousel" data-bs-slide-to="2"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="slide-content" style="background: linear-gradient(135deg, #1a5f2a 0%, #2d8a3e 100%);">
                                    <div class="slide-text">
                                        <span class="slide-badge">عروض حصرية</span>
                                        <h2>أحدث الهواتف الذكية</h2>
                                        <p>خصومات تصل إلى 30% على جميع الهواتف</p>
                                        <a href="{{ route('products.index', ['category' => 'smartphones']) }}" class="slide-btn">تسوق الآن</a>
                                    </div>
                                    <div class="slide-image">
                                        <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=350&fit=crop&q=80" alt="هواتف ذكية">
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="slide-content" style="background: linear-gradient(135deg, #1e3a5f 0%, #2980b9 100%);">
                                    <div class="slide-text">
                                        <span class="slide-badge">جديد</span>
                                        <h2>لابتوبات بأداء خارق</h2>
                                        <p>أحدث المعالجات وأفضل الأسعار</p>
                                        <a href="{{ route('products.index', ['category' => 'laptops']) }}" class="slide-btn">اكتشف المزيد</a>
                                    </div>
                                    <div class="slide-image">
                                        <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=350&fit=crop&q=80" alt="لابتوبات">
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="slide-content" style="background: linear-gradient(135deg, #6c3483 0%, #9b59b6 100%);">
                                    <div class="slide-text">
                                        <span class="slide-badge">الأكثر مبيعاً</span>
                                        <h2>ساعات ذكية متطورة</h2>
                                        <p>تتبع صحتك ونشاطك اليومي</p>
                                        <a href="{{ route('products.index', ['category' => 'watches']) }}" class="slide-btn">تسوق الآن</a>
                                    </div>
                                    <div class="slide-image">
                                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=350&fit=crop&q=80" alt="ساعات ذكية">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#jarirCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#jarirCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Quick Categories Bar (Mobile) --}}
    <div class="container-fluid px-3 d-lg-none mt-3">
        <div class="quick-categories-scroll">
            @foreach($sidebarCategories as $cat)
                <a href="{{ route('products.index', ['category' => $cat['slug']]) }}" class="quick-cat-item">
                    <div class="quick-cat-icon" style="background: {{ $cat['color'] }}15; color: {{ $cat['color'] }}">
                        <i class="bi bi-{{ $cat['icon'] }}"></i>
                    </div>
                    <span>{{ $cat['name'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Features Bar --}}
    <div class="container-fluid px-3 px-lg-5 mt-4">
        <div class="features-bar-jarir">
            <div class="feature-item-jarir">
                <i class="bi bi-truck"></i>
                <div>
                    <strong>توصيل سريع</strong>
                    <span>لجميع المناطق</span>
                </div>
            </div>
            <div class="feature-item-jarir">
                <i class="bi bi-shield-check"></i>
                <div>
                    <strong>ضمان أصلي</strong>
                    <span>على جميع المنتجات</span>
                </div>
            </div>
            <div class="feature-item-jarir">
                <i class="bi bi-credit-card"></i>
                <div>
                    <strong>دفع آمن</strong>
                    <span>عند الاستلام</span>
                </div>
            </div>
            <div class="feature-item-jarir">
                <i class="bi bi-arrow-repeat"></i>
                <div>
                    <strong>استرجاع سهل</strong>
                    <span>خلال 7 أيام</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Deals Section --}}
    <div class="container-fluid px-3 px-lg-5 mt-4">
        <div class="section-jarir">
            <div class="section-header-jarir">
                <div class="section-title-jarir">
                    <i class="bi bi-lightning-charge-fill text-warning"></i>
                    <h2>عروض اليوم</h2>
                    <div class="countdown-timer">
                        <span class="timer-label">ينتهي خلال:</span>
                        <div class="timer-boxes">
                            <div class="timer-box"><span id="hours">12</span><small>ساعة</small></div>
                            <div class="timer-box"><span id="minutes">45</span><small>دقيقة</small></div>
                            <div class="timer-box"><span id="seconds">30</span><small>ثانية</small></div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('offers') }}" class="view-all-jarir">
                    عرض الكل <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            
            <div class="products-grid-jarir">
                @php
                    $flashDeals = \App\Models\Product::with(['category', 'brand'])
                        ->inRandomOrder()
                        ->take(6)
                        ->get()
                        ->map(function($product) {
                            $product->discount = rand(15, 40);
                            $product->old_price = round($product->price * (1 + $product->discount / 100));
                            return $product;
                        });
                @endphp
                
                @foreach($flashDeals as $product)
                    <div class="product-card-jarir">
                        <div class="product-badges">
                            @if($product->discount)
                                <span class="badge-discount">-{{ $product->discount }}%</span>
                            @endif
                        </div>
                        <a href="{{ route('products.show', $product) }}" class="product-image-jarir">
                            <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=200&fit=crop&q=80' }}" alt="{{ $product->name }}">
                        </a>
                        <div class="product-info-jarir">
                            <a href="{{ route('products.show', $product) }}" class="product-name-jarir">
                                {{ Str::limit($product->name, 50) }}
                            </a>
                            <div class="product-rating-jarir">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill {{ $i <= 4 ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span>({{ rand(10, 200) }})</span>
                            </div>
                            <div class="product-price-jarir">
                                <span class="current-price">${{ number_format($product->price, 2) }}</span>
                                @if($product->old_price)
                                    <span class="old-price">${{ number_format($product->old_price, 2) }}</span>
                                @endif
                            </div>
                            <button class="add-to-cart-jarir" onclick="addToCart({{ $product->id }})">
                                <i class="bi bi-cart-plus"></i>
                                أضف للسلة
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    {{-- Shop by Category Section --}}
    <div class="container-fluid px-3 px-lg-5 mt-4">
        <div class="section-jarir">
            <div class="section-header-jarir">
                <div class="section-title-jarir">
                    <i class="bi bi-grid-3x3-gap-fill text-primary"></i>
                    <h2>تسوق حسب الفئة</h2>
                </div>
                <a href="{{ route('categories.index') }}" class="view-all-jarir">
                    جميع الفئات <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            
            <div class="categories-grid-jarir">
                @php
                    $dbCategories = \App\Models\Category::whereNull('parent_id')
                        ->where('is_active', true)
                        ->withCount('products')
                        ->orderBy('order')
                        ->take(6)
                        ->get();
                    
                    $categoryBgs = [
                        'smartphones' => ['bg' => '#fff5f5', 'color' => '#e74c3c'],
                        'laptops' => ['bg' => '#f0f7ff', 'color' => '#3498db'],
                        'watches' => ['bg' => '#fff8f0', 'color' => '#e67e22'],
                        'headphones' => ['bg' => '#f0fff4', 'color' => '#27ae60'],
                        'tablets' => ['bg' => '#f5f0ff', 'color' => '#9b59b6'],
                        'accessories' => ['bg' => '#f0f0f0', 'color' => '#34495e'],
                        'printers' => ['bg' => '#fff0f5', 'color' => '#8e44ad'],
                        'mobiles' => ['bg' => '#fffaf0', 'color' => '#d35400'],
                    ];
                @endphp
                
                @foreach($dbCategories as $cat)
                    @php
                        $colors = $categoryBgs[$cat->slug] ?? ['bg' => '#f8f9fa', 'color' => '#6c757d'];
                    @endphp
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="category-card-jarir" style="background: {{ $colors['bg'] }}">
                        <div class="cat-content">
                            <h4 style="color: {{ $colors['color'] }}">{{ $cat->name }}</h4>
                            <span class="cat-count">{{ $cat->products_count }} منتج</span>
                            <span class="cat-link" style="color: {{ $colors['color'] }}">تسوق الآن <i class="bi bi-arrow-left"></i></span>
                        </div>
                        @if($cat->image)
                            <img src="{{ $cat->image }}" alt="{{ $cat->name }}" loading="lazy">
                        @else
                            <div class="cat-icon-placeholder" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-{{ $cat->icon ?? 'box' }}" style="font-size: 3rem; color: {{ $colors['color'] }};"></i>
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Best Sellers Section --}}
    <div class="container-fluid px-3 px-lg-5 mt-4">
        <div class="section-jarir">
            <div class="section-header-jarir">
                <div class="section-title-jarir">
                    <i class="bi bi-trophy-fill text-warning"></i>
                    <h2>الأكثر مبيعاً</h2>
                </div>
                <a href="{{ route('products.index', ['sort' => 'best-selling']) }}" class="view-all-jarir">
                    عرض الكل <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            
            <div class="products-grid-jarir">
                @php
                    $bestSellers = \App\Models\Product::with(['category', 'brand'])
                        ->inRandomOrder()
                        ->take(6)
                        ->get();
                @endphp
                
                @foreach($bestSellers as $product)
                    <div class="product-card-jarir">
                        <div class="product-badges">
                            <span class="badge-bestseller"><i class="bi bi-fire"></i> الأكثر مبيعاً</span>
                        </div>
                        <a href="{{ route('products.show', $product) }}" class="product-image-jarir">
                            <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=200&h=200&fit=crop&q=80' }}" alt="{{ $product->name }}">
                        </a>
                        <div class="product-info-jarir">
                            <a href="{{ route('products.show', $product) }}" class="product-name-jarir">
                                {{ Str::limit($product->name, 50) }}
                            </a>
                            <div class="product-rating-jarir">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill {{ $i <= rand(4,5) ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span>({{ rand(50, 300) }})</span>
                            </div>
                            <div class="product-price-jarir">
                                <span class="current-price">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <button class="add-to-cart-jarir" onclick="addToCart({{ $product->id }})">
                                <i class="bi bi-cart-plus"></i>
                                أضف للسلة
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- New Arrivals Section --}}
    <div class="container-fluid px-3 px-lg-5 mt-4">
        <div class="section-jarir">
            <div class="section-header-jarir">
                <div class="section-title-jarir">
                    <i class="bi bi-stars text-info"></i>
                    <h2>وصل حديثاً</h2>
                </div>
                <a href="{{ route('products.index', ['filter' => 'new']) }}" class="view-all-jarir">
                    عرض الكل <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            
            <div class="products-grid-jarir">
                @php
                    $newArrivals = \App\Models\Product::with(['category', 'brand'])
                        ->latest()
                        ->take(6)
                        ->get();
                @endphp
                
                @foreach($newArrivals as $product)
                    <div class="product-card-jarir">
                        <div class="product-badges">
                            <span class="badge-new">جديد</span>
                        </div>
                        <a href="{{ route('products.show', $product) }}" class="product-image-jarir">
                            <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=200&h=200&fit=crop&q=80' }}" alt="{{ $product->name }}">
                        </a>
                        <div class="product-info-jarir">
                            <a href="{{ route('products.show', $product) }}" class="product-name-jarir">
                                {{ Str::limit($product->name, 50) }}
                            </a>
                            <div class="product-rating-jarir">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill {{ $i <= rand(3,5) ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span>({{ rand(5, 50) }})</span>
                            </div>
                            <div class="product-price-jarir">
                                <span class="current-price">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <button class="add-to-cart-jarir" onclick="addToCart({{ $product->id }})">
                                <i class="bi bi-cart-plus"></i>
                                أضف للسلة
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Promotional Banners --}}
    <div class="container-fluid px-3 px-lg-5 mt-4 mb-4">
        <div class="promo-banners-jarir">
            <a href="{{ route('products.index', ['category' => 'smartphones']) }}" class="promo-banner" style="background: linear-gradient(135deg, #1a5f2a 0%, #2d8a3e 100%);">
                <div class="promo-content">
                    <span class="promo-label">عرض خاص</span>
                    <h3>هواتف آيفون</h3>
                    <p>خصم يصل إلى 25%</p>
                </div>
                <img src="https://images.unsplash.com/photo-1592286968296-4bb59a7e79a0?w=200&h=150&fit=crop&q=80" alt="iPhone">
            </a>
            <a href="{{ route('products.index', ['category' => 'laptops']) }}" class="promo-banner" style="background: linear-gradient(135deg, #1e3a5f 0%, #2980b9 100%);">
                <div class="promo-content">
                    <span class="promo-label">للطلاب</span>
                    <h3>لابتوبات للدراسة</h3>
                    <p>أسعار مخفضة</p>
                </div>
                <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=200&h=150&fit=crop&q=80" alt="Laptop">
            </a>
            <a href="{{ route('products.index', ['category' => 'accessories']) }}" class="promo-banner" style="background: linear-gradient(135deg, #6c3483 0%, #9b59b6 100%);">
                <div class="promo-content">
                    <span class="promo-label">تشكيلة واسعة</span>
                    <h3>إكسسوارات</h3>
                    <p>جودة عالية</p>
                </div>
                <img src="https://images.unsplash.com/photo-1625772452859-1c03d5bf1137?w=200&h=150&fit=crop&q=80" alt="Accessories">
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Countdown Timer
function updateCountdown() {
    const now = new Date();
    const endOfDay = new Date();
    endOfDay.setHours(23, 59, 59, 999);
    
    const diff = endOfDay - now;
    
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
    document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
    document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
    document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
}

setInterval(updateCountdown, 1000);
updateCountdown();

// Add to Cart Function
function addToCart(productId) {
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showSuccess('تمت الإضافة للسلة بنجاح');
        } else {
            showError(data.message || 'حدث خطأ');
        }
    })
    .catch(() => {
        window.location.href = '/login';
    });
}
</script>
@endsection
