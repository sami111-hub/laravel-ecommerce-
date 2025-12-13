@extends('admin.layout')

@section('title', 'إضافة دور جديد')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-shield-plus"></i> إضافة دور جديد</h4>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> العودة للقائمة
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            
            {{-- معلومات أساسية --}}
            <div class="section-title mb-3">
                <h5 class="text-primary"><i class="bi bi-info-circle"></i> معلومات الدور</h5>
                <hr>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label fw-bold">اسم الدور <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" 
                       placeholder="مثال: مدير المحتوى" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">الوصف</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3" 
                          placeholder="وصف مختصر للدور وصلاحياته...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- الصلاحيات --}}
            <div class="section-title mb-3 mt-4">
                <h5 class="text-primary"><i class="bi bi-shield-check"></i> الصلاحيات</h5>
                <hr>
            </div>

            <div class="mb-3">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="select-all">
                    <label class="form-check-label fw-bold" for="select-all">
                        <i class="bi bi-check-all"></i> تحديد جميع الصلاحيات
                    </label>
                </div>

                @forelse($permissions as $group => $groupPermissions)
                <div class="card mb-3 border">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong><i class="bi bi-folder"></i> {{ $group }}</strong>
                            <span class="badge bg-secondary">{{ $groupPermissions->count() }} صلاحية</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($groupPermissions as $permission)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox" 
                                           type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}" 
                                           id="perm_{{ $permission->id }}"
                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        <i class="bi bi-shield-fill-check text-success"></i> {{ $permission->name }}
                                        @if($permission->description)
                                        <br><small class="text-muted">{{ $permission->description }}</small>
                                        @endif
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @empty
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> لا توجد صلاحيات متاحة
                </div>
                @endforelse
            </div>

            @error('permissions')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            {{-- أزرار الحفظ --}}
            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> حفظ الدور
                </button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-x-circle"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// تحديد/إلغاء تحديد جميع الصلاحيات
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// تحديث حالة "تحديد الكل" عند تغيير أي صلاحية
document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const selectAll = document.getElementById('select-all');
        const allCheckboxes = document.querySelectorAll('.permission-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.permission-checkbox:checked');
        selectAll.checked = allCheckboxes.length === checkedCheckboxes.length;
    });
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

.card-header {
    background-color: #f8f9fa;
}
</style>
@endsection

