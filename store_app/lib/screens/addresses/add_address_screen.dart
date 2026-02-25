import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../core/constants/app_colors.dart';
import '../../models/address_model.dart';
import '../../services/address_service.dart';
import '../../widgets/custom_button.dart';
import '../../widgets/custom_text_field.dart';

class AddAddressScreen extends StatefulWidget {
  const AddAddressScreen({super.key});

  @override
  State<AddAddressScreen> createState() => _AddAddressScreenState();
}

class _AddAddressScreenState extends State<AddAddressScreen> {
  final _formKey = GlobalKey<FormState>();
  final _labelCtrl = TextEditingController();
  final _cityCtrl = TextEditingController();
  final _areaCtrl = TextEditingController();
  final _streetCtrl = TextEditingController();
  final _buildingCtrl = TextEditingController();
  final _floorCtrl = TextEditingController();
  final _apartmentCtrl = TextEditingController();
  final _phoneCtrl = TextEditingController();
  final _infoCtrl = TextEditingController();
  bool _saving = false;
  String? _error;

  @override
  void dispose() {
    _labelCtrl.dispose();
    _cityCtrl.dispose();
    _areaCtrl.dispose();
    _streetCtrl.dispose();
    _buildingCtrl.dispose();
    _floorCtrl.dispose();
    _apartmentCtrl.dispose();
    _phoneCtrl.dispose();
    _infoCtrl.dispose();
    super.dispose();
  }

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() { _saving = true; _error = null; });

    try {
      await AddressService().addAddress(AddressModel(
        id: 0,
        label: _labelCtrl.text.trim(),
        city: _cityCtrl.text.trim(),
        area: _areaCtrl.text.trim(),
        street: _streetCtrl.text.trim(),
        buildingNumber: _buildingCtrl.text.trim().isEmpty ? null : _buildingCtrl.text.trim(),
        floor: _floorCtrl.text.trim().isEmpty ? null : _floorCtrl.text.trim(),
        apartment: _apartmentCtrl.text.trim().isEmpty ? null : _apartmentCtrl.text.trim(),
        phone: _phoneCtrl.text.trim(),
        additionalInfo: _infoCtrl.text.trim().isEmpty ? null : _infoCtrl.text.trim(),
      ));
      if (mounted) context.pop(true);
    } catch (e) {
      if (mounted) setState(() { _error = e.toString(); _saving = false; });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text('إضافة عنوان', style: TextStyle(color: AppColors.textPrimary, fontWeight: FontWeight.bold)),
        centerTitle: true,
        leading: IconButton(icon: const Icon(Icons.arrow_back_ios, color: AppColors.textPrimary), onPressed: () => context.pop()),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              if (_error != null) ...[
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(color: AppColors.error.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                  child: Text(_error!, style: const TextStyle(color: AppColors.error)),
                ),
                const SizedBox(height: 16),
              ],
              CustomTextField(
                label: 'اسم العنوان',
                hint: 'مثال: المنزل، العمل',
                controller: _labelCtrl,
                prefixIcon: Icons.label_outline,
                validator: (v) => v == null || v.isEmpty ? 'مطلوب' : null,
              ),
              const SizedBox(height: 14),
              CustomTextField(
                label: 'المدينة',
                hint: 'صنعاء',
                controller: _cityCtrl,
                prefixIcon: Icons.location_city,
                validator: (v) => v == null || v.isEmpty ? 'مطلوب' : null,
              ),
              const SizedBox(height: 14),
              CustomTextField(
                label: 'المنطقة',
                hint: 'حده',
                controller: _areaCtrl,
                prefixIcon: Icons.map_outlined,
                validator: (v) => v == null || v.isEmpty ? 'مطلوب' : null,
              ),
              const SizedBox(height: 14),
              CustomTextField(
                label: 'الشارع',
                hint: 'شارع الزبيري',
                controller: _streetCtrl,
                prefixIcon: Icons.add_road,
                validator: (v) => v == null || v.isEmpty ? 'مطلوب' : null,
              ),
              const SizedBox(height: 14),
              Row(
                children: [
                  Expanded(child: CustomTextField(label: 'رقم المبنى', hint: '12', controller: _buildingCtrl)),
                  const SizedBox(width: 12),
                  Expanded(child: CustomTextField(label: 'الطابق', hint: '3', controller: _floorCtrl)),
                  const SizedBox(width: 12),
                  Expanded(child: CustomTextField(label: 'الشقة', hint: 'A', controller: _apartmentCtrl)),
                ],
              ),
              const SizedBox(height: 14),
              CustomTextField(
                label: 'رقم الهاتف',
                hint: '777123456',
                controller: _phoneCtrl,
                keyboardType: TextInputType.phone,
                prefixIcon: Icons.phone_outlined,
                validator: (v) => v == null || v.isEmpty ? 'مطلوب' : null,
              ),
              const SizedBox(height: 14),
              CustomTextField(
                label: 'معلومات إضافية (اختياري)',
                hint: 'بالقرب من المسجد...',
                controller: _infoCtrl,
                maxLines: 2,
              ),
              const SizedBox(height: 24),
              CustomButton(text: 'حفظ العنوان', onPressed: _save, isLoading: _saving, icon: Icons.save_outlined),
            ],
          ),
        ),
      ),
    );
  }
}
