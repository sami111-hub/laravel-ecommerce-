<div class="product-card">
    <img src="{{ $product->image ?? '/images/default-product.png' }}" alt="{{ $product->name }}" class="product-image" loading="lazy">
    <div class="product-info">
        <h5 class="product-name">{{ $product->name }}</h5>
        <p class="product-price">${{ number_format($product->price, 2) }}</p>
        <a href="{{ route('products.show', $product) }}" class="btn-primary w-100">عرض التفاصيل</a>
    </div>
</div>