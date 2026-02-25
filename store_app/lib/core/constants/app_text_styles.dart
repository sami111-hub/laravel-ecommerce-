import 'package:flutter/material.dart';
import 'app_colors.dart';

class AppTextStyles {
  static const TextStyle h1 = TextStyle(fontSize: 28, fontWeight: FontWeight.w900, color: AppColors.textPrimary, fontFamily: 'Cairo');
  static const TextStyle h2 = TextStyle(fontSize: 22, fontWeight: FontWeight.w700, color: AppColors.textPrimary, fontFamily: 'Cairo');
  static const TextStyle h3 = TextStyle(fontSize: 18, fontWeight: FontWeight.w600, color: AppColors.textPrimary, fontFamily: 'Cairo');
  static const TextStyle body = TextStyle(fontSize: 14, color: AppColors.textPrimary, fontFamily: 'Cairo');
  static const TextStyle bodySmall = TextStyle(fontSize: 12, color: AppColors.textSecondary, fontFamily: 'Cairo');
  static const TextStyle price = TextStyle(fontSize: 20, fontWeight: FontWeight.w800, color: AppColors.primary, fontFamily: 'Cairo');
  static const TextStyle priceSmall = TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: AppColors.primary, fontFamily: 'Cairo');
  static const TextStyle priceOld = TextStyle(fontSize: 12, color: AppColors.textHint, decoration: TextDecoration.lineThrough, fontFamily: 'Cairo');
  static const TextStyle button = TextStyle(fontSize: 16, fontWeight: FontWeight.w700, color: Colors.white, fontFamily: 'Cairo');
  static const TextStyle sectionTitle = TextStyle(fontSize: 20, fontWeight: FontWeight.w700, color: AppColors.textPrimary, fontFamily: 'Cairo');
}
