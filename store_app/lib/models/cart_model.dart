class CartModel {
  final int id;
  final int productId;
  final String productName;
  final String? productImage;
  final double price;
  final double? originalPrice;
  final int quantity;
  final int stock;

  CartModel({
    required this.id,
    required this.productId,
    required this.productName,
    this.productImage,
    required this.price,
    this.originalPrice,
    required this.quantity,
    this.stock = 0,
  });

  double get total => price * quantity;

  bool get hasDiscount => originalPrice != null && originalPrice! > price;

  factory CartModel.fromJson(Map<String, dynamic> json) {
    final product = json['product'] as Map<String, dynamic>?;

    // حساب السعر الفعلي: خصم > فلاش ديل > أصلي
    double originalPrice = double.tryParse((product?['price'] ?? json['price']).toString()) ?? 0;
    double? discountPrice = product?['discount_price'] != null
        ? double.tryParse(product!['discount_price'].toString())
        : null;
    double? flashDealPrice = (product?['is_flash_deal'] == true && product?['flash_deal_price'] != null)
        ? double.tryParse(product!['flash_deal_price'].toString())
        : null;

    double effectivePrice = flashDealPrice ?? ((discountPrice != null && discountPrice > 0 && discountPrice < originalPrice)
        ? discountPrice
        : originalPrice);

    return CartModel(
      id: json['id'] ?? 0,
      productId: json['product_id'] ?? product?['id'] ?? 0,
      productName: product?['name'] ?? json['product_name'] ?? '',
      productImage: product?['image'] ?? product?['thumbnail'] ?? json['product_image'],
      price: effectivePrice,
      originalPrice: (effectivePrice < originalPrice) ? originalPrice : null,
      quantity: json['quantity'] ?? 1,
      stock: product?['stock'] ?? json['stock'] ?? 0,
    );
  }
}
