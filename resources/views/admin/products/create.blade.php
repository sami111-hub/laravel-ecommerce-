@extends('admin.layout')

@section('title', 'إضافة منتج جديد')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-plus-circle"></i> إضافة منتج جديد</h4>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> العودة للقائمة
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- معلومات أساسية --}}
            <div class="section-title mb-3">
                <h5 class="text-primary"><i class="bi bi-info-circle"></i> المعلومات الأساسية</h5>
                <hr>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="name" class="form-label fw-bold">اسم المنتج <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" 
                           placeholder="مثال: هاتف iPhone 15 Pro Max" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="sku" class="form-label fw-bold">رمز المنتج (SKU)</label>
                    <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                           id="sku" name="sku" value="{{ old('sku') }}" 
                           placeholder="مثال: IPH-15-PM-256">
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">اتركه فارغاً للتوليد التلقائي</small>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">الوصف</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" 
                          placeholder="اكتب وصفاً تفصيلياً للمنتج...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- السعر والمخزون --}}
            <div class="section-title mb-3 mt-4">
                <h5 class="text-primary"><i class="bi bi-cash-stack"></i> السعر والمخزون</h5>
                <hr>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label fw-bold">السعر <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" min="0" 
                               class="form-control @error('price') is-invalid @enderror" 
                               id="price" name="price" value="{{ old('price') }}" 
                               placeholder="0.00" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label fw-bold">الكمية المتوفرة <span class="text-danger">*</span></label>
                    <input type="number" min="0" 
                           class="form-control @error('stock') is-invalid @enderror" 
                           id="stock" name="stock" value="{{ old('stock', 0) }}" 
                           placeholder="0" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- العلامة التجارية والتصنيفات --}}
            <div class="section-title mb-3 mt-4">
                <h5 class="text-primary"><i class="bi bi-tags"></i> التصنيف والعلامة التجارية</h5>
                <hr>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="brand_id" class="form-label fw-bold">العلامة التجارية</label>
                    <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id">
                        <option value="">-- اختر العلامة التجارية --</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">التصنيفات</label>
                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                        @forelse($categories as $category)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" 
                                   name="categories[]" value="{{ $category->id }}" 
                                   id="cat_{{ $category->id }}"
                                   {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cat_{{ $category->id }}">
                                <i class="bi bi-folder"></i> {{ $category->name }}
                            </label>
                        </div>
                        @empty
                        <p class="text-muted mb-0">لا توجد تصنيفات متاحة</p>
                        @endforelse
                    </div>
                    @error('categories')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- الصورة --}}
            <div class="section-title mb-3 mt-4">
                <h5 class="text-primary"><i class="bi bi-image"></i> صورة المنتج</h5>
                <hr>
            </div>

            {{-- خيارات الصورة --}}
            <div class="mb-3">
                <label class="form-label fw-bold">طريقة إضافة الصورة</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="image_type" id="image_type_url" value="url" checked>
                    <label class="btn btn-outline-primary" for="image_type_url">
                        <i class="bi bi-link-45deg"></i> رابط من الإنترنت
                    </label>

                    <input type="radio" class="btn-check" name="image_type" id="image_type_upload" value="upload">
                    <label class="btn btn-outline-primary" for="image_type_upload">
                        <i class="bi bi-upload"></i> رفع من الجهاز
                    </label>
                </div>
            </div>

            {{-- رابط الصورة --}}
            <div id="url-section" class="mb-3">
                <label for="image" class="form-label fw-bold">رابط الصورة</label>
                <input type="url" class="form-control @error('image') is-invalid @enderror" 
                       id="image" name="image" value="{{ old('image') }}" 
                       placeholder="https://example.com/product-image.jpg">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> الصق رابط الصورة من الإنترنت
                </small>
            </div>

            {{-- رفع الصورة --}}
            <div id="upload-section" class="mb-3" style="display: none;">
                <label for="image_file" class="form-label fw-bold">اختر صورة</label>
                <input type="file" class="form-control @error('image_file') is-invalid @enderror" 
                       id="image_file" name="image_file" accept="image/*">
                @error('image_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> الحد الأقصى: 2MB - الصيغ المدعومة: JPG, PNG, WEBP
                </small>
            </div>

            {{-- معاينة الصورة --}}
            <div id="image-preview" class="mb-3" style="display: none;">
                <label class="form-label fw-bold">معاينة الصورة</label>
                <div class="border rounded p-3 text-center bg-light">
                    <img id="preview-img" src="" alt="معاينة" class="img-fluid" style="max-width: 300px; max-height: 300px; border-radius: 8px;">
                </div>
            </div>

            {{-- أزرار الحفظ --}}
            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> حفظ المنتج
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-x-circle"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// التبديل بين رابط URL ورفع ملف
const urlRadio = document.getElementById('image_type_url');
const uploadRadio = document.getElementById('image_type_upload');
const urlSection = document.getElementById('url-section');
const uploadSection = document.getElementById('upload-section');
const imageInput = document.getElementById('image');
const fileInput = document.getElementById('image_file');
const preview = document.getElementById('image-preview');
const previewImg = document.getElementById('preview-img');

urlRadio.addEventListener('change', function() {
    if (this.checked) {
        urlSection.style.display = 'block';
        uploadSection.style.display = 'none';
        fileInput.value = '';
        preview.style.display = 'none';
    }
});

uploadRadio.addEventListener('change', function() {
    if (this.checked) {
        urlSection.style.display = 'none';
        uploadSection.style.display = 'block';
        imageInput.value = '';
        preview.style.display = 'none';
    }
});

// معاينة الصورة عند إدخال رابط URL
imageInput.addEventListener('input', function() {
    const imageUrl = this.value.trim();
    
    if (imageUrl && imageUrl.startsWith('http')) {
        previewImg.src = imageUrl;
        preview.style.display = 'block';
        
        // إخفاء المعاينة إذا فشل تحميل الصورة
        previewImg.onerror = function() {
            preview.style.display = 'none';
        };
    } else {
        preview.style.display = 'none';
    }
});

// معاينة الصورة عند رفع ملف
fileInput.addEventListener('change', function() {
    const file = this.files[0];
    
    if (file) {
        // التحقق من نوع الملف
        if (!file.type.match('image.*')) {
            alert('يرجى اختيار ملف صورة صالح');
            this.value = '';
            return;
        }
        
        // التحقق من حجم الملف (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('حجم الصورة يجب أن يكون أقل من 2MB');
            this.value = '';
            return;
        }
        
        // عرض المعاينة
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

// توليد SKU تلقائياً من اسم المنتج
document.getElementById('name').addEventListener('input', function() {
    const skuField = document.getElementById('sku');
    if (!skuField.value) {
        const name = this.value.trim();
        const sku = name
            .split(' ')
            .map(word => word.substring(0, 3).toUpperCase())
            .join('-')
            .replace(/[^A-Z0-9-]/g, '');
        skuField.placeholder = sku || 'PRD-AUTO';
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
</style>
@endsection

