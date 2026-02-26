@extends('layout')

@section('title', $category->name . ' - Update Aden')
@section('description', $category->description ?? 'تصفح منتجات ' . $category->name . ' بأفضل الأسعار في عدن')

@section('content')
{{-- Category Hero Banner --}}
<div class="category-hero mb-4">
    <div class="category-hero-content">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb breadcrumb-hero mb-0">
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}"><i class="bi bi-house-door"></i> الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">المنتجات</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>
        <h1 class="category-hero-title">
            @if($category->icon)
                <i class="bi bi-{{ $category->icon }}"></i>
            @endif
            {{ $category->name }}
        </h1>
        @if($category->description)
            <p class="category-hero-desc">{{ $category->description }}</p>
        @endif
        <div class="category-hero-stats">
            <span class="hero-stat"><i class="bi bi-box-seam"></i> {{ $products->total() }} منتج</span>
            @if($brands->count())
                <span class="hero-stat"><i class="bi bi-bookmark-star"></i> {{ $brands->count() }} علامة تجارية</span>
            @endif
        </div>
    </div>
</div>

{{-- Category Navigation Chips --}}
@includeWhen(isset($categories) && $categories->count(), 'components.category-chips', ['categories' => $categories])

{{-- Filters Bar --}}
<div class="filters-bar mb-3">
    <form method="GET" action="" class="filters-form">
        <div class="filter-group">
            <label class="filter-label"><i class="bi bi-sort-down"></i> الترتيب</label>
            <select name="sort" class="filter-select" onchange="this.form.submit()">
                <option value="">الافتراضي</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>السعر: من الأقل</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>السعر: من الأعلى</option>
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
            </select>
        </div>
        @if(!$brands->isEmpty())
        <div class="filter-group">
            <label class="filter-label"><i class="bi bi-bookmark"></i> العلامة</label>
            <select name="brand" class="filter-select" onchange="this.form.submit()">
                <option value="">كل العلامات</option>
                @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
                @endforeach
            </select>
        </div>
        @endif
        @if(request()->hasAny(['sort', 'brand']))
        <a href="{{ request()->url() }}" class="filter-clear-btn"><i class="bi bi-x-circle"></i> مسح</a>
        @endif
    </form>
</div>

{{-- Products Grid --}}
<div class="products-grid">
    @forelse($products as $product)
    @php $productImages = $product->all_images; @endphp
    <div class="product-card hover-scale" style="animation-delay: {{ $loop->index * 0.05 }}s">
        {{-- Badge --}}
        @if($product->discount_price && $product->discount_price < $product->price)
            @php $discountPercent = round((($product->price - $product->discount_price) / $product->price) * 100); @endphp
            <span class="product-badge badge-discount-pct">-{{ $discountPercent }}%</span>
        @elseif($product->created_at && $product->created_at->diffInDays(now()) < 14)
            <span class="product-badge badge-new-arrival">جديد</span>
        @endif

        {{-- Stock Badge --}}
        @if($product->stock <= 0)
            <span class="product-badge badge-out-of-stock">نفذ</span>
        @elseif($product->stock <= 5)
            <span class="product-badge badge-low-stock">آخر {{ $product->stock }}</span>
        @endif

        {{-- Image / Carousel --}}
        @if(count($productImages) > 1)
            <div id="carousel-cat-{{ $product->id }}" class="carousel slide product-carousel" data-bs-ride="false">
                <div class="carousel-inner">
                    @foreach($productImages as $idx => $img)
                    <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                        <a href="{{ route('products.show', $product) }}">
                            <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy">
                        </a>
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-cat-{{ $product->id }}" data-bs-slide="prev">
                    <span class="carousel-nav-btn"><i class="bi bi-chevron-right"></i></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel-cat-{{ $product->id }}" data-bs-slide="next">
                    <span class="carousel-nav-btn"><i class="bi bi-chevron-left"></i></span>
                </button>
                <div class="carousel-dots">
                    @foreach($productImages as $idx => $img)
                    <span class="carousel-dot {{ $idx === 0 ? 'active' : '' }}" data-bs-target="#carousel-cat-{{ $product->id }}" data-bs-slide-to="{{ $idx }}"></span>
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

        {{-- Product Info --}}
        <div class="product-info">
            @if($product->brand)
                <span class="product-brand-tag">{{ $product->brand->name }}</span>
            @endif

            <a href="{{ route('products.show', $product) }}" class="product-name-link">
                <h5 class="product-name">{{ $product->name }}</h5>
            </a>

            <p class="product-desc">{{ Str::limit($product->description, 65) }}</p>

            {{-- Price --}}
            <div class="product-price-section">
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

            {{-- Actions --}}
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
            <div class="empty-state-icon">
                <i class="bi bi-inbox"></i>
            </div>
            <h4>لا توجد منتجات في هذه الفئة</h4>
            <p>جرب تصفح فئات أخرى أو قم بزيارتنا لاحقاً</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
                <i class="bi bi-arrow-right"></i> تصفح جميع المنتجات
            </a>
        </div>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($products->hasPages())
