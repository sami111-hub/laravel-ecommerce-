@extends('admin.layout')

@section('title', 'إضافة صلاحية جديدة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-shield-plus"></i> إضافة صلاحية جديدة</h4>
    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> العودة للقائمة
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">
                    اسم الصلاحية <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       placeholder="مثال: عرض المنتجات"
                       required 
                       autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">سيتم إنشاء slug تلقائياً من الاسم</small>
            </div>

            <div class="mb-3">
                <label for="group" class="form-label fw-bold">
                    المجموعة <span class="text-danger">*</span>
                </label>
                <select class="form-select @error('group') is-invalid @enderror" 
                        id="group" 
                        name="group" 
                        required>
                    <option value="">اختر المجموعة</option>
                    @foreach($groups as $key => $label)
                    <option value="{{ $key }}" {{ old('group') == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('group')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">الوصف</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" 
                          name="description" 
                          rows="3" 
                          placeholder="وصف مختصر للصلاحية...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> حفظ الصلاحية
                </button>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-x-circle"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.form-label.fw-bold {
    font-size: 0.95rem;
    color: #2c3e50;
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>
@endsection

