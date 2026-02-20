@extends('admin.layout')

@section('title', 'إعدادات الشريط الترويجي')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 text-brown">⚙️ إعدادات الشريط الترويجي</h4>
                            <p class="text-muted mb-0 small">تحكم في النص الذي يظهر في أعلى الموقع</p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-right"></i> العودة
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- الحالة الحالية -->
            <div class="alert {{ $promoEnabled == '1' ? 'alert-success' : 'alert-warning' }} mb-4">
                <i class="bi {{ $promoEnabled == '1' ? 'bi-check-circle' : 'bi-pause-circle' }} me-2"></i>
                <strong>الحالة الحالية:</strong> 
                {{ $promoEnabled == '1' ? 'الشريط الترويجي مفعل ويظهر للزوار' : 'الشريط الترويجي معطل ولا يظهر للزوار' }}
            </div>

            <!-- معاينة الشريط -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-eye me-2"></i>معاينة الشريط الترويجي</h5>
                </div>
                <div class="card-body p-0">
                    <div id="promo-preview" class="promo-bar text-white text-center py-2" style="background: linear-gradient(90deg, #5D4037 0%, #4E342E 100%);">
                        <div class="promo-text" style="font-size: 14px; font-weight: 600;">
                            {{ $promoText }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- نموذج التعديل -->
            <div class="card shadow-sm">
                <div class="card-header bg-brown text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>تعديل النص</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.promo-bar.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- تفعيل/تعطيل الشريط -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input type="hidden" name="promo_bar_enabled" value="0">
                                <input class="form-check-input" type="checkbox" name="promo_bar_enabled" 
                                       id="promo_bar_enabled" value="1" {{ $promoEnabled == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="promo_bar_enabled">
                                    تفعيل الشريط الترويجي
                                </label>
                            </div>
                            <small class="text-muted">عند إلغاء التفعيل، لن يظهر الشريط في الموقع</small>
                        </div>

                        <!-- نص الشريط -->
                        <div class="mb-4">
                            <label for="promo_bar_text" class="form-label fw-bold">
                                <i class="bi bi-text-left me-2"></i>نص الشريط الترويجي
                            </label>
                            <textarea class="form-control @error('promo_bar_text') is-invalid @enderror" 
                                      id="promo_bar_text" 
                                      name="promo_bar_text" 
                                      rows="4" 
                                      placeholder="مثال: 🎉 عرض خاص اليوم! خصم 20% على جميع الهواتف | 📱 شحن مجاني للطلبات فوق 100$ | 🎁 هدية مع كل طلب"
                                      required
                                      oninput="updatePreview(this.value)">{{ old('promo_bar_text', $promoText) }}</textarea>
                            @error('promo_bar_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                💡 نصائح: استخدم الرموز التعبيرية (🎉📱🎁) لجعل النص أكثر جاذبية، واستخدم | للفصل بين العروض المختلفة
                            </small>
                        </div>

                        <!-- أمثلة جاهزة -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-lightbulb me-2"></i>أمثلة جاهزة
                            </label>
                            <div class="list-group">
                                <button type="button" class="list-group-item list-group-item-action" onclick="setExample(this.dataset.text)" data-text="🎉 عرض خاص اليوم! خصم 20% على جميع الهواتف | 📱 شحن مجاني للطلبات فوق 100$ | 🎁 هدية مع كل طلب">
                                    🎉 عرض خاص اليوم! خصم 20% على جميع الهواتف | 📱 شحن مجاني للطلبات فوق 100$ | 🎁 هدية مع كل طلب
                                </button>
                                <button type="button" class="list-group-item list-group-item-action" onclick="setExample(this.dataset.text)" data-text="⚡ توصيل سريع خلال 24 ساعة | 💳 إمكانية الدفع عند الاستلام | 🔒 ضمان سنة على جميع المنتجات">
                                    ⚡ توصيل سريع خلال 24 ساعة | 💳 إمكانية الدفع عند الاستلام | 🔒 ضمان سنة على جميع المنتجات
                                </button>
                                <button type="button" class="list-group-item list-group-item-action" onclick="setExample(this.dataset.text)" data-text="🔥 تخفيضات نهاية الموسم حتى 50% | 📦 شحن مجاني لجميع المحافظات | ⭐ منتجات أصلية 100%">
                                    🔥 تخفيضات نهاية الموسم حتى 50% | 📦 شحن مجاني لجميع المحافظات | ⭐ منتجات أصلية 100%
                                </button>
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-brown flex-grow-1">
                                <i class="bi bi-save me-2"></i>حفظ التغييرات
                            </button>
                            <button type="reset" class="btn btn-outline-secondary" onclick="resetPreview()">
                                <i class="bi bi-arrow-clockwise"></i> إعادة تعيين
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- نصائح إضافية -->
            <div class="alert alert-info mt-4" role="alert">
                <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>نصائح لكتابة شريط ترويجي فعال:</h6>
                <ul class="mb-0 small">
                    <li>اجعل النص قصيراً ومباشراً (أقل من 500 حرف)</li>
                    <li>استخدم كلمات تحفيزية مثل: "عرض خاص"، "خصم"، "مجاني"، "حصري"</li>
                    <li>أضف رموز تعبيرية لجذب الانتباه 🎉📱🎁⚡🔥</li>
                    <li>حدد مدة العرض إذا كان محدوداً بوقت</li>
                    <li>استخدم | للفصل بين عروض متعددة</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.bg-brown {
    background: linear-gradient(135deg, #5D4037 0%, #4E342E 100%);
}
.btn-brown {
    background: linear-gradient(135deg, #5D4037 0%, #4E342E 100%);
    color: white;
    border: none;
}
.btn-brown:hover {
    background: linear-gradient(135deg, #4E342E 0%, #3E2723 100%);
    color: white;
}
.text-brown {
    color: #5D4037;
}
.list-group-item-action {
    cursor: pointer;
    transition: all 0.3s ease;
}
.list-group-item-action:hover {
    background-color: #f5ebe8;
    border-right: 3px solid #5D4037;
}
</style>

<script>
function updatePreview(text) {
    document.querySelector('#promo-preview .promo-text').textContent = text || 'معاينة النص...';
}

function setExample(text) {
    document.getElementById('promo_bar_text').value = text;
    updatePreview(text);
}

function resetPreview() {
    setTimeout(() => {
        const originalText = '{{ $promoText }}';
        updatePreview(originalText);
    }, 10);
}

// تأثير بصري عند تغيير حالة التفعيل
document.addEventListener('DOMContentLoaded', function() {
    const enableSwitch = document.getElementById('promo_bar_enabled');
    const previewBar = document.getElementById('promo-preview');
    
    if (enableSwitch && previewBar) {
        enableSwitch.addEventListener('change', function() {
            if (this.checked) {
                previewBar.style.opacity = '1';
                previewBar.style.filter = 'none';
            } else {
                previewBar.style.opacity = '0.5';
                previewBar.style.filter = 'grayscale(100%)';
            }
        });
        
        // تطبيق الحالة الأولية
        if (!enableSwitch.checked) {
            previewBar.style.opacity = '0.5';
            previewBar.style.filter = 'grayscale(100%)';
        }
    }
    
    // إظهار تأكيد عند الحفظ
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>جاري الحفظ...';
                btn.disabled = true;
            }
        });
    }
});
</script>
@endsection
