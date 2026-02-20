<div class="product-card">
    <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=400&h=400&fit=crop&q=80' }}" alt="{{ $product->name }}" class="product-image" loading="lazy">
    <div class="product-info">
        <h5 class="product-name">{{ $product->name }}</h5>
        <div class="product-price">
            <x-multi-currency-price :price="$product->price" size="small" />
        </div>
        <a href="{{ route('products.show', $product) }}" class="btn-primary w-100">عرض التفاصيل</a>
    </div>
</div>