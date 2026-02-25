import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../../core/constants/app_colors.dart';
import '../../providers/auth_provider.dart';
import '../../widgets/custom_button.dart';
import '../../widgets/custom_text_field.dart';
import '../info/info_screen.dart';
import '../info/contact_us_screen.dart';
import '../info/about_us_screen.dart';
import '../info/faq_screen.dart';
import '../info/return_policy_screen.dart';

/// 👤 Profile Screen - Update Aden Design System
class ProfileScreen extends ConsumerStatefulWidget {
  const ProfileScreen({super.key});

  @override
  ConsumerState<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends ConsumerState<ProfileScreen> {
  @override
  Widget build(BuildContext context) {
    final authAsync = ref.watch(authStateProvider);
    final user = authAsync.valueOrNull;

    return Scaffold(
      backgroundColor: AppColors.scaffoldBg,
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            children: [
              // ═══════════════════════════════════════════════════════════
              // 👤 PROFILE HEADER - Gradient
              // ═══════════════════════════════════════════════════════════
              Container(
                width: double.infinity,
                padding: const EdgeInsets.fromLTRB(24, 28, 24, 28),
                decoration: const BoxDecoration(
                  gradient: AppColors.primaryIntenseGradient,
                  borderRadius: BorderRadius.only(
                    bottomLeft: Radius.circular(28),
                    bottomRight: Radius.circular(28),
                  ),
                ),
                child: Column(
                  children: [
                    // Avatar
                    Container(
                      width: 80,
                      height: 80,
                      decoration: BoxDecoration(
                        color: Colors.white.withValues(alpha: 0.2),
                        shape: BoxShape.circle,
                        border: Border.all(color: Colors.white.withValues(alpha: 0.4), width: 2),
                      ),
                      child: Center(
                        child: Text(
                          (user?.name ?? 'م')[0].toUpperCase(),
                          style: const TextStyle(
                            fontSize: 32,
                            fontWeight: FontWeight.w800,
                            color: Colors.white,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 14),
                    Text(
                      user?.name ?? 'المستخدم',
                      style: const TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.w700,
                        color: Colors.white,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      user?.email ?? '',
                      style: TextStyle(
                        color: Colors.white.withValues(alpha: 0.8),
                        fontSize: 13,
                      ),
                    ),
                    if (user?.phone != null) ...[
                      const SizedBox(height: 2),
                      Text(
                        user!.phone!,
                        style: TextStyle(
                          color: Colors.white.withValues(alpha: 0.7),
                          fontSize: 13,
                        ),
                      ),
                    ],
                  ],
                ),
              ),
              const SizedBox(height: AppSpacing.lg),

              // ═══════════════════════════════════════════════════════════
              // 📝 ACCOUNT MENU
              // ═══════════════════════════════════════════════════════════
              Container(
                margin: const EdgeInsets.symmetric(horizontal: AppSpacing.lg),
                decoration: BoxDecoration(
                  color: AppColors.cardBackground,
                  borderRadius: AppRadius.cardRadius,
                  boxShadow: const [AppShadows.cardShadow],
                ),
                child: Column(
                  children: [
                    _buildMenuItem(Icons.person_outline, 'تعديل الملف الشخصي', () => _showEditProfileSheet()),
                    _divider(),
                    _buildMenuItem(Icons.lock_outline, 'تغيير كلمة المرور', () => _showChangePasswordSheet()),
                    _divider(),
                    _buildMenuItem(Icons.location_on_outlined, 'العناوين', () => context.push('/addresses')),
                    _divider(),
                    _buildMenuItem(Icons.receipt_long_outlined, 'طلباتي', () => context.push('/orders')),
                    _divider(),
                    _buildMenuItem(Icons.phone_android, 'هواتفي', () => context.push('/phones')),
                  ],
                ),
              ),
              const SizedBox(height: AppSpacing.lg),

              // ═══════════════════════════════════════════════════════════
              // ℹ️ INFO SECTION
              // ═══════════════════════════════════════════════════════════
              Container(
                margin: const EdgeInsets.symmetric(horizontal: AppSpacing.lg),
                decoration: BoxDecoration(
                  color: AppColors.cardBackground,
                  borderRadius: AppRadius.cardRadius,
                  boxShadow: const [AppShadows.cardShadow],
                ),
                child: Column(
                  children: [
                    _buildSectionHeader('معلومات المتجر'),
                    _buildMenuItem(Icons.info_outline, 'من نحن', () => Navigator.push(context, MaterialPageRoute(
                      builder: (_) => const AboutUsScreen(),
                    ))),
                    _divider(),
                    _buildMenuItem(Icons.quiz_outlined, 'الأسئلة الشائعة', () => Navigator.push(context, MaterialPageRoute(
                      builder: (_) => const FaqScreen(),
                    ))),
                    _divider(),
                    _buildMenuItem(Icons.assignment_return_outlined, 'سياسة الإرجاع', () => Navigator.push(context, MaterialPageRoute(
                      builder: (_) => const ReturnPolicyScreen(),
                    ))),
                    _divider(),
                    _buildMenuItem(Icons.description_outlined, 'سياسة الاستخدام', () => Navigator.push(context, MaterialPageRoute(
                      builder: (_) => const InfoScreen(title: 'سياسة الاستخدام', icon: Icons.policy_outlined, content: AppInfoContent.termsOfUse),
                    ))),
                    _divider(),
                    _buildMenuItem(Icons.payment_outlined, 'وسائل الدفع', () => Navigator.push(context, MaterialPageRoute(
                      builder: (_) => const InfoScreen(title: 'وسائل الدفع', icon: Icons.payment_outlined, content: AppInfoContent.paymentMethods),
                    ))),
                    _divider(),
                    _buildMenuItem(Icons.support_agent_outlined, 'تواصل معنا', () => Navigator.push(context, MaterialPageRoute(
                      builder: (_) => const ContactUsScreen(),
                    ))),
                  ],
                ),
              ),
              const SizedBox(height: AppSpacing.lg),

              // ═══════════════════════════════════════════════════════════
              // 🚪 LOGOUT BUTTON
              // ═══════════════════════════════════════════════════════════
              Container(
                margin: const EdgeInsets.symmetric(horizontal: AppSpacing.lg),
                decoration: BoxDecoration(
                  color: AppColors.cardBackground,
                  borderRadius: AppRadius.cardRadius,
                  boxShadow: const [AppShadows.cardShadow],
                ),
                child: _buildMenuItem(
                  Icons.logout,
                  'تسجيل الخروج',
                  () => _showLogoutDialog(),
                  color: AppColors.error,
                ),
              ),
              const SizedBox(height: AppSpacing.xxl),

              // App info
              const Text(
                'الإصدار 1.0.0',
                style: TextStyle(
                  color: AppColors.textSecondary,
                  fontSize: AppFontSize.caption,
                ),
              ),
              const SizedBox(height: AppSpacing.xxl),
            ],
          ),
        ),
      ),
    );
  }

  /// 📌 Section Header
  Widget _buildSectionHeader(String title) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(AppSpacing.lg, AppSpacing.md, AppSpacing.lg, AppSpacing.xs),
      child: Row(
        children: [
          Text(
            title,
            style: const TextStyle(
              fontSize: AppFontSize.caption,
              fontWeight: FontWeight.w600,
              color: AppColors.textSecondary,
            ),
          ),
        ],
      ),
    );
  }

  /// 📋 Menu Item Widget
  Widget _buildMenuItem(IconData icon, String title, VoidCallback onTap, {Color? color}) {
    return ListTile(
      leading: Container(
        width: 40,
        height: 40,
        decoration: BoxDecoration(
          color: (color ?? AppColors.primary).withValues(alpha: 0.1),
          borderRadius: BorderRadius.circular(AppRadius.card),
        ),
        child: Icon(icon, color: color ?? AppColors.primary, size: 20),
      ),
      title: Text(
        title,
        style: TextStyle(
          fontWeight: FontWeight.w500,
          color: color ?? AppColors.textPrimary,
        ),
      ),
      trailing: Icon(
        Icons.arrow_forward_ios,
        size: 16,
        color: color ?? AppColors.textSecondary,
      ),
      onTap: onTap,
    );
  }

  Widget _divider() => const Divider(height: 1, indent: 70);

  /// 🚪 Logout Dialog
  void _showLogoutDialog() {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: AppRadius.modalRadius),
        title: const Text('تسجيل الخروج'),
        content: const Text('هل أنت متأكد من تسجيل الخروج؟'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('إلغاء'),
          ),
          TextButton(
            onPressed: () async {
              Navigator.pop(context);
              await ref.read(authStateProvider.notifier).logout();
              if (mounted) context.go('/login');
            },
            child: const Text('خروج', style: TextStyle(color: AppColors.error)),
          ),
        ],
      ),
    );
  }

  /// ✏️ Edit Profile Sheet
  void _showEditProfileSheet() {
    final user = ref.read(authStateProvider).valueOrNull;
    final nameCtrl = TextEditingController(text: user?.name ?? '');
    final phoneCtrl = TextEditingController(text: user?.phone ?? '');
    bool saving = false;

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(AppRadius.modal)),
      ),
      builder: (_) => StatefulBuilder(
        builder: (ctx, setSheetState) => Padding(
          padding: EdgeInsets.fromLTRB(
            AppSpacing.xl,
            AppSpacing.xl,
            AppSpacing.xl,
            MediaQuery.of(ctx).viewInsets.bottom + AppSpacing.xl,
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: Container(
                  width: 40,
                  height: 4,
                  decoration: BoxDecoration(
                    color: AppColors.border,
                    borderRadius: BorderRadius.circular(2),
                  ),
                ),
              ),
              const SizedBox(height: AppSpacing.lg),
              const Text(
                'تعديل الملف الشخصي',
                style: TextStyle(
                  fontSize: AppFontSize.h1 - 2,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: AppSpacing.xl),
              CustomTextField(label: 'الاسم', controller: nameCtrl, prefixIcon: Icons.person_outline),
              const SizedBox(height: AppSpacing.lg),
              CustomTextField(label: 'رقم الهاتف', controller: phoneCtrl, prefixIcon: Icons.phone_outlined, keyboardType: TextInputType.phone),
              const SizedBox(height: AppSpacing.xl),
              SizedBox(
                width: double.infinity,
                child: CustomButton(
                  text: 'حفظ',
                  isLoading: saving,
                  onPressed: () async {
                    setSheetState(() => saving = true);
                    final ok = await ref.read(authStateProvider.notifier).updateProfile(
                      name: nameCtrl.text.trim(),
                      phone: phoneCtrl.text.trim().isEmpty ? null : phoneCtrl.text.trim(),
                    );
                    if (ctx.mounted) {
                      Navigator.pop(ctx);
                      ScaffoldMessenger.of(ctx).showSnackBar(
                        SnackBar(
                          content: Text(ok ? 'تم تحديث الملف الشخصي' : 'فشل التحديث'),
                          backgroundColor: ok ? AppColors.success : AppColors.error,
                          behavior: SnackBarBehavior.floating,
                          shape: RoundedRectangleBorder(borderRadius: AppRadius.cardRadius),
                        ),
                      );
                    }
                  },
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  /// 🔒 Change Password Sheet
  void _showChangePasswordSheet() {
    final currentPassCtrl = TextEditingController();
    final newPassCtrl = TextEditingController();
    final confirmPassCtrl = TextEditingController();
    bool saving = false;

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(AppRadius.modal)),
      ),
      builder: (_) => StatefulBuilder(
        builder: (ctx, setSheetState) => Padding(
          padding: EdgeInsets.fromLTRB(
            AppSpacing.xl,
            AppSpacing.xl,
            AppSpacing.xl,
            MediaQuery.of(ctx).viewInsets.bottom + AppSpacing.xl,
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: Container(
                  width: 40,
                  height: 4,
                  decoration: BoxDecoration(
                    color: AppColors.border,
                    borderRadius: BorderRadius.circular(2),
                  ),
                ),
              ),
              const SizedBox(height: AppSpacing.lg),
              const Text(
                'تغيير كلمة المرور',
                style: TextStyle(
                  fontSize: AppFontSize.h1 - 2,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: AppSpacing.xl),
              CustomTextField(label: 'كلمة المرور الحالية', controller: currentPassCtrl, obscureText: true, prefixIcon: Icons.lock_outline),
              const SizedBox(height: AppSpacing.md),
              CustomTextField(label: 'كلمة المرور الجديدة', controller: newPassCtrl, obscureText: true, prefixIcon: Icons.lock_outline),
              const SizedBox(height: AppSpacing.md),
              CustomTextField(label: 'تأكيد كلمة المرور', controller: confirmPassCtrl, obscureText: true, prefixIcon: Icons.lock_outline),
              const SizedBox(height: AppSpacing.xl),
              SizedBox(
                width: double.infinity,
                child: CustomButton(
                  text: 'تغيير',
                  isLoading: saving,
                  onPressed: () async {
                    if (newPassCtrl.text != confirmPassCtrl.text) {
                      ScaffoldMessenger.of(ctx).showSnackBar(
                        SnackBar(
                          content: const Text('كلمة المرور غير متطابقة'),
                          backgroundColor: AppColors.error,
                          behavior: SnackBarBehavior.floating,
                          shape: RoundedRectangleBorder(borderRadius: AppRadius.cardRadius),
                        ),
                      );
                      return;
                    }
                    setSheetState(() => saving = true);
                    final ok = await ref.read(authStateProvider.notifier).changePassword(
                      currentPassword: currentPassCtrl.text,
                      newPassword: newPassCtrl.text,
                      confirmPassword: confirmPassCtrl.text,
                    );
                    if (ctx.mounted) {
                      Navigator.pop(ctx);
                      ScaffoldMessenger.of(ctx).showSnackBar(
                        SnackBar(
                          content: Text(ok ? 'تم تغيير كلمة المرور' : 'فشل تغيير كلمة المرور'),
                          backgroundColor: ok ? AppColors.success : AppColors.error,
                          behavior: SnackBarBehavior.floating,
                          shape: RoundedRectangleBorder(borderRadius: AppRadius.cardRadius),
                        ),
                      );
                    }
                  },
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
