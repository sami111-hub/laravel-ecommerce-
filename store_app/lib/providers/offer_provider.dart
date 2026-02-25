import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/offer_model.dart';
import '../services/offer_service.dart';

final offersProvider = FutureProvider<Map<String, List<OfferModel>>>((ref) async {
  return OfferService().getOffers();
});
