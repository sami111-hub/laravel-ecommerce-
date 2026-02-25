@extends('admin.layout')

@section('title', 'تعديل الماركة: ' . $brand->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-pencil-square"></i> تعديل الماركة: {{ $brand->name }}</h4>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> العودة للقائمة
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label fw-bold">اسم الماركة <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $brand->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="slug" class="form-label fw-bold">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                           id="slug" name="slug" value="{{ old('slug', $brand->slug) }}">
                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">الوصف</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $brand->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            @if($brand->logo)
            <div class="mb-3">
                <label class="form-label fw-bold">الشعار الحالي</label>
                <div class="border rounded p-3 bg-light text-center">
                    <img src="{{ $brand->logo }}" alt="{{ $brand->name }}" style="max-height: 80px;">
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="logo" class="form-label fw-bold">رابط شعار جديد</label>
                    <input type="url" class="form-control @error('logo') is-invalid @enderror" 
                           id="logo" name="logo" value="{{ old('logo') }}" placeholder="https://...">
                    @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="logo_file" class="form-label fw-bold">أو رفع شعار جديد</label>
                    <input type="file" class="form-control @error('logo_file') is-invalid @enderror" 
                           id="logo_file" name="logo_file" accept="image/*">
                    @error('logo_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                           {{ $brand->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">نشط</label>
                </div>
            </div>

            <div class="d-flex gap-2 border-top pt-3">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> حفظ التغييرات</button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary px-4"><i class="bi bi-x-circle"></i> إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
