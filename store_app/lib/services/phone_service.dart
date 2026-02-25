import '../core/network/api_client.dart';
import '../core/constants/api_constants.dart';
import '../models/phone_model.dart';

class PhoneService {
  final _client = ApiClient();

  Future<Map<String, dynamic>> getPhones({int? page, int? brandId, String? sort, int perPage = 12}) async {
    final params = <String, dynamic>{
      'per_page': perPage,
    };
    if (page != null) params['page'] = page;
    if (brandId != null) params['brand_id'] = brandId;
    if (sort != null) params['sort'] = sort;
    final res = await _client.get(ApiConstants.phones, queryParameters: params);
    final list = res.data['data'] ?? res.data['phones'] ?? res.data;
    final phones = (list is List) ? list.map((e) => PhoneModel.fromJson(e)).toList() : <PhoneModel>[];
    // Extract pagination meta
    final meta = res.data['meta'];
    return {
      'phones': phones,
      'currentPage': meta?['current_page'] ?? page ?? 1,
      'lastPage': meta?['last_page'] ?? 1,
      'total': meta?['total'] ?? phones.length,
    };
  }

  /// جلب تفاصيل هاتف - API يستخدم slug وليس ID
  Future<PhoneModel> getPhone(String slug) async {
    final res = await _client.get('${ApiConstants.phones}/$slug');
    final data = res.data['phone'] ?? res.data['data'] ?? res.data;
    return PhoneModel.fromJson(data);
  }

  /// جلب هواتف مشابهة مع تفاصيل الهاتف
  Future<Map<String, dynamic>> getPhoneWithRelated(String slug) async {
    final res = await _client.get('${ApiConstants.phones}/$slug');
    final phoneData = res.data['phone'] ?? res.data['data'] ?? res.data;
    final relatedData = res.data['related'];
    final phone = PhoneModel.fromJson(phoneData);
    List<PhoneModel> related = [];
    if (relatedData is List) {
      related = relatedData.map((e) => PhoneModel.fromJson(e)).toList();
    }
    return {'phone': phone, 'related': related};
  }

  Future<List<PhoneModel>> getLatest() async {
    final res = await _client.get(ApiConstants.phonesLatest);
    final list = res.data['phones'] ?? res.data['data'] ?? res.data;
    if (list is List) return list.map((e) => PhoneModel.fromJson(e)).toList();
    return [];
  }

  Future<List<PhoneModel>> getPopular() async {
    final res = await _client.get(ApiConstants.phonesPopular);
    final list = res.data['phones'] ?? res.data['data'] ?? res.data;
    if (list is List) return list.map((e) => PhoneModel.fromJson(e)).toList();
    return [];
  }

  Future<List<PhoneModel>> search(String query) async {
    final res = await _client.get(ApiConstants.phonesSearch, queryParameters: {'q': query});
    final list = res.data['phones'] ?? res.data['data'] ?? res.data;
    if (list is List) return list.map((e) => PhoneModel.fromJson(e)).toList();
    return [];
  }

  /// جلب العلامات التجارية - API يرجع objects وليس strings
  Future<List<PhoneBrandModel>> getBrands() async {
    final res = await _client.get(ApiConstants.phonesBrands);
    final list = res.data['brands'] ?? res.data['data'] ?? res.data;
    if (list is List) {
      return list.map((e) => PhoneBrandModel.fromJson(e as Map<String, dynamic>)).toList();
    }
    return [];
  }

  /// هواتف علامة تجارية معينة (بالـ slug)
  Future<Map<String, dynamic>> getBrandPhones(String brandSlug, {int page = 1}) async {
    final res = await _client.get(
      '${ApiConstants.phonesBrands}/$brandSlug',
      queryParameters: {'page': page},
    );
    final data = res.data;
    final phonesList = data['phones']?['data'] ?? data['phones'] ?? data['data'] ?? [];
    final phones = (phonesList is List)
        ? phonesList.map((e) => PhoneModel.fromJson(e)).toList()
        : <PhoneModel>[];
    PhoneBrandModel? brand;
    if (data['brand'] is Map<String, dynamic>) {
      brand = PhoneBrandModel.fromJson(data['brand']);
    }
    return {
      'brand': brand,
      'phones': phones,
      'meta': data['meta'],
    };
  }

  /// مقارنة هواتف - API يستخدم GET مع ids كـ query string
  Future<List<PhoneModel>> compare(List<int> ids) async {
    final idsStr = ids.join(',');
    final res = await _client.get(
      ApiConstants.phonesCompare,
      queryParameters: {'ids': idsStr},
    );
    final list = res.data['phones'] ?? res.data['data'] ?? res.data;
    if (list is List) return list.map((e) => PhoneModel.fromJson(e)).toList();
    return [];
  }
}
