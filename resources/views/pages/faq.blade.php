@extends('layout')

@section('title', 'الأسئلة الشائعة - Update Aden')
@section('description', 'أجوبة على الأسئلة الشائعة حول التسوق، التوصيل، الدفع، والضمان في Update Aden')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary mb-3">
                    <i class="bi bi-question-circle-fill"></i> الأسئلة الشائعة
                </h1>
                <p class="lead text-muted">إجابات على أكثر الأسئلة شيوعاً حول Update Aden</p>
            </div>

            <!-- Accordion -->
            <div class="accordion" id="faqAccordion">
                
                <!-- السؤال 1 -->
                <div class="accordion-item mb-3 border-0 shadow-sm">
                    <h2 class="accordion-header" id="faq1">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                            <i class="bi bi-truck me-2 text-primary"></i>
                            ما هي مدة التوصيل؟
                        </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="faq1">
                        <div class="accordion-body">
                            <p>مدة التوصيل تعتمد على موقعك:</p>
                            <ul>
                                <li><strong>داخل عدن:</strong> 1-2 يوم عمل</li>
                                <li><strong>المحافظات القريبة:</strong> 2-4 أيام عمل</li>
                                <li><strong>باقي المحافظات:</strong> 4-7 أيام عمل</li>
                            </ul>
                            <p class="mb-0 text-muted"><small>* يتم حساب أيام العمل من السبت إلى الخميس</small></p>
                        </div>
                    </div>
                </div>

                <!-- السؤال 2 -->
                <div class="accordion-item mb-3 border-0 shadow-sm">
                    <h2 class="accordion-header" id="faq2">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                            <i class="bi bi-credit-card me-2 text-primary"></i>
                            ما هي طرق الدفع المتاحة؟
                        </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2">
                        <div class="accordion-body">
                            <p>نوفر عدة طرق للدفع:</p>
                            <ul>
                                <li><i class="bi bi-cash-coin text-success"></i> <strong>الدفع عند الاستلام:</strong> ادفع نقداً عند وصول الطلب</li>
                                <li><i class="bi bi-phone text-primary"></i> <strong>التحويل البنكي:</strong> عبر الحوالات المحلية</li>
                                <li><i class="bi bi-wallet2 text-warning"></i> <strong>المحافظ الإلكترونية:</strong> موبايل موني وغيرها</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- السؤال 3 -->
                <div class="accordion-item mb-3 border-0 shadow-sm">
                    <h2 class="accordion-header" id="faq3">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                            <i class="bi bi-shield-check me-2 text-primary"></i>
                            هل المنتجات أصلية ومضمونة؟
                        </button>
                    </h2>
                    <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3">
                        <div class="accordion-body">
                            <p>نعم، جميع منتجاتنا <strong>أصلية 100%</strong> ومستوردة من مصادر موثوقة.</p>
                            <ul>
                                <li>✅ ضمان الوكيل المعتمد على جميع المنتجات</li>
                                <li>✅ إمكانية فحص المنتج قبل الاستلام</li>
                                <li>✅ فاتورة رسمية مع كل عملية شراء</li>
                                <li>✅ ضمان استرجاع أو استبدال خلال 7 أيام</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- السؤال 4 -->
                <div class="accordion-item mb-3 border-0 shadow-sm">
                    <h2 class="accordion-header" id="faq4">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                            <i class="bi bi-arrow-left-right me-2 text-primary"></i>
                            كيف أقوم بإرجاع أو استبدال منتج؟
                        </button>
                    </h2>
                    <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4">
                        <div class="accordion-body">
                            <p><strong>خطوات الإرجاع أو الاستبدال:</strong></p>
                            <ol>
                                <li>تواصل معنا خلال 7 أيام من تاريخ الاستلام</li>
                                <li>أخبرنا بسبب الإرجاع وأرفق صوراً للمنتج</li>
                                <li>احتفظ بالمنتج في حالته الأصلية مع العلبة</li>
                                <li>سيتم استلام المنتج وفحصه</li>
                                <li>استرجاع المبلغ أو الاستبدال خلال 3-5 أيام</li>
                            </ol>
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle"></i> راجع <a href="{{ route('return-policy') }}" class="alert-link">سياسة الإرجاع</a> للتفاصيل الكاملة
                            </div>
                        </div>
                    </div>
                </div>

                <!-- السؤال 5 -->
                <div class="accordion-item mb-3 border-0 shadow-sm">
                    <h2 class="accordion-header" id="faq5">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                            <i class="bi bi-box-seam me-2 text-primary"></i>
                            كيف أتتبع طلبي؟
                        </button>
                    </h2>
                    <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5">
                        <div class="accordion-body">
                            <p>يمكنك تتبع طلبك بسهولة:</p>
                            <ul>
                                <li><strong>من حسابك:</strong> سجل دخول واذهب إلى "طلباتي"</li>
                                <li><strong>عبر الواتساب:</strong> أرسل رقم الطلب على <a href="https://wa.me/967777116668" class="text-primary">0777 116 668</a></li>
                                <li><strong>الإشعارات:</strong> ستصلك رسائل تحديث عند كل مرحلة</li>
                            </ul>
                            <p class="mb-0"><strong>مراحل الطلب:</strong> قيد المعالجة → تم التحضير → قيد التوصيل → تم التسليم ✅</p>
                        </div>
                    </div>
                </div>

                <!-- السؤال 6 -->
                <div class="accordion-item mb-3 border-0 shadow-sm">
                    <h2 class="accordion-header" id="faq6">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                            <i class="bi bi-receipt me-2 text-primary"></i>
                            هل يمكنني إلغاء الطلب؟
                        </button>
                    </h2>
                    <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="faq6">
                        <div class="accordion-body">
                            <p>نعم، يمكنك إلغاء الطلب في الحالات التالية:</p>
                            <ul>
                                <li>✅ <strong>قبل الشحن:</strong> إلغاء مجاني 100%</li>
                                <li>⚠️ <strong>بعد الشحن:</strong> قد تُطبق رسوم شحن إرجاع</li>
                                <li>❌ <strong>بعد التسليم:</strong> تُطبق سياسة الإرجاع العادية</li>
                            </ul>
                            <p class="mb-0">للإلغاء، تواصل معنا فوراً عبر الواتساب أو الدعم الفني.</p>
                        </div>
                    </div>
                </div>

                <!-- السؤال 7 -->
                <div class="accordion-item mb-3 border-0 shadow-sm">
                    <h2 class="accordion-header" id="faq7">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                            <i class="bi bi-shield-fill-check me-2 text-primary"></i>
                            ما مدة الضمان على المنتجات؟
                        </button>
                    </h2>
                    <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="faq7">
                        <div class="accordion-body">
                            <p><strong>فترات الضمان حسب نوع المنتج:</strong></p>
                            <ul>
                                <li><i class="bi bi-phone"></i> <strong>الهواتف الذكية:</strong> سنة واحدة ضمان الوكيل</li>
                                <li><i class="bi bi-laptop"></i> <strong>اللابتوبات:</strong> سنة واحدة ضمان الوكيل</li>
                                <li><i class="bi bi-smartwatch"></i> <strong>الساعات الذكية:</strong> 6 أشهر ضمان الوكيل</li>
                                <li><i class="bi bi-headphones"></i> <strong>الإكسسوارات:</strong> 3-6 أشهر حسب النوع</li>
                            </ul>
                            <div class="alert alert-warning mt-3">
                                <i class="bi bi-exclamation-triangle"></i> الضمان لا يشمل الأضرار الناتجة عن سوء الاستخدام أو الكسر المتعمد
                            </div>
                        </div>
                    </div>
                </div>

                <!-- السؤال 8 -->
                <div class="accordion-item mb-3 border-0 shadow-sm">
                    <h2 class="accordion-header" id="faq8">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
                            <i class="bi bi-whatsapp me-2 text-primary"></i>
                            كيف أتواصل مع خدمة العملاء؟
                        </button>
                    </h2>
                    <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="faq8">
                        <div class="accordion-body">
                            <p><strong>نحن متاحون لخدمتك:</strong></p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded p-3 text-center">
                                        <i class="bi bi-whatsapp fs-1 text-success"></i>
                                        <h6 class="mt-2">واتساب</h6>
                                        <a href="https://wa.me/967777116668" class="btn btn-sm btn-success">
                                            0777 116 668
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3 text-center">
                                        <i class="bi bi-telephone fs-1 text-primary"></i>
                                        <h6 class="mt-2">الهاتف</h6>
                                        <a href="tel:+967777116668" class="btn btn-sm btn-primary">
                                            0777 116 668
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-center"><strong>ساعات العمل:</strong> من السبت إلى الخميس، 9 صباحاً - 9 مساءً</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- CTA Box -->
            <div class="card bg-primary bg-gradient text-white mt-5">
                <div class="card-body text-center py-4">
                    <h4 class="mb-3">لم تجد إجابة لسؤالك؟</h4>
                    <p class="mb-3">فريق الدعم الفني جاهز لمساعدتك على مدار الساعة</p>
                    <div class="d-flex flex-column gap-2 align-items-center">
                        <a href="https://wa.me/967777116668" class="btn btn-light btn-lg" target="_blank">
                            <i class="bi bi-whatsapp"></i> تواصل واتساب: 0777 116 668
                        </a>
                        <a href="tel:+967777116668" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-telephone-fill"></i> اتصل بنا: 0777 116 668
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إصلاح مشكلة عدم ظهور محتوى الأكورديون
    const accordionButtons = document.querySelectorAll('.accordion-button');
    
    accordionButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-bs-target');
            const target = document.querySelector(targetId);
            
            if (target) {
                setTimeout(function() {
                    const body = target.querySelector('.accordion-body');
                    if (body) {
                        body.style.display = 'block';
                        body.style.visibility = 'visible';
                        body.style.opacity = '1';
                    }
                }, 100);
            }
        });
    });
    
    // التأكد من ظهور المحتوى عند فتح أي accordion
    document.querySelectorAll('.accordion-collapse').forEach(function(collapse) {
        collapse.addEventListener('shown.bs.collapse', function() {
            const body = this.querySelector('.accordion-body');
            if (body) {
                body.style.display = 'block';
                body.style.visibility = 'visible';
                body.style.opacity = '1';
                body.style.color = '#000000';
            }
        });
    });
});
</script>
@endsection

