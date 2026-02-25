import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/cart_model.dart';
import '../services/cart_service.dart';

final cartServiceProvider = Provider((ref) => CartService());

final cartProvider = StateNotifierProvider<CartNotifier, AsyncValue<List<CartModel>>>((ref) {
  return CartNotifier(ref.read(cartServiceProvider));
});

final cartCountProvider = StateProvider<int>((ref) => 0);
final cartSubtotalProvider = StateProvider<double>((ref) => 0);

class CartNotifier extends StateNotifier<AsyncValue<List<CartModel>>> {
  final CartService _service;

  CartNotifier(this._service) : super(const AsyncValue.data([]));

  Future<void> loadCart(WidgetRef? ref) async {
    state = const AsyncValue.loading();
    try {
      final data = await _service.getCart();
      final items = data['items'] as List<CartModel>;
      state = AsyncValue.data(items);
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }

  Future<void> load() async {
    state = const AsyncValue.loading();
    try {
      final data = await _service.getCart();
      final items = data['items'] as List<CartModel>;
      state = AsyncValue.data(items);
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }

  Future<bool> addToCart(int productId, {int quantity = 1}) async {
    try {
      await _service.addToCart(productId, quantity: quantity);
      await load();
      return true;
    } catch (_) {
      return false;
    }
  }

  Future<bool> updateQuantity(int cartId, int quantity) async {
    try {
      await _service.updateQuantity(cartId, quantity);
      await load();
      return true;
    } catch (_) {
      return false;
    }
  }

  Future<bool> removeFromCart(int cartId) async {
    try {
      await _service.removeFromCart(cartId);
      await load();
      return true;
    } catch (_) {
      return false;
    }
  }

  Future<void> clearCart() async {
    final items = state.valueOrNull ?? [];
    for (final item in items) {
      try {
        await _service.removeFromCart(item.id);
      } catch (_) {}
    }
    await load();
  }
}
