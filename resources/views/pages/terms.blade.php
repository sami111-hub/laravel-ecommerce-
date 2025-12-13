@extends('layout')

@section('title', 'شروط الاستخدام - Update Aden')
@section('description', 'شروط وأحكام استخدام متجرUpdate Aden - اقرأ الشروط قبل التسوق')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary mb-3">
                    <i class="bi bi-file-text"></i> شروط الاستخدام
                </h1>
                <p class="lead text-muted">يرجى قراءة هذه الشروط بعناية قبل استخدام الموقع</p>
                <p class="text-muted small">آخر تحديث: ديسمبر 2025</p>
            </div>

            <!-- المحتوى -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-1-circle-fill"></i> القبول بالشروط
                    </h3>
                    <p>باستخدامك لموقع <strong>Update Aden</strong> والشراء من خلاله، فإنك توافق على الالتزام بهذه الشروط والأحكام. إذا كنت لا توافق على أي جزء من هذه الشروط، يجب عليك عدم استخدام الموقع.</p>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> نحتفظ بالحق في تعديل هذه الشروط في أي وقت دون إشعار مسبق
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-2-circle-fill"></i> استخدام الموقع
                    </h3>
                    <h5 class="mb-3">يُسمح لك بـ:</h5>
                    <ul>
                        <li><i class="bi bi-check-circle text-success"></i> تصفح المنتجات والبحث عنها</li>
                        <li><i class="bi bi-check-circle text-success"></i> إنشاء حساب شخصي وإدارته</li>
                        <li><i class="bi bi-check-circle text-success"></i> إضافة منتجات للسلة والشراء</li>
                        <li><i class="bi bi-check-circle text-success"></i> مراجعة المنتجات التي اشتريتها</li>
                    </ul>

                    <h5 class="mb-3 mt-4">يُمنع عليك:</h5>
                    <ul class="text-danger">
                        <li><i class="bi bi-x-circle"></i> استخدام الموقع لأغراض غير قانونية</li>
                        <li><i class="bi bi-x-circle"></i> نسخ أو نشر محتوى الموقع دون إذن</li>
                        <li><i class="bi bi-x-circle"></i> محاولة اختراق أو تعطيل الموقع</li>
                        <li><i class="bi bi-x-circle"></i> استخدام برامج آلية (bots) للتصفح</li>
                        <li><i class="bi bi-x-circle"></i> انتحال شخصية الغير أو استخدام معلومات كاذبة</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-3-circle-fill"></i> التسجيل والحساب الشخصي
                    </h3>
                    <p><strong>عند إنشاء حساب، يجب عليك:</strong></p>
                    <ul>
                        <li>تقديم معلومات صحيحة ودقيقة</li>
                        <li>الحفاظ على سرية كلمة المرور الخاصة بك</li>
                        <li>إبلاغنا فوراً بأي استخدام غير مصرح به لحسابك</li>
                        <li>تحديث معلوماتك عند الحاجة</li>
                    </ul>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> أنت مسؤول عن جميع الأنشطة التي تحدث تحت حسابك
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-4-circle-fill"></i> الأسعار والدفع
                    </h3>
                    <ul>
                        <li><strong>الأسعار:</strong> جميع الأسعار معروضة بالدولار الأمريكي (USD) وتشمل الضرائب المحلية</li>
                        <li><strong>التغييرات:</strong> نحتفظ بالحق في تغيير الأسعار دون إشعار مسبق</li>
                        <li><strong>الأخطاء:</strong> في حالة وجود خطأ في السعر، سيتم إبلاغك وإعطاؤك خيار إلغاء الطلب</li>
                        <li><strong>الدفع:</strong> نقبل الدفع نقداً عند الاستلام والتحويلات البنكية</li>
                        <li><strong>الإلغاء:</strong> يحق لنا إلغاء الطلبات المشبوهة أو غير القانونية</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-5-circle-fill"></i> الطلبات والتوصيل
                    </h3>
                    <p><strong>عند تقديم طلب:</strong></p>
                    <ul>
                        <li>يعتبر الطلب ملزماً بمجرد تأكيده من قبلنا</li>
                        <li>نبذل قصارى جهدنا للتوصيل في المواعيد المحددة</li>
                        <li>قد تتأخر الطلبات بسبب ظروف خارجة عن إرادتنا</li>
                        <li>يجب فحص المنتج عند الاستلام قبل التوقيع</li>
                        <li>لا نتحمل مسؤولية التأخير بسبب معلومات توصيل خاطئة</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-6-circle-fill"></i> الضمان والمنتجات
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success"><i class="bi bi-check-circle-fill"></i> نضمن:</h6>
                            <ul>
                                <li>أصالة جميع المنتجات</li>
                                <li>خلو المنتجات من العيوب المصنعية</li>
                                <li>توفير ضمان الوكيل المعتمد</li>
                                <li>فحص المنتجات قبل الشحن</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-danger"><i class="bi bi-x-circle-fill"></i> لا نضمن:</h6>
                            <ul>
                                <li>الأضرار الناتجة عن سوء الاستخدام</li>
                                <li>الكسر أو التلف المتعمد</li>
                                <li>التعرض للماء (إلا إذا كان مقاوماً)</li>
                                <li>الصدمات أو السقوط</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-7-circle-fill"></i> حقوق الملكية الفكرية
                    </h3>
                    <p>جميع المحتويات على الموقع بما في ذلك:</p>
                    <ul>
                        <li>النصوص والصور والشعارات</li>
                        <li>التصاميم والرسومات</li>
                        <li>الأكواد البرمجية</li>
                        <li>قواعد البيانات</li>
                    </ul>
                    <p class="mb-0"><strong>محمية بحقوق الطبع والنشر © 2025 Update Aden</strong></p>
                    <p class="text-muted small">يُمنع النسخ أو إعادة النشر دون إذن كتابي</p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-8-circle-fill"></i> الخصوصية وحماية البيانات
                    </h3>
                    <p>نلتزم بحماية خصوصيتك:</p>
                    <ul>
                        <li><i class="bi bi-shield-check text-success"></i> نجمع فقط المعلومات الضرورية</li>
                        <li><i class="bi bi-shield-check text-success"></i> نستخدم تشفير SSL لحماية البيانات</li>
                        <li><i class="bi bi-shield-check text-success"></i> لا نبيع معلوماتك لأطراف ثالثة</li>
                        <li><i class="bi bi-shield-check text-success"></i> يمكنك طلب حذف بياناتك في أي وقت</li>
                    </ul>
                    <p class="mb-0 text-muted small">للمزيد، راجع <a href="{{ route('privacy') }}">سياسة الخصوصية</a></p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-9-circle-fill"></i> المراجعات والتعليقات
                    </h3>
                    <p>عند كتابة مراجعة أو تعليق:</p>
                    <ul>
                        <li>يجب أن يكون المحتوى صادقاً وغير مضلل</li>
                        <li>ممنوع الألفاظ البذيئة أو المسيئة</li>
                        <li>ممنوع الإعلانات أو الروابط الخارجية</li>
                        <li>نحتفظ بالحق في حذف المراجعات غير اللائقة</li>
                        <li>المراجعات تعبر عن رأي الكاتب وليس الموقع</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4 bg-danger bg-opacity-10">
                <div class="card-body p-4">
                    <h3 class="text-danger mb-4">
                        <i class="bi bi-exclamation-octagon-fill"></i> إخلاء المسؤولية
                    </h3>
                    <p><strong>الموقع يُقدم "كما هو" بدون أي ضمانات:</strong></p>
                    <ul>
                        <li>لا نضمن خلو الموقع من الأخطاء أو الانقطاعات</li>
                        <li>لا نتحمل مسؤولية الأضرار الناتجة عن استخدام الموقع</li>
                        <li>لا نضمن توفر جميع المنتجات في كل الأوقات</li>
                        <li>لا نتحمل مسؤولية محتوى الروابط الخارجية</li>
                    </ul>
                    <div class="alert alert-danger mt-3 mb-0">
                        <strong>تحديد المسؤولية:</strong> مسؤوليتنا القصوى تقتصر على قيمة المنتج المشترى فقط
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-info-circle-fill"></i> القانون الواجب التطبيق
                    </h3>
                    <p>تخضع هذه الشروط لقوانين <strong>الجمهورية اليمنية</strong></p>
                    <p>أي نزاع ناتج عن استخدام الموقع يتم حله عبر:</p>
                    <ol>
                        <li>التفاوض الودي مع فريق خدمة العملاء</li>
                        <li>الوساطة عند الضرورة</li>
                        <li>المحاكم المختصة في عدن كملاذ أخير</li>
                    </ol>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-telephone-fill"></i> التواصل معنا
                    </h3>
                    <p>لأي استفسارات حول الشروط والأحكام:</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <i class="bi bi-whatsapp text-success fs-4"></i>
                                <strong class="d-block mt-2">واتساب</strong>
                                <a href="https://wa.me/967777116668">0777 116 668</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <i class="bi bi-geo-alt text-danger fs-4"></i>
                                <strong class="d-block mt-2">العنوان</strong>
                                <span class="text-muted small">عدن، اليمن</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="card bg-gradient-primary text-white">
                <div class="card-body text-center p-4">
                    <h4 class="mb-3">بقبولك لهذه الشروط، نتمنى لك تجربة تسوق ممتعة</h4>
                    <a href="{{ route('home') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-shop"></i> ابدأ التسوق الآن
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    border: none;
}

h3 i {
    font-size: 1.2em;
    margin-left: 10px;
}
</style>
@endsection
