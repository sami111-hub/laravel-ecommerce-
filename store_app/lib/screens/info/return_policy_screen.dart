import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../core/constants/app_colors.dart';

class ReturnPolicyScreen extends StatelessWidget {
  const ReturnPolicyScreen({super.key});

  Future<void> _openWhatsApp() async {
    final uri = Uri.parse(
        'https://wa.me/967780800007?text=مرحباً، لدي استفسار حول سياسة الإرجاع');
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text('سياسة الإرجاع',
            style: TextStyle(
                color: AppColors.textPrimary, fontWeight: FontWeight.bold)),
        centerTitle: true,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios, color: AppColors.textPrimary),
          onPressed: () => Navigator.pop(context),
        ),
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // ── Header ──
            Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(vertical: 28, horizontal: 24),
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [AppColors.primary, Color(0xFF1565C0)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
              ),
              child: Column(
                children: const [
                  Icon(Icons.assignment_return_rounded,
                      size: 52, color: Colors.white),
                  SizedBox(height: 12),
                  Text('سياسة الإرجاع والاسترجاع',
                      style: TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: Colors.white)),
                  SizedBox(height: 6),
                  Text('نلتزم بتوفير تجربة تسوق مريحة وآمنة لعملائنا',
                      style: TextStyle(fontSize: 13, color: Colors.white70),
                      textAlign: TextAlign.center),
                ],
              ),
            ),

            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  // ── مدة الإرجاع ──
                  _PolicyCard(
                    icon: Icons.access_time_rounded,
                    iconColor: AppColors.primary,
                    title: 'المدة الزمنية للإرجاع',
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        RichText(
                          text: const TextSpan(
                            style: TextStyle(
                                fontSize: 14,
                                color: AppColors.textSecondary,
                                height: 1.6),
                            children: [
                              TextSpan(
                                  text:
                                      'يمكنك إرجاع أو استبدال المنتجات خلال '),
                              TextSpan(
                                  text: '7 أيام',
                                  style: TextStyle(
                                      fontWeight: FontWeight.bold,
                                      color: AppColors.error)),
                              TextSpan(text: ' من تاريخ الاستلام.'),
                            ],
                          ),
                        ),
                        const SizedBox(height: 12),
                        Container(
                          padding: const EdgeInsets.all(12),
                          decoration: BoxDecoration(
                            color: const Color(0xFF1E88E5).withValues(alpha: 0.08),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: Row(
                            children: const [
                              Icon(Icons.info_outline,
                                  color: AppColors.primary, size: 18),
                              SizedBox(width: 8),
                              Expanded(
                                child: Text(
                                  'يبدأ احتساب المدة من يوم استلامك للمنتج وليس من يوم الطلب',
                                  style: TextStyle(
                                      fontSize: 12,
                                      color: AppColors.primary),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 12),

                  // ── شروط القبول ──
                  _PolicyCard(
                    icon: Icons.check_circle_rounded,
                    iconColor: const Color(0xFF43A047),
                    title: 'شروط قبول الإرجاع',
                    child: Column(
                      children: const [
                        _ConditionRow(
                          icon: Icons.check_circle,
                          color: Color(0xFF43A047),
                          title: 'المنتج بحالة جيدة',
                          desc: 'المنتج غير مستخدم وفي حالته الأصلية',
                        ),
                        _ConditionRow(
                          icon: Icons.check_circle,
                          color: Color(0xFF43A047),
                          title: 'العلبة الأصلية كاملة',
                          desc: 'مع جميع الملحقات والمستندات',
                        ),
                        _ConditionRow(
                          icon: Icons.check_circle,
                          color: Color(0xFF43A047),
                          title: 'الفاتورة الأصلية',
                          desc: 'احتفظ بالفاتورة لإتمام عملية الإرجاع',
                        ),
                        _ConditionRow(
                          icon: Icons.check_circle,
                          color: Color(0xFF43A047),
                          title: 'بدون خدوش أو كسر',
                          desc: 'المنتج خالٍ من أي أضرار',
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 12),

                  // ── حالات عدم القبول ──
                  _PolicyCard(
                    icon: Icons.cancel_rounded,
                    iconColor: AppColors.error,
                    title: 'حالات عدم قبول الإرجاع',
                    child: Column(
                      children: const [
                        _ConditionRow(
                          icon: Icons.cancel,
                          color: AppColors.error,
                          title: 'المنتجات المستخدمة',
                          desc: 'المنتجات التي تم استخدامها أو تشغيلها بشكل واضح',
                        ),
                        _ConditionRow(
                          icon: Icons.cancel,
                          color: AppColors.error,
                          title: 'العلبة التالفة',
                          desc: 'المنتجات التي فُقدت علبتها أو تلفت بشكل كبير',
                        ),
                        _ConditionRow(
                          icon: Icons.cancel,
                          color: AppColors.error,
                          title: 'الملحقات الناقصة',
                          desc: 'المنتجات التي فقدت أي من ملحقاتها الأصلية',
                        ),
                        _ConditionRow(
                          icon: Icons.cancel,
                          color: AppColors.error,
                          title: 'العروض الخاصة',
                          desc: 'بعض منتجات العروض الترويجية (يُذكر عند الشراء)',
                        ),
                        _ConditionRow(
                          icon: Icons.cancel,
                          color: AppColors.error,
                          title: 'الإكسسوارات المفتوحة',
                          desc: 'سماعات الأذن والملحقات الشخصية لأسباب صحية',
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 12),

                  // ── خطوات الإرجاع ──
                  _PolicyCard(
                    icon: Icons.format_list_numbered_rounded,
                    iconColor: AppColors.primary,
                    title: 'خطوات الإرجاع',
                    child: Column(
                      children: const [
                        _StepItem(num: '1', title: 'تواصل معنا', desc: 'اتصل بنا عبر الواتساب 0780 800 007 أو الهاتف'),
                        _StepItem(num: '2', title: 'أخبرنا بالسبب', desc: 'اذكر سبب الإرجاع وأرسل صوراً واضحة للمنتج'),
                        _StepItem(num: '3', title: 'موافقة الإرجاع', desc: 'سنراجع طلبك ونوافق عليه خلال 24 ساعة'),
                        _StepItem(num: '4', title: 'إعادة المنتج', desc: 'يمكنك إحضار المنتج لفرعنا أو سنرسل مندوب'),
                        _StepItem(num: '5', title: 'استرجاع المبلغ', desc: 'بعد الفحص، سيتم الاسترجاع خلال 3-5 أيام عمل', isLast: true),
                      ],
                    ),
                  ),

                  const SizedBox(height: 12),

                  // ── طرق الاسترجاع ──
                  _PolicyCard(
                    icon: Icons.payments_rounded,
                    iconColor: AppColors.primary,
                    title: 'طرق استرجاع المبلغ',
                    child: Row(
                      children: [
                        Expanded(
                          child: _RefundMethod(
                              icon: Icons.money_rounded,
                              color: const Color(0xFF43A047),
                              title: 'نقدي',
                              desc: 'الدفع نقداً\nعند الاستلام'),
                        ),
                        const SizedBox(width: 10),
                        Expanded(
                          child: _RefundMethod(
                              icon: Icons.account_balance_rounded,
                              color: AppColors.primary,
                              title: 'تحويل بنكي',
                              desc: 'إلى حسابك\nالبنكي'),
                        ),
                        const SizedBox(width: 10),
                        Expanded(
                          child: _RefundMethod(
                              icon: Icons.account_balance_wallet_rounded,
                              color: const Color(0xFFFFC107),
                              title: 'محفظة إلك.',
                              desc: 'موبايل موني\nوغيرها'),
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 12),

                  // ── ملاحظات هامة ──
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: const Color(0xFFFFF8E1),
                      borderRadius: BorderRadius.circular(14),
                      border: Border.all(
                          color: const Color(0xFFFFC107).withValues(alpha: 0.4)),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: const [
                            Icon(Icons.warning_amber_rounded,
                                color: Color(0xFFF57F17), size: 20),
                            SizedBox(width: 8),
                            Text('ملاحظات هامة',
                                style: TextStyle(
                                    fontSize: 15,
                                    fontWeight: FontWeight.bold,
                                    color: Color(0xFFF57F17))),
                          ],
                        ),
                        const SizedBox(height: 10),
                        const _BulletText(
                            'تكاليف الشحن للإرجاع قد تُخصم من المبلغ المسترجع (حسب سبب الإرجاع)'),
                        const _BulletText(
                            'إذا كان المنتج معيباً أو تالفاً عند الاستلام، نتحمل كافة التكاليف'),
                        const _BulletText(
                            'لا نقبل إرجاع المنتجات التي تم شراؤها من مصادر أخرى'),
                        const _BulletText(
                            'بعض المنتجات قد يكون لها سياسة إرجاع خاصة (يُذكر عند الشراء)'),
                      ],
                    ),
                  ),

                  const SizedBox(height: 16),

                  // ── CTA ──
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      gradient: const LinearGradient(
                        colors: [AppColors.primary, Color(0xFF1565C0)],
                        begin: Alignment.topLeft,
                        end: Alignment.bottomRight,
                      ),
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Column(
                      children: [
                        const Text('هل لديك استفسار حول سياسة الإرجاع؟',
                            style: TextStyle(
                                fontSize: 15,
                                fontWeight: FontWeight.bold,
                                color: Colors.white),
                            textAlign: TextAlign.center),
                        const SizedBox(height: 6),
                        const Text('فريقنا جاهز لمساعدتك',
                            style:
                                TextStyle(fontSize: 13, color: Colors.white70)),
                        const SizedBox(height: 14),
                        ElevatedButton.icon(
                          onPressed: _openWhatsApp,
                          icon: const Icon(Icons.chat, size: 18),
                          label: const Text('واتساب: 0780 800 007',
                              style:
                                  TextStyle(fontWeight: FontWeight.bold)),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: const Color(0xFF25D366),
                            foregroundColor: Colors.white,
                            padding: const EdgeInsets.symmetric(
                                horizontal: 24, vertical: 12),
                            shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(12)),
                            elevation: 0,
                          ),
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 24),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ─── helpers ─────────────────────────────────────────────────────────────

class _PolicyCard extends StatelessWidget {
  final IconData icon;
  final Color iconColor;
  final String title;
  final Widget child;
  const _PolicyCard(
      {required this.icon,
      required this.iconColor,
      required this.title,
      required this.child});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [
          BoxShadow(
              color: Colors.black.withValues(alpha: 0.04),
              blurRadius: 6,
              offset: const Offset(0, 2))
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                width: 36,
                height: 36,
                decoration: BoxDecoration(
                    color: iconColor.withValues(alpha: 0.1),
                    borderRadius: BorderRadius.circular(9)),
                child: Icon(icon, color: iconColor, size: 18),
              ),
              const SizedBox(width: 10),
              Text(title,
                  style: TextStyle(
                      fontSize: 15,
                      fontWeight: FontWeight.bold,
                      color: iconColor)),
            ],
          ),
          const SizedBox(height: 14),
          child,
        ],
      ),
    );
  }
}

class _ConditionRow extends StatelessWidget {
  final IconData icon;
  final Color color;
  final String title;
  final String desc;
  const _ConditionRow(
      {required this.icon,
      required this.color,
      required this.title,
      required this.desc});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: color, size: 20),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title,
                    style: const TextStyle(
                        fontWeight: FontWeight.bold, fontSize: 13)),
                Text(desc,
                    style: const TextStyle(
                        fontSize: 12,
                        color: AppColors.textSecondary,
                        height: 1.4)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _StepItem extends StatelessWidget {
  final String num;
  final String title;
  final String desc;
  final bool isLast;
  const _StepItem(
      {required this.num,
      required this.title,
      required this.desc,
      this.isLast = false});

  @override
  Widget build(BuildContext context) {
    return IntrinsicHeight(
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Column(
            children: [
              Container(
                width: 34,
                height: 34,
                decoration: BoxDecoration(
                  color: isLast
                      ? const Color(0xFF43A047)
                      : AppColors.primary,
                  shape: BoxShape.circle,
                ),
                child: Center(
                  child: Text(num,
                      style: const TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                          fontSize: 14)),
                ),
              ),
              if (!isLast)
                Expanded(
                  child: Container(
                      width: 2,
                      color: AppColors.divider,
                      margin: const EdgeInsets.symmetric(vertical: 4)),
                ),
            ],
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Padding(
              padding: EdgeInsets.only(bottom: isLast ? 0 : 16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(title,
                      style: const TextStyle(
                          fontWeight: FontWeight.bold, fontSize: 14)),
                  const SizedBox(height: 3),
                  Text(desc,
                      style: const TextStyle(
                          fontSize: 12,
                          color: AppColors.textSecondary,
                          height: 1.4)),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _RefundMethod extends StatelessWidget {
  final IconData icon;
  final Color color;
  final String title;
  final String desc;
  const _RefundMethod(
      {required this.icon,
      required this.color,
      required this.title,
      required this.desc});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        border: Border.all(color: color.withValues(alpha: 0.3)),
        borderRadius: BorderRadius.circular(12),
        color: color.withValues(alpha: 0.05),
      ),
      child: Column(
        children: [
          Icon(icon, color: color, size: 28),
          const SizedBox(height: 6),
          Text(title,
              style: const TextStyle(
                  fontWeight: FontWeight.bold, fontSize: 12)),
          const SizedBox(height: 4),
          Text(desc,
              style: const TextStyle(
                  fontSize: 10,
                  color: AppColors.textSecondary,
                  height: 1.3),
              textAlign: TextAlign.center),
        ],
      ),
    );
  }
}

class _BulletText extends StatelessWidget {
  final String text;
  const _BulletText(this.text);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('• ',
              style: TextStyle(
                  fontSize: 14, color: Color(0xFFF57F17))),
          Expanded(
            child: Text(text,
                style: const TextStyle(
                    fontSize: 12,
                    color: AppColors.textSecondary,
                    height: 1.5)),
          ),
        ],
      ),
    );
  }
}
