import 'package:intl/intl.dart';

class Helpers {
  // أسعار الصرف الافتراضية (من إعدادات الموقع)
  // يتم تحديثها من CurrencyProvider
  static double exchangeRateSar = 3.75;
  static double exchangeRateYer = 535.0;

  /// تحديث أسعار الصرف من الخادم
  static void updateExchangeRates({
    required double rateSar,
    required double rateYer,
  }) {
    exchangeRateSar = rateSar;
    exchangeRateYer = rateYer;
  }

  /// تنسيق السعر بالريال اليمني (من سعر بالدولار)
  static String formatPrice(dynamic priceUsd) {
    final p = (double.tryParse(priceUsd.toString()) ?? 0) * exchangeRateYer;
    return '${NumberFormat('#,###').format(p.round())} ر.ي';
  }

  /// تنسيق السعر بالدولار
  static String formatPriceUsd(dynamic priceUsd) {
    final p = double.tryParse(priceUsd.toString()) ?? 0;
    return '\$${p.toStringAsFixed(2)}';
  }

  /// تنسيق السعر بالريال السعودي (من سعر بالدولار)
  static String formatPriceSar(dynamic priceUsd) {
    final p = (double.tryParse(priceUsd.toString()) ?? 0) * exchangeRateSar;
    return '${p.toStringAsFixed(2)} ر.س';
  }

  /// تنسيق الأسعار الثلاثة معاً
  static String formatAllPrices(dynamic priceUsd) {
    final usd = formatPriceUsd(priceUsd);
    final sar = formatPriceSar(priceUsd);
    final yer = formatPrice(priceUsd);
    return '$usd | $sar | $yer';
  }

  /// الحصول على السعر المحول مباشرة
  static double convertToYer(dynamic priceUsd) {
    return (double.tryParse(priceUsd.toString()) ?? 0) * exchangeRateYer;
  }

  static double convertToSar(dynamic priceUsd) {
    return (double.tryParse(priceUsd.toString()) ?? 0) * exchangeRateSar;
  }

  static String formatDate(String? dateStr) {
    if (dateStr == null) return '';
    try {
      final date = DateTime.parse(dateStr);
      return DateFormat('yyyy/MM/dd - hh:mm a').format(date);
    } catch (_) {
      return dateStr;
    }
  }

  static String orderStatus(String status) {
    switch (status) {
      case 'pending':
        return 'قيد الانتظار';
      case 'processing':
        return 'قيد التجهيز';
      case 'shipped':
        return 'تم الشحن';
      case 'delivered':
        return 'تم التوصيل';
      case 'cancelled':
        return 'ملغي';
      default:
        return status;
    }
  }
}
