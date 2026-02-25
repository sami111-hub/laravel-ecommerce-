import '../core/network/api_client.dart';
import '../core/constants/api_constants.dart';
import '../models/review_model.dart';

class ReviewService {
  final _client = ApiClient();

  Future<Map<String, dynamic>> getReviews(int productId, {int? page}) async {
    final params = <String, dynamic>{};
    if (page != null) params['page'] = page;
    final res = await _client.get(
      '${ApiConstants.products}/$productId/reviews',
      queryParameters: params,
    );
    final data = res.data;
    final list = data['reviews']?['data'] ?? data['reviews'] ?? data['data'] ?? [];
    return {
      'reviews': (list as List).map((e) => ReviewModel.fromJson(e)).toList(),
      'stats': data['stats'] ?? {},
    };
  }

  Future<ReviewModel> addReview(int productId, {required int rating, String? comment}) async {
    final res = await _client.post(
      '${ApiConstants.products}/$productId/reviews',
      data: {
        'rating': rating,
        if (comment != null) 'comment': comment,
      },
    );
    final data = res.data['review'] ?? res.data['data'] ?? res.data;
    return ReviewModel.fromJson(data);
  }

  Future<void> updateReview(int reviewId, {required int rating, String? comment}) async {
    await _client.put('${ApiConstants.reviews}/$reviewId', data: {
      'rating': rating,
      if (comment != null) 'comment': comment,
    });
  }

  Future<void> deleteReview(int reviewId) async {
    await _client.delete('${ApiConstants.reviews}/$reviewId');
  }
}
