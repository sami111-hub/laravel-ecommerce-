import 'package:flutter/foundation.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/product_model.dart';
import '../models/category_model.dart';
import '../services/product_service.dart';

final productServiceProvider = Provider((ref) => ProductService());

final featuredProductsProvider = FutureProvider<List<ProductModel>>((ref) async {
  return ref.read(productServiceProvider).getFeatured();
});

final latestProductsProvider = FutureProvider<List<ProductModel>>((ref) async {
  return ref.read(productServiceProvider).getLatest();
});

final flashDealsProvider = FutureProvider<List<ProductModel>>((ref) async {
  return ref.read(productServiceProvider).getFlashDeals();
});

final categoriesProvider = FutureProvider<List<CategoryModel>>((ref) async {
  return ref.read(productServiceProvider).getCategories();
});

final homeDataProvider = FutureProvider<Map<String, dynamic>>((ref) async {
  return ref.read(productServiceProvider).getHome();
});

final productDetailProvider = FutureProvider.family<ProductModel, int>((ref, id) async {
  return ref.read(productServiceProvider).getProduct(id);
});

final productSearchProvider = FutureProvider.family<List<ProductModel>, String>((ref, query) async {
  if (query.trim().isEmpty) return [];
  return ref.read(productServiceProvider).search(query);
});

/// Notifier for category products with pagination support
class CategoryProductsNotifier extends FamilyAsyncNotifier<List<ProductModel>, int> {
  int _currentPage = 1;
  int _lastPage = 1;
  bool _isLoadingMore = false;

  bool get hasMore => _currentPage < _lastPage;
  bool get isLoadingMore => _isLoadingMore;

  @override
  Future<List<ProductModel>> build(int arg) async {
    _currentPage = 1;
    _lastPage = 1;
    final result = await ref.read(productServiceProvider).getCategoryProducts(arg, page: 1, perPage: 20);
    _currentPage = result['currentPage'] as int;
    _lastPage = result['lastPage'] as int;
    return List<ProductModel>.from(result['products'] as List);
  }

  Future<void> loadMore() async {
    if (_isLoadingMore || !hasMore) return;
    _isLoadingMore = true;
    try {
      final result = await ref.read(productServiceProvider).getCategoryProducts(arg, page: _currentPage + 1, perPage: 20);
      _currentPage = result['currentPage'] as int;
      _lastPage = result['lastPage'] as int;
      final newProducts = List<ProductModel>.from(result['products'] as List);
      final current = state.valueOrNull ?? [];
      state = AsyncData([...current, ...newProducts]);
    } catch (e) {
      // Keep existing data on error, just log
      debugPrint('Error loading more products: $e');
    } finally {
      _isLoadingMore = false;
    }
  }
}

final categoryProductsProvider =
    AsyncNotifierProvider.family<CategoryProductsNotifier, List<ProductModel>, int>(
  CategoryProductsNotifier.new,
);
