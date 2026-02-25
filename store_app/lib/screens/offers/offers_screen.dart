import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:go_router/go_router.dart';
import '../../core/constants/app_colors.dart';
import '../../core/utils/helpers.dart';
import '../../models/offer_model.dart';
import '../../providers/offer_provider.dart';

class OffersScreen extends ConsumerWidget {
  const OffersScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final offersAsync = ref.watch(offersProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('\u0627\u0644\u0639\u0631\u0648\u0636')),
      body: offersAsync.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (e, _) => Center(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Icon(Icons.error_outline, size: 48, color: AppColors.textSecondary),
              const SizedBox(height: 12),
              Text('\u062d\u062f\u062b \u062e\u0637\u0623', style: TextStyle(color: AppColors.textSecondary)),
              const SizedBox(height: 12),
              ElevatedButton(
                onPressed: () => ref.invalidate(offersProvider),
                child: const Text('\u0625\u0639\u0627\u062f\u0629 \u0627\u0644\u0645\u062d\u0627\u0648\u0644\u0629'),
              ),
            ],
          ),
        ),
        data: (offers) {
          final active = offers['active'] ?? [];
          final upcoming = offers['upcoming'] ?? [];

          if (active.isEmpty && upcoming.isEmpty) {
            return const Center(
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(Icons.local_offer_outlined, size: 64, color: AppColors.textSecondary),
                  SizedBox(height: 16),
                  Text('\u0644\u0627 \u062a\u0648\u062c\u062f \u0639\u0631\u0648\u0636 \u062d\u0627\u0644\u064a\u0627\u064b', style: TextStyle(fontSize: 18, color: AppColors.textSecondary)),
                ],
              ),
            );
          }

          return RefreshIndicator(
            onRefresh: () async => ref.invalidate(offersProvider),
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                if (active.isNotEmpty) ...[
                  const Text('\u0627\u0644\u0639\u0631\u0648\u0636 \u0627\u0644\u0646\u0634\u0637\u0629', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  ...active.map((o) => _OfferCard(offer: o)),
                ],
                if (upcoming.isNotEmpty) ...[
                  const SizedBox(height: 24),
                  const Text('\u0639\u0631\u0648\u0636 \u0642\u0627\u062f\u0645\u0629', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  ...upcoming.map((o) => _OfferCard(offer: o, isUpcoming: true)),
                ],
              ],
            ),
          );
        },
      ),
    );
  }
}

class _OfferCard extends StatelessWidget {
  final OfferModel offer;
  final bool isUpcoming;
  const _OfferCard({required this.offer, this.isUpcoming = false});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      clipBehavior: Clip.antiAlias,
      child: InkWell(
        onTap: offer.categorySlug != null ? () => context.push('/products?category=${offer.categorySlug}') : null,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (offer.image != null)
              Stack(
                children: [
                  CachedNetworkImage(
                    imageUrl: offer.image!,
                    height: 180,
                    width: double.infinity,
                    fit: BoxFit.cover,
                    errorWidget: (_, __, ___) => Container(
                      height: 180,
                      color: AppColors.primary.withValues(alpha: 0.1),
                      child: const Center(child: Icon(Icons.local_offer, size: 48, color: AppColors.primary)),
                    ),
                  ),
                  if (offer.hasDiscount)
                    Positioned(
                      top: 12,
                      right: 12,
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: Colors.red,
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: Text(
                          '\u062e\u0635\u0645 ${offer.discountLabel}',
                          style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14),
                        ),
                      ),
                    ),
                  if (isUpcoming)
                    Positioned(
                      top: 12,
                      left: 12,
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: Colors.orange,
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: const Text('\u0642\u0631\u064a\u0628\u0627\u064b', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                      ),
                    ),
                ],
              ),
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(offer.title, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  if (offer.description != null) ...[
                    const SizedBox(height: 8),
                    Text(offer.description!, style: const TextStyle(color: AppColors.textSecondary), maxLines: 3, overflow: TextOverflow.ellipsis),
                  ],
                  if (offer.offerPrice != null && offer.originalPrice != null) ...[
                    const SizedBox(height: 12),
                    Row(
                      children: [
                        Text(Helpers.formatPrice(offer.offerPrice), style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: AppColors.primary)),
                        const SizedBox(width: 12),
                        Text(Helpers.formatPrice(offer.originalPrice), style: const TextStyle(fontSize: 15, color: AppColors.textSecondary, decoration: TextDecoration.lineThrough)),
                        if (offer.savings != null) ...[
                          const SizedBox(width: 12),
                          Text('\u0648\u0641\u0631 ${Helpers.formatPrice(offer.savings)}', style: const TextStyle(color: Colors.green, fontWeight: FontWeight.w600)),
                        ],
                      ],
                    ),
                  ],
                  if (offer.endDate != null) ...[
                    const SizedBox(height: 8),
                    Row(
                      children: [
                        const Icon(Icons.timer_outlined, size: 16, color: AppColors.textSecondary),
                        const SizedBox(width: 4),
                        Text(
                          isUpcoming ? '\u064a\u0628\u062f\u0623: ${offer.startDate ?? ""}' : '\u064a\u0646\u062a\u0647\u064a: ${offer.endDate}',
                          style: const TextStyle(color: AppColors.textSecondary, fontSize: 13),
                        ),
                      ],
                    ),
                  ],
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
