import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../screens/splash/splash_screen.dart';
import '../screens/onboarding/onboarding_screen.dart';
import '../screens/auth/login_screen.dart';
import '../screens/auth/register_screen.dart';
import '../screens/main_screen.dart';
import '../screens/search/search_screen.dart';
import '../screens/products/products_screen.dart';
import '../screens/products/product_detail_screen.dart';
import '../screens/cart/checkout_screen.dart';
import '../screens/orders/orders_screen.dart';
import '../screens/orders/order_detail_screen.dart';
import '../screens/addresses/addresses_screen.dart';
import '../screens/addresses/add_address_screen.dart';
import '../screens/phones/phones_screen.dart';
import '../screens/phones/phone_detail_screen.dart';
import '../screens/offers/offers_screen.dart';

final appRouter = GoRouter(
  initialLocation: '/splash',
  routes: [
    GoRoute(
      path: '/splash',
      builder: (_, __) => const SplashScreen(),
    ),
    GoRoute(
      path: '/onboarding',
      builder: (_, __) => const OnboardingScreen(),
    ),
    GoRoute(
      path: '/login',
      builder: (_, __) => const LoginScreen(),
    ),
    GoRoute(
      path: '/register',
      builder: (_, __) => const RegisterScreen(),
    ),
    GoRoute(
      path: '/main',
      builder: (_, state) {
        final tab = int.tryParse(state.uri.queryParameters['tab'] ?? '0') ?? 0;
        return MainScreen(initialTab: tab);
      },
    ),
    GoRoute(
      path: '/search',
      builder: (_, __) => const SearchScreen(),
    ),
    GoRoute(
      path: '/products',
      builder: (_, state) {
        final filter = state.uri.queryParameters['filter'];
        return ProductsScreen(filter: filter);
      },
    ),
    GoRoute(
      path: '/category/:id',
      builder: (_, state) {
        final id = int.parse(state.pathParameters['id']!);
        final name = state.uri.queryParameters['name'];
        return ProductsScreen(categoryId: id, categoryName: name);
      },
    ),
    GoRoute(
      path: '/product/:id',
      builder: (_, state) {
        final id = int.parse(state.pathParameters['id']!);
        return ProductDetailScreen(productId: id);
      },
    ),
    GoRoute(
      path: '/checkout',
      builder: (_, __) => const CheckoutScreen(),
    ),
    GoRoute(
      path: '/orders',
      builder: (_, __) => const OrdersScreen(),
    ),
    GoRoute(
      path: '/orders/:id',
      builder: (_, state) {
        final id = int.parse(state.pathParameters['id']!);
        return OrderDetailScreen(orderId: id);
      },
    ),
    GoRoute(
      path: '/addresses',
      builder: (_, __) => const AddressesScreen(),
    ),
    GoRoute(
      path: '/addresses/add',
      builder: (_, __) => const AddAddressScreen(),
    ),
    GoRoute(
      path: '/phones',
      builder: (_, __) => const PhonesScreen(),
    ),
    GoRoute(
      path: '/phones/:slug',
      builder: (_, state) {
        final slug = state.pathParameters['slug']!;
        return PhoneDetailScreen(phoneSlug: slug);
      },
    ),
    GoRoute(
      path: '/offers',
      builder: (_, __) => const OffersScreen(),
    ),
    // Placeholder routes for unimplemented screens
    GoRoute(
      path: '/notifications',
      builder: (_, __) => _placeholder('الإشعارات'),
    ),
    GoRoute(
      path: '/categories',
      builder: (_, __) => _placeholder('جميع التصنيفات'),
    ),
  ],
);

Widget _placeholder(String title) {
  return Scaffold(
    appBar: AppBar(title: Text(title)),
    body: Center(child: Text('صفحة $title - قريباً', style: const TextStyle(fontSize: 18))),
  );
}
