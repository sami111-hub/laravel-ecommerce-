import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../../core/constants/app_colors.dart';
import '../../core/utils/helpers.dart';
import '../../models/product_model.dart';
import '../../providers/product_provider.dart';
import '../../widgets/product_card.dart';
import '../../widgets/loading_widget.dart';
import '../../widgets/error_widget.dart';

class ProductsScreen extends ConsumerStatefulWidget {
  final String? filter;
  final int? categoryId;
  final String? categoryName;
  const ProductsScreen({super.key, this.filter, this.categoryId, this.categoryName});

  @override
  ConsumerState<ProductsScreen> createState() => _ProductsScreenState();
}

class _ProductsScreenState extends ConsumerState<ProductsScreen> {
  bool _isGrid = true;
  final ScrollController _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _scrollController.removeListener(_onScroll);
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (!_scrollController.hasClients) return;
    final maxScroll = _scrollController.position.maxScrollExtent;
    final currentScroll = _scrollController.position.pixels;
    // Load more when 200px from bottom
    if (currentScroll >= maxScroll - 200) {
      if (widget.categoryId != null) {
        final notifier = ref.read(categoryProductsProvider(widget.categoryId!).notifier);
        if (notifier.hasMore && !notifier.isLoadingMore) {
          notifier.loadMore();
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final bool isFromCategory = widget.categoryId != null;
    final productsAsync = isFromCategory
        ? ref.watch(categoryProductsProvider(widget.categoryId!))
        : ref.watch(latestProductsProvider);

    return Scaffold(
      backgroundColor: AppColors.scaffoldBg,
      appBar: AppBar(
        backgroundColor: AppColors.cardBackground,
        elevation: 0,
        title: Text(
          widget.categoryName ?? 'المنتجات',
          style: const TextStyle(color: AppColors.textPrimary, fontWeight: FontWeight.bold, fontSize: AppFontSize.h2),
        ),
        centerTitle: true,
        leading: isFromCategory
            ? IconButton(
                icon: const Icon(Icons.arrow_back_ios, color: AppColors.textPrimary),
                onPressed: () => context.pop(),
              )
            : null,
        actions: [
          IconButton(
            icon: const Icon(Icons.search, color: AppColors.textPrimary),
            onPressed: () => context.push('/search'),
          ),
          IconButton(
            icon: Icon(_isGrid ? Icons.view_list : Icons.grid_view, color: AppColors.textPrimary),
            onPressed: () => setState(() => _isGrid = !_isGrid),
          ),
        ],
      ),
      body: productsAsync.when(
        data: (products) {
          if (products.isEmpty) {
            return const EmptyWidget(
              icon: Icons.inventory_2_outlined,
              message: 'لا توجد منتجات\nلم يتم العثور على منتجات في هذا القسم',
            );
          }
          if (_isGrid) {
            final hasMore = isFromCategory
                ? ref.read(categoryProductsProvider(widget.categoryId!).notifier).hasMore
                : false;
            final itemCount = products.length + (hasMore ? 1 : 0);
            return GridView.builder(
              controller: _scrollController,
              padding: const EdgeInsets.all(AppSpacing.lg),
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 2,
                mainAxisSpacing: AppSpacing.md,
                crossAxisSpacing: AppSpacing.md,
                childAspectRatio: 0.68,
              ),
              itemCount: itemCount,
              itemBuilder: (_, i) {
                if (i >= products.length) {
                  return const Center(child: CircularProgressIndicator());
                }
                return ProductCard(
                  product: products[i],
                  onTap: () => context.push('/product/${products[i].id}'),
                );
              },
            );
          }
          final hasMore = isFromCategory
              ? ref.read(categoryProductsProvider(widget.categoryId!).notifier).hasMore
              : false;
          final itemCount = products.length + (hasMore ? 1 : 0);
          return ListView.builder(
            controller: _scrollController,
            padding: const EdgeInsets.all(AppSpacing.lg),
            itemCount: itemCount,
            itemBuilder: (_, i) {
              if (i >= products.length) {
                return const Padding(
                  padding: EdgeInsets.all(AppSpacing.lg),
                  child: Center(child: CircularProgressIndicator()),
                );
              }
              return _buildListItem(products[i]);
            },
          );
        },
        loading: () => const ProductShimmer(),
        error: (e, _) => AppErrorWidget(
          message: e.toString(),
          onRetry: () {
            if (isFromCategory) {
              ref.invalidate(categoryProductsProvider(widget.categoryId!));
            } else {
              ref.invalidate(latestProductsProvider);
            }
          },
        ),
      ),
    );
  }

  Widget _buildListItem(ProductModel product) {
    return GestureDetector(
      onTap: () => context.push('/product/${product.id}'),
      child: Container(
        margin: const EdgeInsets.only(bottom: AppSpacing.md),
        padding: const EdgeInsets.all(AppSpacing.md),
        decoration: BoxDecoration(
          color: AppColors.cardBackground,
          borderRadius: AppRadius.cardRadius,
          boxShadow: [AppShadows.lightShadow],
        ),
        child: Row(
          children: [
            ClipRRect(
              borderRadius: AppRadius.cardRadius,
              child: Container(
                width: 90,
                height: 90,
                color: AppColors.scaffoldBg,
                child: product.image != null
                    ? Image.network(product.image!, fit: BoxFit.cover, errorBuilder: (_, __, ___) => const Icon(Icons.image, size: 40, color: AppColors.textSecondary))
                    : const Icon(Icons.image, size: 40, color: AppColors.textSecondary),
              ),
            ),
            const SizedBox(width: AppSpacing.md),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(product.name, style: const TextStyle(fontSize: AppFontSize.bodyNormal, fontWeight: FontWeight.w600), maxLines: 2, overflow: TextOverflow.ellipsis),
                  const SizedBox(height: AppSpacing.xs),
                  if (product.categoryName != null)
                    Text(product.categoryName!, style: const TextStyle(color: AppColors.textSecondary, fontSize: AppFontSize.caption)),
                  const SizedBox(height: AppSpacing.sm),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        Helpers.formatPrice(product.price),
                        style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.bold, fontSize: AppFontSize.bodyLarge),
                      ),
                      if (product.hasDiscount)
                        Text(
                          Helpers.formatPrice(product.oldPrice!),
                          style: const TextStyle(decoration: TextDecoration.lineThrough, color: AppColors.textSecondary, fontSize: AppFontSize.caption),
                        ),
                      const SizedBox(height: 3),
                      Text(
                        '${Helpers.formatPriceUsd(product.price)}  •  ${Helpers.formatPriceSar(product.price)}',
                        style: TextStyle(fontSize: AppFontSize.small, color: AppColors.textSecondary),
                      ),
                    ],
                  ),
                  const SizedBox(height: AppSpacing.xs),
                  Row(
                    children: [
                      const Icon(Icons.star, color: Colors.amber, size: 16),
                      const SizedBox(width: AppSpacing.xs),
                      Text('${product.rating}', style: const TextStyle(fontSize: AppFontSize.caption, fontWeight: FontWeight.w500)),
                      Text(' (${product.reviewsCount})', style: const TextStyle(fontSize: AppFontSize.caption, color: AppColors.textSecondary)),
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
}
