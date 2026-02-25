import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/constants/app_colors.dart';
import '../../core/utils/helpers.dart';
import '../../models/phone_model.dart';
import '../../services/phone_service.dart';
import '../../widgets/error_widget.dart';

class PhonesScreen extends StatefulWidget {
  const PhonesScreen({super.key});

  @override
  State<PhonesScreen> createState() => _PhonesScreenState();
}

class _PhonesScreenState extends State<PhonesScreen> {
  List<PhoneModel> _phones = [];
  bool _loading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() { _loading = true; _error = null; });
    try {
      final result = await PhoneService().getPhones();
      if (mounted) setState(() { _phones = result['phones'] as List<PhoneModel>; _loading = false; });
    } catch (e) {
      if (mounted) setState(() { _error = e.toString(); _loading = false; });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text('الهواتف', style: TextStyle(color: AppColors.textPrimary, fontWeight: FontWeight.bold)),
        centerTitle: true,
        leading: IconButton(icon: const Icon(Icons.arrow_back_ios, color: AppColors.textPrimary), onPressed: () => context.pop()),
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
          : _error != null
              ? AppErrorWidget(message: _error!, onRetry: _load)
              : _phones.isEmpty
                  ? const EmptyWidget(icon: Icons.phone_android, message: 'لا توجد هواتف')
                  : RefreshIndicator(
                      color: AppColors.primary,
                      onRefresh: _load,
                      child: ListView.builder(
                        padding: const EdgeInsets.all(16),
                        itemCount: _phones.length,
                        itemBuilder: (_, i) => _buildPhoneCard(_phones[i]),
                      ),
                    ),
    );
  }

  Widget _buildPhoneCard(PhoneModel phone) {
    final displayPrice = phone.effectivePrice;
    return GestureDetector(
      onTap: () => context.push('/phones/${phone.slug ?? phone.id}'),
      child: Container(
        margin: const EdgeInsets.only(bottom: 12),
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 8, offset: const Offset(0, 2))],
        ),
        child: Row(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: Container(
                width: 80,
                height: 100,
                color: AppColors.background,
                child: phone.image != null
                    ? CachedNetworkImage(imageUrl: phone.image!, fit: BoxFit.cover, errorWidget: (_, __, ___) => const Icon(Icons.phone_android, size: 40))
                    : const Icon(Icons.phone_android, size: 40, color: AppColors.textSecondary),
              ),
            ),
            const SizedBox(width: 14),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(phone.name, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15), maxLines: 2, overflow: TextOverflow.ellipsis),
                  if (phone.brandName.isNotEmpty) ...[
                    const SizedBox(height: 4),
                    Text(phone.brandName, style: const TextStyle(color: AppColors.primary, fontSize: 13, fontWeight: FontWeight.w500)),
                  ],
                  if (displayPrice != null) ...[
                    const SizedBox(height: 6),
                    Text(Helpers.formatPrice(displayPrice), style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: AppColors.primary)),
                    const SizedBox(height: 2),
                    Text(
                      '${Helpers.formatPriceUsd(displayPrice)}  •  ${Helpers.formatPriceSar(displayPrice)}',
                      style: const TextStyle(fontSize: 10, color: AppColors.textSecondary),
                    ),
                  ],
                  if (phone.releaseYear != null) ...[
                    const SizedBox(height: 4),
                    Text('${phone.releaseYear}', style: const TextStyle(color: AppColors.textSecondary, fontSize: 12)),
                  ],
                ],
              ),
            ),
            const Icon(Icons.arrow_forward_ios, size: 16, color: AppColors.textSecondary),
          ],
        ),
      ),
    );
  }
}
