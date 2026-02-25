import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../../core/constants/app_colors.dart';
import '../../core/storage/storage_service.dart';
import '../../core/utils/helpers.dart';
import '../../models/address_model.dart';

import '../../providers/cart_provider.dart';
import '../../providers/order_provider.dart';
import '../../services/address_service.dart';
import '../../widgets/custom_button.dart';
import '../../widgets/custom_text_field.dart';

class CheckoutScreen extends ConsumerStatefulWidget {
  const CheckoutScreen({super.key});

  @override
  ConsumerState<CheckoutScreen> createState() => _CheckoutScreenState();
}

class _CheckoutScreenState extends ConsumerState<CheckoutScreen> {
  final _phoneCtrl = TextEditingController();
  final _notesCtrl = TextEditingController();
  final _couponCtrl = TextEditingController();
  List<AddressModel> _addresses = [];
  AddressModel? _selectedAddress;
  bool _loadingAddresses = true;
  bool _placing = false;
  bool _validatingCoupon = false;
  bool _isLoggedIn = true;
  String? _errorMsg;
  String? _couponMsg;
  bool _couponValid = false;

  @override
  void initState() {
    super.initState();
    _checkAuthAndLoad();
  }

  @override
  void dispose() {
    _phoneCtrl.dispose();
    _notesCtrl.dispose();
    _couponCtrl.dispose();
    super.dispose();
  }

  Future<void> _checkAuthAndLoad() async {
    final loggedIn = await StorageService.isLoggedIn();
    if (!mounted) return;
    if (!loggedIn) {
      setState(() {
        _loadingAddresses = false;
        _isLoggedIn = false;
        _errorMsg = 'يجب تسجيل الدخول أولاً لإتمام الشراء';
      });
      return;
    }
    _loadAddresses();
  }

  Future<void> _loadAddresses() async {
    try {
      final addrs = await AddressService().getAddresses();
      if (!mounted) return;
      final selected = addrs.isNotEmpty
          ? addrs.firstWhere((a) => a.isDefault, orElse: () => addrs.first)
          : null;
      setState(() {
        _addresses = addrs;
        _selectedAddress = selected;
        _loadingAddresses = false;
      });
      // تعيين نص الحقل بعد اكتمال الـ frame بالكامل (build+layout+paint+semantics)
      // لتجنب خطأ !semantics.parentDataDirty الذي يحدث عند استدعاء
      // notifyListeners من TextEditingController أثناء مرحلة semantics
      if (selected != null) {
        WidgetsBinding.instance.addPostFrameCallback((_) {
          if (mounted) _phoneCtrl.text = selected.phone;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _loadingAddresses = false;
          _errorMsg = 'فشل تحميل العناوين: $e';
        });
      }
    }
  }

  Future<void> _placeOrder() async {
    // التحقق من تسجيل الدخول
    final loggedIn = await StorageService.isLoggedIn();
    if (!mounted) return;
    if (!loggedIn) {
      setState(() => _errorMsg = 'يجب تسجيل الدخول أولاً لإتمام الشراء');
      return;
    }

    if (_selectedAddress == null) {
      setState(() => _errorMsg = 'يرجى اختيار عنوان التوصيل');
      return;
    }
    if (_phoneCtrl.text.trim().isEmpty) {
      setState(() => _errorMsg = 'يرجى إدخال رقم الهاتف');
      return;
    }

    // التحقق من وجود منتجات في السلة
    final cartItems = ref.read(cartProvider).valueOrNull ?? [];
    if (cartItems.isEmpty) {
      setState(() => _errorMsg = 'السلة فارغة، أضف منتجات أولاً');
      return;
    }

    setState(() { _placing = true; _errorMsg = null; });

    final result = await ref.read(ordersProvider.notifier).createOrder(
      shippingAddress: _selectedAddress!.fullAddress,
      phone: _phoneCtrl.text.trim(),
      notes: _notesCtrl.text.trim().isEmpty ? null : _notesCtrl.text.trim(),
      couponCode: _couponValid ? _couponCtrl.text.trim() : null,
    );

    if (!mounted) return;
    setState(() => _placing = false);

    if (result.success && result.order != null) {
      ref.read(cartProvider.notifier).load(); // Refresh cart
      _showSuccessDialog();
    } else {
      setState(() => _errorMsg = result.errorMessage ?? 'فشل في إتمام الطلب، حاول مرة أخرى');
    }
  }

