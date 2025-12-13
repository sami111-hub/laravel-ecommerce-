@extends('layout')

@section('title', 'سياسة الإرجاع والاسترجاع - Update Aden')
@section('description', 'تعرف على سياسة الإرجاع والاستبدال في Update Aden - ضمان 14 يوم لرضاك الكامل')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary mb-3">
                    <i class="bi bi-arrow-left-right"></i> سياسة الإرجاع والاسترجاع
                </h1>
                <p class="lead text-muted">نلتزم بتوفير تجربة تسوق مريحة وآمنة لعملائنا</p>
            </div>

            <!-- محتوى السياسة -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-clock-history"></i> المدة الزمنية للإرجاع
                    </h3>
                    <p class="lead">يمكنك إرجاع أو استبدال المنتجات خلال <strong class="text-danger">7 أيام</strong> من تاريخ الاستلام.</p>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill"></i> 
                        يبدأ احتساب المدة من يوم استلامك للمنتج وليس من يوم الطلب
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-check2-circle"></i> شروط قبول الإرجاع
                    </h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                <div>
                                    <h6>المنتج بحالة جيدة</h6>
                                    <p class="text-muted small mb-0">المنتج غير مستخدم وفي حالته الأصلية</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                <div>
                                    <h6>العلبة الأصلية كاملة</h6>
                                    <p class="text-muted small mb-0">مع جميع الملحقات والمستندات</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                <div>
                                    <h6>الفاتورة الأصلية</h6>
                                    <p class="text-muted small mb-0">احتفظ بالفاتورة لإتمام عملية الإرجاع</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                <div>
                                    <h6>بدون خدوش أو كسر</h6>
                                    <p class="text-muted small mb-0">المنتج خالٍ من أي أضرار</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-x-circle"></i> حالات عدم قبول الإرجاع
                    </h3>
                    <div class="list-group">
                        <div class="list-group-item">
                            <i class="bi bi-x-circle-fill text-danger me-2"></i>
                            <strong>المنتجات المستخدمة:</strong> المنتجات التي تم استخدامها أو تشغيلها بشكل واضح
                        </div>
                        <div class="list-group-item">
                            <i class="bi bi-x-circle-fill text-danger me-2"></i>
                            <strong>العلبة التالفة:</strong> المنتجات التي فُقدت علبتها أو تلفت بشكل كبير
                        </div>
                        <div class="list-group-item">
                            <i class="bi bi-x-circle-fill text-danger me-2"></i>
                            <strong>الملحقات الناقصة:</strong> المنتجات التي فقدت أي من ملحقاتها الأصلية
                        </div>
                        <div class="list-group-item">
                            <i class="bi bi-x-circle-fill text-danger me-2"></i>
                            <strong>العروض الخاصة:</strong> بعض المنتجات في العروض الترويجية (يُذكر عند الشراء)
                        </div>
                        <div class="list-group-item">
                            <i class="bi bi-x-circle-fill text-danger me-2"></i>
                            <strong>الإكسسوارات المفتوحة:</strong> سماعات الأذن والملحقات الشخصية لأسباب صحية
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-list-ol"></i> خطوات الإرجاع
                    </h3>
                    <div class="timeline">
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">1</div>
                            <div class="timeline-content ms-3">
                                <h5>تواصل معنا</h5>
                                <p>اتصل بنا عبر الواتساب <a href="https://wa.me/967777116668">0777 116 668</a> أو الهاتف</p>
                            </div>
                        </div>
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">2</div>
                            <div class="timeline-content ms-3">
                                <h5>أخبرنا بالسبب</h5>
                                <p>اذكر سبب الإرجاع وأرسل صوراً واضحة للمنتج والعلبة</p>
                            </div>
                        </div>
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">3</div>
                            <div class="timeline-content ms-3">
                                <h5>موافقة الإرجاع</h5>
                                <p>سنراجع طلبك ونوافق عليه خلال 24 ساعة</p>
                            </div>
                        </div>
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">4</div>
                            <div class="timeline-content ms-3">
                                <h5>إعادة المنتج</h5>
                                <p>يمكنك إحضار المنتج لفرعنا أو سنرسل مندوب لاستلامه (حسب موقعك)</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">5</div>
                            <div class="timeline-content ms-3">
                                <h5>استرجاع المبلغ</h5>
                                <p>بعد فحص المنتج، سيتم استرجاع المبلغ خلال 3-5 أيام عمل</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-credit-card"></i> طرق استرجاع المبلغ
                    </h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card border-primary h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-cash-coin text-primary" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">نقدي</h5>
                                    <p class="text-muted small">إذا كان الدفع نقداً عند الاستلام</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-primary h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-bank text-primary" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">تحويل بنكي</h5>
                                    <p class="text-muted small">إلى حسابك البنكي المسجل</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-primary h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-wallet2 text-primary" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">محفظة إلكترونية</h5>
                                    <p class="text-muted small">موبايل موني أو أي محفظة أخرى</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-arrow-repeat"></i> الاستبدال
                    </h3>
                    <p>إذا كنت ترغب في استبدال المنتج بدلاً من الإرجاع:</p>
                    <ul>
                        <li>يمكنك استبدال المنتج بآخر من نفس الفئة السعرية</li>
                        <li>إذا كان المنتج الجديد أغلى، ادفع الفرق</li>
                        <li>إذا كان أرخص، سنرد لك الفرق</li>
                        <li>عملية الاستبدال أسرع من الإرجاع (1-2 يوم)</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4 bg-warning bg-opacity-10">
                <div class="card-body p-4">
                    <h3 class="text-warning mb-4">
                        <i class="bi bi-exclamation-triangle-fill"></i> ملاحظات هامة
                    </h3>
                    <ul class="mb-0">
                        <li>تكاليف الشحن للإرجاع قد تُخصم من المبلغ المسترجع (حسب سبب الإرجاع)</li>
                        <li>إذا كان المنتج معيباً أو تالفاً عند الاستلام، نتحمل كافة التكاليف</li>
                        <li>لا نقبل إرجاع المنتجات التي تم شراؤها من مصادر أخرى</li>
                        <li>بعض المنتجات قد يكون لها سياسة إرجاع خاصة (يُذكر عند الشراء)</li>
                    </ul>
                </div>
            </div>

            <!-- CTA -->
            <div class="card bg-gradient-primary text-white text-center">
                <div class="card-body p-4">
                    <h4 class="mb-3">هل لديك استفسار حول سياسة الإرجاع؟</h4>
                    <p class="mb-3">فريقنا جاهز لمساعدتك ويسعدنا الإجابة على أسئلتك</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="https://wa.me/967777116668" class="btn btn-light">
                            <i class="bi bi-whatsapp"></i> واتساب: 0777 116 668
                        </a>
                        <a href="{{ route('faq') }}" class="btn btn-outline-light">
                            <i class="bi bi-question-circle"></i> الأسئلة الشائعة
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.timeline-item {
    position: relative;
    padding-left: 60px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 19px;
    top: 40px;
    width: 2px;
    height: calc(100% - 20px);
    background: linear-gradient(180deg, #667eea 0%, #dee2e6 100%);
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    font-weight: bold;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
