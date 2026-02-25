import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/order_model.dart';
import '../services/order_service.dart';
import '../core/network/api_exception.dart';

final orderServiceProvider = Provider((ref) => OrderService());

final ordersProvider = StateNotifierProvider<OrdersNotifier, AsyncValue<List<OrderModel>>>((ref) {
  return OrdersNotifier(ref.read(orderServiceProvider));
});

/// نتيجة إنشاء الطلب - تحتوي إما على الطلب أو رسالة الخطأ
class CreateOrderResult {
  final OrderModel? order;
  final String? errorMessage;
  final bool success;

  CreateOrderResult.success(this.order)
      : success = true,
        errorMessage = null;

  CreateOrderResult.failure(this.errorMessage)
      : success = false,
        order = null;
}

class OrdersNotifier extends StateNotifier<AsyncValue<List<OrderModel>>> {
  final OrderService _service;

  OrdersNotifier(this._service) : super(const AsyncValue.data([]));

  Future<void> load() async {
    state = const AsyncValue.loading();
    try {
      final orders = await _service.getOrders();
      state = AsyncValue.data(orders);
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }

  Future<CreateOrderResult> createOrder({
    required String shippingAddress,
    required String phone,
    String? notes,
    String? couponCode,
  }) async {
    try {
      final order = await _service.createOrder(
        shippingAddress: shippingAddress,
        phone: phone,
        notes: notes,
        couponCode: couponCode,
      );
      await load();
      return CreateOrderResult.success(order);
    } on ApiException catch (e) {
      return CreateOrderResult.failure(e.message);
    } catch (e) {
      return CreateOrderResult.failure('حدث خطأ غير متوقع: $e');
    }
  }

  Future<bool> cancelOrder(int id) async {
    try {
      await _service.cancelOrder(id);
      await load();
      return true;
    } catch (_) {
      return false;
    }
  }
}
