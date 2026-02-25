import 'package:flutter/material.dart';
import '../../core/constants/app_colors.dart';

class AboutUsScreen extends StatelessWidget {
  const AboutUsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text('من نحن',
            style: TextStyle(color: AppColors.textPrimary, fontWeight: FontWeight.bold)),
        centerTitle: true,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios, color: AppColors.textPrimary),
          onPressed: () => Navigator.pop(context),
        ),
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // ── Hero Banner ──
            Container(
              width: double.infinity,
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [AppColors.primary, Color(0xFF1565C0)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
              ),
              padding: const EdgeInsets.symmetric(vertical: 36, horizontal: 24),
              child: Column(
                children: [
                  Container(
                    width: 80,
                    height: 80,
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.2),
                      shape: BoxShape.circle,
                    ),
                    child: const Icon(Icons.store_rounded, size: 44, color: Colors.white),
                  ),
                  const SizedBox(height: 16),
                  const Text('Update Aden',
                      style: TextStyle(
                          fontSize: 26, fontWeight: FontWeight.bold, color: Colors.white)),
                  const SizedBox(height: 6),
                  const Text('وجهتك الأولى للتكنولوجيا في عدن',
                      style: TextStyle(fontSize: 14, color: Colors.white70)),
                  const SizedBox(height: 24),
                  // Stats Row
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                    children: [
                      _StatItem(value: '+5', label: 'سنوات خبرة'),
                      _StatDivider(),
                      _StatItem(value: '+10K', label: 'عميل سعيد'),
                      _StatDivider(),
                      _StatItem(value: '100%', label: 'منتجات أصلية'),
                    ],
                  ),
                ],
              ),
            ),

            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  // ── قصتنا ──
                  _SectionCard(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _SectionTitle(
                            icon: Icons.auto_stories_outlined,
                            title: 'قصتنا',
                            color: AppColors.primary),
                        const SizedBox(height: 12),
                        const Text(
                          'منذ انطلاقتنا في قلب مدينة عدن، كان هدفنا واضحاً: توفير أحدث التقنيات والأجهزة الإلكترونية بأفضل الأسعار وأعلى معايير الجودة.',
                          style: TextStyle(
                              fontSize: 14,
                              height: 1.8,
                              color: AppColors.textSecondary),
                        ),
                        const SizedBox(height: 10),
                        const Text(
                          'نؤمن بأن التكنولوجيا يجب أن تكون في متناول الجميع، لذلك نسعى جاهدين لتقديم تجربة تسوق استثنائية تجمع بين الجودة، السعر المناسب، والخدمة الممتازة.',
                          style: TextStyle(
                              fontSize: 14,
                              height: 1.8,
                              color: AppColors.textSecondary),
                        ),
                        const SizedBox(height: 16),
                        // Rating badge
                        Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 14, vertical: 10),
                          decoration: BoxDecoration(
                            color: AppColors.primary.withValues(alpha: 0.08),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: const [
                              Icon(Icons.star_rounded, color: Colors.amber, size: 20),
                              SizedBox(width: 6),
                              Text('4.9/5',
                                  style: TextStyle(
                                      fontWeight: FontWeight.bold,
                                      color: AppColors.primary)),
                              SizedBox(width: 6),
                              Text('تقييم العملاء',
                                  style: TextStyle(
                                      fontSize: 13, color: AppColors.textSecondary)),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 16),

                  // ── قيمنا ──
                  const Align(
                    alignment: AlignmentDirectional.centerStart,
                    child: Text('قيمنا ومبادئنا',
                        style:
                            TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  ),
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      Expanded(
                        child: _ValueCard(
                          icon: Icons.verified_rounded,
                          color: const Color(0xFF43A047),
                          title: 'الجودة والأصالة',
                          desc: 'جميع منتجاتنا أصلية 100% مستوردة من مصادر موثوقة مع ضمان الوكيل',
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: _ValueCard(
                          icon: Icons.local_offer_rounded,
                          color: const Color(0xFFE53935),
                          title: 'أسعار تنافسية',
                          desc: 'أفضل الأسعار في السوق اليمني مع عروض وخصومات مستمرة',
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  _ValueCard(
                    icon: Icons.support_agent_rounded,
                    color: AppColors.primary,
                    title: 'دعم فني ممتاز',
                    desc:
                        'فريق دعم متخصص جاهز لمساعدتك قبل وبعد الشراء على مدار الساعة',
                    fullWidth: true,
                  ),

                  const SizedBox(height: 16),

                  // ── منتجاتنا ──
                  _SectionCard(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _SectionTitle(
                            icon: Icons.devices_rounded,
                            title: 'منتجاتنا',
                            color: AppColors.primary),
                        const SizedBox(height: 14),
                        Wrap(
                          spacing: 10,
                          runSpacing: 10,
                          children: const [
                            _ProductChip(icon: Icons.smartphone, label: 'هواتف ذكية'),
                            _ProductChip(icon: Icons.laptop_mac, label: 'لابتوبات'),
                            _ProductChip(icon: Icons.watch_rounded, label: 'ساعات ذكية'),
                            _ProductChip(icon: Icons.headphones_rounded, label: 'سماعات'),
                            _ProductChip(icon: Icons.tablet_mac, label: 'تابلت'),
                            _ProductChip(icon: Icons.cable_rounded, label: 'إكسسوارات'),
                          ],
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 16),

                  // ── العلامات التجارية ──
                  _SectionCard(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _SectionTitle(
                            icon: Icons.emoji_events_rounded,
                            title: 'العلامات التجارية',
                            color: const Color(0xFFFFC107)),
                        const SizedBox(height: 14),
                        Wrap(
                          spacing: 10,
                          runSpacing: 10,
                          children: const [
                            _BrandChip(label: 'Apple'),
                            _BrandChip(label: 'Samsung'),
                            _BrandChip(label: 'Xiaomi'),
                            _BrandChip(label: 'Huawei'),
                            _BrandChip(label: 'Oppo'),
                            _BrandChip(label: 'Vivo'),
                            _BrandChip(label: 'Realme'),
                            _BrandChip(label: 'Lenovo'),
                          ],
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 16),

                  // ── معلومات التواصل ──
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
                      children: const [
                        Icon(Icons.location_on_rounded, color: Colors.white, size: 32),
                        SizedBox(height: 8),
                        Text('عدن، اليمن',
                            style: TextStyle(
                                fontSize: 16,
                                fontWeight: FontWeight.bold,
                                color: Colors.white)),
                        SizedBox(height: 4),
                        Text('نوصل إلى جميع أنحاء اليمن',
                            style: TextStyle(fontSize: 13, color: Colors.white70)),
                        SizedBox(height: 12),
                        Text('store.update-aden.com',
                            style: TextStyle(
                                fontSize: 13,
                                color: Colors.white70,
                                decoration: TextDecoration.underline,
                                decorationColor: Colors.white70)),
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

// ─── helpers ───────────────────────────────────────────────────────────────

class _StatItem extends StatelessWidget {
  final String value;
  final String label;
  const _StatItem({required this.value, required this.label});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text(value,
            style: const TextStyle(
                fontSize: 22, fontWeight: FontWeight.bold, color: Colors.white)),
        const SizedBox(height: 4),
        Text(label,
            style: const TextStyle(fontSize: 12, color: Colors.white70)),
      ],
    );
  }
}

class _StatDivider extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
        height: 40, width: 1, color: Colors.white.withValues(alpha: 0.3));
  }
}

class _SectionCard extends StatelessWidget {
  final Widget child;
  const _SectionCard({required this.child});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
              color: Colors.black.withValues(alpha: 0.04),
              blurRadius: 8,
              offset: const Offset(0, 2))
        ],
      ),
      child: child,
    );
  }
}

