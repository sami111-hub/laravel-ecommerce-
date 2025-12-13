@extends('layout')

@section('title', 'العروض والخصومات - Update Aden')
@section('description', 'اغتنم الفرصة واحصل على أفضل العروض والخصومات على الأجهزة الإلكترونية في Update Aden')

@section('content')
<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-4 mb-3">
            <i class="bi bi-tag-fill text-primary"></i> العروض والخصومات
        </h1>
        <p class="lead text-muted">اغتنم الفرصة واحصل على أفضل العروض والخصومات</p>
    </div>

    @if($activeOffers->count() > 0)
        <div class="row g-4">
            @foreach($activeOffers as $offer)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden">
                    <!-- Discount Badge -->
                    <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                        <div class="badge bg-danger fs-5 p-2">
                            خصم {{ $offer->discount_percentage }}%
                        </div>
                    </div>

                    <!-- Offer Image -->
                    @if($offer->image)
                        <img src="{{ asset('storage/' . $offer->image) }}" 
                             class="card-img-top" 
                             alt="{{ $offer->title }}"
                             style="height: 250px; object-fit: cover;">
                    @else
                        <div class="bg-gradient text-white d-flex align-items-center justify-content-center" 
                             style="height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="text-center">
                                <i class="bi bi-gift fs-1 mb-2"></i>
                                <h3>عرض خاص</h3>
                            </div>
                        </div>
                    @endif

                    <div class="card-body">
                        <h5 class="card-title text-primary mb-3">{{ $offer->title }}</h5>
                        <p class="card-text text-muted">{{ $offer->description }}</p>
                        
                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center text-muted small">
                                <span>
                                    <i class="bi bi-calendar-check"></i>
                                    ينتهي: {{ $offer->end_date->format('Y/m/d') }}
                                </span>
                                <span class="badge bg-success">
                                    <i class="bi bi-clock"></i>
                                    نشط الآن
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-0">
                        <a href="{{ route('products.index') }}" class="btn btn-primary w-100">
                            <i class="bi bi-cart-plus"></i> تسوق الآن
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-inbox" style="font-size: 5rem; color: #ccc;"></i>
            </div>
            <h3 class="text-muted mb-3">لا توجد عروض حالياً</h3>
            <p class="text-muted mb-4">ترقب العروض الجديدة قريباً</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-right"></i> تصفح المنتجات
            </a>
        </div>
    @endif

    <!-- Upcoming Offers Section -->
    @if($upcomingOffers->count() > 0)
        <div class="mt-5 pt-5 border-top">
            <h2 class="text-center mb-4">
                <i class="bi bi-calendar-event text-info"></i> عروض قادمة
            </h2>
            <div class="row g-4">
                @foreach($upcomingOffers as $offer)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="badge bg-info me-2">قريباً</div>
                                <span class="badge bg-secondary">{{ $offer->discount_percentage }}%</span>
                            </div>
                            <h6 class="card-title">{{ $offer->title }}</h6>
                            <p class="card-text small text-muted">{{ Str::limit($offer->description, 80) }}</p>
                            <div class="text-muted small">
                                <i class="bi bi-calendar3"></i>
                                يبدأ: {{ $offer->start_date->format('Y/m/d') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection