@extends('admin.layout')

@section('title', 'إضافة عرض جديد')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-plus-circle text-primary"></i> إضافة عرض جديد</h2>
        <a href="{{ route('admin.offers.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> رجوع
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <strong>يرجى تصحيح الأخطاء التالية:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.offers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- === معلومات العرض الأساسية === --}}
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> معلومات العرض</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">عنوان العرض <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="discount_percentage" class="form-label">نسبة الخصم (%) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('discount_percentage') is-invalid @enderror" 
                                       id="discount_percentage" name="discount_percentage" 
                                       value="{{ old('discount_percentage', 10) }}" min="0" max="100" required>
                                @error('discount_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">وصف العرض <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="original_price" class="form-label">السعر الأصلي ($)</label>
                                <input type="number" step="0.01" class="form-control @error('original_price') is-invalid @enderror" 
                                       id="original_price" name="original_price" value="{{ old('original_price') }}"
                                       placeholder="مثال: 299.99" min="0">
                                @error('original_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="offer_price" class="form-label">سعر العرض ($)</label>
                                <input type="number" step="0.01" class="form-control @error('offer_price') is-invalid @enderror" 
                                       id="offer_price" name="offer_price" value="{{ old('offer_price') }}"
                                       placeholder="مثال: 199.99" min="0">
                                @error('offer_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">تاريخ البدء <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" 
                                       value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">تاريخ الانتهاء <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" 
                                       value="{{ old('end_date', now()->addDays(7)->format('Y-m-d')) }}" required>
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="image" class="form-label">صورة العرض</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                <small class="text-muted">jpeg, png, jpg, gif, webp (حجم أقصى: 5MB)</small>
                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div id="imagePreview" class="mt-2" style="display: none;">
                                    <img id="previewImg" src="" alt="معاينة" class="img-thumbnail" style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-outline-danger mt-1 d-block" onclick="removeImage()">
                                        <i class="bi bi-x-circle"></i> إزالة
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                           value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">تفعيل العرض</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- === تصنيف العرض والمواصفات === --}}
                <div class="card mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-list-check"></i> تصنيف ومواصفات العرض</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category_slug" class="form-label fw-bold">
                                <i class="bi bi-tag text-success"></i> اختر تصنيف المنتج
                            </label>
                            <select class="form-select" id="category_slug" name="category_slug" onchange="onCategoryChange()">
                                <option value="">-- بدون تصنيف --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ old('category_slug') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">اختيار التصنيف سيعرض حقول المواصفات المناسبة</small>
                        </div>

                        {{-- حقول المواصفات الديناميكية --}}
                        <div id="category-specs-fields">
                            <div class="alert alert-light text-muted py-2">
                                <i class="bi bi-info-circle"></i> اختر تصنيفاً لعرض حقول المواصفات المناسبة
                            </div>
                        </div>

                        {{-- مواصفات مخصصة --}}
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold mb-0">
                                    <i class="bi bi-plus-square text-info"></i> مواصفات إضافية
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-info" onclick="addCustomSpec()">
                                    <i class="bi bi-plus-lg"></i> إضافة مواصفة
                                </button>
                            </div>
                            <div id="custom-specs-list"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.offers.index') }}" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save"></i> حفظ العرض
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ===== خريطة المواصفات حسب التصنيف =====
const categorySpecsMap = {
    'video-games': [
        { key: 'platform', label: 'المنصة', placeholder: 'مثال: PlayStation 5, Xbox Series X', icon: 'bi-controller' },
        { key: 'type', label: 'النوع', placeholder: 'مثال: جهاز ألعاب / يد تحكم / مروحة تبريد', icon: 'bi-box' },
        { key: 'connectivity', label: 'طريقة الاتصال', placeholder: 'مثال: سلكي / بلوتوث / لاسلكي', icon: 'bi-bluetooth' },
        { key: 'compatibility', label: 'التوافق', placeholder: 'مثال: PS5, PS4, PC', icon: 'bi-phone' },
        { key: 'battery_life', label: 'عمر البطارية', placeholder: 'مثال: حتى 12 ساعة', icon: 'bi-battery-charging' },
        { key: 'features', label: 'المميزات', placeholder: 'مثال: اهتزاز، أزرار قابلة للتخصيص', icon: 'bi-stars' },
        { key: 'colors', label: 'الألوان المتاحة', placeholder: 'مثال: أسود, أبيض, أحمر', icon: 'bi-palette' },
    ],
    'smartphones': [
        { key: 'screen_size', label: 'حجم الشاشة', placeholder: 'مثال: 6.7 بوصة', icon: 'bi-phone' },
        { key: 'processor', label: 'المعالج', placeholder: 'مثال: Snapdragon 8 Gen 3', icon: 'bi-cpu' },
        { key: 'ram', label: 'الذاكرة العشوائية', placeholder: 'مثال: 8 جيجابايت', icon: 'bi-memory' },
        { key: 'storage', label: 'سعة التخزين', placeholder: 'مثال: 256 جيجابايت', icon: 'bi-device-hdd' },
        { key: 'rear_camera', label: 'الكاميرا الخلفية', placeholder: 'مثال: 48 + 12 ميجابكسل', icon: 'bi-camera' },
        { key: 'battery', label: 'سعة البطارية', placeholder: 'مثال: 4422 mAh', icon: 'bi-battery-charging' },
        { key: 'os', label: 'نظام التشغيل', placeholder: 'مثال: Android 14', icon: 'bi-gear' },
        { key: 'network', label: 'دعم الشبكات', placeholder: 'مثال: 5G, 4G LTE', icon: 'bi-wifi' },
        { key: 'colors', label: 'الألوان المتاحة', placeholder: 'مثال: أسود, أبيض', icon: 'bi-palette' },
    ],
    'computers-tablets': [
        { key: 'device_type', label: 'نوع الجهاز', placeholder: 'مثال: لابتوب / تابليت / آيباد', icon: 'bi-laptop' },
        { key: 'screen_size', label: 'حجم الشاشة', placeholder: 'مثال: 15.6 بوصة', icon: 'bi-display' },
        { key: 'processor', label: 'المعالج', placeholder: 'مثال: Intel Core i7 / Apple M3', icon: 'bi-cpu' },
        { key: 'ram', label: 'الذاكرة العشوائية', placeholder: 'مثال: 16 جيجابايت', icon: 'bi-memory' },
        { key: 'storage', label: 'وحدة التخزين', placeholder: 'مثال: 512 جيجابايت SSD', icon: 'bi-device-hdd' },
        { key: 'gpu', label: 'كرت الشاشة', placeholder: 'مثال: NVIDIA RTX 4060', icon: 'bi-gpu-card' },
        { key: 'os', label: 'نظام التشغيل', placeholder: 'مثال: Windows 11 / iPadOS', icon: 'bi-windows' },
        { key: 'battery_life', label: 'عمر البطارية', placeholder: 'مثال: حتى 10 ساعات', icon: 'bi-battery-charging' },
        { key: 'colors', label: 'الألوان المتاحة', placeholder: 'مثال: فضي, رمادي', icon: 'bi-palette' },
    ],
    'smartwatches-wearables': [
        { key: 'screen_size', label: 'حجم الشاشة', placeholder: 'مثال: 1.9 بوصة / 45 مم', icon: 'bi-smartwatch' },
        { key: 'battery_life', label: 'عمر البطارية', placeholder: 'مثال: حتى 36 ساعة', icon: 'bi-battery-charging' },
        { key: 'water_resistance', label: 'مقاومة الماء', placeholder: 'مثال: 50 متر / IP68', icon: 'bi-droplet' },
        { key: 'sensors', label: 'المستشعرات', placeholder: 'مثال: نبض القلب، أكسجين الدم', icon: 'bi-heart-pulse' },
        { key: 'connectivity', label: 'الاتصال', placeholder: 'مثال: Bluetooth 5.3, WiFi, NFC', icon: 'bi-wifi' },
        { key: 'compatibility', label: 'التوافق', placeholder: 'مثال: iOS و Android', icon: 'bi-phone' },
        { key: 'colors', label: 'الألوان المتاحة', placeholder: 'مثال: أسود, فضي', icon: 'bi-palette' },
    ],
    'phone-accessories': [
        { key: 'type', label: 'النوع', placeholder: 'مثال: كفر / حامي شاشة / حامل', icon: 'bi-box' },
        { key: 'compatible_device', label: 'الجهاز المتوافق', placeholder: 'مثال: iPhone 15 Pro Max', icon: 'bi-phone' },
        { key: 'material', label: 'المادة', placeholder: 'مثال: سيليكون, جلد, زجاج مقوى', icon: 'bi-shield' },
        { key: 'features', label: 'المميزات', placeholder: 'مثال: مسند, حامل بطاقات, MagSafe', icon: 'bi-stars' },
        { key: 'colors', label: 'الألوان المتاحة', placeholder: 'مثال: أسود, شفاف', icon: 'bi-palette' },
    ],
    'headphones-speakers': [
        { key: 'type', label: 'النوع', placeholder: 'مثال: سماعات أذن / سماعات رأس / مكبر صوت', icon: 'bi-headphones' },
        { key: 'connectivity', label: 'طريقة الاتصال', placeholder: 'مثال: بلوتوث 5.3 / سلكية', icon: 'bi-bluetooth' },
        { key: 'noise_cancellation', label: 'إلغاء الضوضاء', placeholder: 'مثال: ANC نشط', icon: 'bi-volume-mute' },
        { key: 'battery_life', label: 'عمر البطارية', placeholder: 'مثال: 30 ساعة', icon: 'bi-battery-charging' },
        { key: 'water_resistance', label: 'مقاومة الماء', placeholder: 'مثال: IPX4', icon: 'bi-droplet' },
        { key: 'colors', label: 'الألوان المتاحة', placeholder: 'مثال: أسود, أبيض', icon: 'bi-palette' },
    ],
    'power-banks-chargers': [
        { key: 'type', label: 'النوع', placeholder: 'مثال: شاحن حائط / باور بانك / كيبل', icon: 'bi-plug' },
        { key: 'wattage', label: 'القدرة (واط)', placeholder: 'مثال: 65 واط', icon: 'bi-lightning-charge' },
        { key: 'ports_count', label: 'عدد المنافذ', placeholder: 'مثال: 2 USB-C + 1 USB-A', icon: 'bi-usb-plug' },
        { key: 'fast_charging', label: 'الشحن السريع', placeholder: 'مثال: PD 3.0, QC 4.0', icon: 'bi-speedometer2' },
        { key: 'battery_capacity', label: 'سعة البطارية', placeholder: 'مثال: 20,000 mAh', icon: 'bi-battery-full' },
        { key: 'compatibility', label: 'التوافق', placeholder: 'مثال: iPhone, Samsung', icon: 'bi-phone' },
    ],
    'special-offers': [
        { key: 'offer_details', label: 'تفاصيل العرض', placeholder: 'مثال: اشتري 2 واحصل على خصم 30%', icon: 'bi-tag' },
        { key: 'warranty', label: 'الضمان', placeholder: 'مثال: سنة واحدة', icon: 'bi-shield-check' },
        { key: 'included_items', label: 'محتويات العلبة', placeholder: 'مثال: الجهاز + شاحن + سماعة', icon: 'bi-box-seam' },
    ],
};

// توافق مع السلاقات القديمة
categorySpecsMap['laptops'] = categorySpecsMap['computers-tablets'];
categorySpecsMap['watches'] = categorySpecsMap['smartwatches-wearables'];
categorySpecsMap['headphones'] = categorySpecsMap['headphones-speakers'];
categorySpecsMap['chargers-cables'] = categorySpecsMap['power-banks-chargers'];
categorySpecsMap['accessories'] = categorySpecsMap['phone-accessories'];
categorySpecsMap['gaming'] = categorySpecsMap['video-games'];
categorySpecsMap['offers'] = categorySpecsMap['special-offers'];

function renderSpecsFields(categorySlug, existingValues = {}) {
    const container = document.getElementById('category-specs-fields');
    container.innerHTML = '';

    const specs = categorySpecsMap[categorySlug];
    if (!specs) {
        container.innerHTML = '<div class="alert alert-light text-muted py-2"><i class="bi bi-info-circle"></i> اختر تصنيفاً لعرض حقول المواصفات المناسبة</div>';
        return;
    }

    let html = '<div class="row">';
    specs.forEach((spec, index) => {
        const value = existingValues[spec.key] || '';
        html += `
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">
                    <i class="bi ${spec.icon} text-primary"></i> ${spec.label}
                </label>
                <input type="text" class="form-control" 
                       name="specifications[${spec.key}]" 
                       value="${value}"
                       placeholder="${spec.placeholder}">
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

let customSpecCount = 0;
function addCustomSpec(key = '', value = '') {
    customSpecCount++;
    const container = document.getElementById('custom-specs-list');
    const div = document.createElement('div');
    div.className = 'row mb-2 custom-spec-row';
    div.innerHTML = `
        <div class="col-5">
            <input type="text" class="form-control" placeholder="اسم المواصفة" 
                   name="custom_spec_keys[]" value="${key}">
        </div>
        <div class="col-5">
            <input type="text" class="form-control" placeholder="القيمة" 
                   name="custom_spec_values[]" value="${value}">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="this.closest('.custom-spec-row').remove()">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(div);
}

function onCategoryChange() {
    const slug = document.getElementById('category_slug').value;
    renderSpecsFields(slug);
}

function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('previewImg').src = '';
}

// تهيئة أولية
document.addEventListener('DOMContentLoaded', function() {
    onCategoryChange();
});
</script>
@endsection