<style>
.accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, #5D4037 0%, #4E342E 100%) !important;
    color: white !important;
    font-weight: 600 !important;
}

.accordion-button.collapsed {
    background: white !important;
    color: #2d3748 !important;
    border: 2px solid #e2e8f0 !important;
}

.accordion-button {
    border-radius: 12px !important;
    font-weight: 600 !important;
    padding: 1.25rem 1.5rem !important;
    transition: all 0.3s ease !important;
}

.accordion-button:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(93, 64, 55, 0.2) !important;
}

.accordion-button:focus {
    box-shadow: 0 0 0 3px rgba(93, 64, 55, 0.3) !important;
    border-color: #5D4037 !important;
}

.accordion-item {
    border-radius: 12px !important;
    overflow: visible !important;
    background: white !important;
    margin-bottom: 1.5rem !important;
    border: 2px solid #f1f5f9 !important;
}

.accordion-collapse {
    background: #fffbf7 !important;
    visibility: visible !important;
    overflow: visible !important;
}

.accordion-body {
    background: #fffbf7 !important;
    color: #000000 !important;
    padding: 2rem 1.5rem !important;
    font-size: 1rem !important;
    line-height: 1.8 !important;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    overflow: visible !important;
    height: auto !important;
    max-height: none !important;
}

