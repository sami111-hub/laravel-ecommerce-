import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/constants/app_colors.dart';
import '../../core/constants/category_icons.dart';
import '../../models/category_model.dart';
import '../../providers/product_provider.dart';
import '../../providers/cart_provider.dart';
import '../../providers/auth_provider.dart';
import '../../widgets/product_card.dart';
import '../../widgets/loading_widget.dart';

class HomeScreen extends ConsumerStatefulWidget {
  const HomeScreen({super.key});

  @override
  ConsumerState<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends ConsumerState<HomeScreen> {

  @override
  Widget build(BuildContext context) {
    final authAsync = ref.watch(authStateProvider);
    final user = authAsync.valueOrNull;
    final categories = ref.watch(categoriesProvider);
    final featured = ref.watch(featuredProductsProvider);
    final latest = ref.watch(latestProductsProvider);
    final flashDeals = ref.watch(flashDealsProvider);

    return Scaffold(
      backgroundColor: AppColors.scaffoldBg,
      body: RefreshIndicator(
        color: AppColors.primary,
        onRefresh: () async {
          ref.invalidate(categoriesProvider);
          ref.invalidate(featuredProductsProvider);
          ref.invalidate(latestProductsProvider);
          ref.invalidate(flashDealsProvider);
        },
        child: CustomScrollView(
          slivers: [
            // ═══════════════ HEADER with gradient ═══════════════
            SliverToBoxAdapter(
              child: Container(
                decoration: const BoxDecoration(
                  gradient: AppColors.primaryIntenseGradient,
                ),
                child: SafeArea(
                  bottom: false,
                  child: Padding(
                    padding: const EdgeInsets.fromLTRB(20, 16, 20, 28),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Top row: welcome + notification
                        Row(
                          children: [
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    '\u0645\u0631\u062d\u0628\u0627\u064b ${user?.name ?? '\u0628\u0643'}',
                                    style: const TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.w800),
                                  ),
                                  const SizedBox(height: 4),
                                  Text(
                                    '\u0645\u0627\u0630\u0627 \u062a\u0628\u062d\u062b \u0639\u0646\u0647 \u0627\u0644\u064a\u0648\u0645\u061f',
                                    style: TextStyle(color: Colors.white.withValues(alpha: 0.7), fontSize: 14),
                                  ),
                                ],
                              ),
                            ),
                            _headerIconBtn(Icons.notifications_outlined, () => context.push('/notifications')),
                          ],
                        ),
                        const SizedBox(height: 20),
                        // Search bar — pill style like website
                        GestureDetector(
                          onTap: () => context.push('/search'),
                          child: Container(
                            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 14),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(50),
                              boxShadow: const [BoxShadow(color: Color(0x20000000), blurRadius: 15, offset: Offset(0, 4))],
                            ),
                            child: Row(
                              children: [
                                Icon(Icons.search_rounded, color: AppColors.accent, size: 22),
                                const SizedBox(width: 12),
                                const Text('\u0627\u0628\u062d\u062b \u0639\u0646 \u0645\u0646\u062a\u062c\u0627\u062a...', style: TextStyle(color: AppColors.textHint, fontSize: 15)),
                                const Spacer(),
                                Container(
                                  padding: const EdgeInsets.all(6),
                                  decoration: BoxDecoration(
                                    color: AppColors.primaryLight,
                                    borderRadius: BorderRadius.circular(8),
                                  ),
                                  child: const Icon(Icons.tune_rounded, size: 18, color: AppColors.primary),
                                ),
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),

            const SliverToBoxAdapter(child: SizedBox(height: 24)),

            // ═══════════════ CATEGORIES ═══════════════
            SliverToBoxAdapter(
              child: Column(
                children: [
                  _sectionHeader('\u0627\u0644\u062a\u0635\u0646\u064a\u0641\u0627\u062a', onSeeAll: () => context.push('/categories')),
                  const SizedBox(height: 14),
                  categories.when(
                    data: (cats) => SizedBox(
                      height: 105,
                      child: ListView.builder(
                        scrollDirection: Axis.horizontal,
                        padding: const EdgeInsets.symmetric(horizontal: 16),
                        itemCount: cats.length,
                        itemBuilder: (_, i) => _buildCategoryItem(cats[i], i),
                      ),
                    ),
                    loading: () => const SizedBox(height: 105, child: Center(child: CircularProgressIndicator(color: AppColors.primary))),
                    error: (e, _) => const SizedBox.shrink(),
                  ),
                ],
              ),
            ),

            const SliverToBoxAdapter(child: SizedBox(height: 28)),

            // ═══════════════ FLASH DEALS ═══════════════
            SliverToBoxAdapter(
              child: flashDeals.when(
                data: (products) {
                  if (products.isEmpty) return const SizedBox.shrink();
                  return Column(
                    children: [
                      _sectionHeader('\u0639\u0631\u0648\u0636 \u062e\u0627\u0635\u0629', icon: Icons.local_fire_department_rounded, iconColor: AppColors.secondary),
                      const SizedBox(height: 14),
                      SizedBox(
                        height: 280,
                        child: ListView.builder(
                          scrollDirection: Axis.horizontal,
                          padding: const EdgeInsets.symmetric(horizontal: 16),
                          itemCount: products.length,
                          itemBuilder: (_, i) => Container(
                            width: 175,
                            margin: const EdgeInsets.only(left: 12),
                            child: ProductCard(
                              product: products[i],
                              onTap: () => context.push('/product/${products[i].id}'),
                              onAddToCart: products[i].inStock ? () => _addToCart(products[i].id) : null,
                            ),
                          ),
                        ),
                      ),
                    ],
                  );
                },
                loading: () => const SizedBox(height: 280, child: Center(child: CircularProgressIndicator(color: AppColors.primary))),
                error: (_, __) => const SizedBox.shrink(),
              ),
            ),

            const SliverToBoxAdapter(child: SizedBox(height: 28)),

            // ═══════════════ PROMO BANNER ═══════════════
            SliverToBoxAdapter(
              child: Container(
                margin: const EdgeInsets.symmetric(horizontal: 20),
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  gradient: AppColors.accentGradient,
                  borderRadius: BorderRadius.circular(24),
                  boxShadow: const [AppShadows.brownShadow],
                ),
                child: Row(
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('\u062e\u0635\u0645 \u064a\u0635\u0644 \u0625\u0644\u0649', style: TextStyle(color: Colors.white.withValues(alpha: 0.8), fontSize: 14)),
                          const SizedBox(height: 6),
                          const Text('50%', style: TextStyle(color: Colors.white, fontSize: 48, fontWeight: FontWeight.w900, height: 1)),
                          const SizedBox(height: 12),
                          GestureDetector(
                            onTap: () => context.push('/offers'),
                            child: Container(
                              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 10),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(50),
                              ),
                              child: const Text('\u062a\u0633\u0648\u0651\u0642 \u0627\u0644\u0622\u0646', style: TextStyle(color: AppColors.primary, fontWeight: FontWeight.w700, fontSize: 14)),
                            ),
                          ),
                        ],
                      ),
                    ),
                    const Icon(Icons.local_offer_rounded, size: 90, color: Colors.white12),
                  ],
                ),
              ),
            ),

            const SliverToBoxAdapter(child: SizedBox(height: 28)),

            // ═══════════════ FEATURED PRODUCTS ═══════════════
            SliverToBoxAdapter(
              child: _sectionHeader('\u0645\u0646\u062a\u062c\u0627\u062a \u0645\u0645\u064a\u0632\u0629', icon: Icons.star_rounded, iconColor: AppColors.star, onSeeAll: () => context.push('/products?filter=featured')),
            ),
            const SliverToBoxAdapter(child: SizedBox(height: 14)),
            featured.when(
              data: (products) => SliverPadding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                sliver: SliverGrid(
                  delegate: SliverChildBuilderDelegate(
                    (_, i) => ProductCard(
                      product: products[i],
                      onTap: () => context.push('/product/${products[i].id}'),
                      onAddToCart: products[i].inStock ? () => _addToCart(products[i].id) : null,
                    ),
                    childCount: products.length > 6 ? 6 : products.length,
                  ),
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2,
                    mainAxisSpacing: 14,
                    crossAxisSpacing: 14,
                    childAspectRatio: 0.58,
                  ),
                ),
              ),
              loading: () => const SliverToBoxAdapter(child: ProductShimmer()),
              error: (_, __) => const SliverToBoxAdapter(child: SizedBox.shrink()),
            ),

            const SliverToBoxAdapter(child: SizedBox(height: 28)),

            // ═══════════════ LATEST PRODUCTS ═══════════════
            SliverToBoxAdapter(
              child: _sectionHeader('\u0623\u062d\u062f\u062b \u0627\u0644\u0645\u0646\u062a\u062c\u0627\u062a', icon: Icons.fiber_new_rounded, iconColor: AppColors.success, onSeeAll: () => context.push('/products?filter=latest')),
            ),
            const SliverToBoxAdapter(child: SizedBox(height: 14)),
            latest.when(
              data: (products) => SliverPadding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                sliver: SliverGrid(
                  delegate: SliverChildBuilderDelegate(
                    (_, i) => ProductCard(
                      product: products[i],
                      onTap: () => context.push('/product/${products[i].id}'),
                      onAddToCart: products[i].inStock ? () => _addToCart(products[i].id) : null,
                    ),
                    childCount: products.length > 6 ? 6 : products.length,
                  ),
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2,
                    mainAxisSpacing: 14,
                    crossAxisSpacing: 14,
                    childAspectRatio: 0.58,
                  ),
                ),
              ),
              loading: () => const SliverToBoxAdapter(child: ProductShimmer()),
              error: (_, __) => const SliverToBoxAdapter(child: SizedBox.shrink()),
            ),

            const SliverToBoxAdapter(child: SizedBox(height: 32)),
          ],
        ),
      ),
    );
  }

  // ─── Helpers ───

  Future<void> _addToCart(int productId) async {
    final messenger = ScaffoldMessenger.of(context);
    final ok = await ref.read(cartProvider.notifier).addToCart(productId);
    if (mounted) {
      messenger.showSnackBar(SnackBar(
        content: Text(ok ? '\u062a\u0645\u062a \u0627\u0644\u0625\u0636\u0627\u0641\u0629 \u0644\u0644\u0633\u0644\u0629 \u2713' : '\u0641\u0634\u0644 \u0627\u0644\u0625\u0636\u0627\u0641\u0629'),
        backgroundColor: ok ? AppColors.success : AppColors.error,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        duration: const Duration(seconds: 2),
      ));
    }
  }

  Widget _headerIconBtn(IconData icon, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 44, height: 44,
        decoration: BoxDecoration(
          color: Colors.white.withValues(alpha: 0.15),
          borderRadius: BorderRadius.circular(14),
          border: Border.all(color: Colors.white.withValues(alpha: 0.2)),
        ),
        child: Icon(icon, color: Colors.white, size: 22),
      ),
    );
  }

  Widget _sectionHeader(String title, {VoidCallback? onSeeAll, IconData? icon, Color? iconColor}) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Row(
        children: [
          if (icon != null) ...[
            Container(
              padding: const EdgeInsets.all(6),
              decoration: BoxDecoration(
                color: (iconColor ?? AppColors.primary).withValues(alpha: 0.12),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Icon(icon, size: 18, color: iconColor ?? AppColors.primary),
            ),
            const SizedBox(width: 10),
          ],
          Expanded(
            child: Text(title, style: const TextStyle(fontSize: 19, fontWeight: FontWeight.w800, color: AppColors.textPrimary)),
          ),
          if (onSeeAll != null)
            GestureDetector(
              onTap: onSeeAll,
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                decoration: BoxDecoration(
                  color: AppColors.primaryLight,
                  borderRadius: BorderRadius.circular(50),
                  border: Border.all(color: AppColors.primary.withValues(alpha: 0.15)),
                ),
                child: const Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text('\u0639\u0631\u0636 \u0627\u0644\u0643\u0644', style: TextStyle(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.w600)),
                    SizedBox(width: 4),
                    Icon(Icons.arrow_forward_ios_rounded, size: 12, color: AppColors.primary),
                  ],
                ),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildCategoryItem(CategoryModel cat, int index) {
    final icon = CategoryIcons.getIcon(cat.name);
    final color = CategoryIcons.getColor(cat.name);

    return GestureDetector(
      onTap: () => context.push('/category/${cat.id}'),
      child: Container(
        width: 80,
        margin: const EdgeInsets.only(left: 12),
        child: Column(
          children: [
            Container(
              width: 62, height: 62,
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                  colors: [color.withValues(alpha: 0.15), color.withValues(alpha: 0.08)],
                ),
                borderRadius: BorderRadius.circular(18),
                boxShadow: [BoxShadow(color: color.withValues(alpha: 0.12), blurRadius: 10, offset: const Offset(0, 4))],
              ),
              child: cat.image != null && cat.image!.isNotEmpty
                  ? ClipRRect(
                      borderRadius: BorderRadius.circular(18),
                      child: CachedNetworkImage(imageUrl: cat.image!, fit: BoxFit.cover, errorWidget: (_, __, ___) => Icon(icon, color: color, size: 26)),
                    )
                  : Icon(icon, color: color, size: 26),
            ),
            const SizedBox(height: 8),
            Text(
              cat.name,
              style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: AppColors.textPrimary),
              textAlign: TextAlign.center,
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
            ),
          ],
        ),
      ),
    );
  }
}
