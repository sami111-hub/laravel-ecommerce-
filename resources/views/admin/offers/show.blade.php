@extends('admin.layout')

@section('title', 'تفاصيل العرض')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-eye text-primary"></i> تفاصيل العرض</h2>
        <div>
            <a href="{{ route('admin.offers.edit', $offer->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> تعديل
            </a>
            <a href="{{ route('admin.offers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-right"></i> رجوع
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    @if($offer->image)
                        <img src="{{ asset('storage/' . $offer->image) }}" 
                             alt="{{ $offer->title }}" 
                             class="img-fluid rounded">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> معلومات العرض</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>العنوان:</strong>
                            <p>{{ $offer->title }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>نسبة الخصم:</strong>
                            <p><span class="badge bg-success fs-6">{{ $offer->discount_percentage }}%</span></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>الوصف:</strong>
                        <p>{{ $offer->description }}</p>
                    </div>

                    @if($offer->category_slug)
                    <div class="mb-3">
                        <strong>التصنيف:</strong>
                        <p><span class="badge bg-info">{{ $offer->category ? $offer->category->name : $offer->category_slug }}</span></p>
                    </div>
                    @endif

                    <div class="row mb-3">
                        @if($offer->original_price)
                        <div class="col-md-3">
                            <strong>السعر الأصلي:</strong>
                            <p class="text-muted"><del>${{ number_format($offer->original_price, 2) }}</del></p>
                        </div>
                        @endif
                        @if($offer->offer_price)
                        <div class="col-md-3">
                            <strong>سعر العرض:</strong>
                            <p class="text-success fw-bold">${{ number_format($offer->offer_price, 2) }}</p>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <strong>تاريخ البدء:</strong>
                            <p>{{ $offer->start_date->format('Y/m/d') }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>تاريخ الانتهاء:</strong>
                            <p>{{ $offer->end_date->format('Y/m/d') }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>الحالة:</strong>
                            <p>
                                @if($offer->isActive())
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">غير نشط</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>تاريخ الإنشاء:</strong>
                            <p>{{ $offer->created_at->format('Y/m/d h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- المواصفات --}}
            @php $allSpecs = $offer->getAllSpecifications(); @endphp
            @if(!empty($allSpecs))
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> المواصفات</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <tbody>
                            @foreach($allSpecs as $key => $value)
                            <tr>
                                <td class="fw-bold text-end" style="width: 35%;">{{ $key }}</td>
                                <td>{{ $value }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-trash"></i> منطقة الخطر</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">حذف العرض نهائياً من النظام</p>
                    <form action="{{ route('admin.offers.destroy', $offer->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟ هذا الإجراء لا يمكن التراجع عنه!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> حذف العرض
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
