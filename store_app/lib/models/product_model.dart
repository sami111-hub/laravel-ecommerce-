class ProductModel {
  final int id;
  final String name;
  final String? description;
  final double price;        // سعر البيع الفعلي (بالدولار) - المخفض إن وُجد خصم
  final double? oldPrice;    // السعر الأصلي قبل الخصم (بالدولار) - null إذا لا يوجد خصم
  final double priceSar;     // سعر البيع بالريال السعودي
  final double priceYer;     // سعر البيع بالريال اليمني
  final double? oldPriceSar;
  final double? oldPriceYer;
  final String? image;
  final List<String> images;
  final int stock;
  final int? categoryId;
  final String? categoryName;
  final String? brandName;
  final double rating;
  final int reviewsCount;
  final bool inWishlist;
  final bool isFlashDeal;
  final double? flashDealPrice;
  final String? flashDealEndsAt;
  final Map<String, dynamic> specifications;

  ProductModel({
    required this.id,
    required this.name,
    this.description,
    required this.price,
    this.oldPrice,
    this.priceSar = 0,
    this.priceYer = 0,
    this.oldPriceSar,
    this.oldPriceYer,
    this.image,
    this.images = const [],
    this.stock = 0,
    this.categoryId,
    this.categoryName,
    this.brandName,
    this.rating = 0,
    this.reviewsCount = 0,
    this.inWishlist = false,
    this.isFlashDeal = false,
    this.flashDealPrice,
    this.flashDealEndsAt,
    this.specifications = const {},
  });

  bool get hasDiscount => oldPrice != null && oldPrice! > price;

  int get discountPercent {
    if (!hasDiscount) return 0;
    return (((oldPrice! - price) / oldPrice!) * 100).round();
  }

  bool get inStock => stock > 0;

  factory ProductModel.fromJson(Map<String, dynamic> json) {
    List<String> imgs = [];
    if (json['images'] != null) {
      if (json['images'] is List) {
        imgs = (json['images'] as List).map((e) => e.toString()).toList();
      }
    }

    // Category name: handle both single 'category' object and 'categories' array
    String? catName = json['category_name'] ?? json['category']?['name'];
    if (catName == null && json['categories'] is List && (json['categories'] as List).isNotEmpty) {
      catName = (json['categories'] as List).first['name']?.toString();
    }

    // === حساب السعر الصحيح ===
    // API يرسل: price = السعر الأصلي, discount_price = السعر المخفض (الأقل)
    // flash_deal_price = سعر الفلاش ديل (الأقل)
    // المطلوب: price = سعر البيع الفعلي, oldPrice = السعر الأصلي قبل الخصم
    final double originalPrice = double.tryParse(json['price'].toString()) ?? 0;
    final double? discountPrice = json['discount_price'] != null
        ? double.tryParse(json['discount_price'].toString())
        : null;
    final double? flashPrice = json['flash_deal_price'] != null
        ? double.tryParse(json['flash_deal_price'].toString())
        : null;
    final bool isFlash = json['is_flash_deal'] == true && flashPrice != null && flashPrice > 0;

    // السعر الفعلي = فلاش ديل > خصم > أصلي
    final double effectivePrice = isFlash
        ? flashPrice
        : (discountPrice != null && discountPrice > 0 && discountPrice < originalPrice)
            ? discountPrice
            : originalPrice;
    final double? effectiveOldPrice = (effectivePrice < originalPrice) ? originalPrice : null;

    // أسعار العملات
    final double originalSar = double.tryParse((json['price_sar'] ?? 0).toString()) ?? 0;
    final double originalYer = double.tryParse((json['price_yer'] ?? 0).toString()) ?? 0;
    final double? discountSar = json['discount_price_sar'] != null
        ? double.tryParse(json['discount_price_sar'].toString())
        : null;
    final double? discountYer = json['discount_price_yer'] != null
        ? double.tryParse(json['discount_price_yer'].toString())
        : null;

    final double effectiveSar = (discountSar != null && discountSar > 0 && discountSar < originalSar)
        ? discountSar : originalSar;
    final double effectiveYer = (discountYer != null && discountYer > 0 && discountYer < originalYer)
        ? discountYer : originalYer;
    final double? effectiveOldSar = (effectiveSar < originalSar) ? originalSar : null;
    final double? effectiveOldYer = (effectiveYer < originalYer) ? originalYer : null;

    return ProductModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      description: json['description'],
      price: effectivePrice,
      oldPrice: effectiveOldPrice,
      priceSar: effectiveSar,
      priceYer: effectiveYer,
      oldPriceSar: effectiveOldSar,
      oldPriceYer: effectiveOldYer,
      image: json['image'] ?? json['thumbnail'],
      images: imgs,
      stock: json['stock'] ?? 0,
      categoryId: json['category_id'],
      categoryName: catName,
      brandName: json['brand_name'] ?? json['brand']?['name'],
      // API returns 'average_rating' not 'rating'
      rating: double.tryParse((json['average_rating'] ?? json['rating'] ?? 0).toString()) ?? 0,
      reviewsCount: json['reviews_count'] ?? 0,
      inWishlist: json['in_wishlist'] ?? json['is_wishlisted'] ?? false,
      isFlashDeal: isFlash,
      flashDealPrice: flashPrice,
      flashDealEndsAt: json['flash_deal_ends_at'],
      specifications: (() {
        final s = json['specifications'];
        if (s is Map) return Map<String, dynamic>.from(s);
        return <String, dynamic>{};
      })(),
    );
  }
}
