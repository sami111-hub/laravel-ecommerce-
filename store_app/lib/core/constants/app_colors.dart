import 'package:flutter/material.dart';

/// 🎨 Update Aden Design System - Colors
/// Matching website: https://store.update-aden.com
/// Warm Brown Premium Theme

class AppColors {
  // ═══════════════════════════════════════════════════════════════
  // 🟤 PRIMARY - Warm Brown (Material Brown 700)
  // ═══════════════════════════════════════════════════════════════
  static const Color primary = Color(0xFF5D4037);
  static const Color primaryDark = Color(0xFF4E342E);
  static const Color primaryDarkest = Color(0xFF3E2723);
  static const Color primaryLight = Color(0xFFFFF8F3);
  static const Color primaryLight100 = Color(0xFFFFEDE0);
  static const Color primaryLight200 = Color(0xFFFFD9BF);

  // ═══════════════════════════════════════════════════════════════
  // 🟠 ACCENT / SECONDARY
  // ═══════════════════════════════════════════════════════════════
  static const Color accent = Color(0xFF8D6E63);
  static const Color secondary = Color(0xFFFF9800);
  static const Color secondaryDark = Color(0xFFF57C00);
  static const Color secondaryLight = Color(0xFFFFF3E0);

  // ═══════════════════════════════════════════════════════════════
  // ✅ SEMANTIC COLORS
  // ═══════════════════════════════════════════════════════════════
  static const Color success = Color(0xFF43A047);
  static const Color error = Color(0xFFD84315);
  static const Color warning = Color(0xFFFF9800);
  static const Color info = Color(0xFF2196F3);

  // ═══════════════════════════════════════════════════════════════
  // 🎨 BACKGROUND SYSTEM
  // ═══════════════════════════════════════════════════════════════
  static const Color scaffoldBg = Color(0xFFFAF9F7);
  static const Color background = Color(0xFFFAF9F7);
  static const Color cardBackground = Color(0xFFFFFFFF);
  static const Color surface = Color(0xFFFFFFFF);
  static const Color sectionBg = Color(0xFFF5F0EB);

  // ═══════════════════════════════════════════════════════════════
  // 📝 TEXT COLORS
  // ═══════════════════════════════════════════════════════════════
  static const Color textPrimary = Color(0xFF3E2723);
  static const Color textSecondary = Color(0xFF8D6E63);
  static const Color textHint = Color(0xFFBCAAA4);
  static const Color textMuted = Color(0xFFD7CCC8);

  // ═══════════════════════════════════════════════════════════════
  // 🔲 BORDERS & DIVIDERS
  // ═══════════════════════════════════════════════════════════════
  static const Color border = Color(0xFFEFEBE9);
  static const Color divider = Color(0xFFEFEBE9);
  static const Color cardBorder = Color(0xFFF3F4F6);

  // ═══════════════════════════════════════════════════════════════
  // ⭐ SPECIAL COLORS
  // ═══════════════════════════════════════════════════════════════
  static const Color star = Color(0xFFF59E0B);
  static const Color heart = Color(0xFFE53935);
  static const Color discount = Color(0xFFD84315);

  // ═══════════════════════════════════════════════════════════════
  // 🌈 GRADIENTS
  // ═══════════════════════════════════════════════════════════════
  static const LinearGradient primaryGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [Color(0xFF5D4037), Color(0xFF4E342E)],
  );

  static const LinearGradient primaryIntenseGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [Color(0xFF5D4037), Color(0xFF3E2723)],
  );

  static const LinearGradient accentGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [Color(0xFF8D6E63), Color(0xFF5D4037)],
  );

  static const LinearGradient secondaryGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [Color(0xFFFF9800), Color(0xFFF57C00)],
  );

  static const LinearGradient warmGradient = LinearGradient(
    begin: Alignment.topCenter,
    end: Alignment.bottomCenter,
    colors: [Color(0xFFFFF8F3), Color(0xFFFAF9F7)],
  );

  // Hero slider gradients (matching website)
  static const List<LinearGradient> sliderGradients = [
    LinearGradient(begin: Alignment.topLeft, end: Alignment.bottomRight, colors: [Color(0xFF667EEA), Color(0xFF764BA2)]),
    LinearGradient(begin: Alignment.topLeft, end: Alignment.bottomRight, colors: [Color(0xFFF093FB), Color(0xFFF5576C)]),
    LinearGradient(begin: Alignment.topLeft, end: Alignment.bottomRight, colors: [Color(0xFF4FACFE), Color(0xFF00F2FE)]),
  ];
}

/// 📐 SPACING SYSTEM (8pt Grid)
class AppSpacing {
  static const double xs = 4.0;
  static const double sm = 8.0;
  static const double md = 12.0;
  static const double lg = 16.0;
  static const double xl = 20.0;
  static const double xxl = 24.0;
  static const double xxxl = 32.0;
}

/// 🔲 BORDER RADIUS (matching website)
class AppRadius {
  static const double small = 8.0;
  static const double card = 12.0;
  static const double button = 12.0;
  static const double modal = 20.0;
  static const double badge = 20.0;
  static const double input = 16.0;
  static const double pill = 999.0;
  static const double circular = 999.0;

  static BorderRadius get smallRadius => BorderRadius.circular(small);
  static BorderRadius get cardRadius => BorderRadius.circular(card);
  static BorderRadius get buttonRadius => BorderRadius.circular(button);
  static BorderRadius get modalRadius => BorderRadius.circular(modal);
  static BorderRadius get badgeRadius => BorderRadius.circular(badge);
  static BorderRadius get inputRadius => BorderRadius.circular(input);
  static BorderRadius get pillRadius => BorderRadius.circular(pill);
}

/// 📏 FONT SIZES
class AppFontSize {
  static const double h1 = 24.0;
  static const double h2 = 20.0;
  static const double h3 = 17.0;
  static const double bodyLarge = 15.0;
  static const double bodyNormal = 14.0;
  static const double bodySmall = 13.0;
  static const double caption = 12.0;
  static const double small = 11.0;
  static const double price = 16.0;
  static const double priceLarge = 20.0;
}

/// 🌫 SHADOWS (matching website brown-tinted)
class AppShadows {
  static const BoxShadow none = BoxShadow(color: Colors.transparent);

  static const BoxShadow cardShadow = BoxShadow(
    color: Color(0x0F000000),
    blurRadius: 15,
    offset: Offset(0, 4),
  );

  static const BoxShadow lightShadow = BoxShadow(
    color: Color(0x0A000000),
    blurRadius: 6,
    offset: Offset(0, 2),
  );

  static const BoxShadow buttonShadow = BoxShadow(
    color: Color(0x405D4037),
    blurRadius: 15,
    offset: Offset(0, 4),
  );

  static const BoxShadow brownShadow = BoxShadow(
    color: Color(0x405D4037),
    blurRadius: 15,
    offset: Offset(0, 4),
  );

  static const BoxShadow elevatedShadow = BoxShadow(
    color: Color(0x1A5D4037),
    blurRadius: 30,
    offset: Offset(0, 10),
  );
}