class _SectionTitle extends StatelessWidget {
  final IconData icon;
  final String title;
  final Color color;
  const _SectionTitle(
      {required this.icon, required this.title, required this.color});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: color.withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(10),
          ),
          child: Icon(icon, color: color, size: 20),
        ),
        const SizedBox(width: 10),
        Text(title,
            style: TextStyle(
                fontSize: 16, fontWeight: FontWeight.bold, color: color)),
      ],
    );
  }
}

class _ValueCard extends StatelessWidget {
  final IconData icon;
  final Color color;
  final String title;
  final String desc;
  final bool fullWidth;
  const _ValueCard(
      {required this.icon,
      required this.color,
      required this.title,
      required this.desc,
      this.fullWidth = false});

  @override
  Widget build(BuildContext context) {
    final card = Container(
      padding: const EdgeInsets.all(14),
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
          Container(
            width: 42,
            height: 42,
            decoration: BoxDecoration(
              color: color.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(icon, color: color, size: 22),
          ),
          const SizedBox(height: 10),
          Text(title,
              style: const TextStyle(
                  fontSize: 14, fontWeight: FontWeight.bold)),
          const SizedBox(height: 6),
          Text(desc,
              style: const TextStyle(
                  fontSize: 12, color: AppColors.textSecondary, height: 1.5)),
        ],
      ),
    );
    return fullWidth ? SizedBox(width: double.infinity, child: card) : card;
  }
}

class _ProductChip extends StatelessWidget {
  final IconData icon;
  final String label;
  const _ProductChip({required this.icon, required this.label});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: AppColors.primary.withValues(alpha: 0.08),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: AppColors.primary.withValues(alpha: 0.2)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 16, color: AppColors.primary),
          const SizedBox(width: 6),
          Text(label,
              style: const TextStyle(
                  fontSize: 13,
                  color: AppColors.primary,
                  fontWeight: FontWeight.w500)),
        ],
      ),
    );
  }
}

class _BrandChip extends StatelessWidget {
  final String label;
  const _BrandChip({required this.label});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: AppColors.divider),
        boxShadow: [
          BoxShadow(
              color: Colors.black.withValues(alpha: 0.03),
              blurRadius: 4,
              offset: const Offset(0, 1))
        ],
      ),
      child: Text(label,
          style: const TextStyle(
              fontSize: 13,
              color: AppColors.textPrimary,
              fontWeight: FontWeight.w600)),
    );
  }
}
