import 'package:flutter/material.dart';
import 'app_colors.dart';

/// أيقونات وألوان التصنيفات
class CategoryIcons {
  // خريطة الأيقونات حسب اسم التصنيف
  static final Map<String, IconData> _icons = {
    // الهواتف
    'هواتف': Icons.smartphone_rounded,
    'جوالات': Icons.smartphone_rounded,
    'موبايلات': Icons.smartphone_rounded,
    'phones': Icons.smartphone_rounded,
    'mobile': Icons.smartphone_rounded,
    
    // اللابتوبات
    'لابتوب': Icons.laptop_mac_rounded,
    'لابتوبات': Icons.laptop_mac_rounded,
    'حاسوب': Icons.laptop_mac_rounded,
    'كمبيوتر': Icons.computer_rounded,
    'laptop': Icons.laptop_mac_rounded,
    'laptops': Icons.laptop_mac_rounded,
    
    // التابلت
    'تابلت': Icons.tablet_mac_rounded,
    'أجهزة لوحية': Icons.tablet_mac_rounded,
    'tablet': Icons.tablet_mac_rounded,
    'tablets': Icons.tablet_mac_rounded,
    'ipad': Icons.tablet_mac_rounded,
    
    // الساعات
    'ساعات': Icons.watch_rounded,
    'ساعات ذكية': Icons.watch_rounded,
    'smartwatch': Icons.watch_rounded,
    'watches': Icons.watch_rounded,
    
    // السماعات
    'سماعات': Icons.headphones_rounded,
    'سماعات بلوتوث': Icons.headset_rounded,
    'headphones': Icons.headphones_rounded,
    'earphones': Icons.earbuds_rounded,
    'airpods': Icons.earbuds_rounded,
    
    // الإكسسوارات
    'إكسسوارات': Icons.cable_rounded,
    'اكسسوارات': Icons.cable_rounded,
    'ملحقات': Icons.extension_rounded,
    'accessories': Icons.cable_rounded,
    
    // الشواحن
    'شواحن': Icons.power_rounded,
    'شاحن': Icons.power_rounded,
    'chargers': Icons.power_rounded,
    
    // الكفرات والحافظات
    'كفرات': Icons.phone_android_rounded,
    'حافظات': Icons.cases_rounded,
    'cases': Icons.cases_rounded,
    'covers': Icons.phone_android_rounded,
    
    // ألعاب
    'ألعاب': Icons.sports_esports_rounded,
    'gaming': Icons.sports_esports_rounded,
    'games': Icons.sports_esports_rounded,
    'بلايستيشن': Icons.sports_esports_rounded,
    'playstation': Icons.sports_esports_rounded,
    
    // كاميرات
    'كاميرا': Icons.camera_alt_rounded,
    'كاميرات': Icons.camera_alt_rounded,
    'camera': Icons.camera_alt_rounded,
    
    // تلفزيونات
    'تلفزيون': Icons.tv_rounded,
    'شاشات': Icons.tv_rounded,
    'tv': Icons.tv_rounded,
    
    // باور بانك
    'بطاريات': Icons.battery_charging_full_rounded,
    'باور بانك': Icons.battery_charging_full_rounded,
    'powerbank': Icons.battery_charging_full_rounded,
    
    // طابعات
    'طابعات': Icons.print_rounded,
    'printer': Icons.print_rounded,
    
    // روتر
    'روتر': Icons.router_rounded,
    'راوتر': Icons.router_rounded,
    'router': Icons.router_rounded,
    'networking': Icons.router_rounded,
    
    // أخرى
    'عروض': Icons.local_offer_rounded,
    'offers': Icons.local_offer_rounded,
    'جديد': Icons.fiber_new_rounded,
    'new': Icons.fiber_new_rounded,
  };

  // خريطة الألوان حسب اسم التصنيف
  static final Map<String, Color> _colors = {
    'هواتف': const Color(0xFF1E88E5),
    'جوالات': const Color(0xFF1E88E5),
    'phones': const Color(0xFF1E88E5),
    
    'لابتوب': const Color(0xFF5E35B1),
    'لابتوبات': const Color(0xFF5E35B1),
    'laptop': const Color(0xFF5E35B1),
    
    'تابلت': const Color(0xFF00897B),
    'tablet': const Color(0xFF00897B),
    
    'ساعات': const Color(0xFFD81B60),
    'smartwatch': const Color(0xFFD81B60),
    
    'سماعات': const Color(0xFFFF6F00),
    'headphones': const Color(0xFFFF6F00),
    
    'إكسسوارات': const Color(0xFF43A047),
    'اكسسوارات': const Color(0xFF43A047),
    'accessories': const Color(0xFF43A047),
    
    'ألعاب': const Color(0xFFE53935),
    'gaming': const Color(0xFFE53935),
    
    'كاميرات': const Color(0xFF6D4C41),
    'camera': const Color(0xFF6D4C41),
    
    'عروض': const Color(0xFFE53935),
    'offers': const Color(0xFFE53935),
  };

  /// الحصول على أيقونة التصنيف
  static IconData getIcon(String? categoryName) {
    if (categoryName == null) return Icons.category_rounded;
    
    final name = categoryName.toLowerCase().trim();
    
    // البحث عن تطابق
    for (final entry in _icons.entries) {
      if (name.contains(entry.key.toLowerCase())) {
        return entry.value;
      }
    }
    
    return Icons.category_rounded;
  }

  /// الحصول على لون التصنيف
  static Color getColor(String? categoryName) {
    if (categoryName == null) return AppColors.primary;
    
    final name = categoryName.toLowerCase().trim();
    
    for (final entry in _colors.entries) {
      if (name.contains(entry.key.toLowerCase())) {
        return entry.value;
      }
    }
    
    return AppColors.primary;
  }

  /// الحصول على لون الخلفية الفاتح للتصنيف
  static Color getBackgroundColor(String? categoryName) {
    return getColor(categoryName).withValues(alpha: 0.12);
  }
}
