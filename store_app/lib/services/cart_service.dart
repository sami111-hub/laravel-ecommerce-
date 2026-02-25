import '../core/network/api_client.dart';
import '../core/constants/api_constants.dart';
import '../models/cart_model.dart';

class CartService {
  final _client = ApiClient();

  Future<Map<String, dynamic>> getCart() async {
    final res = await _client.get(ApiConstants.cart);
    final data = res.data;
    final cartList = data['cart'] ?? data['data'] ?? [];
    final items = (cartList as List).map((e) => CartModel.fromJson(e)).toList();
    return {
      'items': items,
      'subtotal': double.tryParse(data['subtotal'].toString()) ?? 0.0,
      'items_count': data['items_count'] ?? items.length,
    };
  }

  Future<int> getCount() async {
    final res = await _client.get(ApiConstants.cartCount);
    return res.data['count'] ?? 0;
  }

  Future<void> addToCart(int productId, {int quantity = 1}) async {
    await _client.post(ApiConstants.cart, data: {
      'product_id': productId,
      'quantity': quantity,
    });
  }

  Future<void> updateQuantity(int cartId, int quantity) async {
    await _client.put('${ApiConstants.cart}/$cartId', data: {
      'quantity': quantity,
    });
  }

  Future<void> removeFromCart(int cartId) async {
    await _client.delete('${ApiConstants.cart}/$cartId');
  }
}
