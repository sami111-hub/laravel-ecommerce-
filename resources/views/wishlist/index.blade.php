@extends('layout')

@section('title', 'قائمة المفضلة - متجر Update Aden')

@section('content')
<div class="wishlist-page">
    <div class="page-header-wishlist">
        <h1><i class="bi bi-heart-fill"></i> قائمة المفضلة</h1>
        <p>{{ $wishlists->count() }} منتج</p>
    </div>

    @if($wishlists->count() > 0)
    <div class="row g-4">
        @foreach($wishlists as $wishlist)
            <div class="col-lg-3 col-md-4 col-sm-6">
                @include('components.product-card-hybrid', ['product' => $wishlist->product])
            </div>
        @endforeach
    </div>
    @else
    <div class="empty-wishlist">
        <i class="bi bi-heart"></i>
        <h3>قائمة المفضلة فارغة</h3>
        <p>لم تقم بإضافة أي منتجات للمفضلة بعد</p>
        <a href="{{ route('products.index') }}" class="btn-browse-products">
            <i class="bi bi-shop"></i>
            تصفح المنتجات
        </a>
    </div>
    @endif
</div>

<style>
.wishlist-page {
    min-height: 60vh;
    padding: 30px 0;
}

.page-header-wishlist {
    text-align: center;
    margin-bottom: 40px;
}

.page-header-wishlist h1 {
    font-size: 32px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
}

.page-header-wishlist h1 i {
    color: #e74c3c;
}

.page-header-wishlist p {
    color: #7f8c8d;
    font-size: 16px;
}

.empty-wishlist {
    text-align: center;
    padding: 80px 20px;
}

.empty-wishlist i {
    font-size: 100px;
    color: #ecf0f1;
    margin-bottom: 20px;
}

.empty-wishlist h3 {
    font-size: 24px;
    color: #2c3e50;
    margin-bottom: 10px;
}

.empty-wishlist p {
    color: #7f8c8d;
    margin-bottom: 30px;
}

.btn-browse-products {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #FF6B00 0%, #ff8533 100%);
    color: white;
    padding: 12px 30px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-browse-products:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(255, 107, 0, 0.3);
    color: white;
}
</style>
@endsection
