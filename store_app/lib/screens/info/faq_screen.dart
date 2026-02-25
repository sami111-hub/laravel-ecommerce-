import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../core/constants/app_colors.dart';

class FaqScreen extends StatefulWidget {
  const FaqScreen({super.key});

  @override
  State<FaqScreen> createState() => _FaqScreenState();
}

class _FaqScreenState extends State<FaqScreen> {
  int? _openIndex;

  final List<_FaqItem> _faqs = const [
    _FaqItem(
      icon: Icons.local_shipping_rounded,
      question: 'ما هي مدة التوصيل؟',
      answer: 'مدة التوصيل تعتمد على موقعك:\n'
          '• داخل عدن: 1-2 يوم عمل\n'
          '• المحافظات القريبة: 2-4 أيام عمل\n'
          '• باقي المحافظات: 4-7 أيام عمل\n\n'
          '* يتم حساب أيام العمل من السبت إلى الخميس',
    ),
    _FaqItem(
      icon: Icons.credit_card_rounded,
      question: 'ما هي طرق الدفع المتاحة؟',
      answer: 'نوفر عدة طرق للدفع:\n'
          '• 💵 الدفع عند الاستلام: ادفع نقداً عند وصول الطلب\n'
          '• 🏦 التحويل البنكي: عبر الحوالات المحلية\n'
          '• 📱 المحافظ الإلكترونية: موبايل موني وغيرها',
    ),
    _FaqItem(
      icon: Icons.verified_rounded,
      question: 'هل المنتجات أصلية ومضمونة؟',
      answer: 'نعم، جميع منتجاتنا أصلية 100% ومستوردة من مصادر موثوقة.\n\n'
          '✅ ضمان الوكيل المعتمد على جميع المنتجات\n'
          '✅ إمكانية فحص المنتج قبل الاستلام\n'
          '✅ فاتورة رسمية مع كل عملية شراء\n'
          '✅ ضمان استرجاع أو استبدال خلال 7 أيام',
    ),
    _FaqItem(
      icon: Icons.assignment_return_rounded,
      question: 'كيف أقوم بإرجاع أو استبدال منتج؟',
      answer: 'خطوات الإرجاع أو الاستبدال:\n\n'
          '1. تواصل معنا خلال 7 أيام من تاريخ الاستلام\n'
          '2. أخبرنا بسبب الإرجاع وأرفق صوراً للمنتج\n'
          '3. احتفظ بالمنتج في حالته الأصلية مع العلبة\n'
          '4. سيتم استلام المنتج وفحصه\n'
          '5. استرجاع المبلغ أو الاستبدال خلال 3-5 أيام',
    ),
    _FaqItem(
      icon: Icons.track_changes_rounded,
      question: 'كيف أتتبع طلبي؟',
      answer: 'يمكنك تتبع طلبك بسهولة:\n\n'
          '• من حسابك: سجل دخول واذهب إلى "طلباتي"\n'
          '• عبر الواتساب: أرسل رقم الطلب على 0780 800 007\n'
          '• الإشعارات: ستصلك رسائل تحديث عند كل مرحلة\n\n'
          'مراحل الطلب: قيد المعالجة → تم التحضير → قيد التوصيل → تم التسليم ✅',
    ),
    _FaqItem(
      icon: Icons.cancel_rounded,
      question: 'هل يمكنني إلغاء الطلب؟',
      answer: 'نعم، يمكنك إلغاء الطلب في الحالات التالية:\n\n'
          '✅ قبل الشحن: إلغاء مجاني 100%\n'
          '⚠️ بعد الشحن: قد تُطبق رسوم شحن إرجاع\n'
          '❌ بعد التسليم: تُطبق سياسة الإرجاع العادية\n\n'
          'للإلغاء، تواصل معنا فوراً عبر الواتساب أو الدعم الفني.',
    ),
    _FaqItem(
      icon: Icons.shield_rounded,
      question: 'ما مدة الضمان على المنتجات؟',
      answer: 'فترات الضمان حسب نوع المنتج:\n\n'
          '📱 الهواتف الذكية: سنة واحدة ضمان الوكيل\n'
          '💻 اللابتوبات: سنة واحدة ضمان الوكيل\n'
          '⌚ الساعات الذكية: 6 أشهر ضمان الوكيل\n'
          '🎧 الإكسسوارات: 3-6 أشهر حسب النوع\n\n'
          '⚠️ الضمان لا يشمل الأضرار الناتجة عن سوء الاستخدام أو الكسر المتعمد',
    ),
    _FaqItem(
      icon: Icons.support_agent_rounded,
      question: 'كيف أتواصل مع خدمة العملاء؟',
      answer: 'نحن متاحون لخدمتك:\n\n'
          '📱 واتساب: 0780 800 007\n'
          '📞 هاتف: 0777 116 668\n'
          '📧 إيميل: info@update-aden.com\n\n'
          'أوقات العمل: السبت-الخميس 8 ص - 8 م',
    ),
  ];

