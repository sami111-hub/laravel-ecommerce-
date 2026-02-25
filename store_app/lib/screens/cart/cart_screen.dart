import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/constants/app_colors.dart';
import '../../core/utils/helpers.dart';
import '../../models/cart_model.dart';
import '../../providers/cart_provider.dart';
import '../../widgets/custom_button.dart';
import '../../widgets/error_widget.dart';

/// 🛒 Cart Screen - Update Aden Design System
class CartScreen extends ConsumerStatefulWidget {
  const CartScreen({super.key});

  @override
  ConsumerState<CartScreen> createState() => _CartScreenState();
}

class _CartScreenState extends ConsumerState<CartScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(cartProvider.notifier).load());
  }

  @override
  Widget build(BuildContext context) {
    final cartAsync = ref.watch(cartProvider);

    return Scaffold(
      backgroundColor: AppColors.scaffoldBg,
      appBar: AppBar(
        backgroundColor: AppColors.cardBackground,
        elevation: 0,
        title: const Text(
          'سلة التسوق',
          style: TextStyle(
            color: AppColors.textPrimary,
            fontWeight: FontWeight.bold,
            fontSize: AppFontSize.h2,
          ),
        ),
        centerTitle: true,
        actions: [
          cartAsync.whenOrNull(
            data: (items) => items.isNotEmpty
                ? IconButton(
                    icon: const Icon(Icons.delete_outline, color: AppColors.error),
                    onPressed: () => _showClearDialog(),
                  )
                : null,
          ) ?? const SizedBox.shrink(),
        ],
      ),
      body: cartAsync.when(
        data: (items) {
          if (items.isEmpty) {
            return const EmptyWidget(
              icon: Icons.shopping_cart_outlined,
              message: 'السلة فارغة\nابدأ بإضافة المنتجات للسلة',
            );
          }
          return Column(
            children: [
              Expanded(
                child: ListView.builder(
                  padding: const EdgeInsets.all(AppSpacing.lg),
                  itemCount: items.length,
                  itemBuilder: (_, i) => _buildCartItem(items[i]),
                ),
              ),
              _buildBottomBar(items),
            ],
          );
        },
        loading: () => const Center(child: CircularProgressIndicator(color: AppColors.primary)),
        error: (e, _) => AppErrorWidget(
          message: e.toString(),
          onRetry: () => ref.read(cartProvider.notifier).load(),
        ),
      ),
    );
  }

  /// 🛍️ Cart Item Widget
  Widget _buildCartItem(CartModel item) {
    return Dismissible(
      key: Key('cart_${item.id}'),
      direction: DismissDirection.endToStart,
      background: Container(
        alignment: Alignment.centerLeft,
        padding: const EdgeInsets.only(left: AppSpacing.xl),
        margin: const EdgeInsets.only(bottom: AppSpacing.md),
        decoration: BoxDecoration(
          color: AppColors.error,
          borderRadius: AppRadius.cardRadius,
        ),
        child: const Icon(Icons.delete, color: Colors.white, size: 28),
      ),
      onDismissed: (_) => ref.read(cartProvider.notifier).removeFromCart(item.id),
      child: Container(
        margin: const EdgeInsets.only(bottom: AppSpacing.md),
        padding: const EdgeInsets.all(AppSpacing.md),
        decoration: BoxDecoration(
          color: AppColors.cardBackground,
          borderRadius: AppRadius.cardRadius,
          boxShadow: const [AppShadows.cardShadow],
        ),
        child: Row(
          children: [
            // Image
            ClipRRect(
              borderRadius: BorderRadius.circular(AppRadius.small),
              child: Container(
                width: 80,
                height: 80,
                color: AppColors.sectionBg,
                child: item.productImage != null
                    ? CachedNetworkImage(
                        imageUrl: item.productImage!,
                        fit: BoxFit.cover,
                        errorWidget: (_, __, ___) => const Icon(Icons.image),
                      )
                    : const Icon(Icons.image, color: AppColors.textSecondary),
              ),
            ),
            const SizedBox(width: AppSpacing.md),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    item.productName,
                    style: const TextStyle(
                      fontWeight: FontWeight.w600,
                      fontSize: AppFontSize.bodyNormal,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: AppSpacing.xs + 2),
                  Text(
                    Helpers.formatPrice(item.price),
                    style: const TextStyle(
                      color: AppColors.primary,
                      fontWeight: FontWeight.bold,
                      fontSize: AppFontSize.bodyLarge,
                    ),
                  ),
                  const SizedBox(height: AppSpacing.sm),
                  // Quantity controls
                  Row(
                    children: [
                      _qtyBtn(Icons.remove, () {
                        if (item.quantity > 1) {
                          ref.read(cartProvider.notifier).updateQuantity(item.id, item.quantity - 1);
                        }
                      }),
                      Container(
                        width: 40,
                        alignment: Alignment.center,
                        child: Text(
                          '${item.quantity}',
                          style: const TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: AppFontSize.bodyLarge,
                          ),
                        ),
                      ),
                      _qtyBtn(Icons.add, () {
                        ref.read(cartProvider.notifier).updateQuantity(item.id, item.quantity + 1);
                      }),
                      const Spacer(),
                      Text(
                        Helpers.formatPrice(item.total),
                        style: const TextStyle(
                          fontWeight: FontWeight.bold,
                          fontSize: AppFontSize.bodyNormal,
                          color: AppColors.textPrimary,
                        ),
                      ),
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

  /// ➕➖ Quantity Button
  Widget _qtyBtn(IconData icon, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 32,
        height: 32,
        decoration: BoxDecoration(
          color: AppColors.sectionBg,
          borderRadius: BorderRadius.circular(10),
          border: Border.all(color: AppColors.border),
        ),
        child: Icon(icon, size: 16, color: AppColors.primary),
      ),
    );
  }

  /// 💰 Bottom Summary Bar
  Widget _buildBottomBar(List<CartModel> items) {
    final subtotal = items.fold<double>(0, (s, e) => s + e.total);
    return Container(
      padding: EdgeInsets.fromLTRB(
        AppSpacing.xl,
        AppSpacing.lg,
        AppSpacing.xl,
        MediaQuery.of(context).padding.bottom + AppSpacing.lg,
      ),
      decoration: BoxDecoration(
        color: AppColors.cardBackground,
        borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
        boxShadow: [
          BoxShadow(
            color: AppColors.primary.withValues(alpha: 0.08),
            blurRadius: 20,
            offset: const Offset(0, -5),
          ),
        ],
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text(
                'المجموع الفرعي',
                style: TextStyle(color: AppColors.textSecondary),
              ),
              Text(
                Helpers.formatPrice(subtotal),
                style: const TextStyle(fontWeight: FontWeight.w600),
              ),
            ],
          ),
          const SizedBox(height: AppSpacing.xs + 2),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: const [
              Text('التوصيل', style: TextStyle(color: AppColors.textSecondary)),
              Text(
                'مجاني',
                style: TextStyle(
                  color: AppColors.success,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
          const Divider(height: AppSpacing.xxl),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text(
                'الإجمالي',
                style: TextStyle(
                  fontWeight: FontWeight.bold,
                  fontSize: AppFontSize.h3,
                ),
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(
                    Helpers.formatPrice(subtotal),
                    style: const TextStyle(
                      color: AppColors.primary,
                      fontWeight: FontWeight.bold,
                      fontSize: AppFontSize.priceLarge,
                    ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    '${Helpers.formatPriceUsd(subtotal)} • ${Helpers.formatPriceSar(subtotal)}',
                    style: const TextStyle(
                      fontSize: 11,
                      color: AppColors.textSecondary,
                    ),
                  ),
                ],
              ),
            ],
          ),
          const SizedBox(height: AppSpacing.lg),
          CustomButton(
            text: 'إتمام الشراء',
            onPressed: () => context.push('/checkout'),
            icon: Icons.payment,
          ),
        ],
      ),
    );
  }

  /// 🗑️ Clear Cart Dialog
  void _showClearDialog() {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: AppRadius.modalRadius),
        title: const Text('تفريغ السلة'),
        content: const Text('هل أنت متأكد من تفريغ السلة؟'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('إلغاء'),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              ref.read(cartProvider.notifier).clearCart();
            },
            child: const Text('تفريغ', style: TextStyle(color: AppColors.error)),
          ),
        ],
      ),
    );
  }
}