<div class="pagination-wrapper mt-4 mb-3">
    {{ $products->appends(request()->query())->links() }}
</div>
@endif

{{-- Subcategories --}}
@if($category->children && $category->children->count())
<div class="subcategories-section mt-4 mb-3">
    <h4 class="section-title"><i class="bi bi-grid-3x3-gap"></i> أقسام فرعية</h4>
    <div class="subcategories-grid">
        @foreach($category->children->where('is_active', true) as $sub)
        <a href="{{ route('products.category', $sub) }}" class="subcategory-card">
            @if($sub->image)
                <img src="{{ $sub->image }}" alt="{{ $sub->name }}" class="subcategory-img">
            @else
                <div class="subcategory-icon"><i class="bi bi-{{ $sub->icon ?? 'folder' }}"></i></div>
            @endif
            <span class="subcategory-name">{{ $sub->name }}</span>
        </a>
        @endforeach
    </div>
</div>
@endif

<style>
/* === Category Hero === */
.category-hero {
    position: relative;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    border-radius: 16px;
    padding: 40px 30px;
    overflow: hidden;
}
.category-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(99,102,241,0.15), transparent 70%);
    border-radius: 50%;
}
.category-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(168,85,247,0.1), transparent 70%);
    border-radius: 50%;
}
.category-hero-content { position: relative; z-index: 2; }
.breadcrumb-hero { font-size: 13px; }
.breadcrumb-hero .breadcrumb-item a { color: rgba(255,255,255,0.7); text-decoration: none; transition: color 0.2s; }
.breadcrumb-hero .breadcrumb-item a:hover { color: #fff; }
.breadcrumb-hero .breadcrumb-item.active { color: rgba(255,255,255,0.5); }
.breadcrumb-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.4); }
.category-hero-title { font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 8px; }
.category-hero-title i { margin-left: 10px; font-size: 1.8rem; }
.category-hero-desc { color: rgba(255,255,255,0.75); font-size: 15px; margin-bottom: 12px; max-width: 600px; }
.category-hero-stats { display: flex; gap: 20px; flex-wrap: wrap; }
.hero-stat {
    display: inline-flex; align-items: center; gap: 6px;
    color: rgba(255,255,255,0.85); background: rgba(255,255,255,0.1);
    padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;
    backdrop-filter: blur(10px);
}

