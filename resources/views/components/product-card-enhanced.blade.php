{{-- بطاقة منتج محسّنة مع خصومات واضحة --}}
<div class="product-card-enhanced {{ $featured ?? false ? 'featured' : '' }}">
    {{-- شارة الخصم --}}
    @if(isset($product->discount) && $product->discount > 0)
    <div class="discount-badge">
        <span class="discount-percentage">-{{ $product->discount }}%</span>
    </div>
    @endif

    {{-- شارة جديد --}}
    @if(isset($product->is_new) && $product->is_new)
    <div class="new-badge">
        <i class="bi bi-star-fill"></i> جديد
    </div>
    @endif

    {{-- صورة المنتج --}}
    <div class="product-image-wrapper">
        @if($product->image)
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-image" loading="lazy">
        @else
            <div class="product-image-placeholder">
                <i class="bi bi-image"></i>
            </div>
        @endif
        
        {{-- أزرار سريعة عند التمرير --}}
        <div class="quick-actions">
            <button class="quick-action-btn" onclick="quickView({{ $product->id }})" title="نظرة سريعة">
                <i class="bi bi-eye-fill"></i>
            </button>
            <button class="quick-action-btn" onclick="addToWishlist({{ $product->id }})" title="إضافة للمفضلة">
                <i class="bi bi-heart-fill"></i>
            </button>
        </div>
    </div>

    {{-- معلومات المنتج --}}
    <div class="product-info-wrapper">
        {{-- الفئة/العلامة --}}
        @if(isset($product->category))
        <span class="product-category">{{ $product->category->name }}</span>
        @elseif(isset($product->brand))
        <span class="product-brand">{{ $product->brand->name }}</span>
        @endif

        {{-- اسم المنتج --}}
        <h3 class="product-title">
            <a href="{{ route('products.show', $product) }}">{{ Str::limit($product->name, 45) }}</a>
        </h3>

        {{-- التقييم --}}
        @if(isset($product->rating))
        <div class="product-rating">
            <div class="stars">
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star-fill {{ $i <= $product->rating ? 'active' : '' }}"></i>
                @endfor
            </div>
            <span class="rating-count">({{ $product->reviews_count ?? 0 }})</span>
        </div>
        @endif

        {{-- السعر --}}
        <div class="product-price-wrapper">
            @if(isset($product->old_price) && $product->old_price > $product->price)
            <div class="price-comparison">
                <span class="price-old">${{ number_format($product->old_price, 2) }}</span>
                <span class="price-new">${{ number_format($product->price, 2) }}</span>
            </div>
            <div class="price-save">
                <i class="bi bi-piggy-bank-fill"></i>
                وفر ${{ number_format($product->old_price - $product->price, 2) }}
            </div>
            @else
            <span class="price-current">${{ number_format($product->price, 2) }}</span>
            @endif
        </div>

        {{-- حالة التوفر --}}
        @if(isset($product->stock))
        <div class="product-stock {{ $product->stock > 0 ? 'in-stock' : 'out-of-stock' }}">
            @if($product->stock > 0)
                <i class="bi bi-check-circle-fill"></i>
                <span>متوفر ({{ $product->stock }} قطعة)</span>
            @else
                <i class="bi bi-x-circle-fill"></i>
                <span>غير متوفر</span>
            @endif
        </div>
        @endif

        {{-- زر الإضافة للسلة --}}
        <div class="product-actions">
            @auth
                @if(!isset($product->stock) || $product->stock > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-add-to-cart">
                        <i class="bi bi-cart-plus-fill"></i>
                        <span>أضف للسلة</span>
                    </button>
                </form>
                @else
                <button class="btn-notify-me" onclick="notifyWhenAvailable({{ $product->id }})">
                    <i class="bi bi-bell-fill"></i>
                    <span>أخبرني عند التوفر</span>
                </button>
                @endif
            @else
            <a href="{{ route('login') }}" class="btn-login-to-buy">
                <i class="bi bi-lock-fill"></i>
                <span>سجل دخول للشراء</span>
            </a>
            @endauth
            
            <a href="{{ route('products.show', $product) }}" class="btn-view-details">
                عرض التفاصيل
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
</div>
