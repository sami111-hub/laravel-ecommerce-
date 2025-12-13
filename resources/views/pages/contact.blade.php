@extends('layout')

@section('title', 'اتصل بنا - Update Aden')
@section('description', 'تواصل معنا عبر الواتساب، الهاتف، أو البريد الإلكتروني - نحن هنا لمساعدتك')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold mb-3">اتصل بنا</h1>
                <p class="text-muted">نحن هنا لمساعدتك! تواصل معنا عبر الطرق التالية</p>
            </div>

            <div class="row g-4 mb-5">
                <!-- واتساب -->
                <div class="col-md-6">
                    <div class="contact-method-card p-4 border rounded-3 h-100 text-center">
                        <div class="icon-circle bg-success bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-whatsapp fs-1 text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-2">واتساب</h4>
                        <p class="text-muted mb-3">تواصل معنا مباشرة</p>
                        @php
                            $rawPhone = env('SUPPORT_PHONE', '0777 116 668');
                            $digits = preg_replace('/\D+/', '', $rawPhone);
                            $intl = str_starts_with($digits, '967') ? $digits : ('967' . $digits);
                        @endphp
                        <a href="https://wa.me/{{ $intl }}" 
                           class="btn btn-success" 
                           target="_blank">
                            <i class="bi bi-whatsapp"></i> راسلنا الآن
                        </a>
                    </div>
                </div>

                <!-- الهاتف -->
                <div class="col-md-6">
                    <div class="contact-method-card p-4 border rounded-3 h-100 text-center">
                        <div class="icon-circle bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-telephone fs-1 text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-2">الهاتف</h4>
                        <p class="text-muted mb-3">اتصل بنا مباشرة</p>
                        <a href="tel:{{ $rawPhone }}" class="btn btn-primary">
                            <i class="bi bi-telephone"></i> {{ $rawPhone }}
                        </a>
                    </div>
                </div>

                <!-- البريد الإلكتروني -->
                <div class="col-md-6">
                    <div class="contact-method-card p-4 border rounded-3 h-100 text-center">
                        <div class="icon-circle bg-danger bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-envelope fs-1 text-danger"></i>
                        </div>
                        <h4 class="fw-bold mb-2">البريد الإلكتروني</h4>
                        <p class="text-muted mb-3">راسلنا على</p>
                        <a href="mailto:info@update-aden.com" class="btn btn-danger">
                            <i class="bi bi-envelope"></i> info@update-aden.com
                        </a>
                    </div>
                </div>

                <!-- العنوان -->
                <div class="col-md-6">
                    <div class="contact-method-card p-4 border rounded-3 h-100 text-center">
                        <div class="icon-circle bg-warning bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-geo-alt fs-1 text-warning"></i>
                        </div>
                        <h4 class="fw-bold mb-2">العنوان</h4>
                        <p class="text-muted mb-3">عدن، اليمن</p>
                        <p class="small text-muted">التوصيل إلى جميع أنحاء عدن</p>
                    </div>
                </div>
            </div>

            <!-- أوقات العمل -->
            <div class="bg-light rounded-3 p-4 text-center">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-clock text-primary"></i>
                    أوقات العمل
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>السبت - الخميس:</strong></p>
                        <p class="text-muted">8:00 صباحاً - 8:00 مساءً</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>الجمعة:</strong></p>
                        <p class="text-muted">9:00 صباحاً - 12:00 ظهراً</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.contact-method-card {
    transition: all 0.3s ease;
}

.contact-method-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.icon-circle {
    transition: all 0.3s ease;
}

.contact-method-card:hover .icon-circle {
    transform: scale(1.1);
}
</style>
@endsection
