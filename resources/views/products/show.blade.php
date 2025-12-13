@extends('layout')

@section('title', $product->name . ' - Update Aden')
@section('description', Str::limit($product->description, 150) . ' - اشتري الآن بسعر ' . number_format($product->price, 2) . ' $ مع التوصيل السريع')

@section('content')
<div class="row">
    <div class="col-md-6 mb-3">
        @if($product->image)
            <img src="{{ $product->image }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
        @else
            <div class="bg-light d-flex align-items-center justify-content-center rounded shadow" style="height: 400px;">
                <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <h1>{{ $product->name }}</h1>
        <p class="text-muted lead">{{ $product->description }}</p>
        <h3 class="text-primary mb-3">${{ number_format($product->price, 2) }}</h3>
        
        <div class="mb-3">
            <strong>المخزون:</strong> {{ $product->stock }}
        </div>

        @auth
        <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-3">
            @csrf
            <div class="mb-3">
                <label for="quantity" class="form-label">الكمية</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" required>
            </div>
            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="bi bi-cart-plus"></i> أضف إلى السلة
            </button>
        </form>
        @else
        <div class="alert alert-info">
            <a href="{{ route('login') }}" class="btn btn-primary">تسجيل الدخول لإضافة المنتج للسلة</a>
        </div>
        @endauth

        {{-- Reviews Section --}}
        @if($product->reviews->where('is_approved', true)->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h5>التقييمات ({{ $product->reviews->where('is_approved', true)->count() }})</h5>
            </div>
            <div class="card-body">
                @foreach($product->reviews->where('is_approved', true) as $review)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <strong>{{ $review->user->name }}</strong>
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-muted"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    @if($review->comment)
                        <p class="mb-0">{{ $review->comment }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> رجوع إلى المنتجات
        </a>
    </div>
</div>
@endsection

