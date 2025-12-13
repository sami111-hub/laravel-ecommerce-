@extends('layout')

@section('title', 'التصنيفات - Update Aden')
@section('description', 'تصفح جميع تصنيفات المنتجات - هواتف، لابتوبات، ساعات ذكية، إكسسوارات والمزيد في Update Aden')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-6 fw-bold">
                    <i class="bi bi-grid-3x3-gap text-primary"></i>
                    جميع التصنيفات
                </h1>
                <p class="text-muted mb-0">{{ $categories->count() }} تصنيف</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($categories as $category)
            @if($category->products_count > 0)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('products.category', $category) }}" class="text-decoration-none">
                    <div class="category-card border rounded-3 p-4 h-100 text-center hover-shadow transition">
                        @if($category->image)
                            <img src="{{ $category->image }}" 
                                 alt="{{ $category->name }}" 
                                 class="img-fluid rounded-circle mb-3"
                                 style="width: 100px; height: 100px; object-fit: cover;"
                                 loading="lazy">
                        @else
                            <div class="category-icon-placeholder bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                 style="width: 100px; height: 100px;">
                                <i class="bi bi-box-seam fs-1 text-primary"></i>
                            </div>
                        @endif
                        
                        <h5 class="fw-bold mb-2">{{ $category->name }}</h5>
                        <p class="text-muted small mb-0">
                            {{ $category->products_count }} 
                            {{ $category->products_count == 1 ? 'منتج' : 'منتجات' }}
                        </p>
                    </div>
                </a>
            </div>
            @endif
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle fs-3"></i>
                    <p class="mb-0 mt-2">لا توجد تصنيفات متاحة حالياً</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
.category-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-color: var(--bs-primary) !important;
}

.hover-shadow {
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.transition {
    transition: all 0.3s ease;
}
</style>
@endsection
