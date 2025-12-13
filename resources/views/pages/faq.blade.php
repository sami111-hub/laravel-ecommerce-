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
                        <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                            <i class="bi bi-truck me-2 text-primary"></i>
                            ما هي مدة التوصيل؟
                        </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
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
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                            <i class="bi bi-credit-card me-2 text-primary"></i>
                            ما هي طرق الدفع المتاحة؟
                        </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
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
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                            <i class="bi bi-shield-check me-2 text-primary"></i>
                            هل المنتجات أصلية ومضمونة؟
                        </button>
                    </h2>
                    <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
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
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                            <i class="bi bi-arrow-left-right me-2 text-primary"></i>
                            كيف أقوم بإرجاع أو استبدال منتج؟
                        </button>
                    </h2>
                    <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
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
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                            <i class="bi bi-box-seam me-2 text-primary"></i>
                            كيف أتتبع طلبي؟
                        </button>
                    </h2>
                    <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
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
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6">
                            <i class="bi bi-receipt me-2 text-primary"></i>
                            هل يمكنني إلغاء الطلب؟
                        </button>
                    </h2>
                    <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
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
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7">
                            <i class="bi bi-shield-fill-check me-2 text-primary"></i>
                            ما مدة الضمان على المنتجات؟
                        </button>
                    </h2>
                    <div id="collapse7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
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
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8">
                            <i class="bi bi-whatsapp me-2 text-primary"></i>
                            كيف أتواصل مع خدمة العملاء؟
                        </button>
                    </h2>
                    <div id="collapse8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
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

<style>
.accordion-button:not(.collapsed) {
    background-color: #f0f5ff;
    color: #667eea;
}

.accordion-button:focus {
    box-shadow: none;
    border-color: #667eea;
}
</style>
@endsection
