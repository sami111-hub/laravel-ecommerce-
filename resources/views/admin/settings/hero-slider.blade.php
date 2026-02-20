@extends('admin.layout')
@section('title', 'إدارة السلايدر الرئيسي')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-images me-2"></i>إدارة السلايدر الرئيسي</h4>
            <p class="text-muted mb-0">تحكم في محتوى السلايدر الرئيسي في الصفحة الرئيسية</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary">
            <i class="bi bi-eye me-1"></i> معاينة الموقع
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.settings.hero-slider.update') }}" method="POST">
        @csrf
        @method('PUT')

        @foreach($slides as $i => $slide)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-card-image me-2 text-primary"></i>
                    السلايد {{ $i }}
                </h5>
                <span class="badge bg-primary rounded-pill">Slide {{ $i }}</span>
            </div>
            <div class="card-body">
                <!-- معاينة مصغرة -->
                <div class="mb-4 rounded-3 overflow-hidden" id="preview-{{ $i }}" style="background: {{ $slide['bg_gradient'] }}; padding: 30px; min-height: 180px;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="text-white" style="max-width: 60%;">
                            <span class="badge bg-warning text-dark mb-2" id="preview-badge-{{ $i }}">{{ $slide['badge'] }}</span>
                            <h4 class="text-white mb-2" id="preview-title-{{ $i }}">{{ $slide['title'] }}</h4>
                            <p class="text-white-50 mb-3" id="preview-desc-{{ $i }}">{{ $slide['description'] }}</p>
                            <span class="btn btn-light btn-sm" id="preview-btn-{{ $i }}">{{ $slide['button_text'] }}</span>
                        </div>
                        <div>
                            <img id="preview-img-{{ $i }}" src="{{ $slide['image_url'] }}" alt="معاينة" style="max-height: 120px; max-width: 150px; border-radius: 10px; object-fit: cover;">
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-tag me-1 text-warning"></i> الشارة (Badge)
                        </label>
                        <input type="text" name="slide[{{ $i }}][badge]" value="{{ $slide['badge'] }}" 
                               class="form-control" placeholder="مثال: عروض حصرية"
                               oninput="document.getElementById('preview-badge-{{ $i }}').textContent = this.value">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold">
                            <i class="bi bi-type-h2 me-1 text-primary"></i> العنوان الرئيسي *
                        </label>
                        <input type="text" name="slide[{{ $i }}][title]" value="{{ $slide['title'] }}" 
                               class="form-control" required placeholder="مثال: أحدث الهواتف الذكية"
                               oninput="document.getElementById('preview-title-{{ $i }}').textContent = this.value">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold">
                            <i class="bi bi-text-paragraph me-1 text-info"></i> الوصف
                        </label>
                        <input type="text" name="slide[{{ $i }}][description]" value="{{ $slide['description'] }}" 
                               class="form-control" placeholder="مثال: خصومات تصل إلى 30%"
                               oninput="document.getElementById('preview-desc-{{ $i }}').textContent = this.value">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-cursor me-1 text-success"></i> نص الزر
                        </label>
                        <input type="text" name="slide[{{ $i }}][button_text]" value="{{ $slide['button_text'] }}" 
                               class="form-control" placeholder="مثال: تسوق الآن"
                               oninput="document.getElementById('preview-btn-{{ $i }}').textContent = this.value">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold">
                            <i class="bi bi-link-45deg me-1 text-secondary"></i> رابط الزر
                        </label>
                        <input type="text" name="slide[{{ $i }}][button_link]" value="{{ $slide['button_link'] }}" 
                               class="form-control" dir="ltr" placeholder="مثال: /products?category=smartphones">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold">
                            <i class="bi bi-image me-1 text-danger"></i> رابط الصورة
                        </label>
                        <input type="text" name="slide[{{ $i }}][image_url]" value="{{ $slide['image_url'] }}" 
                               class="form-control" dir="ltr" placeholder="https://..."
                               oninput="document.getElementById('preview-img-{{ $i }}').src = this.value">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-palette me-1 text-purple"></i> خلفية التدرج
                        </label>
                        <input type="text" name="slide[{{ $i }}][bg_gradient]" value="{{ $slide['bg_gradient'] }}" 
                               class="form-control" dir="ltr" placeholder="linear-gradient(135deg, #1a265f 0%, #2d398a 100%)"
                               oninput="document.getElementById('preview-{{ $i }}').style.background = this.value">
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- أزرار التحكم -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right me-1"></i> رجوع
            </a>
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-check-lg me-2"></i> حفظ التغييرات
            </button>
        </div>
    </form>
</div>
@endsection
