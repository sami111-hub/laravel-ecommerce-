@extends('layout')

@section('title', 'من نحن - Update Aden')
@section('description', 'تعرف على Update Aden - وجهتك الأولى للتكنولوجيا في عدن - توفير أحدث التقنيات بأفضل الأسعار')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10 text-center">
            <h1 class="display-4 fw-bold text-primary mb-3">
                🏪 من نحن
            </h1>
            <p class="lead text-muted">رائدون في مجال التقنية والإلكترونيات في اليمن</p>
        </div>
    </div>

    <!-- Story Section -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    <i class="bi bi-star-fill"></i> قصتنا
                                </span>
                            </div>
                            <h2 class="fw-bold mb-4">Update Aden<br>وجهتك الأولى للتكنولوجيا</h2>
                            <p class="text-muted mb-4">
                                منذ انطلاقتنا في قلب مدينة <strong class="text-primary">عدن</strong>، كان هدفنا واضحاً: توفير أحدث التقنيات والأجهزة الإلكترونية بأفضل الأسعار وأعلى معايير الجودة.
                            </p>
                            <p class="text-muted mb-4">
                                نؤمن بأن التكنولوجيا يجب أن تكون في متناول الجميع، لذلك نسعى جاهدين لتقديم تجربة تسوق استثنائية تجمع بين الجودة، السعر المناسب، والخدمة الممتازة.
                            </p>
                            <div class="d-flex gap-3">
                                <div class="text-center">
                                    <h3 class="text-primary fw-bold mb-0">5+</h3>
                                    <small class="text-muted">سنوات خبرة</small>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-primary fw-bold mb-0">10K+</h3>
                                    <small class="text-muted">عميل سعيد</small>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-primary fw-bold mb-0">100%</h3>
                                    <small class="text-muted">منتجات أصلية</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative">
                                <div class="ratio ratio-1x1 rounded-4 overflow-hidden shadow">
                                    <img src="https://images.unsplash.com/photo-1601524909162-ae8725290836?w=600&h=600&fit=crop&q=80" 
                                         alt="Update Aden - متجر إلكترونيات وتقنية عدن" 
                                         class="object-fit-cover">
                                </div>
                                <div class="position-absolute top-0 end-0 m-3 bg-white rounded-3 shadow p-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <div>
                                            <strong class="d-block">4.9/5</strong>
                                            <small class="text-muted">تقييم العملاء</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">قيمنا ومبادئنا</h2>
                <p class="text-muted">ما يميزنا عن الآخرين</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="rounded-3 overflow-hidden shadow-sm mb-2">
                                    <img src="https://images.unsplash.com/photo-1556656793-08538906a9f8?w=400&h=250&fit=crop&q=80" 
                                         alt="منتجات أصلية معتمدة" 
                                         class="img-fluid" 
                                         style="height: 180px; object-fit: cover; width: 100%;">
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3">الجودة والأصالة</h4>
                            <p class="text-muted mb-0">جميع منتجاتنا أصلية 100% ومستوردة من مصادر موثوقة مع ضمان الوكيل المعتمد</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="rounded-3 overflow-hidden shadow-sm mb-2">
                                    <img src="https://images.unsplash.com/photo-1607082350899-7e105aa886ae?w=400&h=250&fit=crop&q=80" 
                                         alt="أسعار تنافسية وعروض مميزة" 
                                         class="img-fluid" 
                                         style="height: 180px; object-fit: cover; width: 100%;">
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3">أسعار تنافسية</h4>
                            <p class="text-muted mb-0">نقدم أفضل الأسعار في السوق اليمني مع عروض وخصومات مستمرة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="rounded-3 overflow-hidden shadow-sm mb-2">
                                    <img src="https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?w=400&h=250&fit=crop&q=80" 
                                         alt="دعم فني 24/7" 
                                         class="img-fluid" 
                                         style="height: 180px; object-fit: cover; width: 100%;">
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3">دعم فني ممتاز</h4>
                            <p class="text-muted mb-0">فريق دعم متخصص جاهز لمساعدتك قبل وبعد الشراء على مدار الساعة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="mb-4 rounded-3 overflow-hidden shadow">
                                <img src="https://images.unsplash.com/photo-1580910051074-3eb694886505?w=800&h=400&fit=crop&q=80" 
                                     alt="مجموعة منتجاتنا التقنية" 
                                     class="img-fluid" 
                                     style="width: 100%; height: 250px; object-fit: cover;">
                            </div>
                            <h3 class="fw-bold mb-4">
                                <i class="bi bi-box-seam text-primary"></i> منتجاتنا
                            </h3>
                            <p class="text-muted mb-4">نوفر مجموعة واسعة من أحدث المنتجات التقنية:</p></p>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-phone text-primary"></i>
                                        <span>هواتف ذكية</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-laptop text-primary"></i>
                                        <span>لابتوبات</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-smartwatch text-primary"></i>
                                        <span>ساعات ذكية</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-headphones text-primary"></i>
                                        <span>سماعات</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-tablet text-primary"></i>
                                        <span>تابلت</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-plug text-primary"></i>
                                        <span>إكسسوارات</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4 rounded-3 overflow-hidden shadow">
                                <img src="https://images.unsplash.com/photo-1607252650355-f7fd0460ccdb?w=800&h=400&fit=crop&q=80" 
                                     alt="أشهر العلامات التجارية العالمية" 
                                     class="img-fluid" 
                                     style="width: 100%; height: 250px; object-fit: cover;">
                            </div>
                            <h3 class="fw-bold mb-4">
                                <i class="bi bi-trophy text-warning"></i> العلامات التجارية
                            </h3>
                            <p class="text-muted mb-4">نتعامل مع أشهر وأفضل العلامات العالمية:</p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-light text-dark border px-3 py-2">Apple</span>
                                <span class="badge bg-light text-dark border px-3 py-2">Samsung</span>
                                <span class="badge bg-light text-dark border px-3 py-2">Xiaomi</span>
                                <span class="badge bg-light text-dark border px-3 py-2">Huawei</span>
                                <span class="badge bg-light text-dark border px-3 py-2">Oppo</span>
                                <span class="badge bg-light text-dark border px-3 py-2">Realme</span>
                                <span class="badge bg-light text-dark border px-3 py-2">HP</span>
                                <span class="badge bg-light text-dark border px-3 py-2">Dell</span>
                                <span class="badge bg-light text-dark border px-3 py-2">Lenovo</span>
                                <span class="badge bg-light text-dark border px-3 py-2">Asus</span>
                                <span class="badge bg-light text-dark border px-3 py-2">Sony</span>
                                <span class="badge bg-light text-dark border px-3 py-2">JBL</span>
                                <span class="badge bg-success text-white border px-3 py-2">Anker SoundCore</span>
                                <span class="badge bg-danger text-white border px-3 py-2">ZTE Redmagic</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg overflow-hidden" style="position: relative;">
                <div class="position-relative" style="background: linear-gradient(135deg, rgba(43, 182, 115, 0.95) 0%, rgba(30, 160, 94, 0.95) 100%), url('https://images.unsplash.com/photo-1556656793-08538906a9f8?w=1200&h=600&fit=crop&q=80'); background-size: cover; background-position: center;">
                    <div class="card-body p-5 text-white">
                        <h2 class="fw-bold text-center mb-5">لماذا تختار Update Aden؟</h2>
                        <div class="row g-4">
                            <div class="col-md-3 col-6">
                                <div class="text-center">
                                    <i class="bi bi-truck fs-1 mb-3"></i>
                                    <h5 class="fw-bold">التوصيل إلى جميع أنحاء عدن</h5>
                                    <p class="mb-0 opacity-75 small">داخل عدن خلال 24 ساعة</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center">
                                    <i class="bi bi-shield-check fs-1 mb-3"></i>
                                    <h5 class="fw-bold">ضمان شامل</h5>
                                    <p class="mb-0 opacity-75 small">على جميع المنتجات</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center">
                                    <i class="bi bi-arrow-left-right fs-1 mb-3"></i>
                                    <h5 class="fw-bold">استرجاع سهل</h5>
                                    <p class="mb-0 opacity-75 small">خلال 7 أيام</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center">
                                    <i class="bi bi-cash-coin fs-1 mb-3"></i>
                                    <h5 class="fw-bold">دفع آمن</h5>
                                    <p class="mb-0 opacity-75 small">طرق دفع متعددة</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h3 class="fw-bold mb-4">
                                <i class="bi bi-geo-alt-fill text-danger"></i> موقعنا
                            </h3>
                            <p class="text-muted mb-3">
                                <i class="bi bi-geo-alt text-primary"></i> 
                                <strong>العنوان:</strong> عدن، اليمن
                            </p>
                            <p class="text-muted mb-3">
                                <i class="bi bi-telephone text-primary"></i> 
                                <strong>الهاتف:</strong> <a href="tel:+967780800007">0780 800 007</a>
                            </p>
                            <p class="text-muted mb-3">
                                <i class="bi bi-whatsapp text-primary"></i> 
                                <strong>واتساب:</strong> <a href="https://wa.me/967780800007">0780 800 007</a>
                            </p>
                            <p class="text-muted mb-4">
                                <i class="bi bi-envelope-fill text-primary"></i> 
                                <strong>البريد الإلكتروني:</strong> <a href="mailto:adenupdate@gmail.com">adenupdate@gmail.com</a>
                            </p>
                            <p class="text-muted mb-4">
                                <i class="bi bi-clock text-primary"></i> 
                                <strong>ساعات العمل:</strong><br>
                                السبت - الخميس: 9:00 ص - 9:00 م<br>
                                الجمعة: مغلق
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="rounded-3 overflow-hidden shadow mb-3">
                                <img src="https://images.unsplash.com/photo-1423666639041-f56000c27a9a?w=600&h=400&fit=crop&q=80" 
                                     alt="تواصل معنا في Update Aden" 
                                     class="img-fluid" 
                                     style="width: 100%; height: 300px; object-fit: cover;">
                            </div>
                            <h3 class="fw-bold mb-4">
                                <i class="bi bi-envelope-fill text-primary"></i> تواصل معنا
                            </h3>
                            <p class="text-muted mb-4">لأي استفسارات أو اقتراحات، نحن هنا لخدمتك</p>
                            <div class="d-grid gap-2">
                                <a href="https://wa.me/967780800007" class="btn btn-success btn-lg">
                                    <i class="bi bi-whatsapp"></i> راسلنا على واتساب: 0780 800 007
                                </a>
                                <a href="tel:+967780800007" class="btn btn-outline-primary btn-lg">
                                    <i class="bi bi-telephone"></i> اتصل بنا: 0780 800 007
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Final CTA -->
    <div class="row justify-content-center mt-5">
        <div class="col-lg-10">
            <div class="text-center bg-light rounded-4 p-5">
                <h2 class="fw-bold mb-3">جاهز لتجربة تسوق استثنائية؟</h2>
                <p class="text-muted mb-4">اكتشف أحدث العروض والمنتجات على موقعنا</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-shop"></i> تصفح المنتجات
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    border-radius: 1rem;
}
</style>
@endsection
