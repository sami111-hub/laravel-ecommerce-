import 'package:flutter/material.dart';
import '../../core/constants/app_colors.dart';

class InfoScreen extends StatelessWidget {
  final String title;
  final String content;
  final IconData icon;

  const InfoScreen({super.key, required this.title, required this.content, required this.icon});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: Text(title, style: const TextStyle(color: AppColors.textPrimary, fontWeight: FontWeight.bold)),
        centerTitle: true,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios, color: AppColors.textPrimary),
          onPressed: () => Navigator.pop(context),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(vertical: 24),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [AppColors.primary, Color(0xFF1a7a52)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(20),
              ),
              child: Column(
                children: [
                  Icon(icon, size: 48, color: Colors.white),
                  const SizedBox(height: 10),
                  Text(title, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white)),
                ],
              ),
            ),
            const SizedBox(height: 20),
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
              ),
              child: Text(
                content,
                style: const TextStyle(fontSize: 15, height: 1.8, color: AppColors.textSecondary),
                textAlign: TextAlign.start,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// محتوى الصفحات
class AppInfoContent {
  static const String aboutUs = '''
مرحباً بكم في متجرنا الإلكتروني

نحن منصة تجارة إلكترونية متخصصة في تقديم أفضل المنتجات التقنية والإلكترونية بأسعار تنافسية.

🎯 رسالتنا:
تقديم تجربة تسوق مريحة وآمنة لعملائنا في اليمن والعالم العربي.

✨ مميزاتنا:
• منتجات أصلية ومضمونة
• أسعار تنافسية
• توصيل سريع لجميع المحافظات
• خدمة عملاء متميزة على مدار الساعة
• سياسة إرجاع مرنة

📍 موقعنا:
عدن، اليمن

📞 للتواصل:
+967 777 123 456

🌐 الموقع الإلكتروني:
store.update-aden.com
''';

  static const String termsOfUse = '''
سياسة الاستخدام

مرحباً بك في متجرنا الإلكتروني. باستخدامك لهذا التطبيق، فإنك توافق على الشروط والأحكام التالية:

1. قبول الشروط
باستخدامك للتطبيق فأنت توافق على جميع شروط الاستخدام المذكورة هنا.

2. الحسابات الشخصية
• أنت مسؤول عن الحفاظ على سرية معلومات حسابك
• يُمنع مشاركة بيانات الحساب مع الآخرين

3. الطلبات والمدفوعات
• جميع الأسعار بالريال اليمني
• يتم تأكيد الطلب بعد إتمام الدفع

4. الاستخدام المقبول
يُمنع استخدام التطبيق في أنشطة غير قانونية أو ما يخالف الآداب العامة.

5. حقوق الملكية الفكرية
جميع المحتويات محمية بحقوق الملكية الفكرية.
''';

  static const String returnPolicy = '''
سياسة الإرجاع والاستبدال

نحن نضمن رضاك التام عن مشترياتك. فيما يلي شروط الإرجاع والاستبدال:

⏰ مدة الإرجاع:
خلال 7 أيام من تاريخ الاستلام

✅ شروط القبول:
• المنتج في حالته الأصلية غير مستخدم
• العبوة الأصلية كاملة
• وجود الفاتورة الأصلية
• عدم وجود تلف بسبب الإهمال

❌ حالات عدم القبول:
• المنتجات المستخدمة أو التالفة
• المنتجات الخاصة والملابس الداخلية
• منتجات المواد الاستهلاكية بعد الفتح

💰 طريقة الاسترداد:
• رصيد في المتجر أو تحويل بنكي
• خلال 3-5 أيام عمل

📞 للتواصل بخصوص الإرجاع:
+967 777 123 456
''';

  static const String paymentMethods = '''
وسائل الدفع المتاحة

نوفر لكم أفضل وسائل الدفع الآمنة:

💵 الدفع عند الاستلام:
ادفع نقداً عند وصول طلبك إليك
• متاح في عدن وجميع المحافظات
• بدون رسوم إضافية

🏦 التحويل البنكي:
• بنك التضامن الإسلامي
• بنك اليمن والخليج
• يرجى إرسال صورة التحويل على واتساب

📱 الدفع الرقمي:
• فلوسك
• كريمي

💳 بطاقات الائتمان:
• Visa
• Mastercard

⭐ ملاحظات:
• جميع المدفوعات آمنة ومشفرة
• نحتفظ بسياسة خصوصية صارمة لبياناتك البنكية
''';
}
