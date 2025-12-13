@extends('admin.layout')

@section('title', 'تفاصيل التصنيف')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h5>تفاصيل التصنيف: {{ $category->name }}</h5>
    </div>
    <div class="card-body">
        <p><strong>الاسم:</strong> {{ $category->name }}</p>
        <p><strong>الوصف:</strong> {{ $category->description ?? '-' }}</p>
        <p><strong>التصنيف الأب:</strong> {{ $category->parent->name ?? '-' }}</p>
        <p><strong>الحالة:</strong> 
            @if($category->is_active)
                <span class="badge bg-success">نشط</span>
            @else
                <span class="badge bg-danger">غير نشط</span>
            @endif
        </p>
        <p><strong>عدد المنتجات:</strong> {{ $category->products->count() }}</p>
        <p><strong>عدد التصنيفات الفرعية:</strong> {{ $category->children->count() }}</p>
    </div>
</div>

@if($category->children->count() > 0)
<div class="card mb-4">
    <div class="card-header">
        <h5>التصنيفات الفرعية</h5>
    </div>
    <div class="card-body">
        <ul>
            @foreach($category->children as $child)
            <li><a href="{{ route('admin.categories.show', $child) }}">{{ $child->name }}</a></li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="mt-4">
    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">تعديل</a>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">رجوع</a>
</div>
@endsection

