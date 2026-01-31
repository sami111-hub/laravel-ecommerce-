{{-- Hero Slider الخرافي --}}
<div class="hero-slider-container">
    <div class="hero-slider">
        {{-- Slide 1: عروض التكنولوجيا --}}
        <div class="hero-slide active">
            <div class="hero-background" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="hero-pattern"></div>
            </div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 hero-content">
                        <span class="hero-badge">
                            <i class="bi bi-lightning-charge-fill"></i>
                            عرض محدود
                        </span>
                        <h1 class="hero-title">
                            أحدث تقنيات<br>
                            <span class="hero-highlight">الإلكترونيات</span>
                        </h1>
                        <p class="hero-description">
                            خصم يصل إلى <strong>40%</strong> على الهواتف الذكية واللابتوبات والساعات
                        </p>
                        <div class="hero-actions">
                            <a href="{{ route('products.index') }}" class="hero-btn hero-btn-primary">
                                <span>تسوق الآن</span>
                                <i class="bi bi-arrow-left"></i>
                            </a>
                            <a href="#categories" class="hero-btn hero-btn-outline">
                                <span>تصفح الأقسام</span>
                            </a>
                        </div>
                        <div class="hero-features">
                            <div class="feature-item">
                                <i class="bi bi-truck-fill"></i>
                                <span>شحن مجاني</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-shield-check-fill"></i>
                                <span>ضمان أصلي</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-credit-card-fill"></i>
                                <span>دفع آمن</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 hero-image">
                        <div class="hero-product-showcase">
                            <div class="showcase-image" style="width: 100%; height: 450px; display: flex; align-items: center; justify-content: center; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                                <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=600&fit=crop&q=80" 
                                     alt="لابتوبات وأجهزة تقنية متطورة" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="floating-badge badge-1">
                                <div class="badge-content">
                                    <span class="badge-discount">40%</span>
                                    <span class="badge-text">خصم</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 2: الهواتف الذكية --}}
        <div class="hero-slide">
            <div class="hero-background" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="hero-pattern"></div>
            </div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 hero-content">
                        <span class="hero-badge">
                            <i class="bi bi-phone-fill"></i>
                            جديد 2025
                        </span>
                        <h1 class="hero-title">
                            هواتف ذكية<br>
                            <span class="hero-highlight">متطورة</span>
                        </h1>
                        <p class="hero-description">
                            أحدث الهواتف الذكية من Apple, Samsung, Xiaomi بأفضل الأسعار
                        </p>
                        <div class="hero-actions">
                            <a href="{{ route('products.index', ['category' => 'smartphones']) }}" class="hero-btn hero-btn-primary">
                                <span>تسوق الآن</span>
                                <i class="bi bi-arrow-left"></i>
                            </a>
                            <a href="#new-arrivals" class="hero-btn hero-btn-outline">
                                <span>الأجهزة الجديدة</span>
                            </a>
                        </div>
                        <div class="hero-features">
                            <div class="feature-item">
                                <i class="bi bi-patch-check-fill"></i>
                                <span>ضمان الوكيل</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-box-seam-fill"></i>
                                <span>توصيل سريع</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-credit-card-fill"></i>
                                <span>دفع عند الاستلام</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 hero-image">
                        <div class="hero-product-showcase">
                            <div class="showcase-image" style="width: 100%; height: 450px; display: flex; align-items: center; justify-content: center; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                                <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=600&fit=crop&q=80" 
                                     alt="أحدث الهواتف الذكية 2025" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="floating-badge badge-2">
                                <div class="badge-content">
                                    <i class="bi bi-star-fill"></i>
                                    <span class="badge-text">الأكثر مبيعاً</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 3: اللابتوبات والأجهزة --}}
        <div class="hero-slide">
            <div class="hero-background" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="hero-pattern"></div>
            </div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 hero-content">
                        <span class="hero-badge">
                            <i class="bi bi-laptop-fill"></i>
                            أجهزة قوية
                        </span>
                        <h1 class="hero-title">
                            المنزل<br>
                            لابتوبات وأجهزة<br>
                            <span class="hero-highlight">احترافية</span>
                        </h1>
                        <p class="hero-description">
                            أقوى أجهزة اللابتوب من Dell, HP, Lenovo, MacBook لجميع الاستخدامات
                        </p>
                        <div class="hero-actions">
                            <a href="{{ route('products.index', ['category' => 'laptops']) }}" class="hero-btn hero-btn-primary">
                                <span>تسوق الآن</span>
                                <i class="bi bi-arrow-left"></i>
                            </a>
                            <a href="#best-sellers" class="hero-btn hero-btn-outline">
                                <span>الأكثر مبيعاً</span>
                            </a>
                        </div>
                        <div class="hero-features">
                            <div class="feature-item">
                                <i class="bi bi-cpu-fill"></i>
                                <span>معالجات قوية</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-lightning-charge-fill"></i>
                                <span>أداء عالي</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-shield-fill-check"></i>
                                <span>ضمان شامل</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 hero-image">
                        <div class="hero-product-showcase">
                            <div class="showcase-image" style="width: 100%; height: 450px; display: flex; align-items: center; justify-content: center; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                                <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&h=600&fit=crop&q=80" 
                                     alt="لابتوبات احترافية عالية الأداء" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="floating-badge badge-3">
                                <div class="badge-content">
                                    <span class="badge-discount">25%</span>
                                    <span class="badge-text">خصم</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Controls --}}
    <div class="hero-nav-controls">
        <button class="hero-nav-btn prev" onclick="heroSlider.prev()">
            <i class="bi bi-chevron-right"></i>
        </button>
        <div class="hero-dots">
            <span class="hero-dot active" onclick="heroSlider.goTo(0)"></span>
            <span class="hero-dot" onclick="heroSlider.goTo(1)"></span>
            <span class="hero-dot" onclick="heroSlider.goTo(2)"></span>
        </div>
        <button class="hero-nav-btn next" onclick="heroSlider.next()">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>
</div>
