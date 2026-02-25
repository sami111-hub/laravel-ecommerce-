import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/constants/app_colors.dart';
import '../../core/utils/helpers.dart';
import '../../models/phone_model.dart';
import '../../services/phone_service.dart';

class PhoneDetailScreen extends StatefulWidget {
  final String phoneSlug;
  const PhoneDetailScreen({super.key, required this.phoneSlug});

  @override
  State<PhoneDetailScreen> createState() => _PhoneDetailScreenState();
}

class _PhoneDetailScreenState extends State<PhoneDetailScreen> {
  PhoneModel? _phone;
  List<PhoneModel> _related = [];
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
      final result = await PhoneService().getPhoneWithRelated(widget.phoneSlug);
      if (mounted) {
        setState(() {
          _phone = result['phone'] as PhoneModel;
          _related = result['related'] as List<PhoneModel>;
          _loading = false;
        });
      }
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
        title: Text(_phone?.name ?? 'تفاصيل الهاتف', style: const TextStyle(color: AppColors.textPrimary, fontWeight: FontWeight.bold, fontSize: 16)),
        centerTitle: true,
        leading: IconButton(icon: const Icon(Icons.arrow_back_ios, color: AppColors.textPrimary), onPressed: () => context.pop()),
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
          : _error != null
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(_error!, style: const TextStyle(color: AppColors.error)),
                      const SizedBox(height: 12),
                      ElevatedButton(onPressed: _load, child: const Text('إعادة المحاولة')),
                    ],
                  ),
                )
              : _buildContent(),
    );
  }

  Widget _buildContent() {
    final phone = _phone!;
    final displayPrice = phone.effectivePrice;
    return SingleChildScrollView(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Image
          Container(
            width: double.infinity,
            height: 300,
            color: Colors.white,
            child: phone.image != null
                ? CachedNetworkImage(imageUrl: phone.image!, fit: BoxFit.contain, errorWidget: (_, __, ___) => const Icon(Icons.phone_android, size: 80))
                : const Center(child: Icon(Icons.phone_android, size: 80, color: AppColors.textSecondary)),
          ),

          Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Name & Brand
                if (phone.brandName.isNotEmpty)
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: AppColors.primary.withValues(alpha: 0.1),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(phone.brandName, style: const TextStyle(color: AppColors.primary, fontSize: 13, fontWeight: FontWeight.w500)),
                  ),
                const SizedBox(height: 12),
                Text(phone.name, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                const SizedBox(height: 8),

                if (displayPrice != null) ...[
                  Text(Helpers.formatPrice(displayPrice), style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: AppColors.primary)),
                  const SizedBox(height: 4),
                  Text(
                    '${Helpers.formatPriceUsd(displayPrice)}   \u2022   ${Helpers.formatPriceSar(displayPrice)}',
                    style: const TextStyle(fontSize: 13, color: AppColors.textSecondary),
                  ),
                  const SizedBox(height: 8),
                ],

                if (phone.releaseYear != null)
                  Row(
                    children: [
                      const Icon(Icons.calendar_today, size: 16, color: AppColors.textSecondary),
                      const SizedBox(width: 6),
                      Text('\u062a\u0627\u0631\u064a\u062e \u0627\u0644\u0625\u0635\u062f\u0627\u0631: ${phone.releaseYear}', style: const TextStyle(color: AppColors.textSecondary)),
                    ],
                  ),

                // Quick specs strip
                if (phone.chipset != null || phone.ram != null || phone.storage != null || phone.batteryMah != null) ...[
                  const SizedBox(height: 16),
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: [
                      if (phone.ram != null) _specChip(Icons.memory, phone.ram!),
                      if (phone.storage != null) _specChip(Icons.sd_storage, phone.storage!),
                      if (phone.batteryMah != null) _specChip(Icons.battery_full, '${phone.batteryMah} mAh'),
                      if (phone.displaySize != null) _specChip(Icons.phone_android, '${phone.displaySize}"'),
                      if (phone.os != null) _specChip(Icons.phone_iphone, phone.os!),
                    ],
                  ),
                ],

                if (phone.description != null) ...[
                  const SizedBox(height: 20),
                  const Text('\u0627\u0644\u0648\u0635\u0641', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  _buildDescriptionText(phone.description!),
                ],

                // Detailed specs by group
                if (phone.specs.isNotEmpty) ...[
                  const SizedBox(height: 24),
                  const Text('\u0627\u0644\u0645\u0648\u0627\u0635\u0641\u0627\u062a', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  ...phone.groupedSpecs.entries.map((group) => Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      if (group.key.isNotEmpty)
                        Padding(
                          padding: const EdgeInsets.only(bottom: 8, top: 8),
                          child: Text(group.key, style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w600, color: AppColors.primary)),
                        ),
                      Container(
                        decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
                        child: Column(
                          children: group.value.asMap().entries.map((entry) {
                            final isLast = entry.key == group.value.length - 1;
                            return Column(
                              children: [
                                Padding(
                                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                                  child: Row(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      SizedBox(width: 120, child: Text(entry.value.key, style: const TextStyle(color: AppColors.textSecondary, fontWeight: FontWeight.w500, fontSize: 14))),
                                      Expanded(child: Text(entry.value.value, style: const TextStyle(fontWeight: FontWeight.w500, fontSize: 14))),
                                    ],
                                  ),
                                ),
                                if (!isLast) const Divider(height: 1),
                              ],
                            );
                          }).toList(),
                        ),
                      ),
                    ],
                  )),
                ],

                // Related phones
                if (_related.isNotEmpty) ...[
                  const SizedBox(height: 24),
                  const Text('\u0647\u0648\u0627\u062a\u0641 \u0645\u0634\u0627\u0628\u0647\u0629', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  SizedBox(
                    height: 180,
                    child: ListView.builder(
                      scrollDirection: Axis.horizontal,
                      itemCount: _related.length,
                      itemBuilder: (_, i) => GestureDetector(
                        onTap: () => context.pushReplacement('/phones/${_related[i].slug ?? _related[i].id}'),
                        child: Container(
                          width: 140,
                          margin: const EdgeInsets.only(left: 12),
                          padding: const EdgeInsets.all(10),
                          decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(12)),
                          child: Column(
                            children: [
                              Expanded(
                                child: _related[i].image != null
                                    ? CachedNetworkImage(imageUrl: _related[i].image!, fit: BoxFit.contain)
                                    : const Icon(Icons.phone_android, size: 40),
                              ),
                              const SizedBox(height: 8),
                              Text(_related[i].name, maxLines: 2, overflow: TextOverflow.ellipsis, textAlign: TextAlign.center, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w500)),
                            ],
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
                const SizedBox(height: 24),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _specChip(IconData icon, String text) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: AppColors.primary.withValues(alpha: 0.08),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 14, color: AppColors.primary),
          const SizedBox(width: 4),
          Text(text, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w500)),
        ],
      ),
    );
  }

  /// يبني نص الوصف مع تحديد الاتجاه تلقائياً (عربي/إنجليزي)
  Widget _buildDescriptionText(String text) {
    final paragraphs = text.split(RegExp(r'\n+'));
    
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: paragraphs.map((paragraph) {
        if (paragraph.trim().isEmpty) return const SizedBox.shrink();
        
        final isRtl = _isArabicText(paragraph);
        
        return Padding(
          padding: const EdgeInsets.only(bottom: 8),
          child: Directionality(
            textDirection: isRtl ? TextDirection.rtl : TextDirection.ltr,
            child: SizedBox(
              width: double.infinity,
              child: Text(
                paragraph.trim(),
                style: const TextStyle(
                  fontSize: 15,
                  color: AppColors.textSecondary,
                  height: 1.6,
                ),
                textAlign: isRtl ? TextAlign.right : TextAlign.left,
              ),
            ),
          ),
        );
      }).toList(),
    );
  }

  bool _isArabicText(String text) {
    final arabicRegex = RegExp(r'[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF]');
    final latinRegex = RegExp(r'[a-zA-Z]');
    int arabicCount = arabicRegex.allMatches(text).length;
    int latinCount = latinRegex.allMatches(text).length;
    return arabicCount >= latinCount;
  }
}