  Future<void> _openWhatsApp() async {
    final uri = Uri.parse(
        'https://wa.me/967780800007?text=مرحباً، لدي استفسار');
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
        title: const Text('الأسئلة الشائعة',
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
                  Icon(Icons.quiz_rounded, size: 52, color: Colors.white),
                  SizedBox(height: 12),
                  Text('الأسئلة الشائعة',
                      style: TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: Colors.white)),
                  SizedBox(height: 6),
                  Text('إجابات على أكثر الأسئلة شيوعاً حول Update Aden',
                      style: TextStyle(fontSize: 13, color: Colors.white70),
                      textAlign: TextAlign.center),
                ],
              ),
            ),

            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  // ── Accordion ──
                  ...List.generate(_faqs.length, (i) {
                    final faq = _faqs[i];
                    final isOpen = _openIndex == i;
                    return Padding(
                      padding: const EdgeInsets.only(bottom: 10),
                      child: _FaqTile(
                        faq: faq,
                        isOpen: isOpen,
                        onTap: () => setState(
                            () => _openIndex = isOpen ? null : i),
                      ),
                    );
                  }),

                  const SizedBox(height: 16),

                  // ── CTA ──
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      gradient: const LinearGradient(
                        colors: [Color(0xFF1E88E5), Color(0xFF1565C0)],
                        begin: Alignment.topLeft,
                        end: Alignment.bottomRight,
                      ),
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Column(
                      children: [
                        const Text('لم تجد إجابة على سؤالك؟',
                            style: TextStyle(
                                fontSize: 16,
                                fontWeight: FontWeight.bold,
                                color: Colors.white)),
                        const SizedBox(height: 6),
                        const Text('فريقنا جاهز لمساعدتك',
                            style: TextStyle(
                                fontSize: 13, color: Colors.white70)),
                        const SizedBox(height: 14),
                        ElevatedButton.icon(
                          onPressed: _openWhatsApp,
                          icon: const Icon(Icons.chat, size: 18),
                          label: const Text('تواصل عبر واتساب',
                              style: TextStyle(fontWeight: FontWeight.bold)),
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

// ─── data ────────────────────────────────────────────────────────────────

class _FaqItem {
  final IconData icon;
  final String question;
  final String answer;
  const _FaqItem(
      {required this.icon, required this.question, required this.answer});
}

// ─── tile ────────────────────────────────────────────────────────────────

class _FaqTile extends StatelessWidget {
  final _FaqItem faq;
  final bool isOpen;
  final VoidCallback onTap;

  const _FaqTile(
      {required this.faq, required this.isOpen, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return AnimatedContainer(
      duration: const Duration(milliseconds: 200),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [
          BoxShadow(
              color: isOpen
                  ? AppColors.primary.withValues(alpha: 0.12)
                  : Colors.black.withValues(alpha: 0.04),
              blurRadius: isOpen ? 10 : 6,
              offset: const Offset(0, 2))
        ],
        border: isOpen
            ? Border.all(color: AppColors.primary.withValues(alpha: 0.3))
            : Border.all(color: Colors.transparent),
      ),
      child: Column(
        children: [
          InkWell(
            onTap: onTap,
            borderRadius: BorderRadius.circular(14),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Row(
                children: [
                  Container(
                    width: 40,
                    height: 40,
                    decoration: BoxDecoration(
                      color: AppColors.primary.withValues(alpha: 0.1),
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Icon(faq.icon, color: AppColors.primary, size: 20),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Text(faq.question,
                        style: TextStyle(
                            fontSize: 14,
                            fontWeight: FontWeight.bold,
                            color: isOpen
                                ? AppColors.primary
                                : AppColors.textPrimary)),
                  ),
                  AnimatedRotation(
                    turns: isOpen ? 0.5 : 0,
                    duration: const Duration(milliseconds: 200),
                    child: Icon(
                      Icons.keyboard_arrow_down_rounded,
                      color: isOpen ? AppColors.primary : AppColors.textSecondary,
                    ),
                  ),
                ],
              ),
            ),
          ),
          if (isOpen)
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
              child: Column(
                children: [
                  const Divider(height: 1),
                  const SizedBox(height: 12),
                  Text(
                    faq.answer,
                    style: const TextStyle(
                        fontSize: 13,
                        height: 1.7,
                        color: AppColors.textSecondary),
                  ),
                ],
              ),
            ),
        ],
      ),
    );
  }
}