  void _showSuccessDialog() {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 80,
              height: 80,
              decoration: BoxDecoration(
                color: AppColors.success.withValues(alpha: 0.1),
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.check_circle, color: AppColors.success, size: 48),
            ),
            const SizedBox(height: 16),
            const Text('تم الطلب بنجاح!', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            const Text('سيتم معالجة طلبك قريباً', style: TextStyle(color: AppColors.textSecondary), textAlign: TextAlign.center),
            const SizedBox(height: 20),
            Row(
              children: [
                Expanded(
                  child: OutlinedButton(
                    onPressed: () {
                      Navigator.pop(context);
                      context.go('/main');
                    },
                    style: OutlinedButton.styleFrom(
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      padding: const EdgeInsets.symmetric(vertical: 12),
                    ),
                    child: const Text('الرئيسية'),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: ElevatedButton(
                    onPressed: () {
                      Navigator.pop(context);
                      context.go('/orders');
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      padding: const EdgeInsets.symmetric(vertical: 12),
                    ),
                    child: const Text('طلباتي', style: TextStyle(color: Colors.white)),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final cartAsync = ref.watch(cartProvider);

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text('إتمام الشراء', style: TextStyle(color: AppColors.textPrimary, fontWeight: FontWeight.bold)),
        centerTitle: true,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios, color: AppColors.textPrimary),
          onPressed: () => context.pop(),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Error
            if (_errorMsg != null) ...[
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(color: AppColors.error.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        const Icon(Icons.error_outline, color: AppColors.error, size: 20),
                        const SizedBox(width: 8),
                        Expanded(child: Text(_errorMsg!, style: const TextStyle(color: AppColors.error))),
                      ],
                    ),
                    if (!_isLoggedIn) ...[
                      const SizedBox(height: 8),
                      SizedBox(
                        width: double.infinity,
                        child: ElevatedButton.icon(
                          onPressed: () => context.go('/login'),
                          icon: const Icon(Icons.login, size: 18),
                          label: const Text('تسجيل الدخول', style: TextStyle(color: Colors.white)),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppColors.primary,
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                          ),
                        ),
                      ),
                    ],
                  ],
                ),
              ),
              const SizedBox(height: 16),
            ],

            // Address section
            _sectionTitle('عنوان التوصيل', Icons.location_on_outlined),
            const SizedBox(height: 12),
            if (_loadingAddresses)
              const Center(child: CircularProgressIndicator(color: AppColors.primary))
            else if (_addresses.isEmpty)
              _buildAddAddressCard()
            else
              ..._addresses.map((addr) => _buildAddressCard(addr)),
            const SizedBox(height: 8),
            if (_addresses.isNotEmpty)
              TextButton.icon(
                onPressed: () async {
                  final result = await context.push('/addresses/add');
                  if (result == true) _loadAddresses();
                },
                icon: const Icon(Icons.add, size: 18),
                label: const Text('إضافة عنوان جديد'),
              ),

            const SizedBox(height: 20),

            // Phone
            _sectionTitle('رقم الهاتف', Icons.phone_outlined),
            const SizedBox(height: 12),
            CustomTextField(
              label: 'رقم الهاتف',
              hint: '777123456',
              controller: _phoneCtrl,
              keyboardType: TextInputType.phone,
              prefixIcon: Icons.phone,
            ),

            const SizedBox(height: 20),

            // Notes
            _sectionTitle('ملاحظات (اختياري)', Icons.note_outlined),
            const SizedBox(height: 12),
            CustomTextField(
              label: 'ملاحظات',
              hint: 'أي ملاحظات على الطلب...',
              controller: _notesCtrl,
              maxLines: 3,
            ),

            const SizedBox(height: 20),

