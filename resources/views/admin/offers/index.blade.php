@extends('admin.layout')

@section('title', 'العروض')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-tag-fill text-primary"></i> إدارة العروض</h2>
        <a href="{{ route('admin.offers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> إضافة عرض جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>الصورة</th>
                            <th>العنوان</th>
                            <th>نسبة الخصم</th>
                            <th>تاريخ البدء</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                        <tr>
                            <td>
                                @if($offer->image)
                                    <img src="{{ asset('storage/' . $offer->image) }}" 
                                         alt="{{ $offer->title }}" 
                                         class="img-thumbnail" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $offer->title }}</td>
                            <td>
                                <span class="badge bg-success">{{ $offer->discount_percentage }}%</span>
                            </td>
                            <td>{{ $offer->start_date->format('Y/m/d') }}</td>
                            <td>{{ $offer->end_date->format('Y/m/d') }}</td>
                            <td>
                                @if($offer->isActive())
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.offers.show', $offer->id) }}" 
                                       class="btn btn-sm btn-info" title="عرض">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.offers.edit', $offer->id) }}" 
                                       class="btn btn-sm btn-warning" title="تعديل">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.offers.destroy', $offer->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted">لا توجد عروض حالياً</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($offers->hasPages())
                <div class="mt-3">
                    {{ $offers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
