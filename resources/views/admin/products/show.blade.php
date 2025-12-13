@extends('admin.layout')

@section('title', 'تفاصيل المنتج')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>{{ $product->name }}</h5>
            </div>
            <div class="card-body">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid rounded mb-3" style="max-height: 400px;">
                @endif
                <p><strong>الوصف:</strong></p>
                <p>{{ $product->description ?? 'لا يوجد وصف' }}</p>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>السعر:</strong> ${{ number_format($product->price, 2) }}</p>
                        <p><strong>المخزون:</strong> 
                            @if($product->stock > 0)
                                <span class="badge bg-success">{{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger">نفد</span>
                            @endif
                        </p>
                        <p><strong>SKU:</strong> {{ $product->sku ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>العلامة:</strong> {{ $product->brand->name ?? '-' }}</p>
                        <p><strong>التصنيفات:</strong>
                            @foreach($product->categories as $cat)
                                <span class="badge bg-info">{{ $cat->name }}</span>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>الإجراءات</h5>
                <hr>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning w-100 mb-2">تعديل</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100 mb-2">حذف</button>
                </form>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100">رجوع</a>
            </div>
        </div>
    </div>
</div>
@endsection

