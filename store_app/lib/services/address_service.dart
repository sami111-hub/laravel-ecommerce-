import '../core/network/api_client.dart';
import '../core/constants/api_constants.dart';
import '../models/address_model.dart';

class AddressService {
  final _client = ApiClient();

  Future<List<AddressModel>> getAddresses() async {
    final res = await _client.get(ApiConstants.addresses);
    final list = res.data['addresses'] ?? res.data['data'] ?? res.data;
    if (list is List) return list.map((e) => AddressModel.fromJson(e)).toList();
    return [];
  }

  Future<AddressModel> addAddress(AddressModel address) async {
    final res = await _client.post(ApiConstants.addresses, data: address.toJson());
    final data = res.data['address'] ?? res.data['data'] ?? res.data;
    return AddressModel.fromJson(data);
  }

  Future<void> updateAddress(int id, Map<String, dynamic> data) async {
    await _client.put('${ApiConstants.addresses}/$id', data: data);
  }

  Future<void> deleteAddress(int id) async {
    await _client.delete('${ApiConstants.addresses}/$id');
  }

  Future<void> setDefault(int id) async {
    await _client.put('${ApiConstants.addresses}/$id/default');
  }
}
