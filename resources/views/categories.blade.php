@extends('layout')

@section('content')
<div class="categories-page">
    <h1 class="text-center mb-4">التصنيفات</h1>
    <div class="categories-grid">
        @foreach($categories as $category)
        <div class="category-card">
            <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-{{ $category->icon ?? 'box' }}" style="font-size: 3rem; color: var(--primary-color);"></i>
                        <h5 class="mt-3">{{ $category->name }}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection