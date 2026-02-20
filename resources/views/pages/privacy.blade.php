@extends('layout')

@section('title', 'سياسة الخصوصية - Update Aden')
@section('description', 'سياسة الخصوصية وحماية بياناتك في Update Aden - نلتزم بحماية معلوماتك الشخصية')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="mb-4 rounded-4 overflow-hidden shadow-lg">
                <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=1200&h=400&fit=crop&q=80" 
                     alt="سياسة الخصوصية - Update Aden" 
                     class="img-fluid" 
                     style="width: 100%; height: 300px; object-fit: cover;">
            </div>
            <div class="bg-white rounded-3 shadow-sm p-4 p-md-5">
                <h1 class="display-6 fw-bold mb-4">سياسة الخصوصية</h1>
                <p class="text-muted mb-4">آخر تحديث: {{ now()->format('Y-m-d') }}</p>

                <section class="mb-5">
                    <h2 class="h4 fw-bold mb-3">1. جمع المعلومات</h2>
                    <p>نحن نجمع المعلومات التالية عند استخدامك لمتجرنا:</p>
                    <ul>
                        <li><strong>معلومات الحساب:</strong> الاسم، البريد الإلكتروني، رقم الهاتف</li>
                        <li><strong>معلومات الطلبات:</strong> عنوان التوصيل، تفاصيل الدفع</li>
                        <li><strong>معلومات التصفح:</strong> سجل المنتجات المشاهدة، السلة</li>
                    </ul>
                </section>

                <section class="mb-5">
                    <h2 class="h4 fw-bold mb-3">2. استخدام المعلومات</h2>
                    <p>نستخدم معلوماتك للأغراض التالية:</p>
                    <ul>
                        <li>معالجة وتوصيل طلباتك</li>
                        <li>التواصل معك بشأن طلباتك</li>
                        <li>تحسين تجربة التسوق</li>
                        <li>إرسال عروض خاصة (يمكنك إلغاء الاشتراك)</li>
                    </ul>
                </section>

                <section class="mb-5">
                    <h2 class="h4 fw-bold mb-3">3. حماية المعلومات</h2>
                    <p>نحن ملتزمون بحماية معلوماتك الشخصية:</p>
                    <ul>
                        <li>تشفير جميع البيانات الحساسة</li>
                        <li>استخدام بروتوكولات أمان متقدمة (HTTPS)</li>
                        <li>عدم مشاركة معلوماتك مع أطراف ثالثة بدون موافقتك</li>
                        <li>الوصول المحدود للبيانات للموظفين المخولين فقط</li>
                    </ul>
                </section>

                <section class="mb-5">
                    <h2 class="h4 fw-bold mb-3">4. ملفات تعريف الارتباط (Cookies)</h2>
                    <p>نستخدم ملفات تعريف الارتباط لـ:</p>
                    <ul>
                        <li>تذكر تفضيلاتك وإعدادات الحساب</li>
                        <li>حفظ محتويات سلة التسوق</li>
                        <li>تحليل استخدام الموقع لتحسين الأداء</li>
                    </ul>
                    <p>يمكنك تعطيل ملفات تعريف الارتباط من إعدادات المتصفح.</p>
                </section>

                <section class="mb-5">
                    <h2 class="h4 fw-bold mb-3">5. حقوقك</h2>
                    <p>لديك الحقوق التالية:</p>
                    <ul>
                        <li><strong>الوصول:</strong> طلب نسخة من بياناتك الشخصية</li>
                        <li><strong>التصحيح:</strong> تحديث أو تصحيح معلوماتك</li>
                        <li><strong>الحذف:</strong> طلب حذف حسابك وبياناتك</li>
                        <li><strong>الاعتراض:</strong> رفض استخدام معلوماتك لأغراض تسويقية</li>
                    </ul>
                </section>

                <section class="mb-5">
                    <h2 class="h4 fw-bold mb-3">6. الروابط الخارجية</h2>
                    <p>قد يحتوي موقعنا على روابط لمواقع خارجية. نحن غير مسؤولين عن سياسات الخصوصية الخاصة بهذه المواقع.</p>
                </section>

                <section class="mb-5">
                    <h2 class="h4 fw-bold mb-3">7. تحديثات السياسة</h2>
                    <p>قد نقوم بتحديث سياسة الخصوصية من وقت لآخر. سيتم نشر أي تغييرات على هذه الصفحة مع تحديث التاريخ.</p>
                </section>

                <section class="mb-0">
                    <h2 class="h4 fw-bold mb-3">8. اتصل بنا</h2>
                    <p>إذا كان لديك أي استفسارات حول سياسة الخصوصية:</p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope text-primary"></i> info@update-aden.com</li>
                        <li><i class="bi bi-telephone text-primary"></i> {{ env('SUPPORT_PHONE', '0780 800 007') }}</li>
                        <li><i class="bi bi-geo-alt text-primary"></i> عدن، اليمن</li>
                    </ul>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
