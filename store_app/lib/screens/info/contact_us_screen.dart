import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../core/constants/app_colors.dart';

class ContactUsScreen extends StatelessWidget {
  const ContactUsScreen({super.key});

  Future<void> _launch(String url) async {
    final uri = Uri.parse(url);
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
        title: const Text('تواصل معنا', style: TextStyle(color: AppColors.textPrimary, fontWeight: FontWeight.bold)),
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
            // Header
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [AppColors.primary, Color(0xFF1a7a52)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(20),
              ),
              child: Column(
                children: const [
                  Icon(Icons.support_agent, size: 56, color: Colors.white),
                  SizedBox(height: 12),
                  Text('نحن هنا لمساعدتك', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white)),
                  SizedBox(height: 6),
                  Text('تواصل معنا عبر أي من الطرق التالية', style: TextStyle(fontSize: 13, color: Colors.white70)),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Contact items
            _ContactCard(
              icon: Icons.phone,
              color: AppColors.primary,
              title: 'رقم الهاتف',
              subtitle: '0777 116 668',
              onTap: () => _launch('tel:+967777116668'),
              buttonText: 'اتصل الآن',
            ),
            const SizedBox(height: 12),
            _ContactCard(
              icon: Icons.chat,
              color: const Color(0xFF25D366),
              title: 'واتساب',
              subtitle: '0780 800 007',
              onTap: () => _launch('https://wa.me/967780800007?text=مرحباً، أحتاج مساعدة'),
              buttonText: 'فتح واتساب',
            ),
            const SizedBox(height: 12),
            _ContactCard(
              icon: Icons.email_outlined,
              color: AppColors.error,
              title: 'البريد الإلكتروني',
              subtitle: 'info@update-aden.com',
              onTap: () => _launch('mailto:info@update-aden.com'),
              buttonText: 'أرسل إيميل',
            ),
            const SizedBox(height: 12),
            _ContactCard(
              icon: Icons.language,
              color: const Color(0xFF1877F2),
              title: 'الموقع الإلكتروني',
              subtitle: 'store.update-aden.com',
              onTap: () => _launch('https://store.update-aden.com'),
              buttonText: 'فتح الموقع',
            ),
            const SizedBox(height: 12),
            _ContactCard(
              icon: Icons.location_on_rounded,
              color: const Color(0xFFFFA000),
              title: 'العنوان',
              subtitle: 'عدن، اليمن',
              onTap: () {},
              buttonText: 'عدن',
            ),
            const SizedBox(height: 20),

            // أوقات العمل
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                boxShadow: [
                  BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 8, offset: const Offset(0, 2))
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        width: 40, height: 40,
                        decoration: BoxDecoration(
                          color: AppColors.primary.withValues(alpha: 0.1),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: const Icon(Icons.schedule_rounded, color: AppColors.primary, size: 20),
                      ),
                      const SizedBox(width: 10),
                      const Text('أوقات العمل',
                          style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    ],
                  ),
                  const SizedBox(height: 14),
                  _WorkingHoursRow(day: 'السبت - الخميس', hours: '8:00 صباحاً - 8:00 مساءً'),
                  const Divider(height: 16),
                  _WorkingHoursRow(day: 'الجمعة', hours: '9:00 صباحاً - 12:00 ظهراً'),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Social Media
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('تابعنا على السوشيال ميديا', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceAround,
                    children: [
                      _SocialButton(icon: Icons.facebook, color: const Color(0xFF1877F2), label: 'فيسبوك', onTap: () => _launch('https://facebook.com')),
                      _SocialButton(icon: Icons.camera_alt_outlined, color: const Color(0xFFE1306C), label: 'انستقرام', onTap: () => _launch('https://instagram.com')),
                      _SocialButton(icon: Icons.telegram, color: const Color(0xFF0088CC), label: 'تيليجرام', onTap: () => _launch('https://t.me/')),
                      _SocialButton(icon: Icons.chat_bubble_outline, color: const Color(0xFF25D366), label: 'واتساب', onTap: () => _launch('https://wa.me/967780800007')),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _ContactCard extends StatelessWidget {
  final IconData icon;
  final Color color;
  final String title;
  final String subtitle;
  final VoidCallback onTap;
  final String buttonText;

  const _ContactCard({
    required this.icon,
    required this.color,
    required this.title,
    required this.subtitle,
    required this.onTap,
    required this.buttonText,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 8, offset: const Offset(0, 2))],
      ),
      child: Row(
        children: [
          Container(
            width: 50,
            height: 50,
            decoration: BoxDecoration(color: color.withValues(alpha: 0.12), shape: BoxShape.circle),
            child: Icon(icon, color: color, size: 26),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
                const SizedBox(height: 3),
                Text(subtitle, style: const TextStyle(color: AppColors.textSecondary, fontSize: 13)),
              ],
            ),
          ),
          ElevatedButton(
            onPressed: onTap,
            style: ElevatedButton.styleFrom(
              backgroundColor: color,
              foregroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
              elevation: 0,
            ),
            child: Text(buttonText, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600)),
          ),
        ],
      ),
    );
  }
}

class _SocialButton extends StatelessWidget {
  final IconData icon;
  final Color color;
  final String label;
  final VoidCallback onTap;

  const _SocialButton({required this.icon, required this.color, required this.label, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Column(
        children: [
          Container(
            width: 52,
            height: 52,
            decoration: BoxDecoration(color: color.withValues(alpha: 0.12), shape: BoxShape.circle),
            child: Icon(icon, color: color, size: 28),
          ),
          const SizedBox(height: 6),
          Text(label, style: const TextStyle(fontSize: 11, color: AppColors.textSecondary)),
        ],
      ),
    );
  }
}

class _WorkingHoursRow extends StatelessWidget {
  final String day;
  final String hours;
  const _WorkingHoursRow({required this.day, required this.hours});

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(day,
            style: const TextStyle(
                fontWeight: FontWeight.w600,
                fontSize: 13,
                color: AppColors.textPrimary)),
        Text(hours,
            style: const TextStyle(
                fontSize: 13, color: AppColors.textSecondary)),
      ],
    );
  }
}
