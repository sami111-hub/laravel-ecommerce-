import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../core/constants/app_colors.dart';

class LoadingWidget extends StatelessWidget {
  const LoadingWidget({super.key});

  @override
  Widget build(BuildContext context) {
    return const Center(child: CircularProgressIndicator(color: AppColors.primary));
  }
}

class ProductShimmer extends StatelessWidget {
  final int count;
  const ProductShimmer({super.key, this.count = 4});

  @override
  Widget build(BuildContext context) {
    return GridView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
        childAspectRatio: 0.65,
        crossAxisSpacing: AppSpacing.md,
        mainAxisSpacing: AppSpacing.md,
      ),
      itemCount: count,
      itemBuilder: (_, __) => Shimmer.fromColors(
        baseColor: AppColors.border,
        highlightColor: AppColors.scaffoldBg,
        child: Container(
          decoration: BoxDecoration(color: AppColors.cardBackground, borderRadius: AppRadius.cardRadius),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(height: 140, decoration: BoxDecoration(color: AppColors.cardBackground, borderRadius: AppRadius.cardRadius)),
              Padding(
                padding: const EdgeInsets.all(AppSpacing.sm + 2),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(height: 12, width: 60, color: AppColors.cardBackground),
                    const SizedBox(height: AppSpacing.sm),
                    Container(height: 14, width: double.infinity, color: AppColors.cardBackground),
                    const SizedBox(height: AppSpacing.sm),
                    Container(height: 14, width: 80, color: AppColors.cardBackground),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class ListShimmer extends StatelessWidget {
  final int count;
  const ListShimmer({super.key, this.count = 5});

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: count,
      itemBuilder: (_, __) => Shimmer.fromColors(
        baseColor: AppColors.border,
        highlightColor: AppColors.scaffoldBg,
        child: Container(
          margin: const EdgeInsets.symmetric(vertical: AppSpacing.xs + 2, horizontal: AppSpacing.lg),
          height: 80,
          decoration: BoxDecoration(color: AppColors.cardBackground, borderRadius: AppRadius.cardRadius),
        ),
      ),
    );
  }
}
