@extends('admin.layout')

@section('title', 'تعديل المنتج')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>تعديل المنتج: {{ $product->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">اسم المنتج *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="sku" class="form-label">رمز المنتج (SKU)</label>
                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">الوصف</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">السعر *</label>
                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="stock" class="form-label">المخزون *</label>
                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="brand_id" class="form-label">العلامة التجارية</label>
                    <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id">
                        <option value="">اختر العلامة</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- الصورة --}}
            <div class="mb-3">
                <label class="form-label fw-bold">طريقة تحديث الصورة</label>
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

            {{-- الصورة الحالية --}}
            @if($product->image)
            <div class="mb-3">
                <label class="form-label fw-bold">الصورة الحالية</label>
                <div class="border rounded p-3 text-center bg-light">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid" style="max-width: 200px; border-radius: 4px;">
                </div>
            </div>
            @endif

            {{-- رابط الصورة --}}
            <div id="url-section" class="mb-3">
                <label for="image" class="form-label fw-bold">رابط الصورة الجديدة</label>
                <input type="url" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ old('image') }}" placeholder="https://example.com/image.jpg">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted"><i class="bi bi-info-circle"></i> اتركه فارغاً للاحتفاظ بالصورة الحالية</small>
            </div>

            {{-- رفع الصورة --}}
            <div id="upload-section" class="mb-3" style="display: none;">
                <label for="image_file" class="form-label fw-bold">اختر صورة جديدة</label>
                <input type="file" class="form-control @error('image_file') is-invalid @enderror" id="image_file" name="image_file" accept="image/*">
                @error('image_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted"><i class="bi bi-info-circle"></i> الحد الأقصى: 2MB - الصيغ المدعومة: JPG, PNG, WEBP</small>
            </div>

            {{-- معاينة الصورة الجديدة --}}
            <div id="image-preview" class="mb-3" style="display: none;">
                <label class="form-label fw-bold">معاينة الصورة الجديدة</label>
                <div class="border rounded p-3 text-center bg-light">
                    <img id="preview-img" src="" alt="معاينة" class="img-fluid" style="max-width: 300px; max-height: 300px; border-radius: 8px;">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">التصنيفات</label>
                <div class="row">
                    @foreach($categories as $category)
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="cat_{{ $category->id }}" 
                                {{ $product->categories->contains($category->id) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cat_{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">إلغاء</a>
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
        if (!file.type.match('image.*')) {
            alert('يرجى اختيار ملف صورة صالح');
            this.value = '';
            return;
        }
        
        if (file.size > 2 * 1024 * 1024) {
            alert('حجم الصورة يجب أن يكون أقل من 2MB');
            this.value = '';
            return;
        }
        
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
</script>
@endsection

