import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/constants/app_colors.dart';
import '../../core/utils/helpers.dart';
import '../../models/product_model.dart';
import '../../models/review_model.dart';
import '../../providers/product_provider.dart';
import '../../providers/cart_provider.dart';
import '../../providers/wishlist_provider.dart';
import '../../services/review_service.dart';
import '../../widgets/custom_button.dart';

class ProductDetailScreen extends ConsumerStatefulWidget {
  final int productId;
  const ProductDetailScreen({super.key, required this.productId});

  @override
  ConsumerState<ProductDetailScreen> createState() => _ProductDetailScreenState();
}

class _ProductDetailScreenState extends ConsumerState<ProductDetailScreen> {
  int _selectedImage = 0;
  int _qty = 1;
  bool _addingToCart = false;
  List<ReviewModel> _reviews = [];
  bool _loadingReviews = true;

  @override
  void initState() {
    super.initState();
    _loadReviews();
  }

  Future<void> _loadReviews() async {
    try {
      final data = await ReviewService().getReviews(widget.productId);
      if (mounted) {
        setState(() {
          _reviews = data['reviews'] as List<ReviewModel>;
          _loadingReviews = false;
        });
      }
    } catch (_) {
      if (mounted) setState(() => _loadingReviews = false);
    }
  }

  Future<void> _addToCart(int productId) async {
    setState(() => _addingToCart = true);
    final ok = await ref.read(cartProvider.notifier).addToCart(productId, quantity: _qty);
    if (!mounted) return;
    setState(() => _addingToCart = false);
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(ok ? 'تمت الإضافة للسلة' : 'فشل إضافة المنتج'),
        backgroundColor: ok ? AppColors.success : AppColors.error,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      ),
    );
  }

  Future<void> _toggleWishlist(int productId) async {
    await ref.read(wishlistProvider.notifier).toggle(productId);
  }

  @override
  Widget build(BuildContext context) {
    final productAsync = ref.watch(productDetailProvider(widget.productId));

    return Scaffold(
      backgroundColor: AppColors.cardBackground,
      body: productAsync.when(
        data: (product) => _buildContent(product),
        loading: () => const Center(child: CircularProgressIndicator(color: AppColors.primary)),
        error: (e, _) => Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.error_outline, size: 48, color: AppColors.error),
              const SizedBox(height: AppSpacing.md),
              const Text('خطأ في تحميل المنتج', style: TextStyle(color: AppColors.textSecondary)),
              const SizedBox(height: AppSpacing.md),
              ElevatedButton(
                onPressed: () => ref.invalidate(productDetailProvider(widget.productId)),
                child: const Text('إعادة المحاولة'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildContent(ProductModel product) {
    final allImages = product.images.isNotEmpty ? product.images : (product.image != null ? [product.image!] : <String>[]);

    return Stack(
      children: [
        CustomScrollView(
          slivers: [
            // Image gallery with warm background
            SliverToBoxAdapter(
              child: Stack(
                children: [
                  // Main image with warm bg
                  Container(
                    height: 400,
                    decoration: const BoxDecoration(
                      gradient: LinearGradient(
                        begin: Alignment.topCenter,
                        end: Alignment.bottomCenter,
                        colors: [AppColors.sectionBg, AppColors.cardBackground],
                      ),
                    ),
                    child: allImages.isNotEmpty
                        ? PageView.builder(
                            itemCount: allImages.length,
                            onPageChanged: (i) => setState(() => _selectedImage = i),
                            itemBuilder: (_, i) => CachedNetworkImage(
                              imageUrl: allImages[i],
                              fit: BoxFit.contain,
                              errorWidget: (_, __, ___) => Container(
                                color: AppColors.sectionBg,
                                child: const Icon(Icons.image, size: 80, color: AppColors.textMuted),
                              ),
                            ),
                          )
                        : Center(child: Icon(Icons.image, size: 80, color: AppColors.textMuted)),
                  ),

                  // Back & Wishlist buttons (glassmorphic)
                  Positioned(
                    top: MediaQuery.of(context).padding.top + 8,
                    left: 16,
                    right: 16,
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        _circleButton(Icons.arrow_back_ios_new, () => context.pop()),
                        _circleButton(
                          product.inWishlist ? Icons.favorite_rounded : Icons.favorite_border_rounded,
                          () => _toggleWishlist(product.id),
                          iconColor: product.inWishlist ? AppColors.heart : null,
                        ),
                      ],
                    ),
                  ),

                  // Image indicators (pill style)
                  if (allImages.length > 1)
                    Positioned(
                      bottom: 20,
                      left: 0,
                      right: 0,
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                              color: Colors.black.withValues(alpha: 0.4),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: List.generate(
                                allImages.length,
                                (i) => AnimatedContainer(
                                  duration: const Duration(milliseconds: 250),
                                  width: i == _selectedImage ? 20 : 6,
                                  height: 6,
                                  margin: const EdgeInsets.symmetric(horizontal: 2),
                                  decoration: BoxDecoration(
                                    color: i == _selectedImage ? Colors.white : Colors.white.withValues(alpha: 0.4),
                                    borderRadius: BorderRadius.circular(3),
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),

                  // Discount badge (gradient pill)
                  if (product.hasDiscount)
                    Positioned(
                      top: MediaQuery.of(context).padding.top + 12,
                      left: 70,
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                        decoration: BoxDecoration(
                          gradient: const LinearGradient(colors: [Color(0xFFD84315), Color(0xFFE64A19)]),
                          borderRadius: BorderRadius.circular(20),
                          boxShadow: const [BoxShadow(color: Color(0x40D84315), blurRadius: 8, offset: Offset(0, 2))],
                        ),
                        child: Text(
                          '-${product.discountPercent}%',
                          style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 13),
                        ),
                      ),
                    ),
                ],
              ),
            ),

            // Product info card
            SliverToBoxAdapter(
              child: Container(
                margin: const EdgeInsets.only(top: 0),
                padding: const EdgeInsets.all(AppSpacing.xl),
                decoration: const BoxDecoration(
                  color: AppColors.cardBackground,
                  borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Category & Stock chips
                    Row(
                      children: [
                        if (product.categoryName != null)
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                            decoration: BoxDecoration(
                              color: AppColors.primaryLight,
                              borderRadius: BorderRadius.circular(20),
                              border: Border.all(color: AppColors.primary.withValues(alpha: 0.15)),
                            ),
                            child: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                const Icon(Icons.category_outlined, size: 14, color: AppColors.primary),
                                const SizedBox(width: 4),
                                Text(product.categoryName!, style: const TextStyle(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.w600)),
                              ],
                            ),
                          ),
                        const SizedBox(width: 8),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                          decoration: BoxDecoration(
                            color: product.inStock ? AppColors.success.withValues(alpha: 0.08) : AppColors.error.withValues(alpha: 0.08),
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Icon(
                                product.inStock ? Icons.check_circle_outline : Icons.cancel_outlined,
                                size: 14,
                                color: product.inStock ? AppColors.success : AppColors.error,
                              ),
                              const SizedBox(width: 4),
                              Text(
                                product.inStock ? 'متوفر' : 'غير متوفر',
                                style: TextStyle(
                                  color: product.inStock ? AppColors.success : AppColors.error,
                                  fontSize: 12,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),

                    // Name
                    Text(product.name, style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w800, color: AppColors.textPrimary, height: 1.3)),
                    const SizedBox(height: 8),

                    // Brand
                    if (product.brandName != null)
                      Padding(
                        padding: const EdgeInsets.only(bottom: 12),
                        child: Row(
                          children: [
                            Container(
                              padding: const EdgeInsets.all(4),
                              decoration: BoxDecoration(
                                color: AppColors.primary.withValues(alpha: 0.1),
                                borderRadius: BorderRadius.circular(6),
                              ),
                              child: const Icon(Icons.verified_rounded, size: 14, color: AppColors.primary),
                            ),
                            const SizedBox(width: 6),
                            Text(product.brandName!, style: const TextStyle(color: AppColors.accent, fontSize: 14, fontWeight: FontWeight.w500)),
                          ],
                        ),
                      ),

                    // Rating
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                      decoration: BoxDecoration(
                        color: AppColors.sectionBg,
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          ...List.generate(5, (i) => Icon(
                            i < product.rating.round() ? Icons.star_rounded : Icons.star_border_rounded,
                            color: AppColors.star,
                            size: 20,
                          )),
                          const SizedBox(width: 8),
                          Text('${product.rating}', style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15, color: AppColors.textPrimary)),
                          Text(' (${product.reviewsCount} تقييم)', style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),

                    // Price card
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        gradient: const LinearGradient(
                          colors: [Color(0xFFFFF8F3), Color(0xFFFFF0E5)],
                        ),
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(color: AppColors.primary.withValues(alpha: 0.12)),
                      ),
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.end,
                        children: [
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('السعر', style: TextStyle(fontSize: 12, color: AppColors.accent, fontWeight: FontWeight.w500)),
                              const SizedBox(height: 4),
                              Text(
                                Helpers.formatPrice(product.price),
                                style: const TextStyle(fontSize: 28, fontWeight: FontWeight.w900, color: AppColors.primary),
                              ),
                              const SizedBox(height: 2),
                              Text(
                                '${Helpers.formatPriceUsd(product.price)}   •   ${Helpers.formatPriceSar(product.price)}',
                                style: const TextStyle(fontSize: 12, color: AppColors.accent),
                              ),
                            ],
                          ),
                          if (product.hasDiscount) ...[
                            const SizedBox(width: 16),
                            Padding(
                              padding: const EdgeInsets.only(bottom: 8),
                              child: Text(
                                Helpers.formatPrice(product.oldPrice),
                                style: const TextStyle(
                                  fontSize: 16,
                                  decoration: TextDecoration.lineThrough,
                                  decorationColor: AppColors.textHint,
                                  color: AppColors.textHint,
                                ),
                              ),
                            ),
                          ],
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),

                    // Quantity selector
                    if (product.inStock) ...[
                      const Text('الكمية', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700, color: AppColors.textPrimary)),
                      const SizedBox(height: 10),
                      Row(
                        children: [
                          _qtyButton(Icons.remove, () {
                            if (_qty > 1) setState(() => _qty--);
                          }),
                          Container(
                            width: 56,
                            alignment: Alignment.center,
                            child: Text('$_qty', style: const TextStyle(fontSize: 20, fontWeight: FontWeight.w800, color: AppColors.textPrimary)),
                          ),
                          _qtyButton(Icons.add, () {
                            if (_qty < product.stock) setState(() => _qty++);
                          }),
                          const Spacer(),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                              color: AppColors.success.withValues(alpha: 0.08),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text('${product.stock} متوفر', style: const TextStyle(color: AppColors.success, fontSize: 12, fontWeight: FontWeight.w500)),
                          ),
                        ],
                      ),
                      const SizedBox(height: AppSpacing.xl),
                    ],

                    // Description
                    if (product.description != null) ...[
                      const Divider(),
                      const SizedBox(height: AppSpacing.md),
                      const Text('الوصف', style: TextStyle(fontSize: AppFontSize.h2, fontWeight: FontWeight.bold)),
                      const SizedBox(height: AppSpacing.sm),
                      _buildDescriptionText(product.description!),
                      const SizedBox(height: AppSpacing.xl),
                    ],

                    // Specifications
                    if (product.specifications.isNotEmpty) ...[
                      const Divider(),
                      const SizedBox(height: AppSpacing.md),
                      Row(
                        children: const [
                          Icon(Icons.settings_outlined, color: AppColors.primary, size: 22),
                          SizedBox(width: AppSpacing.sm),
                          Text('المواصفات التقنية', style: TextStyle(fontSize: AppFontSize.h2, fontWeight: FontWeight.bold)),
                        ],
                      ),
                      const SizedBox(height: AppSpacing.md),
                      Container(
                        decoration: BoxDecoration(
                          borderRadius: AppRadius.cardRadius,
                          border: Border.all(color: AppColors.border),
                        ),
                        child: Column(
                          children: () {
                            final entries = product.specifications.entries.toList();
                            return entries.asMap().entries.map((e) {
                              final idx = e.key;
                              final spec = e.value;
                              final isLast = idx == entries.length - 1;
                              return Container(
                                padding: const EdgeInsets.symmetric(horizontal: AppSpacing.lg - 2, vertical: AppSpacing.sm + 3),
                                decoration: BoxDecoration(
                                  color: idx.isEven ? AppColors.scaffoldBg : AppColors.cardBackground,
                                  borderRadius: isLast
                                      ? const BorderRadius.vertical(bottom: Radius.circular(AppRadius.card))
                                      : (idx == 0 ? const BorderRadius.vertical(top: Radius.circular(AppRadius.card)) : BorderRadius.zero),
                                  border: isLast ? null : const Border(bottom: BorderSide(color: AppColors.border)),
                                ),
                                child: Row(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    SizedBox(
                                      width: 130,
                                      child: Text(
                                        _translateSpecKey(spec.key),
                                        style: const TextStyle(fontWeight: FontWeight.w600, fontSize: AppFontSize.caption, color: AppColors.textPrimary),
                                      ),
                                    ),
                                    const SizedBox(width: AppSpacing.sm),
                                    Expanded(
                                      child: Text(
                                        spec.value?.toString() ?? '',
                                        style: const TextStyle(fontSize: AppFontSize.caption, color: AppColors.textSecondary),
                                        textAlign: TextAlign.end,
                                      ),
                                    ),
                                  ],
                                ),
                              );
                            }).toList();
                          }(),
                        ),
                      ),
                      const SizedBox(height: AppSpacing.xl),
                    ],

                    // Reviews
                    const Divider(),
                    const SizedBox(height: AppSpacing.md),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text('التقييمات', style: TextStyle(fontSize: AppFontSize.h2, fontWeight: FontWeight.bold)),
                        TextButton(
                          onPressed: () => _showAddReviewSheet(product.id),
                          child: const Text('أضف تقييم', style: TextStyle(color: AppColors.primary)),
                        ),
                      ],
                    ),
                    const SizedBox(height: AppSpacing.sm),
                    if (_loadingReviews)
                      const Center(child: CircularProgressIndicator(color: AppColors.primary))
                    else if (_reviews.isEmpty)
                      const Padding(
                        padding: EdgeInsets.all(16),
                        child: Center(child: Text('لا توجد تقييمات بعد', style: TextStyle(color: AppColors.textSecondary))),
                      )
                    else
                      ...(_reviews.take(3).map((r) => _buildReviewItem(r))),

                    const SizedBox(height: 80), // Space for bottom bar
                  ],
                ),
              ),
            ),
          ],
        ),

        // Bottom bar - Add to cart (premium gradient)
        if (product.inStock)
          Positioned(
            bottom: 0,
            left: 0,
            right: 0,
            child: Container(
              padding: EdgeInsets.fromLTRB(AppSpacing.xl, AppSpacing.lg, AppSpacing.xl, MediaQuery.of(context).padding.bottom + AppSpacing.lg),
              decoration: BoxDecoration(
                color: AppColors.cardBackground,
                borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
                boxShadow: [
                  BoxShadow(color: AppColors.primary.withValues(alpha: 0.08), blurRadius: 20, offset: const Offset(0, -4)),
                ],
              ),
              child: Row(
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      const Text('الإجمالي', style: TextStyle(color: AppColors.accent, fontSize: 12, fontWeight: FontWeight.w500)),
                      const SizedBox(height: 2),
                      Text(
                        Helpers.formatPrice(product.price * _qty),
                        style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w900, color: AppColors.primary),
                      ),
                    ],
                  ),
                  const SizedBox(width: AppSpacing.xl),
                  Expanded(
                    child: CustomButton(
                      text: 'أضف للسلة',
                      onPressed: () => _addToCart(product.id),
                      isLoading: _addingToCart,
                      icon: Icons.shopping_cart_outlined,
                    ),
                  ),
                ],
              ),
            ),
          ),
      ],
    );
  }

  Widget _circleButton(IconData icon, VoidCallback onTap, {Color? iconColor}) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 44,
        height: 44,
        decoration: BoxDecoration(
          color: Colors.white,
          shape: BoxShape.circle,
          boxShadow: [
            BoxShadow(color: AppColors.primary.withValues(alpha: 0.15), blurRadius: 12, offset: const Offset(0, 3)),
          ],
        ),
        child: Icon(icon, size: 20, color: iconColor ?? AppColors.textPrimary),
      ),
    );
  }

  Widget _qtyButton(IconData icon, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 42,
        height: 42,
        decoration: BoxDecoration(
          color: AppColors.sectionBg,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: AppColors.border),
        ),
        child: Icon(icon, color: AppColors.primary, size: 20),
      ),
    );
  }

  String _translateSpecKey(String key) {
    const map = {
      'screen_size': 'حجم الشاشة',
      'screen_type': 'نوع الشاشة',
      'resolution': 'دقة الشاشة',
      'processor': 'المعالج',
      'chipset': 'المعالج',
      'ram': 'الذاكرة العشوائية',
      'storage': 'سعة التخزين',
      'battery': 'البطارية',
      'battery_capacity': 'سعة البطارية',
      'camera': 'الكاميرا',
      'main_camera': 'الكاميرا الرئيسية',
      'front_camera': 'كاميرا السيلفي',
      'os': 'نظام التشغيل',
      'colors': 'الألوان المتاحة',
      'weight': 'الوزن',
      'dimensions': 'الأبعاد',
      'connectivity': 'الاتصال',
      'wifi': 'الواي فاي',
      'bluetooth': 'البلوتوث',
      'gpu': 'كرت الشاشة',
      'ports': 'المنافذ',
      'warranty': 'الضمان',
      'material': 'المادة',
      'features': 'المميزات',
      'charging': 'الشحن',
      'sim': 'الشريحة',
      'network': 'الشبكة',
      'water_resistance': 'مقاومة الماء',
      'fingerprint': 'البصمة',
      'display_size': 'حجم الشاشة',
      'refresh_rate': 'معدل التحديث',
      'platform': 'المنصة',
      'type': 'النوع',
      'keyboard': 'لوحة المفاتيح',
      'stylus': 'دعم القلم',
      'webcam': 'الكاميرا',
      'fast_charging': 'الشحن السريع',
      'compatible_device': 'الجهاز المتوافق',
      'cable_type': 'نوع الكيبل',
      'cable_length': 'طول الكيبل',
      'protection_level': 'مستوى الحماية',
      'magsafe': 'دعم MagSafe',
      'offer_type': 'نوع العرض',
      'original_price': 'السعر الأصلي',
      'discount_percent': 'نسبة الخصم',
      'offer_end': 'تاريخ انتهاء العرض',
    };
    return map[key] ?? key;
  }

  Widget _buildReviewItem(ReviewModel review) {
    return Container(
      margin: const EdgeInsets.only(bottom: AppSpacing.md),
      padding: const EdgeInsets.all(AppSpacing.lg - 2),
      decoration: BoxDecoration(
        color: AppColors.scaffoldBg,
        borderRadius: AppRadius.cardRadius,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              CircleAvatar(
                radius: 18,
                backgroundColor: AppColors.primary.withValues(alpha: 0.1),
                child: Text(
                  review.userName[0],
                  style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.bold),
                ),
              ),
              const SizedBox(width: AppSpacing.sm + 2),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(review.userName, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: AppFontSize.bodyNormal)),
                    const SizedBox(height: 2),
                    Row(
                      children: List.generate(5, (i) => Icon(
                        i < review.rating ? Icons.star : Icons.star_border,
                        color: Colors.amber,
                        size: 14,
                      )),
                    ),
                  ],
                ),
              ),
              if (review.createdAt != null)
                Text(Helpers.formatDate(review.createdAt), style: TextStyle(color: AppColors.textSecondary, fontSize: AppFontSize.small)),
            ],
          ),
          if (review.comment != null && review.comment!.isNotEmpty) ...[
            const SizedBox(height: AppSpacing.sm + 2),
            Text(review.comment!, style: const TextStyle(color: AppColors.textSecondary, fontSize: AppFontSize.bodyNormal, height: 1.5)),
          ],
        ],
      ),
    );
  }

  void _showAddReviewSheet(int productId) {
    int rating = 5;
    final commentCtrl = TextEditingController();
    bool sending = false;

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(AppRadius.modal))),
      builder: (_) => StatefulBuilder(
        builder: (ctx, setSheetState) => Padding(
          padding: EdgeInsets.fromLTRB(AppSpacing.xl, AppSpacing.xl, AppSpacing.xl, MediaQuery.of(ctx).viewInsets.bottom + AppSpacing.xl),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: Container(
                  width: 40,
                  height: 4,
                  decoration: BoxDecoration(color: AppColors.border, borderRadius: BorderRadius.circular(2)),
                ),
              ),
              const SizedBox(height: AppSpacing.lg),
              const Text('أضف تقييمك', style: TextStyle(fontSize: AppFontSize.h1 - 2, fontWeight: FontWeight.bold)),
              const SizedBox(height: AppSpacing.lg),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: List.generate(5, (i) => GestureDetector(
                  onTap: () => setSheetState(() => rating = i + 1),
                  child: Padding(
                    padding: const EdgeInsets.all(AppSpacing.xs),
                    child: Icon(
                      i < rating ? Icons.star : Icons.star_border,
                      color: Colors.amber,
                      size: 36,
                    ),
                  ),
                )),
              ),
              const SizedBox(height: AppSpacing.lg),
              TextField(
                controller: commentCtrl,
                maxLines: 3,
                decoration: InputDecoration(
                  hintText: 'اكتب تعليقك (اختياري)',
                  filled: true,
                  fillColor: AppColors.scaffoldBg,
                  border: OutlineInputBorder(borderRadius: AppRadius.cardRadius, borderSide: BorderSide.none),
                ),
              ),
              const SizedBox(height: AppSpacing.lg),
              SizedBox(
                width: double.infinity,
                child: CustomButton(
                  text: 'إرسال التقييم',
                  isLoading: sending,
                  onPressed: () async {
                    setSheetState(() => sending = true);
                    try {
                      await ReviewService().addReview(productId, rating: rating, comment: commentCtrl.text.trim().isEmpty ? null : commentCtrl.text.trim());
                      if (ctx.mounted) Navigator.pop(ctx);
                      _loadReviews();
                      ref.invalidate(productDetailProvider(productId));
                    } catch (e) {
                      setSheetState(() => sending = false);
                      if (ctx.mounted) {
                        ScaffoldMessenger.of(ctx).showSnackBar(SnackBar(
                          content: Text('خطأ: $e'),
                          backgroundColor: AppColors.error,
                          behavior: SnackBarBehavior.floating,
                          shape: RoundedRectangleBorder(borderRadius: AppRadius.cardRadius),
                        ));
                      }
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

  /// يبني نص الوصف مع تحديد الاتجاه تلقائياً (عربي/إنجليزي)
  Widget _buildDescriptionText(String text) {
    // تقسيم النص إلى فقرات
    final paragraphs = text.split(RegExp(r'\n+'));
    
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: paragraphs.map((paragraph) {
        if (paragraph.trim().isEmpty) return const SizedBox.shrink();
        
        // تحديد اتجاه النص
        final isRtl = _isArabicText(paragraph);
        
        return Padding(
          padding: const EdgeInsets.only(bottom: AppSpacing.sm),
          child: Directionality(
            textDirection: isRtl ? TextDirection.rtl : TextDirection.ltr,
            child: SizedBox(
              width: double.infinity,
              child: Text(
                paragraph.trim(),
                style: const TextStyle(
                  fontSize: AppFontSize.bodyLarge,
                  color: AppColors.textSecondary,
                  height: 1.6,
                ),
                textAlign: isRtl ? TextAlign.right : TextAlign.left,
              ),
            ),
          ),
        );
      }).toList(),
    );
  }

  /// يفحص هل النص عربي أم لا
  bool _isArabicText(String text) {
    // نبحث عن أول حرف غير فراغ
    final arabicRegex = RegExp(r'[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF]');
    final latinRegex = RegExp(r'[a-zA-Z]');
    
    // نحسب عدد الحروف العربية والإنجليزية
    int arabicCount = arabicRegex.allMatches(text).length;
    int latinCount = latinRegex.allMatches(text).length;
    
    // إذا كانت الحروف العربية أكثر، فهو عربي
    return arabicCount >= latinCount;
  }
}