.accordion-body * {
    visibility: visible !important;
    opacity: 1 !important;
}

.accordion-body p {
    color: #000000 !important;
    margin-bottom: 1rem !important;
    font-size: 1rem !important;
}

.accordion-body ul,
.accordion-body ol {
    color: #000000 !important;
    padding-right: 1.5rem !important;
    margin-bottom: 1rem !important;
}

.accordion-body li {
    color: #000000 !important;
    margin-bottom: 0.75rem !important;
    font-size: 0.95rem !important;
}

.accordion-body small {
    color: #718096 !important;
    font-size: 0.875rem !important;
}

.accordion-body strong {
    color: #000000 !important;
    font-weight: 700 !important;
}

.accordion-body h6 {
    color: #000000 !important;
    font-weight: 600 !important;
    margin-bottom: 0.5rem !important;
}

.accordion-body .text-muted {
    color: #000000 !important;
}

.accordion-body .text-primary,
.accordion-body .bi-question-circle,
.accordion-body .bi-truck,
.accordion-body .bi-credit-card,
.accordion-body .bi-shield-check,
.accordion-body .bi-arrow-left-right,
.accordion-body .bi-box-seam,
.accordion-body .bi-receipt,
.accordion-body .bi-shield-fill-check,
.accordion-body .bi-whatsapp {
    color: #5D4037 !important;
}

