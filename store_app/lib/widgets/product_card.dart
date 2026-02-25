import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../core/constants/app_colors.dart';
import '../core/utils/helpers.dart';
import '../models/product_model.dart';

/// 🛍️ Product Card - Update Aden Premium Design
/// Warm brown theme matching website: https://store.update-aden.com
class ProductCard extends StatelessWidget {
  final ProductModel product;
  final VoidCallback? onTap;
  final VoidCallback? onFavorite;
  final VoidCallback? onAddToCart;
  final bool showFavorite;

  const ProductCard({
    super.key,
    required this.product,
    this.onTap,
    this.onFavorite,
    this.onAddToCart,
    this.showFavorite = true,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        decoration: BoxDecoration(
          color: AppColors.cardBackground,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: AppColors.border, width: 1),
          boxShadow: const [
            BoxShadow(
              color: Color(0x0D5D4037),
              blurRadius: 12,
              offset: Offset(0, 4),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // ═══════════════════════════════════════════════════════════
            // 🖼️ IMAGE SECTION
            // ═══════════════════════════════════════════════════════════
            Expanded(
              child: Stack(
                fit: StackFit.expand,
                children: [
                  // Product image with warm bg
                  ClipRRect(
                    borderRadius: const BorderRadius.vertical(top: Radius.circular(15)),
                    child: Container(
                      color: AppColors.sectionBg,
                      child: product.image != null
                          ? CachedNetworkImage(
                              imageUrl: product.image!,
                              fit: BoxFit.cover,
                              placeholder: (_, __) => Container(
                                color: AppColors.sectionBg,
                                child: const Center(
                                  child: SizedBox(
                                    width: 24, height: 24,
                                    child: CircularProgressIndicator(strokeWidth: 2, color: AppColors.accent),
                                  ),
                                ),
                              ),
                              errorWidget: (_, __, ___) => _buildImagePlaceholder(),
                            )
                          : _buildImagePlaceholder(),
                    ),
                  ),

                  // 🏷️ Discount Badge (Top-Right) - Gradient pill
                  if (product.hasDiscount)
                    Positioned(
                      top: 8,
                      right: 8,
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                        decoration: BoxDecoration(
                          gradient: const LinearGradient(
                            colors: [Color(0xFFD84315), Color(0xFFE64A19)],
                          ),
                          borderRadius: BorderRadius.circular(20),
                          boxShadow: const [
                            BoxShadow(color: Color(0x40D84315), blurRadius: 6, offset: Offset(0, 2)),
                          ],
                        ),
                        child: Text(
                          '-${product.discountPercent}%',
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 11,
                            fontWeight: FontWeight.w800,
                          ),
                        ),
                      ),
                    ),

                  // ❤️ Favorite Button (Top Left)
                  if (showFavorite)
                    Positioned(
                      top: 8,
                      left: 8,
                      child: GestureDetector(
                        onTap: onFavorite,
                        child: Container(
                          width: 34,
                          height: 34,
                          decoration: BoxDecoration(
                            color: Colors.white,
                            shape: BoxShape.circle,
                            boxShadow: const [
                              BoxShadow(color: Color(0x205D4037), blurRadius: 8, offset: Offset(0, 2)),
                            ],
                          ),
                          child: Icon(
                            product.inWishlist ? Icons.favorite_rounded : Icons.favorite_border_rounded,
                            size: 18,
                            color: product.inWishlist ? AppColors.heart : AppColors.textHint,
                          ),
                        ),
                      ),
                    ),

                  // 🚫 Out of Stock Overlay
                  if (!product.inStock)
                    Positioned.fill(
                      child: ClipRRect(
                        borderRadius: const BorderRadius.vertical(top: Radius.circular(15)),
                        child: Container(
                          color: Colors.black.withValues(alpha: 0.5),
                          child: Center(
                            child: Container(
                              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
                              decoration: BoxDecoration(
                                color: Colors.black.withValues(alpha: 0.6),
                                borderRadius: BorderRadius.circular(20),
                              ),
                              child: const Text(
                                'غير متوفر',
                                style: TextStyle(
                                  color: Colors.white,
                                  fontWeight: FontWeight.w700,
                                  fontSize: 12,
                                ),
                              ),
                            ),
                          ),
                        ),
                      ),
                    ),
                ],
              ),
            ),

            // ═══════════════════════════════════════════════════════════
            // 📝 INFO SECTION
            // ═══════════════════════════════════════════════════════════
            Padding(
              padding: const EdgeInsets.fromLTRB(10, 10, 10, 6),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  // Brand
                  if (product.brandName != null)
                    Text(
                      product.brandName!,
                      style: const TextStyle(
                        fontSize: 10,
                        fontWeight: FontWeight.w500,
                        color: AppColors.accent,
                        letterSpacing: 0.3,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),

                  const SizedBox(height: 3),

                  // Product Name
                  Text(
                    product.name,
                    style: const TextStyle(
                      fontSize: 13,
                      fontWeight: FontWeight.w700,
                      color: AppColors.textPrimary,
                      height: 1.3,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),

                  const SizedBox(height: 4),

                  // ⭐ Rating
                  if (product.rating > 0)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 4),
                      child: Row(
                        children: [
                          ...List.generate(5, (i) => Icon(
                            i < product.rating.round() ? Icons.star_rounded : Icons.star_border_rounded,
                            size: 13,
                            color: i < product.rating.round() ? AppColors.star : AppColors.textMuted,
                          )),
                          const SizedBox(width: 3),
                          Text(
                            '(${product.reviewsCount})',
                            style: const TextStyle(fontSize: 10, color: AppColors.textSecondary),
                          ),
                        ],
                      ),
                    ),

                  // 💰 Price
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      Text(
                        Helpers.formatPrice(product.price),
                        style: const TextStyle(
                          fontSize: 15,
                          fontWeight: FontWeight.w800,
                          color: AppColors.primary,
                        ),
                      ),
                      if (product.hasDiscount) ...[
                        const SizedBox(width: 4),
                        Text(
                          Helpers.formatPrice(product.oldPrice!),
                          style: const TextStyle(
                            fontSize: 10,
                            color: AppColors.textHint,
                            decoration: TextDecoration.lineThrough,
                            decorationColor: AppColors.textHint,
                          ),
                        ),
                      ],
                    ],
                  ),

                  // Multi-currency
                  Padding(
                    padding: const EdgeInsets.only(top: 2),
                    child: Text(
                      '${Helpers.formatPriceUsd(product.price)} • ${Helpers.formatPriceSar(product.price)}',
                      style: const TextStyle(fontSize: 9, color: AppColors.textHint),
                    ),
                  ),
                ],
              ),
            ),

            // 🛒 Add to Cart Button (full-width gradient)
            if (onAddToCart != null && product.inStock)
              GestureDetector(
                onTap: onAddToCart,
                child: Container(
                  width: double.infinity,
                  padding: const EdgeInsets.symmetric(vertical: 8),
                  decoration: const BoxDecoration(
                    gradient: AppColors.primaryGradient,
                    borderRadius: BorderRadius.vertical(bottom: Radius.circular(15)),
                  ),
                  child: const Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.add_shopping_cart_rounded, color: Colors.white, size: 16),
                      SizedBox(width: 4),
                      Text(
                        'أضف للسلة',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 12,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                    ],
                  ),
                ),
              )
            else
              const SizedBox(height: 6),
          ],
        ),
      ),
    );
  }

  Widget _buildImagePlaceholder() {
    return Container(
      color: AppColors.sectionBg,
      child: const Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.image_outlined, size: 36, color: AppColors.textMuted),
          SizedBox(height: 4),
          Text('لا توجد صورة', style: TextStyle(fontSize: 10, color: AppColors.textHint)),
        ],
      ),
    );
  }
}
