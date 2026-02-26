@extends('layout')

@section('title', 'تصفح المنتجات - Update Aden')
@section('description', 'تصفح جميع منتجاتنا من أحدث الأجهزة الإلكترونية والتقنية بأفضل الأسعار في عدن - توصيل سريع')

@section('content')
{{-- Title Section --}}
<div class="d-flex justify-content-between align-items-center mb-3 mt-2 page-transition">
    <h2 class="mb-0 fw-bold neon-text">
        <i class="bi bi-shop icon-bounce"></i> تصفح المنتجات
    </h2>
    @if($products->total() > 0)
        <span class="badge-glow badge bg-primary">{{ $products->total() }} منتج</span>
    @endif
</div>

{{-- Filters Card --}}
<div class="card mb-3 shadow-medium glass-card border-gradient">
    <div class="card-body p-2">
        <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold mb-1">البحث</label>
                <input type="text" name="search" class="form-control" placeholder="ابحث هنا..." value="{{ request('search') }}">
            </div>
            @if(!$categories->isEmpty())
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold mb-1">الفئة</label>
                <select name="category" class="form-select">
                    <option value="">كل الفئات</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif
            @if(!$brands->isEmpty())
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold mb-1">العلامة</label>
                <select name="brand" class="form-select">
                    <option value="">كل العلامات</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-3 col-sm-6">
                <button type="submit" class="btn btn-gradient btn-glow w-100"><i class="bi bi-search"></i> بحث</button>
            </div>
            @if(request()->hasAny(['search', 'category', 'brand']))
            <div class="col-12">
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i> مسح الفلاتر</a>
            </div>
            @endif
        </form>
    </div>
</div>

@includeWhen(isset($categories) && $categories->count(), 'components.category-chips', ['categories' => $categories])

{{-- Featured Products Section --}}
@php
    try { $featured = \App\Models\Product::with('images')->latest()->take(12)->get(); } catch (\Throwable $e) { $featured = collect(); }
@endphp
@if($featured->count())
<div class="mb-3 featured-section fade-in-on-scroll">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-star-fill text-warning icon-spin"></i> 
            <span class="gradient-primary text-white px-3 py-1 rounded-pill">عروض مميزة</span>
        </h4>
        <span class="badge-hot">🔥 HOT</span>
    </div>
    <div class="h-scroll">
        <div class="h-row">
            @foreach($featured as $product)
                @php $productImages = $product->all_images; @endphp
                <div class="product-card hover-scale" data-aos="fade-up">
                    <span class="badge-new position-absolute top-0 start-0 m-2" style="z-index:2;">جديد</span>
                    @if(count($productImages) > 1)
                        <div id="carousel-feat-{{ $product->id }}" class="carousel slide product-carousel" data-bs-ride="false">
                            <div class="carousel-inner">
                                @foreach($productImages as $idx => $img)
                                <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                    <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy">
                                </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-feat-{{ $product->id }}" data-bs-slide="prev">
                                <span class="carousel-nav-btn"><i class="bi bi-chevron-right"></i></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-feat-{{ $product->id }}" data-bs-slide="next">
                                <span class="carousel-nav-btn"><i class="bi bi-chevron-left"></i></span>
                            </button>
                            <div class="carousel-dots">
                                @foreach($productImages as $idx => $img)
                                <span class="carousel-dot {{ $idx === 0 ? 'active' : '' }}" data-bs-target="#carousel-feat-{{ $product->id }}" data-bs-slide-to="{{ $idx }}"></span>
                                @endforeach
                            </div>
                        </div>
                    @elseif($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                    <div class="product-info">
                        <h6 class="product-name">{{ Str::limit($product->name, 30) }}</h6>
                        <div class="price">
                            <x-multi-currency-price :price="$product->price" size="small" />
                        </div>
                        <a href="{{ route('products.show', $product) }}" class="btn-primary w-100 mt-2">عرض</a>
                        @auth
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-2">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-add-cart w-100"><i class="bi bi-cart-plus"></i> أضف للسلة</button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="btn-add-cart w-100 mt-2">تسجيل الدخول للإضافة</a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Categories Section --}}
@if(!$categories->isEmpty() && $categories->whereNull('parent_id')->count() > 0)
<div class="mb-3 fade-in-on-scroll">
    <h4 class="mb-2 fw-bold">
        <i class="bi bi-grid-3x3-gap-fill text-primary icon-bounce"></i> 
        <span class="gradient-purple text-white px-3 py-1 rounded-pill">التسوق حسب الفئات</span>
    </h4>
    <div class="row g-2">
        @foreach($categories->whereNull('parent_id')->take(6) as $category)
        <div class="col-lg-2 col-md-3 col-4 category-card">
            <a href="{{ route('products.category', $category) }}" class="text-decoration-none">
                <div class="card text-center h-100 shadow-soft border-gradient">
                    <div class="card-body py-3">
                        @if($category->image)
                            <div class="category-image-wrapper mb-2">
                                <img src="{{ $category->image }}" 
                                     alt="{{ $category->name }}" 
                                     class="category-image rounded-3"
                                     style="width: 100%; height: 80px; object-fit: cover;"
                                     loading="lazy">
                            </div>
                        @else
                            <i class="bi bi-{{ $category->icon ?? 'shop' }}" style="font-size: 2rem; color: var(--primary-color);"></i>
                        @endif
                        <p class="mb-0 mt-1 small fw-bold">{{ $category->name }}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Products Grid Title --}}
