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
    try { $featured = \App\Models\Product::latest()->take(12)->get(); } catch (\Throwable $e) { $featured = collect(); }
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
                <div class="product-card hover-scale" data-aos="fade-up">
                    <span class="badge-new position-absolute top-0 start-0 m-2">جديد</span>
                    @if($product->image)
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
    <div class="product-card hover-scale">
        @if($loop->index % 3 == 0)
            <span class="badge-sale position-absolute top-0 start-0 m-2">خصم</span>
        @elseif($loop->index % 5 == 0)
            <span class="badge-hot position-absolute top-0 start-0 m-2">الأكثر مبيعاً</span>
        @endif
        @if($product->image)
            <img src="{{ $product->image }}" alt="{{ $product->name }}">
        @else
            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
            </div>
        @endif
        <div class="product-info">
            <h5 class="product-name">{{ $product->name }}</h5>
            <p class="text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>
            <div class="price-tag mb-3">
                <x-multi-currency-price :price="$product->price" size="small" />
            </div>
            <div class="mt-auto">
                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                    <i class="bi bi-eye"></i> عرض التفاصيل
                </a>
                @auth
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-add-cart">
                        <i class="bi bi-cart-plus"></i> أضف للسلة
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-box-arrow-in-right"></i> تسجيل الدخول
                </a>
                @endauth
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="alert alert-info text-center py-5 mb-5">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <h4 class="mt-3">لا توجد منتجات متاحة حالياً</h4>
            <p class="text-muted">جرب البحث بكلمات أخرى أو تصفح الفئات المختلفة</p>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection
