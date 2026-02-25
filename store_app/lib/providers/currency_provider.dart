import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/currency_model.dart';
import '../core/network/api_client.dart';
import '../core/utils/helpers.dart';

/// حالة العملات
class CurrencyState {
  final List<CurrencyModel> currencies;
  final CurrencyModel selectedCurrency;
  final bool isLoading;
  final String? error;

  CurrencyState({
    required this.currencies,
    required this.selectedCurrency,
    this.isLoading = false,
    this.error,
  });

  factory CurrencyState.initial() {
    return CurrencyState(
      currencies: [
        CurrencyModel.usd,
        CurrencyModel.sar,
        CurrencyModel.yer,
      ],
      selectedCurrency: CurrencyModel.yer, // اليمني افتراضي
    );
  }

  CurrencyState copyWith({
    List<CurrencyModel>? currencies,
    CurrencyModel? selectedCurrency,
    bool? isLoading,
    String? error,
  }) {
    return CurrencyState(
      currencies: currencies ?? this.currencies,
      selectedCurrency: selectedCurrency ?? this.selectedCurrency,
      isLoading: isLoading ?? this.isLoading,
      error: error,
    );
  }
}

/// Provider للعملات
class CurrencyNotifier extends StateNotifier<CurrencyState> {
  CurrencyNotifier() : super(CurrencyState.initial()) {
    _loadSavedCurrency();
    fetchCurrencies();
  }

  static const String _currencyKey = 'selected_currency';

  /// تحميل العملة المحفوظة
  Future<void> _loadSavedCurrency() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final savedCode = prefs.getString(_currencyKey);
      if (savedCode != null) {
        final currency = state.currencies.firstWhere(
          (c) => c.code == savedCode,
          orElse: () => CurrencyModel.yer,
        );
        state = state.copyWith(selectedCurrency: currency);
      }
    } catch (e) {
      // ignore
    }
  }

  /// جلب أسعار الصرف من الخادم
  Future<void> fetchCurrencies() async {
    state = state.copyWith(isLoading: true);

    try {
      final response = await ApiClient().get('/currencies');
      final data = response.data;

      if (data['success'] == true && data['data'] != null) {
        final currenciesData = data['data']['currencies'] as List;
        final currencies = currenciesData
            .map((c) => CurrencyModel.fromJson(c))
            .toList();

        // الحفاظ على العملة المختارة
        final selectedCode = state.selectedCurrency.code;
        final selected = currencies.firstWhere(
          (c) => c.code == selectedCode,
          orElse: () => currencies.firstWhere(
            (c) => c.code == 'YER',
            orElse: () => currencies.first,
          ),
        );

        state = state.copyWith(
          currencies: currencies,
          selectedCurrency: selected,
          isLoading: false,
          error: null,
        );

        // تحديث أسعار الصرف في Helpers
        final sarRate = currencies.firstWhere(
          (c) => c.code == 'SAR',
          orElse: () => CurrencyModel.sar,
        ).rate;
        final yerRate = currencies.firstWhere(
          (c) => c.code == 'YER',
          orElse: () => CurrencyModel.yer,
        ).rate;
        Helpers.updateExchangeRates(rateSar: sarRate, rateYer: yerRate);
      }
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        error: e.toString(),
      );
    }
  }

  /// تغيير العملة المختارة
  Future<void> selectCurrency(CurrencyModel currency) async {
    state = state.copyWith(selectedCurrency: currency);

    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString(_currencyKey, currency.code);
    } catch (e) {
      // ignore
    }
  }

  /// تحويل السعر من الدولار للعملة المختارة
  double convertPrice(double priceUsd) {
    return priceUsd * state.selectedCurrency.rate;
  }

  /// تنسيق السعر بالعملة المختارة
  String formatPrice(double priceUsd) {
    final converted = convertPrice(priceUsd);
    final symbol = state.selectedCurrency.symbol;
    
    if (state.selectedCurrency.code == 'YER') {
      return '${converted.toStringAsFixed(0)} $symbol';
    }
    return '${converted.toStringAsFixed(2)} $symbol';
  }

  /// الحصول على سعر معين بعملة معينة
  double getPriceInCurrency(double priceUsd, String currencyCode) {
    final currency = state.currencies.firstWhere(
      (c) => c.code == currencyCode,
      orElse: () => CurrencyModel.usd,
    );
    return priceUsd * currency.rate;
  }

  /// تنسيق جميع العملات (للعرض المتعدد)
  List<String> formatAllPrices(double priceUsd) {
    return state.currencies.map((c) {
      final converted = priceUsd * c.rate;
      if (c.code == 'YER') {
        return '${converted.toStringAsFixed(0)} ${c.symbol}';
      }
      return '${converted.toStringAsFixed(2)} ${c.symbol}';
    }).toList();
  }
}

/// Provider الرئيسي للعملات
final currencyProvider =
    StateNotifierProvider<CurrencyNotifier, CurrencyState>((ref) {
  return CurrencyNotifier();
});

/// Provider للعملة المختارة فقط
final selectedCurrencyProvider = Provider<CurrencyModel>((ref) {
  return ref.watch(currencyProvider).selectedCurrency;
});

/// Provider لقائمة العملات
final currenciesListProvider = Provider<List<CurrencyModel>>((ref) {
  return ref.watch(currencyProvider).currencies;
});
