class ApiConstants {
  static const String baseUrl = 'https://store.update-aden.com/api/v1';

  // Auth
  static const String login = '/login';
  static const String register = '/register';
  static const String logout = '/logout';
  static const String user = '/user';
  static const String updateProfile = '/user/profile';
  static const String changePassword = '/user/password';

  // Home
  static const String home = '/home';
  static const String settings = '/settings';

  // Products
  static const String products = '/products';
  static const String featured = '/products/featured';
  static const String latest = '/products/latest';
  static const String flashDeals = '/products/flash-deals';
  static const String productSearch = '/products/search';

  // Categories
  static const String categories = '/categories';

  // Phones
  static const String phones = '/phones';
  static const String phonesLatest = '/phones/latest';
  static const String phonesPopular = '/phones/popular';
  static const String phonesSearch = '/phones/search';
  static const String phonesBrands = '/phones/brands';
  static const String phonesCompare = '/phones/compare';

  // Cart
  static const String cart = '/cart';
  static const String cartCount = '/cart/count';

  // Orders
  static const String orders = '/orders';

  // Wishlist
  static const String wishlist = '/wishlist';
  static const String wishlistToggle = '/wishlist/toggle';

  // Reviews
  static const String reviews = '/reviews';

  // Addresses
  static const String addresses = '/addresses';

  // Coupons
  static const String validateCoupon = '/coupons/validate';

  // Offers
  static const String offers = '/offers';
}