/* === Filters Bar === */
.filters-bar {
    background: var(--surface, #fff); border-radius: 14px;
    padding: 14px 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.06);
}
.filters-form { display: flex; gap: 16px; align-items: center; flex-wrap: wrap; }
.filter-group { display: flex; align-items: center; gap: 8px; }
.filter-label { font-size: 13px; font-weight: 600; color: var(--text-muted, #666); white-space: nowrap; }
.filter-select {
    padding: 8px 14px; border: 1.5px solid rgba(0,0,0,0.1); border-radius: 10px;
    font-size: 13px; font-weight: 500; background: var(--surface, #fff);
    color: var(--text-dark, #333); transition: all 0.2s; min-width: 150px; cursor: pointer;
}
.filter-select:focus { border-color: #5D4037; box-shadow: 0 0 0 3px rgba(93,64,55,0.1); outline: none; }
.filter-clear-btn {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 8px 14px; border-radius: 10px; font-size: 13px; font-weight: 600;
    color: #ef4444; background: rgba(239,68,68,0.08); text-decoration: none; transition: all 0.2s;
}
.filter-clear-btn:hover { background: rgba(239,68,68,0.15); color: #dc2626; }

/* === Product Badges === */
.product-badge {
    position: absolute; top: 10px; right: 10px; z-index: 3;
    padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;
}
.badge-discount-pct { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }
.badge-new-arrival { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
.badge-out-of-stock { top: auto; bottom: 10px; right: 10px; background: rgba(0,0,0,0.7); color: #fff; }
.badge-low-stock { top: 10px; right: auto; left: 10px; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }

/* === Product Card === */
.product-image-link { display: block; overflow: hidden; }
.product-image-link img { width: 100%; height: 200px; object-fit: cover; transition: transform 0.4s ease; }
.product-card:hover .product-image-link img { transform: scale(1.08); }
.product-no-image {
    width: 100%; height: 200px; display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0); color: #94a3b8; font-size: 3rem;
}

/* === Product Info === */
.product-brand-tag {
    display: inline-block; font-size: 11px; font-weight: 600;
    color: #5D4037; background: rgba(93,64,55,0.08);
    padding: 2px 10px; border-radius: 6px; margin-bottom: 6px;
}
.product-name-link { text-decoration: none; color: inherit; }
.product-name-link:hover .product-name { color: #5D4037; }
.product-desc {
    font-size: 13px; color: var(--text-muted, #888); line-height: 1.5; margin-bottom: 8px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.product-price-section { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
.product-price-old { text-decoration: line-through; color: #94a3b8; font-size: 13px; }

/* === Stock === */
.stock-indicator { display: flex; }
.stock-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 6px;
}
.stock-available { color: #059669; background: rgba(16,185,129,0.08); }
.stock-low { color: #d97706; background: rgba(245,158,11,0.08); }
.stock-out { color: #dc2626; background: rgba(239,68,68,0.08); }

/* === Actions === */
.product-actions { display: flex; flex-direction: column; gap: 8px; }
.btn-view-details {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 10px; border-radius: 10px; font-size: 13px; font-weight: 600;
    color: #5D4037; background: rgba(93,64,55,0.06); border: 1.5px solid rgba(93,64,55,0.2);
    text-decoration: none; transition: all 0.25s;
}
.btn-view-details:hover {
    background: #5D4037; color: #fff; border-color: #5D4037;
    transform: translateY(-2px); box-shadow: 0 4px 15px rgba(93,64,55,0.3);
}

/* === Empty State === */
.empty-state {
    text-align: center; padding: 60px 20px;
    background: var(--surface, #fff); border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.empty-state-icon { font-size: 4rem; color: #cbd5e1; margin-bottom: 16px; }
.empty-state h4 { color: var(--text-dark, #333); font-weight: 700; margin-bottom: 8px; }
.empty-state p { color: var(--text-muted, #888); font-size: 15px; }

/* === Pagination === */
.pagination-wrapper { display: flex; justify-content: center; }

/* === Subcategories === */
.section-title { font-weight: 700; font-size: 1.2rem; margin-bottom: 16px; color: var(--text-dark, #333); }
.subcategories-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 14px; }
.subcategory-card {
    display: flex; flex-direction: column; align-items: center; text-decoration: none;
    background: var(--surface, #fff); border-radius: 14px; padding: 18px 12px;
    border: 1.5px solid transparent; transition: all 0.25s; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.subcategory-card:hover {
    transform: translateY(-4px); border-color: rgba(93,64,55,0.3);
    box-shadow: 0 8px 24px rgba(93,64,55,0.12);
}
.subcategory-img { width: 60px; height: 60px; border-radius: 12px; object-fit: cover; margin-bottom: 8px; }
.subcategory-icon {
    width: 60px; height: 60px; border-radius: 12px;
    background: linear-gradient(135deg, #efebe9, #d7ccc8);
    display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #5D4037; margin-bottom: 8px;
}
.subcategory-name { font-size: 13px; font-weight: 600; color: var(--text-dark, #333); text-align: center; }

/* === Responsive === */
@media (max-width: 768px) {
    .category-hero { padding: 24px 18px; border-radius: 0 0 16px 16px; }
    .category-hero-title { font-size: 1.4rem; }
    .category-hero-desc { font-size: 13px; }
    .filters-form { flex-direction: column; gap: 10px; }
    .filter-group { width: 100%; }
    .filter-select { flex: 1; width: 100%; }
}
</style>
@endsection