.accordion-body .text-success,
.accordion-body .bi-cash-coin,
.accordion-body i.text-success {
    color: #059669 !important;
}

.accordion-body .text-warning,
.accordion-body .bi-wallet2,
.accordion-body i.text-warning {
    color: #d97706 !important;
}

.accordion-body .bi-phone,
.accordion-body i.text-primary {
    color: #5D4037 !important;
}

.accordion-body .alert {
    background: #fef3c7 !important;
    border: 2px solid #fcd34d !important;
    border-radius: 10px !important;
    padding: 1.25rem !important;
    color: #78350f !important;
    font-weight: 500 !important;
}

.accordion-body .alert * {
    color: #78350f !important;
}

.accordion-body .alert i {
    color: #d97706 !important;
}

.accordion-body .alert-info {
    background: #dbeafe !important;
    border-color: #93c5fd !important;
    color: #1e3a8a !important;
}

.accordion-body .alert-info * {
    color: #1e3a8a !important;
}

.accordion-body .alert-info i {
    color: #3b82f6 !important;
}

.accordion-body .alert-warning {
    background: #fef3c7 !important;
    border-color: #fcd34d !important;
    color: #78350f !important;
}

.accordion-body .alert-warning * {
    color: #78350f !important;
}

.accordion-body .alert-warning i {
    color: #d97706 !important;
}

.accordion-body .alert-link {
    color: #5D4037 !important;
    font-weight: 700 !important;
    text-decoration: underline !important;
}

.accordion-body .alert-link:hover {
    color: #4E342E !important;
}

.accordion-body .border {
    border: 2px solid #e5e7eb !important;
    background: white !important;
    padding: 1.25rem !important;
}

.accordion-body .btn {
    color: white !important;
    font-weight: 600 !important;
    padding: 0.5rem 1.5rem !important;
    border-radius: 8px !important;
}

.accordion-body .btn-success {
    background: #059669 !important;
    border-color: #059669 !important;
}

.accordion-body .btn-success:hover {
    background: #047857 !important;
}

.accordion-body .btn-primary {
    background: #5D4037 !important;
    border-color: #5D4037 !important;
}

.accordion-body .btn-primary:hover {
    background: #4E342E !important;
}

.text-primary {
    color: #5D4037 !important;
}

h1.text-primary,
.display-5.text-primary {
    color: #5D4037 !important;
}

.bg-primary {
    background: linear-gradient(135deg, #5D4037 0%, #4E342E 100%) !important;
}

.btn-light {
    background: white !important;
    color: #5D4037 !important;
    border: none !important;
    font-weight: 600 !important;
}

.btn-light:hover {
    background: #fef7f0 !important;
    color: #5D4037 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}

.btn-outline-light {
    border-color: white !important;
    color: white !important;
    font-weight: 600 !important;
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.2) !important;
    color: white !important;
    transform: translateY(-2px) !important;
}

.lead {
    color: #4a5568 !important;
}

/* إصلاح مشكلة اختفاء المحتوى */
.accordion-collapse {
    transition: height 0.35s ease !important;
}

.accordion-collapse.show {
    display: block !important;
    visibility: visible !important;
    height: auto !important;
}

.accordion-collapse.show .accordion-body {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.accordion-collapse:not(.show) {
    display: none !important;
}

.accordion-body {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}
</style>
@endsection
