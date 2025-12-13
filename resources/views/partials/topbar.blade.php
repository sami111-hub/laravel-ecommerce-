<div class="topbar bg-primary text-white small py-2" dir="rtl">
    <div class="container d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-truck"></i>
            <span>التوصيل إلى جميع أنحاء عدن</span>
        </div>
        @php
            $rawPhoneTop = env('SUPPORT_PHONE', '0777 116 668');
            $digitsTop = preg_replace('/\D+/', '', $rawPhoneTop);
            $intlTop = str_starts_with($digitsTop, '967') ? $digitsTop : ('967' . $digitsTop);
        @endphp
        <div class="d-none d-md-flex gap-3">
            <a href="mailto:adenupdate@gmail.com" class="text-decoration-none text-white">
                <i class="bi bi-envelope-fill"></i> adenupdate@gmail.com
            </a>
            <a href="tel:+{{ $intlTop }}" class="text-decoration-none text-white">
                <i class="bi bi-telephone-fill"></i> {{ $rawPhoneTop }}
            </a>
            <a href="{{ route('products.index') }}" class="text-decoration-none text-white">
                <i class="bi bi-fire"></i> عروض اليوم
            </a>
        </div>
    </div>
</div>
