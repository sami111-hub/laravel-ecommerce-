import '../core/network/api_client.dart';
import '../core/constants/api_constants.dart';
import '../models/offer_model.dart';

class OfferService {
  final _client = ApiClient();

  /// جلب العروض النشطة والقادمة
  Future<Map<String, List<OfferModel>>> getOffers() async {
    final response = await _client.get(ApiConstants.offers);
    final data = response.data;

    final activeList = (data['active_offers'] as List? ?? [])
        .map((e) => OfferModel.fromJson(e))
        .toList();

    final upcomingList = (data['upcoming_offers'] as List? ?? [])
        .map((e) => OfferModel.fromJson(e))
        .toList();

    return {
      'active': activeList,
      'upcoming': upcomingList,
    };
  }
}
