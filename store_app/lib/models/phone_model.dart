class PhoneBrandModel {
  final int id;
  final String name;
  final String? slug;
  final String? logo;
  final int phonesCount;

  PhoneBrandModel({
    required this.id,
    required this.name,
    this.slug,
    this.logo,
    this.phonesCount = 0,
  });

  factory PhoneBrandModel.fromJson(Map<String, dynamic> json) {
    return PhoneBrandModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'],
      logo: json['logo'],
      phonesCount: json['phones_count'] ?? 0,
    );
  }
}

class PhoneSpecModel {
  final int id;
  final String group;
  final String key;
  final String value;
  final int order;

  PhoneSpecModel({
    required this.id,
    required this.group,
    required this.key,
    required this.value,
    this.order = 0,
  });

  factory PhoneSpecModel.fromJson(Map<String, dynamic> json) {
    return PhoneSpecModel(
      id: json['id'] ?? 0,
      group: json['group'] ?? '',
      key: json['key'] ?? '',
      value: json['value']?.toString() ?? '',
      order: json['order'] ?? 0,
    );
  }
}

class PhonePriceModel {
  final int id;
  final String? region;
  final String? currency;
  final double price;
  final String? formattedPrice;
  final String? source;
  final String? effectiveDate;
  final bool isCurrent;

  PhonePriceModel({
    required this.id,
    this.region,
    this.currency,
    required this.price,
    this.formattedPrice,
    this.source,
    this.effectiveDate,
    this.isCurrent = false,
  });

  factory PhonePriceModel.fromJson(Map<String, dynamic> json) {
    return PhonePriceModel(
      id: json['id'] ?? 0,
      region: json['region'],
      currency: json['currency'],
      price: double.tryParse(json['price'].toString()) ?? 0,
      formattedPrice: json['formatted_price'],
      source: json['source'],
      effectiveDate: json['effective_date'],
      isCurrent: json['is_current'] ?? false,
    );
  }
}

class PhoneModel {
  final int id;
  final String name;
  final String? slug;
  final PhoneBrandModel? brand;
  final String? image;
  final List<String> images;
  final String? description;
  final double? price;
  final PhonePriceModel? currentPrice;
  final List<PhoneSpecModel> specs;
  final List<PhonePriceModel> prices;
  final String? chipset;
  final String? ram;
  final String? storage;
  final double? displaySize;
  final int? batteryMah;
  final String? os;
  final int? releaseYear;
  final int views;
  final String? createdAt;

  PhoneModel({
    required this.id,
    required this.name,
    this.slug,
    this.brand,
    this.image,
    this.images = const [],
    this.description,
    this.price,
    this.currentPrice,
    this.specs = const [],
    this.prices = const [],
    this.chipset,
    this.ram,
    this.storage,
    this.displaySize,
    this.batteryMah,
    this.os,
    this.releaseYear,
    this.views = 0,
    this.createdAt,
  });

  /// اسم البراند كنص
  String get brandName => brand?.name ?? '';

  /// أفضل سعر متاح
  double? get effectivePrice => currentPrice?.price ?? price;

  /// المواصفات كـ Map (group → list of key:value)
  Map<String, List<PhoneSpecModel>> get groupedSpecs {
    final map = <String, List<PhoneSpecModel>>{};
    for (final spec in specs) {
      map.putIfAbsent(spec.group, () => []).add(spec);
    }
    return map;
  }

  /// المواصفات كـ Map مسطح (key → value)
  Map<String, String> get specsMap {
    return {for (final s in specs) s.key: s.value};
  }

  factory PhoneModel.fromJson(Map<String, dynamic> json) {
    // Brand
    PhoneBrandModel? brandModel;
    if (json['brand'] is Map<String, dynamic>) {
      brandModel = PhoneBrandModel.fromJson(json['brand']);
    }

    // Specs (API returns List, not Map)
    List<PhoneSpecModel> specsList = [];
    if (json['specs'] is List) {
      specsList = (json['specs'] as List)
          .map((e) => PhoneSpecModel.fromJson(e as Map<String, dynamic>))
          .toList();
    }

    // Prices
    List<PhonePriceModel> pricesList = [];
    if (json['prices'] is List) {
      pricesList = (json['prices'] as List)
          .map((e) => PhonePriceModel.fromJson(e as Map<String, dynamic>))
          .toList();
    }

    // Current price
    PhonePriceModel? currentPriceModel;
    if (json['current_price'] is Map<String, dynamic>) {
      currentPriceModel = PhonePriceModel.fromJson(json['current_price']);
    }

    // Images
    List<String> imagesList = [];
    if (json['images'] is List) {
      imagesList = (json['images'] as List).map((e) => e.toString()).toList();
    }

    return PhoneModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'],
      brand: brandModel,
      image: json['thumbnail'] ?? json['image'],
      images: imagesList,
      description: json['description'],
      price: json['price'] != null ? double.tryParse(json['price'].toString()) : null,
      currentPrice: currentPriceModel,
      specs: specsList,
      prices: pricesList,
      chipset: json['chipset'],
      ram: json['ram'],
      storage: json['storage'],
      displaySize: json['display_size'] != null
          ? double.tryParse(json['display_size'].toString())
          : null,
      batteryMah: json['battery_mah'],
      os: json['os'],
      releaseYear: json['release_year'],
      views: json['views'] ?? 0,
      createdAt: json['created_at'],
    );
  }
}
