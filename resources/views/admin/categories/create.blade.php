@extends('admin.layout')

@section('title', 'إضافة تصنيف جديد')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-folder-plus"></i> إضافة تصنيف جديد</h4>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> العودة للقائمة
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            {{-- معلومات أساسية --}}
            <div class="section-title mb-3">
                <h5 class="text-primary"><i class="bi bi-info-circle"></i> المعلومات الأساسية</h5>
                <hr>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="name" class="form-label fw-bold">اسم التصنيف <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" 
                           placeholder="مثال: الهواتف الذكية" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="order" class="form-label fw-bold">ترتيب العرض</label>
                    <input type="number" min="0" class="form-control @error('order') is-invalid @enderror" 
                           id="order" name="order" value="{{ old('order', 0) }}" 
                           placeholder="0">
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">الأقل رقماً يظهر أولاً</small>
                </div>
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label fw-bold">الرابط (Slug)</label>
                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                       id="slug" name="slug" value="{{ old('slug') }}" 
                       placeholder="smartphones">
                @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> سيتم توليده تلقائياً من الاسم إذا تركته فارغاً
                </small>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">الوصف</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3" 
                          placeholder="وصف مختصر للتصنيف...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- التصنيف والمظهر --}}
            <div class="section-title mb-3 mt-4">
                <h5 class="text-primary"><i class="bi bi-palette"></i> المظهر والتنظيم</h5>
                <hr>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="parent_id" class="form-label fw-bold">التصنيف الأب</label>
                    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                        <option value="">-- تصنيف رئيسي --</option>
                        @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">اتركه فارغاً إذا كان تصنيف رئيسي</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="icon" class="form-label fw-bold">الأيقونة</label>
                    <div class="input-group">
                        <span class="input-group-text"><i id="icon-preview" class="bi bi-folder"></i></span>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                               id="icon" name="icon" value="{{ old('icon', 'folder') }}" 
                               placeholder="phone">
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted">
                        <a href="https://icons.getbootstrap.com/" target="_blank" class="text-decoration-none">
                            <i class="bi bi-box-arrow-up-right"></i> تصفح الأيقونات
                        </a>
                    </small>
                </div>
            </div>

            {{-- الحالة --}}
            <div class="section-title mb-3 mt-4">
                <h5 class="text-primary"><i class="bi bi-toggles"></i> الحالة</h5>
                <hr>
            </div>

            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" 
                           name="is_active" value="1" id="is_active" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="is_active">
                        <i class="bi bi-check-circle"></i> تصنيف نشط
                    </label>
                    <div class="form-text">التصنيفات غير النشطة لن تظهر في الموقع</div>
                </div>
            </div>

            {{-- أزرار الحفظ --}}
            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> حفظ التصنيف
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-x-circle"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// معاينة الأيقونة عند الكتابة
document.getElementById('icon').addEventListener('input', function() {
    const iconName = this.value.trim();
    const preview = document.getElementById('icon-preview');
    preview.className = iconName ? `bi bi-${iconName}` : 'bi bi-folder';
});

// توليد slug تلقائياً من الاسم
document.getElementById('name').addEventListener('input', function() {
    const slugField = document.getElementById('slug');
    if (!slugField.value) {
        const name = this.value.trim();
        const slug = name
            .toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\u0600-\u06FFa-z0-9-]/g, '');
        slugField.placeholder = slug || 'category-name';
    }
});
</script>

<style>
.section-title h5 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.section-title hr {
    margin-top: 0.5rem;
    opacity: 0.1;
}

.form-label.fw-bold {
    font-size: 0.95rem;
    color: #2c3e50;
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.input-group-text i {
    font-size: 1.2rem;
    color: #667eea;
}
</style>
@endsection

