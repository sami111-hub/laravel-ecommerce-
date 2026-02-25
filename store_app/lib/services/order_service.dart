import '../core/network/api_client.dart';
import '../core/constants/api_constants.dart';
import '../models/order_model.dart';

class OrderService {
  final _client = ApiClient();

  Future<List<OrderModel>> getOrders({int? page}) async {
    final params = <String, dynamic>{};
    if (page != null) params['page'] = page;
    final res = await _client.get(ApiConstants.orders, queryParameters: params);
    final list = res.data['data'] ?? res.data;
    if (list is List) return list.map((e) => OrderModel.fromJson(e)).toList();
    return [];
  }

  Future<OrderModel> getOrder(int id) async {
    final res = await _client.get('${ApiConstants.orders}/$id');
    final data = res.data['order'] ?? res.data['data'] ?? res.data;
    return OrderModel.fromJson(data);
  }

  Future<OrderModel> createOrder({
    required String shippingAddress,
    required String phone,
    String? notes,
    String? couponCode,
  }) async {
    final res = await _client.post(ApiConstants.orders, data: {
      'shipping_address': shippingAddress,
      'phone': phone,
      if (notes != null) 'notes': notes,
      if (couponCode != null) 'coupon_code': couponCode,
    });
    final data = res.data['order'] ?? res.data['data'] ?? res.data;
    return OrderModel.fromJson(data);
  }

  Future<void> cancelOrder(int id) async {
    await _client.put('${ApiConstants.orders}/$id/cancel');
  }

  Future<Map<String, dynamic>> validateCoupon(String code, {double? amount}) async {
    final res = await _client.post(ApiConstants.validateCoupon, data: {
      'code': code,
      if (amount != null) 'amount': amount,
    });
    return res.data;
  }
}