<div class="d-flex justify-content-between align-items-center mb-2 fade-in-on-scroll">
    <h4 class="mb-0 fw-bold">
        <i class="bi bi-bag-check-fill text-success"></i> 
        <span class="gradient-blue text-white px-3 py-1 rounded-pill">جميع المنتجات</span>
    </h4>
</div>

{{-- Products Grid --}}
<div class="products-grid">
    @forelse($products as $product)
    @php $productImages = $product->all_images; @endphp
    <div class="product-card hover-scale" style="animation-delay: {{ $loop->index * 0.05 }}s">
        {{-- Smart Badges --}}
        @if($product->discount_price && $product->discount_price < $product->price)
            @php $discountPercent = round((($product->price - $product->discount_price) / $product->price) * 100); @endphp
            <span class="product-badge badge-discount-pct" style="z-index:2;">-{{ $discountPercent }}%</span>
        @elseif($product->created_at && $product->created_at->diffInDays(now()) < 14)
            <span class="product-badge badge-new-arrival" style="z-index:2;">جديد</span>
        @endif
        @if($product->stock <= 5 && $product->stock > 0)
            <span class="product-badge badge-low-stock" style="z-index:2;">آخر {{ $product->stock }}</span>
        @endif

        @if(count($productImages) > 1)
            <div id="carousel-grid-{{ $product->id }}" class="carousel slide product-carousel" data-bs-ride="false">
                <div class="carousel-inner">
                    @foreach($productImages as $idx => $img)
                    <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                        <a href="{{ route('products.show', $product) }}">
                            <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy">
                        </a>
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-grid-{{ $product->id }}" data-bs-slide="prev">
                    <span class="carousel-nav-btn"><i class="bi bi-chevron-right"></i></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel-grid-{{ $product->id }}" data-bs-slide="next">
                    <span class="carousel-nav-btn"><i class="bi bi-chevron-left"></i></span>
                </button>
                <div class="carousel-dots">
                    @foreach($productImages as $idx => $img)
                    <span class="carousel-dot {{ $idx === 0 ? 'active' : '' }}" data-bs-target="#carousel-grid-{{ $product->id }}" data-bs-slide-to="{{ $idx }}"></span>
                    @endforeach
                </div>
            </div>
        @elseif($product->image)
            <a href="{{ route('products.show', $product) }}" class="product-image-link">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy">
            </a>
        @else
            <a href="{{ route('products.show', $product) }}" class="product-image-link">
                <div class="product-no-image">
                    <i class="bi bi-image"></i>
                </div>
            </a>
        @endif

        <div class="product-info">
            @if($product->brand)
                <span class="product-brand-tag">{{ $product->brand->name }}</span>
            @endif

            <a href="{{ route('products.show', $product) }}" class="product-name-link">
                <h5 class="product-name">{{ $product->name }}</h5>
            </a>

            <p class="product-desc">{{ Str::limit($product->description, 60) }}</p>

            <div class="product-price-section mb-1">
                @if($product->discount_price && $product->discount_price < $product->price)
                    <span class="product-price-old">{{ number_format($product->price, 0) }} $</span>
                    <x-multi-currency-price :price="$product->discount_price" size="small" />
                @else
                    <x-multi-currency-price :price="$product->price" size="small" />
                @endif
            </div>

            {{-- Stock --}}
            <div class="stock-indicator mb-2">
                @if($product->stock > 10)
                    <span class="stock-badge stock-available"><i class="bi bi-check-circle-fill"></i> متوفر</span>
                @elseif($product->stock > 0)
                    <span class="stock-badge stock-low"><i class="bi bi-exclamation-circle-fill"></i> كمية محدودة</span>
                @else
                    <span class="stock-badge stock-out"><i class="bi bi-x-circle-fill"></i> غير متوفر</span>
                @endif
            </div>

            <div class="product-actions mt-auto">
                <a href="{{ route('products.show', $product) }}" class="btn-view-details">
                    <i class="bi bi-eye"></i> التفاصيل
                </a>
                @auth
                @if($product->stock > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-add-cart">
                        <i class="bi bi-cart-plus"></i> أضف للسلة
                    </button>
                </form>
                @else
                <button class="btn-add-cart" disabled>
                    <i class="bi bi-cart-x"></i> غير متوفر
                </button>
                @endif
                @else
                <a href="{{ route('login') }}" class="btn-add-cart">
                    <i class="bi bi-box-arrow-in-right"></i> سجل دخول
                </a>
                @endauth
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
            <h4>لا توجد منتجات متاحة حالياً</h4>
            <p>جرب البحث بكلمات أخرى أو تصفح الفئات المختلفة</p>
        </div>
    </div>
    @endforelse
</div>

@if($products->hasPages())
<div class="pagination-wrapper mt-4 mb-3">
    {{ $products->appends(request()->query())->links() }}
</div>
@endif

{{-- Shared Styles --}}
<style>
.product-badge { position: absolute; top: 10px; right: 10px; z-index: 3; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; }
.badge-discount-pct { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }
.badge-new-arrival { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
.badge-low-stock { top: 10px; right: auto; left: 10px; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
.product-image-link { display: block; overflow: hidden; }
.product-image-link img { width: 100%; height: 200px; object-fit: cover; transition: transform 0.4s ease; }
.product-card:hover .product-image-link img { transform: scale(1.08); }
.product-no-image { width: 100%; height: 200px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); color: #94a3b8; font-size: 3rem; }
.product-brand-tag { display: inline-block; font-size: 11px; font-weight: 600; color: #5D4037; background: rgba(93,64,55,0.08); padding: 2px 10px; border-radius: 6px; margin-bottom: 6px; }
.product-name-link { text-decoration: none; color: inherit; }
.product-name-link:hover .product-name { color: #5D4037; }
.product-desc { font-size: 13px; color: var(--text-muted, #888); line-height: 1.5; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.product-price-section { display: flex; align-items: center; gap: 8px; }
.product-price-old { text-decoration: line-through; color: #94a3b8; font-size: 13px; }
.stock-indicator { display: flex; }
.stock-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 6px; }
.stock-available { color: #059669; background: rgba(16,185,129,0.08); }
.stock-low { color: #d97706; background: rgba(245,158,11,0.08); }
.stock-out { color: #dc2626; background: rgba(239,68,68,0.08); }
.product-actions { display: flex; flex-direction: column; gap: 8px; }
.btn-view-details { display: flex; align-items: center; justify-content: center; gap: 6px; padding: 10px; border-radius: 10px; font-size: 13px; font-weight: 600; color: #5D4037; background: rgba(93,64,55,0.06); border: 1.5px solid rgba(93,64,55,0.2); text-decoration: none; transition: all 0.25s; }
.btn-view-details:hover { background: #5D4037; color: #fff; border-color: #5D4037; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(93,64,55,0.3); }
.empty-state { text-align: center; padding: 60px 20px; background: var(--surface, #fff); border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
.empty-state-icon { font-size: 4rem; color: #cbd5e1; margin-bottom: 16px; }
.empty-state h4 { font-weight: 700; margin-bottom: 8px; }
.empty-state p { color: var(--text-muted, #888); font-size: 15px; }
.pagination-wrapper { display: flex; justify-content: center; }
</style>
@endsection
