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

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>تاريخ البدء:</strong>
                            <p>{{ $offer->start_date->format('Y/m/d') }}</p>
                        </div>
                        <div class="col-md-6">
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
