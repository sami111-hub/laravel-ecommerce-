import 'package:flutter/material.dart';
import '../core/constants/app_colors.dart';
import '../core/utils/helpers.dart';

/// ويدجت عرض الأسعار بالعملات الثلاث
class MultiCurrencyPrice extends StatelessWidget {
  final double priceUsd;
  final double? oldPriceUsd;
  final double? priceSar;
  final double? priceYer;
  final double? oldPriceSar;
  final double? oldPriceYer;
  final bool showAllCurrencies;
  final double mainFontSize;
  final double secondaryFontSize;
  final bool compact;

  const MultiCurrencyPrice({
    super.key,
    required this.priceUsd,
    this.oldPriceUsd,
    this.priceSar,
    this.priceYer,
    this.oldPriceSar,
    this.oldPriceYer,
    this.showAllCurrencies = true,
    this.mainFontSize = 14,
    this.secondaryFontSize = 10,
    this.compact = false,
  });

  bool get hasDiscount => oldPriceUsd != null && oldPriceUsd! > priceUsd;

  @override
  Widget build(BuildContext context) {
    if (compact) {
      return _buildCompact();
    }
    return _buildFull();
  }

  Widget _buildCompact() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      mainAxisSize: MainAxisSize.min,
      children: [
        // السعر الرئيسي بالريال اليمني
        Text(
          _formatYer(priceYer ?? Helpers.convertToYer(priceUsd)),
          style: TextStyle(
            fontSize: mainFontSize,
            fontWeight: FontWeight.bold,
            color: AppColors.primary,
          ),
        ),
        // سعر الخصم
        if (hasDiscount)
          Text(
            _formatYer(oldPriceYer ?? Helpers.convertToYer(oldPriceUsd!)),
            style: TextStyle(
              fontSize: secondaryFontSize,
              color: AppColors.textHint,
              decoration: TextDecoration.lineThrough,
            ),
          ),
        // العملات الأخرى
        if (showAllCurrencies)
          Text(
            '${Helpers.formatPriceUsd(priceUsd)}  •  ${Helpers.formatPriceSar(priceUsd)}',
            style: TextStyle(
              fontSize: secondaryFontSize,
              color: AppColors.textSecondary,
            ),
          ),
      ],
    );
  }

  Widget _buildFull() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      mainAxisSize: MainAxisSize.min,
      children: [
        // الريال اليمني (الأساسي)
        Row(
          children: [
            Text(
              _formatYer(priceYer ?? Helpers.convertToYer(priceUsd)),
              style: TextStyle(
                fontSize: mainFontSize,
                fontWeight: FontWeight.bold,
                color: AppColors.primary,
              ),
            ),
            if (hasDiscount) ...[
              const SizedBox(width: 8),
              Text(
                _formatYer(oldPriceYer ?? Helpers.convertToYer(oldPriceUsd!)),
                style: TextStyle(
                  fontSize: secondaryFontSize,
                  color: AppColors.textHint,
                  decoration: TextDecoration.lineThrough,
                ),
              ),
            ],
          ],
        ),
        if (showAllCurrencies) ...[
          const SizedBox(height: 4),
          // الدولار
          Row(
            children: [
              Text(
                Helpers.formatPriceUsd(priceUsd),
                style: TextStyle(
                  fontSize: secondaryFontSize + 1,
                  color: AppColors.textSecondary,
                ),
              ),
              if (hasDiscount) ...[
                const SizedBox(width: 6),
                Text(
                  Helpers.formatPriceUsd(oldPriceUsd!),
                  style: TextStyle(
                    fontSize: secondaryFontSize,
                    color: AppColors.textHint,
                    decoration: TextDecoration.lineThrough,
                  ),
                ),
              ],
            ],
          ),
          const SizedBox(height: 2),
          // الريال السعودي
          Row(
            children: [
              Text(
                Helpers.formatPriceSar(priceUsd),
                style: TextStyle(
                  fontSize: secondaryFontSize + 1,
                  color: AppColors.textSecondary,
                ),
              ),
              if (hasDiscount) ...[
                const SizedBox(width: 6),
                Text(
                  Helpers.formatPriceSar(oldPriceUsd!),
                  style: TextStyle(
                    fontSize: secondaryFontSize,
                    color: AppColors.textHint,
                    decoration: TextDecoration.lineThrough,
                  ),
                ),
              ],
            ],
          ),
        ],
      ],
    );
  }

  String _formatYer(double price) {
    return '${price.toStringAsFixed(0).replaceAllMapped(
          RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'),
          (Match m) => '${m[1]},',
        )} ر.ي';
  }
}

/// ويدجت مختصر لعرض السعر بعملة واحدة
class PriceText extends StatelessWidget {
  final double priceUsd;
  final String currency;
  final double fontSize;
  final FontWeight fontWeight;
  final Color? color;
  final bool lineThrough;

  const PriceText({
    super.key,
    required this.priceUsd,
    this.currency = 'YER',
    this.fontSize = 14,
    this.fontWeight = FontWeight.bold,
    this.color,
    this.lineThrough = false,
  });

  @override
  Widget build(BuildContext context) {
    return Text(
      _getFormattedPrice(),
      style: TextStyle(
        fontSize: fontSize,
        fontWeight: fontWeight,
        color: color ?? AppColors.primary,
        decoration: lineThrough ? TextDecoration.lineThrough : null,
      ),
    );
  }

  String _getFormattedPrice() {
    switch (currency.toUpperCase()) {
      case 'USD':
        return Helpers.formatPriceUsd(priceUsd);
      case 'SAR':
        return Helpers.formatPriceSar(priceUsd);
      case 'YER':
      default:
        return Helpers.formatPrice(priceUsd);
    }
  }
}
