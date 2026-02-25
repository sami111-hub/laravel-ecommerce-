/// نموذج العملة
class CurrencyModel {
  final String code;
  final String name;
  final String nameEn;
  final String symbol;
  final double rate;

  CurrencyModel({
    required this.code,
    required this.name,
    required this.nameEn,
    required this.symbol,
    required this.rate,
  });

  factory CurrencyModel.fromJson(Map<String, dynamic> json) {
    return CurrencyModel(
      code: json['code'] ?? '',
      name: json['name'] ?? '',
      nameEn: json['name_en'] ?? '',
      symbol: json['symbol'] ?? '',
      rate: double.tryParse(json['rate'].toString()) ?? 1.0,
    );
  }

  // العملات الافتراضية
  static CurrencyModel get usd => CurrencyModel(
        code: 'USD',
        name: 'دولار أمريكي',
        nameEn: 'US Dollar',
        symbol: '\$',
        rate: 1.0,
      );

  static CurrencyModel get sar => CurrencyModel(
        code: 'SAR',
        name: 'ريال سعودي',
        nameEn: 'Saudi Riyal',
        symbol: 'ر.س',
        rate: 3.75,
      );

  static CurrencyModel get yer => CurrencyModel(
        code: 'YER',
        name: 'ريال يمني',
        nameEn: 'Yemeni Rial',
        symbol: 'ر.ي',
        rate: 535.0,
      );
}
