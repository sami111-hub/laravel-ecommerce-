class OrderModel {
  final int id;
  final String? trackingCode;
  final String status;
  final double total;
  final double subtotal;
  final double discount;
  final String? couponCode;
  final String? shippingAddress;
  final String? phone;
  final String? notes;
  final String? createdAt;
  final List<OrderItemModel> items;

  OrderModel({
    required this.id,
    this.trackingCode,
    required this.status,
    required this.total,
    this.subtotal = 0,
    this.discount = 0,
    this.couponCode,
    this.shippingAddress,
    this.phone,
    this.notes,
    this.createdAt,
    this.items = const [],
  });

  bool get canCancel => status == 'pending';

  factory OrderModel.fromJson(Map<String, dynamic> json) {
    List<OrderItemModel> orderItems = [];
    if (json['items'] != null && json['items'] is List) {
      orderItems = (json['items'] as List).map((e) => OrderItemModel.fromJson(e)).toList();
    }

    return OrderModel(
      id: json['id'] ?? 0,
      trackingCode: json['tracking_code'],
      status: json['status'] ?? 'pending',
      total: double.tryParse(json['total'].toString()) ?? 0,
      subtotal: double.tryParse(json['subtotal'].toString()) ?? 0,
      discount: double.tryParse(json['discount'].toString()) ?? 0,
      couponCode: json['coupon_code'],
      shippingAddress: json['shipping_address'],
      phone: json['phone'],
      notes: json['notes'],
      createdAt: json['created_at'],
      items: orderItems,
    );
  }
}

class OrderItemModel {
  final int id;
  final int productId;
  final String productName;
  final double productPrice;
  final int quantity;
  final double subtotal;
  final String? productImage;

  OrderItemModel({
    required this.id,
    required this.productId,
    required this.productName,
    required this.productPrice,
    required this.quantity,
    required this.subtotal,
    this.productImage,
  });

  factory OrderItemModel.fromJson(Map<String, dynamic> json) {
    final product = json['product'] as Map<String, dynamic>?;
    return OrderItemModel(
      id: json['id'] ?? 0,
      productId: json['product_id'] ?? 0,
      productName: json['product_name'] ?? product?['name'] ?? '',
      productPrice: double.tryParse(json['product_price'].toString()) ?? 0,
      quantity: json['quantity'] ?? 1,
      subtotal: double.tryParse(json['subtotal'].toString()) ?? 0,
      productImage: product?['image'] ?? product?['thumbnail'],
    );
  }
}
