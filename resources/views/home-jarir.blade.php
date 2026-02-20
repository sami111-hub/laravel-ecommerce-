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
                                ['name' => 'ألعاب الفيديو', 'icon' => 'controller', 'slug' => 'video-games', 'color' => '#c0392b'],
                                ['name' => 'الهواتف الذكية', 'icon' => 'phone', 'slug' => 'smartphones', 'color' => '#e74c3c'],
                                ['name' => 'الكمبيوتر والتابليت', 'icon' => 'laptop', 'slug' => 'computers-tablets', 'color' => '#3498db'],
                                ['name' => 'الساعات الذكية والأجهزة القابلة للإرتداء', 'icon' => 'smartwatch', 'slug' => 'smartwatches-wearables', 'color' => '#e67e22'],
                                ['name' => 'ملحقات الهواتف الذكية', 'icon' => 'phone-flip', 'slug' => 'phone-accessories', 'color' => '#9b59b6'],
                                ['name' => 'سماعات ومكبرات الصوت', 'icon' => 'headphones', 'slug' => 'headphones-speakers', 'color' => '#1abc9c'],
                                ['name' => 'خزائن الطاقة والشواحن', 'icon' => 'battery-charging', 'slug' => 'power-banks-chargers', 'color' => '#27ae60'],
                                ['name' => 'عروض مميزة', 'icon' => 'tag', 'slug' => 'special-offers', 'color' => '#f39c12'],
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
                            @php
                                $slideDefaults = [
                                    1 => [
                                        'badge' => 'عروض حصرية',
                                        'title' => 'أحدث الهواتف الذكية',
                                        'description' => 'خصومات تصل إلى 30% على جميع الهواتف',
                                        'button_text' => 'تسوق الآن',
                                        'button_link' => route('products.index', ['category' => 'smartphones']),
                                        'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=350&fit=crop&q=80',
                                        'bg_gradient' => 'linear-gradient(135deg, #1a265f 0%, #2d398a 100%)',
                                    ],
                                    2 => [
                                        'badge' => 'جديد',
                                        'title' => 'كمبيوتر وتابليت بأداء خارق',
                                        'description' => 'أحدث المعالجات وأفضل الأسعار',
                                        'button_text' => 'اكتشف المزيد',
                                        'button_link' => route('products.index', ['category' => 'computers-tablets']),
                                        'image_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=350&fit=crop&q=80',
                                        'bg_gradient' => 'linear-gradient(135deg, #1e3a5f 0%, #2980b9 100%)',
                                    ],
                                    3 => [
                                        'badge' => 'الأكثر مبيعاً',
                                        'title' => 'ساعات ذكية متطورة',
                                        'description' => 'تتبع صحتك ونشاطك اليومي',
                                        'button_text' => 'تسوق الآن',
                                        'button_link' => route('products.index', ['category' => 'smartwatches-wearables']),
                                        'image_url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=350&fit=crop&q=80',
                                        'bg_gradient' => 'linear-gradient(135deg, #6c3483 0%, #9b59b6 100%)',
                                    ],
                                ];
                            @endphp
                            @foreach([1, 2, 3] as $i)
                            @php
                                $badge = \App\Models\SiteSetting::get("slide{$i}_badge", $slideDefaults[$i]['badge']);
                                $title = \App\Models\SiteSetting::get("slide{$i}_title", $slideDefaults[$i]['title']);
                                $description = \App\Models\SiteSetting::get("slide{$i}_description", $slideDefaults[$i]['description']);
                                $buttonText = \App\Models\SiteSetting::get("slide{$i}_button_text", $slideDefaults[$i]['button_text']);
                                $buttonLink = \App\Models\SiteSetting::get("slide{$i}_button_link", $slideDefaults[$i]['button_link']);
                                $imageUrl = \App\Models\SiteSetting::get("slide{$i}_image_url", $slideDefaults[$i]['image_url']);
                                $bgGradient = \App\Models\SiteSetting::get("slide{$i}_bg_gradient", $slideDefaults[$i]['bg_gradient']);
                            @endphp
                            <div class="carousel-item {{ $i === 1 ? 'active' : '' }}">
                                <div class="slide-content" style="background: {{ $bgGradient }};">
                                    <div class="slide-text">
                                        <span class="slide-badge">{{ $badge }}</span>
                                        <h2>{{ $title }}</h2>
                                        <p>{{ $description }}</p>
                                        <a href="{{ $buttonLink }}" class="slide-btn">{{ $buttonText }}</a>
                                    </div>
                                    <div class="slide-image">
                                        <img src="{{ $imageUrl }}" alt="{{ $title }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
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
    @php
        $flashDealsActive = \App\Models\SiteSetting::get('flash_deals_active', '1');
        $flashDealsTitle = \App\Models\SiteSetting::get('flash_deals_title', 'عروض اليوم');
        $flashDealsEndDate = \App\Models\SiteSetting::get('flash_deals_end_date', now()->endOfDay()->format('Y-m-d'));
        $flashDealsEndTime = \App\Models\SiteSetting::get('flash_deals_end_time', '23:59');
        $flashDealsMax = (int) \App\Models\SiteSetting::get('flash_deals_max_products', '6');
        $flashDealsEndTimestamp = strtotime($flashDealsEndDate . ' ' . $flashDealsEndTime . ':00');

        $flashDeals = $flashDealsActive == '1'
            ? \App\Models\Product::with(['category', 'brand'])
                ->activeFlashDeals()
                ->take($flashDealsMax)
                ->get()
            : collect();
    @endphp

    @if($flashDealsActive == '1' && $flashDeals->isNotEmpty())
    <div class="container-fluid px-3 px-lg-5 mt-4">
        <div class="section-jarir">
            <div class="section-header-jarir">
                <div class="section-title-jarir">
                    <i class="bi bi-lightning-charge-fill text-warning"></i>
                    <h2>{{ $flashDealsTitle }}</h2>
                    <div class="countdown-timer">
                        <span class="timer-label">ينتهي خلال:</span>
                        <div class="timer-boxes">
                            <div class="timer-box"><span id="hours">00</span><small>ساعة</small></div>
                            <div class="timer-box"><span id="minutes">00</span><small>دقيقة</small></div>
                            <div class="timer-box"><span id="seconds">00</span><small>ثانية</small></div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('offers') }}" class="view-all-jarir">
                    عرض الكل <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            
            <div class="products-grid-jarir">
                @foreach($flashDeals as $product)
                    @php
                        $originalPrice = $product->price;
                        $discount = $product->flash_deal_discount ?? 0;
                        $dealPrice = $product->flash_deal_price ?? round($originalPrice * (1 - $discount / 100), 2);
                    @endphp
                    <div class="product-card-jarir">
                        <div class="product-badges">
                            @if($discount > 0)
                                <span class="badge-discount">-{{ $discount }}%</span>
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
                                @php $avgRating = $product->average_rating ?: 4; @endphp
                                @for($s = 1; $s <= 5; $s++)
                                    <i class="bi bi-star-fill {{ $s <= $avgRating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span>({{ $product->reviews_count ?? $product->reviews()->count() }})</span>
                            </div>
                            <div class="product-price-jarir">
                                <x-multi-currency-price :price="$dealPrice" size="small" />
                                <span class="old-price">${{ number_format($originalPrice, 2) }}</span>
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
    @endif


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
                        'video-games' => ['bg' => '#fef5f5', 'color' => '#c0392b'],
                        'smartphones' => ['bg' => '#fff5f5', 'color' => '#e74c3c'],
                        'computers-tablets' => ['bg' => '#f0f7ff', 'color' => '#3498db'],
                        'smartwatches-wearables' => ['bg' => '#fff8f0', 'color' => '#e67e22'],
                        'phone-accessories' => ['bg' => '#f5f0ff', 'color' => '#9b59b6'],
                        'headphones-speakers' => ['bg' => '#f0fff4', 'color' => '#1abc9c'],
                        'power-banks-chargers' => ['bg' => '#f0fff4', 'color' => '#27ae60'],
                        'special-offers' => ['bg' => '#fffcf0', 'color' => '#f39c12'],
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
                                <x-multi-currency-price :price="$product->price" size="small" />
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
                                <x-multi-currency-price :price="$product->price" size="small" />
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
                    <h3>الهواتف الذكية</h3>
                    <p>خصم يصل إلى 25%</p>
                </div>
                <img src="https://images.unsplash.com/photo-1592286968296-4bb59a7e79a0?w=200&h=150&fit=crop&q=80" alt="الهواتف الذكية">
            </a>
            <a href="{{ route('products.index', ['category' => 'computers-tablets']) }}" class="promo-banner" style="background: linear-gradient(135deg, #1e3a5f 0%, #2980b9 100%);">
                <div class="promo-content">
                    <span class="promo-label">للطلاب</span>
                    <h3>الكمبيوتر والتابليت</h3>
                    <p>أسعار مخفضة</p>
                </div>
                <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=200&h=150&fit=crop&q=80" alt="الكمبيوتر والتابليت">
            </a>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Countdown Timer - يقرأ من إعدادات عروض اليوم
function updateCountdown() {
    const endTimestamp = {{ $flashDealsEndTimestamp ?? 'Math.floor(new Date().setHours(23,59,59,999) / 1000)' }};
    const now = Math.floor(Date.now() / 1000);
    let diff = endTimestamp - now;
    if (diff < 0) diff = 0;

    const hours = Math.floor(diff / 3600);
    const minutes = Math.floor((diff % 3600) / 60);
    const seconds = diff % 60;

    const h = document.getElementById('hours');
    const m = document.getElementById('minutes');
    const s = document.getElementById('seconds');
    if (h) h.textContent = hours.toString().padStart(2, '0');
    if (m) m.textContent = minutes.toString().padStart(2, '0');
    if (s) s.textContent = seconds.toString().padStart(2, '0');
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
