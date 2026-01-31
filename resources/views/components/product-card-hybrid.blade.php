{{-- بطاقة منتج احترافية: Update Aden Store --}}
<div class="product-card-hybrid hover-scale">
    {{-- شارة الخصم --}}
    @if(isset($product->discount) && $product->discount > 0)
    <div class="discount-badge-hybrid">
        <i class="bi bi-tag-fill"></i> خصم {{ $product->discount }}%
    </div>
    @elseif(isset($product->old_price) && $product->old_price > $product->price)
    @php
        $discountPercent = round((($product->old_price - $product->price) / $product->old_price) * 100);
    @endphp
    <div class="discount-badge-hybrid">
        <i class="bi bi-tag-fill"></i> خصم {{ $discountPercent }}%
    </div>
    @endif

    {{-- شارة جديد --}}
    @if(isset($product->is_new) && $product->is_new)
    <div class="new-badge-hybrid">
        <i class="bi bi-star-fill"></i> جديد
    </div>
    @elseif($product->created_at && $product->created_at->diffInDays(now()) <= 7)
    <div class="new-badge-hybrid">
        <i class="bi bi-star-fill"></i> جديد
    </div>
    @endif

    {{-- أيقونة المفضلة --}}
    <button class="wishlist-btn-hybrid tooltip-custom" 
            data-tooltip="أضف للمفضلة"
            onclick="addToWishlist({{ $product->id }})" 
            title="إضافة للمفضلة">
        <i class="bi bi-heart"></i>
    </button>

    {{-- صورة المنتج --}}
    <div class="product-image-hybrid">
        @if($product->image)
            <a href="{{ route('products.show', $product) }}">
                <img src="{{ $product->image }}" 
                     alt="{{ $product->name }}" 
                     loading="lazy"
                     onerror="this.src='https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=400&h=400&fit=crop&q=80'">
            </a>
        @else
            <a href="{{ route('products.show', $product) }}">
                <div class="product-placeholder-hybrid">
                    <i class="bi bi-image"></i>
                    <span>لا توجد صورة</span>
                </div>
            </a>
        @endif
    </div>

    {{-- معلومات المنتج --}}
    <div class="product-info-hybrid">
        {{-- التقييم --}}
        @if(isset($product->rating) && $product->rating > 0)
        <div class="product-rating" style="display: flex; align-items: center; gap: 4px; font-size: 12px; margin-bottom: 6px;">
            <div style="color: #fbbf24;">
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $product->rating ? '-fill' : '' }}"></i>
                @endfor
            </div>
            <span class="text-muted">({{ $product->reviews_count ?? 0 }})</span>
        </div>
        @endif

        {{-- اسم المنتج --}}
        <h3 class="product-name-hybrid">
            <a href="{{ route('products.show', $product) }}">{{ Str::limit($product->name, 55) }}</a>
        </h3>

        {{-- الفئة --}}
        @if(isset($product->category))
        <div style="margin-bottom: 8px;">
            <span class="badge" style="background: #f0fdf4; color: #166534; font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 6px;">
                <i class="bi bi-tag"></i> {{ $product->category->name }}
            </span>
        </div>
        @endif

        {{-- السعر --}}
        <div class="product-price-hybrid">
            @if(isset($product->old_price) && $product->old_price > $product->price)
                <div class="price-group">
                    <span class="price-new-hybrid">${{ number_format($product->price, 2) }}</span>
                    <span class="price-old-hybrid">${{ number_format($product->old_price, 2) }}</span>
                </div>
            @else
                <span class="price-current-hybrid">${{ number_format($product->price, 2) }}</span>
            @endif
        </div>

        {{-- معلومات التوصيل --}}
        <div class="delivery-info-hybrid">
            <i class="bi bi-truck"></i>
            <span>توصيل خلال {{ $product->delivery_days ?? 2 }}-{{ ($product->delivery_days ?? 2) + 1 }} أيام</span>
        </div>

        {{-- حالة المخزون --}}
        @if(isset($product->stock))
        <div style="margin-top: 8px;">
            @if($product->stock > 10)
                <small class="text-success" style="display: flex; align-items: center; gap: 4px;">
                    <i class="bi bi-check-circle-fill"></i> متوفر بكثرة
                </small>
            @elseif($product->stock > 0)
                <small class="text-warning" style="display: flex; align-items: center; gap: 4px;">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ $product->stock }} قطع متبقية
                </small>
            @else
                <small class="text-danger" style="display: flex; align-items: center; gap: 4px;">
                    <i class="bi bi-x-circle-fill"></i> نفذت الكمية
                </small>
            @endif
        </div>
        @endif

        {{-- زر الإضافة للسلة --}}
        @auth
            @if(!isset($product->stock) || $product->stock > 0)
            <button type="button" 
                    class="btn-add-cart-hybrid" 
                    onclick="addToCartAjax({{ $product->id }}, '{{ addslashes($product->name) }}')">
                <i class="bi bi-cart-plus"></i>
                <span>أضف للسلة</span>
            </button>
            @else
            <button class="btn-out-of-stock-hybrid" disabled>
                <i class="bi bi-x-circle"></i>
                <span>غير متوفر</span>
            </button>
            @endif
        @else
        <a href="{{ route('login') }}" class="btn-login-hybrid">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>سجل دخول للشراء</span>
        </a>
        @endauth
    </div>
</div>
