import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/product_model.dart';
import '../services/wishlist_service.dart';

final wishlistServiceProvider = Provider((ref) => WishlistService());

final wishlistProvider = StateNotifierProvider<WishlistNotifier, AsyncValue<List<ProductModel>>>((ref) {
  return WishlistNotifier(ref.read(wishlistServiceProvider));
});

class WishlistNotifier extends StateNotifier<AsyncValue<List<ProductModel>>> {
  final WishlistService _service;

  WishlistNotifier(this._service) : super(const AsyncValue.data([]));

  Future<void> load() async {
    state = const AsyncValue.loading();
    try {
      final items = await _service.getWishlist();
      state = AsyncValue.data(items);
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }

  Future<bool> toggle(int productId) async {
    try {
      final inWishlist = await _service.toggle(productId);
      await load();
      return inWishlist;
    } catch (_) {
      return false;
    }
  }
}
