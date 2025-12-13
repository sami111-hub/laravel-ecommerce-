@extends('layout')

@section('title', 'الموقع قيد الصيانة')

@section('content')
<div class="maintenance-page" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 text-center">
                <div class="maintenance-content bg-white rounded-4 shadow-xl p-5">
                    <!-- أيقونة الصيانة -->
                    <div class="mb-4">
                        <div class="maintenance-icon">
                            <i class="bi bi-tools text-warning" style="font-size: 5rem;"></i>
                        </div>
                    </div>
                    
                    <!-- العنوان -->
                    <h1 class="h2 fw-bold mb-3">الموقع قيد الصيانة</h1>
                    
                    <!-- الوصف -->
                    <p class="text-muted mb-4">
                        نعمل حالياً على تحسين موقعنا لتقديم تجربة أفضل لك.
                        <br>سنعود قريباً بمميزات جديدة ورائعة!
                    </p>
                    
                    <!-- العد التنازلي (اختياري) -->
                    <div class="countdown-wrapper bg-light rounded-3 p-4 mb-4">
                        <p class="small text-muted mb-3">الوقت المتوقع للعودة:</p>
                        <div class="d-flex gap-3 justify-content-center">
                            <div class="time-box">
                                <div class="time-value h3 fw-bold text-primary mb-0">00</div>
                                <div class="time-label small text-muted">ساعة</div>
                            </div>
                            <div class="time-separator h3 fw-bold text-primary">:</div>
                            <div class="time-box">
                                <div class="time-value h3 fw-bold text-primary mb-0">30</div>
                                <div class="time-label small text-muted">دقيقة</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- معلومات التواصل -->
                    <div class="contact-info">
                        <p class="text-muted small mb-3">في حال الاستفسار:</p>
                        <div class="d-flex gap-4 justify-content-center">
                            @php
                                $rawPhone = env('SUPPORT_PHONE', '0777 116 668');
                                $digits = preg_replace('/\D+/', '', $rawPhone);
                                $intl = str_starts_with($digits, '967') ? $digits : ('967' . $digits);
                            @endphp
                            <a href="https://wa.me/{{ $intl }}" class="text-success text-decoration-none" target="_blank">
                                <i class="bi bi-whatsapp fs-4"></i>
                                <div class="small">واتساب</div>
                            </a>
                            <a href="tel:{{ $rawPhone }}" class="text-primary text-decoration-none">
                                <i class="bi bi-telephone fs-4"></i>
                                <div class="small">اتصال</div>
                            </a>
                            <a href="mailto:info@update-aden.com" class="text-danger text-decoration-none">
                                <i class="bi bi-envelope fs-4"></i>
                                <div class="small">بريد</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.maintenance-page {
    animation: fadeIn 0.6s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.maintenance-icon {
    animation: wrench 2s ease-in-out infinite;
}

@keyframes wrench {
    0%, 100% { transform: rotate(-15deg); }
    50% { transform: rotate(15deg); }
}

.time-box {
    background: white;
    border-radius: 8px;
    padding: 10px 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.contact-info a {
    transition: all 0.3s ease;
}

.contact-info a:hover {
    transform: translateY(-3px);
}
</style>
@endsection
