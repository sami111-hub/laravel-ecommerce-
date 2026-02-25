import '../core/network/api_client.dart';
import '../core/constants/api_constants.dart';
import '../models/product_model.dart';

class WishlistService {
  final _client = ApiClient();

  Future<List<ProductModel>> getWishlist() async {
    final res = await _client.get(ApiConstants.wishlist);
    final list = res.data['products'] ?? res.data['data'] ?? res.data;
    if (list is List) return list.map((e) => ProductModel.fromJson(e)).toList();
    return [];
  }

  Future<bool> toggle(int productId) async {
    final res = await _client.post(ApiConstants.wishlistToggle, data: {
      'product_id': productId,
    });
    return res.data['in_wishlist'] ?? false;
  }
}