            // Coupon
            _sectionTitle('كود الخصم', Icons.local_offer_outlined),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: CustomTextField(
                    label: 'كود الخصم',
                    hint: 'أدخل كود الخصم',
                    controller: _couponCtrl,
                  ),
                ),
                const SizedBox(width: 12),
                SizedBox(
                  width: 100,
                  height: 52,
                  child: ElevatedButton(
                    onPressed: _validatingCoupon ? null : () async {
                      if (_couponCtrl.text.trim().isEmpty) return;
                      setState(() => _validatingCoupon = true);
                      try {
                        final cartItems = ref.read(cartProvider).valueOrNull ?? [];
                        final subtotal = cartItems.fold<double>(0, (s, e) => s + e.total);
                        final result = await ref.read(orderServiceProvider).validateCoupon(
                          _couponCtrl.text.trim(),
                          amount: subtotal,
                        );
                        if (!mounted) return;
                        setState(() {
                          _couponValid = result['success'] == true;
                          _couponMsg = _couponValid ? 'تم تطبيق الخصم!' : (result['message'] ?? 'كود غير صالح');
                          _validatingCoupon = false;
                        });
                      } catch (e) {
                        if (!mounted) return;
                        setState(() { _couponValid = false; _couponMsg = 'كود غير صالح'; _validatingCoupon = false; });
                      }
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                    child: _validatingCoupon
                        ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                        : const Text('تطبيق', style: TextStyle(color: Colors.white)),
                  ),
                ),
              ],
            ),
            if (_couponMsg != null)
              Padding(
                padding: const EdgeInsets.only(top: 8),
                child: Text(_couponMsg!, style: TextStyle(color: _couponValid ? AppColors.success : AppColors.error, fontSize: 13)),
              ),

            const SizedBox(height: 24),

            // Order summary
            _sectionTitle('ملخص الطلب', Icons.receipt_long_outlined),
            const SizedBox(height: 12),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
              ),
              child: cartAsync.when(
                data: (items) {
                  final subtotal = items.fold<double>(0, (s, e) => s + e.total);
                  return Column(
                    children: [
                      ...items.map((item) => Padding(
                        padding: const EdgeInsets.only(bottom: 8),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Expanded(child: Text('${item.productName} × ${item.quantity}', style: const TextStyle(fontSize: 13), maxLines: 1, overflow: TextOverflow.ellipsis)),
                            Text(Helpers.formatPrice(item.total), style: const TextStyle(fontWeight: FontWeight.w500, fontSize: 13)),
                          ],
                        ),
                      )),
                      const Divider(height: 20),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text('المجموع', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.end,
                            children: [
                              Text(Helpers.formatPrice(subtotal), style: const TextStyle(color: AppColors.primary, fontWeight: FontWeight.bold, fontSize: 18)),
                              const SizedBox(height: 2),
                              Text(
                                '${Helpers.formatPriceUsd(subtotal)}  •  ${Helpers.formatPriceSar(subtotal)}',
                                style: const TextStyle(fontSize: 11, color: AppColors.textSecondary),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ],
                  );
                },
                loading: () => const CircularProgressIndicator(color: AppColors.primary),
                error: (_, __) => const Text('خطأ في تحميل السلة'),
              ),
            ),

            const SizedBox(height: 24),
            CustomButton(text: 'تأكيد الطلب', onPressed: _placeOrder, isLoading: _placing, icon: Icons.check_circle_outline),
            const SizedBox(height: 24),
          ],
        ),
      ),
    );
  }

  Widget _sectionTitle(String title, IconData icon) {
    return Row(
      children: [
        Icon(icon, color: AppColors.primary, size: 20),
        const SizedBox(width: 8),
        Text(title, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.textPrimary)),
      ],
    );
  }

  Widget _buildAddressCard(AddressModel addr) {
    final isSelected = _selectedAddress?.id == addr.id;
    return GestureDetector(
      onTap: () {
        setState(() => _selectedAddress = addr);
        // تأجيل تعيين النص لبعد اكتمال الـ frame لتجنب خطأ semantics
        WidgetsBinding.instance.addPostFrameCallback((_) {
          if (mounted) _phoneCtrl.text = addr.phone;
        });
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 10),
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(14),
          border: Border.all(color: isSelected ? AppColors.primary : AppColors.divider, width: isSelected ? 2 : 1),
        ),
        child: Row(
          children: [
            Icon(
              isSelected ? Icons.radio_button_checked : Icons.radio_button_off,
              color: isSelected ? AppColors.primary : AppColors.textSecondary,
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Text(addr.label, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                      if (addr.isDefault) ...[
                        const SizedBox(width: 8),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                          decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(8)),
                          child: const Text('افتراضي', style: TextStyle(color: AppColors.primary, fontSize: 10)),
                        ),
                      ],
                    ],
                  ),
                  const SizedBox(height: 4),
                  Text(addr.fullAddress, style: const TextStyle(color: AppColors.textSecondary, fontSize: 13)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAddAddressCard() {
    return GestureDetector(
      onTap: () async {
        final result = await context.push('/addresses/add');
        if (result == true) _loadAddresses();
      },
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(14),
          border: Border.all(color: AppColors.divider, style: BorderStyle.solid),
        ),
        child: const Column(
          children: [
            Icon(Icons.add_location_alt_outlined, size: 40, color: AppColors.primary),
            SizedBox(height: 8),
            Text('إضافة عنوان توصيل', style: TextStyle(color: AppColors.primary, fontWeight: FontWeight.w600)),
          ],
        ),
      ),
    );
  }
}
