@extends('layout')

@section('title', 'مقارنة المنتجات - Update Aden')
@section('description', 'قارن بين المنتجات واختر الأفضل')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-arrow-left-right"></i> مقارنة المنتجات</h1>
        <form action="{{ route('compare.clear') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> مسح الكل
            </button>
        </form>
    </div>

    @if($products->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th style="width: 200px;">الميزة</th>
                    @foreach($products as $product)
                    <th class="text-center">
                        <div class="position-relative">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                    onclick="removeFromCompare({{ $product->id }})" title="إزالة">
                                <i class="bi bi-x"></i>
                            </button>
                            @if($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" 
                                     class="img-fluid mb-2" style="max-height: 150px;">
                            @endif
                            <h5>{{ $product->name }}</h5>
                            <div class="text-primary fs-4 mb-2">${{ number_format($product->price, 2) }}</div>
                            @auth
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-cart-plus"></i> أضف للسلة
                                    </button>
                                </form>
                            @endauth
                            <div class="mt-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                                    عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>السعر</strong></td>
                    @foreach($products as $product)
                    <td class="text-center">${{ number_format($product->price, 2) }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td><strong>العلامة التجارية</strong></td>
                    @foreach($products as $product)
                    <td class="text-center">{{ $product->brand->name ?? 'غير محدد' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td><strong>المخزون</strong></td>
                    @foreach($products as $product)
                    <td class="text-center">
                        @if($product->stock > 0)
                            <span class="badge bg-success">متوفر ({{ $product->stock }})</span>
                        @else
                            <span class="badge bg-danger">غير متوفر</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                <tr>
                    <td><strong>التقييم</strong></td>
                    @foreach($products as $product)
                    <td class="text-center">
                        @php
                            $rating = $product->average_rating ?? 0;
                            $fullStars = floor($rating);
                            $hasHalfStar = ($rating - $fullStars) >= 0.5;
                        @endphp
                        <div>
                            @for($i = 0; $i < $fullStars; $i++)
                                <i class="bi bi-star-fill text-warning"></i>
                            @endfor
                            @if($hasHalfStar)
                                <i class="bi bi-star-half text-warning"></i>
                            @endif
                            @for($i = $fullStars + ($hasHalfStar ? 1 : 0); $i < 5; $i++)
                                <i class="bi bi-star text-muted"></i>
                            @endfor
                            <span class="ms-1">({{ number_format($rating, 1) }})</span>
                        </div>
                    </td>
                    @endforeach
                </tr>
                <tr>
                    <td><strong>الوصف</strong></td>
                    @foreach($products as $product)
                    <td class="text-center">{{ Str::limit($product->description, 100) }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td><strong>الفئات</strong></td>
                    @foreach($products as $product)
                    <td class="text-center">
                        @foreach($product->categories as $category)
                            <span class="badge bg-info me-1">{{ $category->name }}</span>
                        @endforeach
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-arrow-left-right" style="font-size: 5rem; color: #ddd;"></i>
        <h3 class="mt-3">قائمة المقارنة فارغة</h3>
        <p class="text-muted">أضف منتجات للمقارنة بينها</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">تصفح المنتجات</a>
    </div>
    @endif
</div>

@section('scripts')
<script>
function removeFromCompare(productId) {
    fetch(`/compare/remove/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endsection
@endsection


