<section class="support-footer py-3" dir="rtl">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-4">
                @php
                    $rawPhone = env('SUPPORT_PHONE', '0777 116 668');
                    $digits = preg_replace('/\D+/', '', $rawPhone);
                    $intl = str_starts_with($digits, '967') ? $digits : ('967' . $digits);
                @endphp
                <p class="text-muted mb-1">التواصل عبر الهاتف</p>
                <a href="tel:+{{ $intl }}" class="contact-big-link" aria-label="اتصال">
                    <span class="contact-big">{{ $rawPhone }}</span>
                </a>
            </div>
            <div class="col-lg-4 d-flex justify-content-lg-center">
                @php
                    // WhatsApp number with country code, no plus signs or spaces.
                    $waNumber = env('WHATSAPP_NUMBER', '967777116668');
                    $defaultText = rawurlencode('مرحبا، أحتاج مساعدة.');
                    $waFallback = "https://wa.me/{$waNumber}?text={$defaultText}";
                    $waLink = env('WHATSAPP_LINK', $waFallback);
                @endphp
                <a href="{{ $waLink }}" target="_blank" rel="noopener" class="cta-whatsapp-btn">ابدأ الآن</a>
            </div>
            <div class="col-lg-4">
                <h2 class="h3 fw-bold mb-1">جاهزون دائمًا لمساعدتك</h2>
                <p class="text-muted m-0">تواصل معنا عبر أي قناة من قنوات الدعم التالية</p>
            </div>
        </div>

        <hr class="my-3">

        <div class="row gy-4">
            <div class="col-md-3">
                <h5 class="mb-3">خلّيك متصل فيك</h5>
                <div class="d-flex gap-3 fs-4">
                    <a href="https://twitter.com/AlmMry32267" target="_blank" rel="noopener" class="text-muted hover-primary" aria-label="Twitter">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=100065092428355" target="_blank" rel="noopener" class="text-muted hover-primary" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="mailto:adenupdate@gmail.com" class="text-muted hover-primary" aria-label="Email"><i class="bi bi-envelope-fill"></i></a>
                    <a href="https://wa.me/{{ $intl }}" target="_blank" rel="noopener" class="text-muted hover-primary" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                </div>
                <div class="mt-3">
                    <p class="mb-2" style="font-size: 14px; color: #166534; font-weight: 600;">
                        <i class="bi bi-envelope-at-fill"></i> البريد الإلكتروني:
                    </p>
                    <a href="mailto:adenupdate@gmail.com" style="color: #2BB673; font-weight: 700; text-decoration: none; font-size: 15px;">
                        adenupdate@gmail.com
                    </a>
                </div>
                <div class="mt-3">
                    <p class="text-muted small mb-0">
                        <i class="bi bi-geo-alt-fill"></i> عدن، اليمن
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <h5 class="mb-3">خدمات الدفع</h5>
                <ul class="list-unstyled m-0 support-links">
                    <li><i class="bi bi-credit-card-2-front me-1"></i> بطاقات بنكية</li>
                    <li><i class="bi bi-wallet2 me-1"></i> محافظ إلكترونية</li>
                    <li><i class="bi bi-shield-check me-1"></i> دفع آمن</li>
                </ul>
            </div>

            <div class="col-md-3">
                <h5 class="mb-3">خدمة العملاء</h5>
                <ul class="list-unstyled m-0 support-links">
                    <li><a href="{{ route('faq') }}">الأسئلة الشائعة</a></li>
                    <li><a href="{{ route('return-policy') }}">سياسة الإرجاع</a></li>
                    <li><a href="{{ route('terms') }}">شروط الاستخدام</a></li>
                    <li><a href="{{ route('about') }}">من نحن</a></li>
                </ul>
            </div>

            <div class="col-md-3">
                <h5 class="mb-3">حسابي</h5>
                <ul class="list-unstyled m-0 support-links">
                    @auth
                        <li><a href="{{ route('dashboard.index') }}">لوحة التحكم</a></li>
                        <li><a href="{{ route('orders.index') }}">طلباتي</a></li>
                    @else
                        <li><a href="{{ route('login') }}">تسجيل الدخول</a></li>
                        <li><a href="{{ route('register') }}">إنشاء حساب</a></li>
                    @endauth
                    <li><a href="{{ route('cart.index') }}">سلة التسوق</a></li>
                    <li><a href="{{ route('products.index') }}">تصفح المنتجات</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2 mt-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:28px;">
                    <small class="text-muted">متجر Update Aden</small>
                </div>
            </div>
        </div>
    </div>
</section>
