class AddressModel {
  final int id;
  final String label;
  final String street;
  final String city;
  final String area;
  final String? buildingNumber;
  final String? floor;
  final String? apartment;
  final String phone;
  final String? additionalInfo;
  final bool isDefault;

  AddressModel({
    required this.id,
    required this.label,
    required this.street,
    required this.city,
    required this.area,
    this.buildingNumber,
    this.floor,
    this.apartment,
    required this.phone,
    this.additionalInfo,
    this.isDefault = false,
  });

  String get fullAddress => '$city، $area، $street';

  factory AddressModel.fromJson(Map<String, dynamic> json) {
    return AddressModel(
      id: json['id'] ?? 0,
      label: json['label'] ?? '',
      street: json['street'] ?? '',
      city: json['city'] ?? '',
      area: json['area'] ?? '',
      buildingNumber: json['building_number'],
      floor: json['floor'],
      apartment: json['apartment'],
      phone: json['phone'] ?? '',
      additionalInfo: json['additional_info'],
      isDefault: json['is_default'] ?? false,
    );
  }

  Map<String, dynamic> toJson() => {
        'label': label,
        'street': street,
        'city': city,
        'area': area,
        'building_number': buildingNumber,
        'floor': floor,
        'apartment': apartment,
        'phone': phone,
        'additional_info': additionalInfo,
        'is_default': isDefault,
      };
}
