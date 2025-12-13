@extends('layout')

@section('content')
<h1 class="mb-4">{{ $category->name }}</h1>
@if($category->description)
<p class="text-muted mb-4">{{ $category->description }}</p>
@endif

{{-- Filters --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">الترتيب</label>
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="">الافتراضي</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                        </select>
                    </div>
                    @if(!$brands->isEmpty())
                    <div class="col-md-4">
                        <label class="form-label">العلامة التجارية</label>
                        <select name="brand" class="form-select" onchange="this.form.submit()">
                            <option value="">كل العلامات</option>
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <br>
                        <a href="{{ request()->url() }}" class="btn btn-secondary w-100">إعادة</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Products Grid --}}
<div class="row">
    @forelse($products as $product)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            @if($product->image)
                <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;" loading="lazy">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                </div>
            @endif
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                <p class="text-primary fw-bold">${{ number_format($product->price, 2) }}</p>
                <p class="text-muted mb-2">
                    <strong>المخزون:</strong> {{ $product->stock }}
                </p>
                @if($product->brand)
                    <p class="text-muted">
                        <strong>العلامة:</strong> {{ $product->brand->name }}
                    </p>
                @endif
                <div class="mt-auto">
                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100">عرض التفاصيل</a>
                    @auth
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-2">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-success w-100"><i class="bi bi-cart-plus"></i> أضف للسلة</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 mt-2">تسجيل الدخول للإضافة</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <h4 class="mt-3">لا توجد منتجات في هذه الفئة</h4>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection



