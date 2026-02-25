class OfferModel {
  final int id;
  final String title;
  final String? description;
  final String? image;
  final double? discountPercentage;
  final double? originalPrice;
  final double? offerPrice;
  final double? savings;
  final String? startDate;
  final String? endDate;
  final bool isActive;
  final String? categorySlug;
  final Map<String, dynamic>? specifications;

  OfferModel({
    required this.id,
    required this.title,
    this.description,
    this.image,
    this.discountPercentage,
    this.originalPrice,
    this.offerPrice,
    this.savings,
    this.startDate,
    this.endDate,
    this.isActive = true,
    this.categorySlug,
    this.specifications,
  });

  factory OfferModel.fromJson(Map<String, dynamic> json) {
    return OfferModel(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      description: json['description'],
      image: json['image'],
      discountPercentage: (json['discount_percentage'] as num?)?.toDouble(),
      originalPrice: (json['original_price'] as num?)?.toDouble(),
      offerPrice: (json['offer_price'] as num?)?.toDouble(),
      savings: (json['savings'] as num?)?.toDouble(),
      startDate: json['start_date'],
      endDate: json['end_date'],
      isActive: json['is_active'] ?? true,
      categorySlug: json['category_slug'],
      specifications: json['specifications'] is Map ? Map<String, dynamic>.from(json['specifications']) : null,
    );
  }

  bool get hasDiscount => discountPercentage != null && discountPercentage! > 0;
  String get discountLabel => hasDiscount ? '${discountPercentage!.toStringAsFixed(0)}%' : '';
}
