import '../core/network/api_client.dart';
import '../core/constants/api_constants.dart';
import '../models/product_model.dart';
import '../models/category_model.dart';

class ProductService {
  final _client = ApiClient();

  Future<List<ProductModel>> getProducts({int? page, int? categoryId}) async {
    final params = <String, dynamic>{};
    if (page != null) params['page'] = page;
    if (categoryId != null) params['category_id'] = categoryId;
    final res = await _client.get(ApiConstants.products, queryParameters: params);
    final list = res.data['data'] ?? res.data['products'] ?? res.data;
    if (list is List) return list.map((e) => ProductModel.fromJson(e)).toList();
    return [];
  }

  Future<List<ProductModel>> getFeatured() async {
    final res = await _client.get(ApiConstants.featured);
    final list = res.data['data'] ?? res.data['products'] ?? res.data;
    if (list is List) return list.map((e) => ProductModel.fromJson(e)).toList();
    return [];
  }

  Future<List<ProductModel>> getLatest() async {
    final res = await _client.get(ApiConstants.latest);
    final list = res.data['data'] ?? res.data['products'] ?? res.data;
    if (list is List) return list.map((e) => ProductModel.fromJson(e)).toList();
    return [];
  }

  Future<List<ProductModel>> getFlashDeals() async {
    final res = await _client.get(ApiConstants.flashDeals);
    final list = res.data['data'] ?? res.data['products'] ?? res.data;
    if (list is List) return list.map((e) => ProductModel.fromJson(e)).toList();
    return [];
  }

  Future<List<ProductModel>> search(String query) async {
    final res = await _client.get(ApiConstants.productSearch, queryParameters: {'q': query});
    final list = res.data['data'] ?? res.data['products'] ?? res.data;
    if (list is List) return list.map((e) => ProductModel.fromJson(e)).toList();
    return [];
  }

  Future<ProductModel> getProduct(int id) async {
    final res = await _client.get('${ApiConstants.products}/$id');
    final data = res.data['product'] ?? res.data['data'] ?? res.data;
    return ProductModel.fromJson(data);
  }

  Future<List<CategoryModel>> getCategories() async {
    final res = await _client.get(ApiConstants.categories);
    final list = res.data['data'] ?? res.data['categories'] ?? res.data;
    if (list is List) return list.map((e) => CategoryModel.fromJson(e)).toList();
    return [];
  }

  Future<Map<String, dynamic>> getCategoryProducts(int categoryId, {int page = 1, int perPage = 20}) async {
    final res = await _client.get(
      '${ApiConstants.categories}/$categoryId/products',
      queryParameters: {'page': page, 'per_page': perPage},
    );
    final list = res.data['data'] ?? res.data['products'] ?? res.data;
    final products = (list is List) ? list.map((e) => ProductModel.fromJson(e)).toList() : <ProductModel>[];
    final meta = res.data['meta'];
    return {
      'products': products,
      'currentPage': meta?['current_page'] ?? page,
      'lastPage': meta?['last_page'] ?? 1,
      'total': meta?['total'] ?? products.length,
    };
  }

  Future<Map<String, dynamic>> getHome() async {
    final res = await _client.get(ApiConstants.home);
    return res.data is Map<String, dynamic> ? res.data : {};
  }
}
