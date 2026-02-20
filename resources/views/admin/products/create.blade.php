@extends('admin.layout')

@section('title', 'إضافة منتج جديد')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-plus-circle"></i> إضافة منتج جديد</h4>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> العودة للقائمة
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- معلومات أساسية --}}
            <div class="section-title mb-3">
                <h5 class="text-primary"><i class="bi bi-info-circle"></i> المعلومات الأساسية</h5>
                <hr>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="name" class="form-label fw-bold">اسم المنتج <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" 
                           placeholder="مثال: هاتف iPhone 15 Pro Max" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="sku" class="form-label fw-bold">رمز المنتج (SKU)</label>
                    <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                           id="sku" name="sku" value="{{ old('sku') }}" 
                           placeholder="مثال: IPH-15-PM-256">
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">اتركه فارغاً للتوليد التلقائي</small>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">الوصف</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" 
                          placeholder="اكتب وصفاً تفصيلياً للمنتج...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- المواصفات التقنية --}}
            <div class="section-title mb-3 mt-4">
                <h5 class="text-primary"><i class="bi bi-cpu"></i> المواصفات التقنية</h5>
                <hr>
                <small class="text-muted"><i class="bi bi-info-circle"></i> اختر التصنيف أولاً لعرض حقول المواصفات المناسبة، أو أضف مواصفات مخصصة</small>
            </div>

            <div id="specifications-container">
                {{-- حقول المواصفات الديناميكية ستظهر هنا --}}
                <div id="category-specs-fields"></div>

                {{-- مواصفات مخصصة --}}
                <div id="custom-specs" class="mt-3">
                    <label class="form-label fw-bold"><i class="bi bi-plus-circle"></i> مواصفات إضافية</label>
                    <div id="custom-specs-list"></div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addCustomSpec()">
                        <i class="bi bi-plus-lg"></i> إضافة مواصفة مخصصة
                    </button>
                </div>
            </div>

            {{-- السعر والمخزون --}}
            <div class="section-title mb-3 mt-4">
                <h5 class="text-primary"><i class="bi bi-cash-stack"></i> السعر والمخزون</h5>
                <hr>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label fw-bold">السعر <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" min="0" 
                               class="form-control @error('price') is-invalid @enderror" 
                               id="price" name="price" value="{{ old('price') }}" 
                               placeholder="0.00" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label fw-bold">الكمية المتوفرة <span class="text-danger">*</span></label>
                    <input type="number" min="0" 
                           class="form-control @error('stock') is-invalid @enderror" 
                           id="stock" name="stock" value="{{ old('stock', 0) }}" 
                           placeholder="0" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- العلامة التجارية والتصنيفات --}}
            <div class="section-title mb-3 mt-4">
                <h5 class="text-primary"><i class="bi bi-tags"></i> التصنيف والعلامة التجارية</h5>
                <hr>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="brand_id" class="form-label fw-bold">العلامة التجارية</label>
                    <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id">
                        <option value="">-- اختر العلامة التجارية --</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">التصنيفات</label>
                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                        @forelse($categories as $category)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" 
                                   name="categories[]" value="{{ $category->id }}" 
                                   id="cat_{{ $category->id }}"
                                   {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cat_{{ $category->id }}">
                                <i class="bi bi-folder"></i> {{ $category->name }}
                            </label>
                        </div>
                        @empty
                        <p class="text-muted mb-0">لا توجد تصنيفات متاحة</p>
                        @endforelse
                    </div>
                    @error('categories')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- الصورة --}}
            <div class="section-title mb-3 mt-4">
                <h5 class="text-primary"><i class="bi bi-image"></i> صورة المنتج</h5>
                <hr>
            </div>

            {{-- خيارات الصورة --}}
            <div class="mb-3">
                <label class="form-label fw-bold">طريقة إضافة الصورة</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="image_type" id="image_type_url" value="url" checked>
                    <label class="btn btn-outline-primary" for="image_type_url">
                        <i class="bi bi-link-45deg"></i> رابط من الإنترنت
                    </label>

                    <input type="radio" class="btn-check" name="image_type" id="image_type_upload" value="upload">
                    <label class="btn btn-outline-primary" for="image_type_upload">
                        <i class="bi bi-upload"></i> رفع من الجهاز
                    </label>
                </div>
            </div>

            {{-- رابط الصورة --}}
            <div id="url-section" class="mb-3">
                <label for="image" class="form-label fw-bold">رابط الصورة</label>
                <input type="url" class="form-control @error('image') is-invalid @enderror" 
                       id="image" name="image" value="{{ old('image') }}" 
                       placeholder="https://example.com/product-image.jpg">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> الصق رابط الصورة من الإنترنت
                </small>
            </div>

            {{-- رفع الصورة --}}
            <div id="upload-section" class="mb-3" style="display: none;">
                <label for="image_file" class="form-label fw-bold">اختر صورة</label>
                <input type="file" class="form-control @error('image_file') is-invalid @enderror" 
                       id="image_file" name="image_file" accept="image/*">
                @error('image_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> الحد الأقصى: 2MB - الصيغ المدعومة: JPG, PNG, WEBP
                </small>
            </div>

            {{-- معاينة الصورة --}}
            <div id="image-preview" class="mb-3" style="display: none;">
                <label class="form-label fw-bold">معاينة الصورة</label>
                <div class="border rounded p-3 text-center bg-light">
                    <img id="preview-img" src="" alt="معاينة" class="img-fluid" style="max-width: 300px; max-height: 300px; border-radius: 8px;">
                </div>
            </div>

            {{-- أزرار الحفظ --}}
            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> حفظ المنتج
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-x-circle"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// ===== خريطة المواصفات حسب التصنيف =====
const categorySpecsMap = {
    // ألعاب الفيديو
    'video-games': [
        { key: 'platform', label: 'المنصة', placeholder: 'مثال: PlayStation 5, Xbox Series X, Nintendo Switch', icon: 'bi-controller' },
        { key: 'type', label: 'النوع', placeholder: 'مثال: جهاز ألعاب / يد تحكم / مروحة تبريد / ملحقات', icon: 'bi-box' },
        { key: 'connectivity', label: 'طريقة الاتصال', placeholder: 'مثال: سلكي USB / بلوتوث / لاسلكي 2.4GHz', icon: 'bi-bluetooth' },
        { key: 'compatibility', label: 'التوافق', placeholder: 'مثال: PS5, PS4, PC, Mobile', icon: 'bi-phone' },
        { key: 'battery_life', label: 'عمر البطارية', placeholder: 'مثال: حتى 12 ساعة', icon: 'bi-battery-charging' },
        { key: 'storage', label: 'سعة التخزين', placeholder: 'مثال: 825 جيجابايت SSD', icon: 'bi-device-hdd' },
        { key: 'features', label: 'المميزات', placeholder: 'مثال: اهتزاز، أزرار قابلة للتخصيص', icon: 'bi-stars' },
        { key: 'weight', label: 'الوزن', placeholder: 'مثال: 280 جرام', icon: 'bi-speedometer' },
        { key: 'colors', label: 'الألوان المتاحة', placeholder: 'مثال: أسود, أبيض, أحمر', icon: 'bi-palette' },
    ],

    // الهواتف الذكية
    'smartphones': [
        { key: 'screen_size', label: 'حجم الشاشة', placeholder: 'مثال: 6.7 بوصة', icon: 'bi-phone' },
        { key: 'screen_type', label: 'نوع الشاشة', placeholder: 'مثال: AMOLED, IPS LCD', icon: 'bi-display' },
        { key: 'resolution', label: 'دقة الشاشة', placeholder: 'مثال: 2796 x 1290 بكسل', icon: 'bi-aspect-ratio' },
        { key: 'processor', label: 'المعالج', placeholder: 'مثال: Apple A17 Pro / Snapdragon 8 Gen 3', icon: 'bi-cpu' },
        { key: 'ram', label: 'الذاكرة العشوائية (RAM)', placeholder: 'مثال: 8 جيجابايت', icon: 'bi-memory' },
        { key: 'storage', label: 'سعة التخزين', placeholder: 'مثال: 256 جيجابايت', icon: 'bi-device-hdd' },
        { key: 'rear_camera', label: 'الكاميرا الخلفية', placeholder: 'مثال: 48 + 12 + 12 ميجابكسل', icon: 'bi-camera' },
        { key: 'front_camera', label: 'الكاميرا الأمامية', placeholder: 'مثال: 12 ميجابكسل', icon: 'bi-camera-video' },
        { key: 'battery', label: 'سعة البطارية', placeholder: 'مثال: 4422 mAh', icon: 'bi-battery-charging' },
        { key: 'charging', label: 'سرعة الشحن', placeholder: 'مثال: شحن سريع 27 واط', icon: 'bi-lightning-charge' },
        { key: 'os', label: 'نظام التشغيل', placeholder: 'مثال: iOS 17 / Android 14', icon: 'bi-gear' },
        { key: 'sim', label: 'نوع الشريحة', placeholder: 'مثال: Nano SIM + eSIM', icon: 'bi-sim' },
        { key: 'network', label: 'دعم الشبكات', placeholder: 'مثال: 5G, 4G LTE, WiFi 6E', icon: 'bi-wifi' },
        { key: 'water_resistance', label: 'مقاومة الماء', placeholder: 'مثال: IP68', icon: 'bi-droplet' },
        { key: 'weight', label: 'الوزن', placeholder: 'مثال: 221 جرام', icon: 'bi-speedometer' },
        { key: 'dimensions', label: 'الأبعاد', placeholder: 'مثال: 159.9 x 76.7 x 8.25 مم', icon: 'bi-rulers' },
        { key: 'colors', label: 'الألوان المتاحة', placeholder: 'مثال: أسود, أبيض, ذهبي', icon: 'bi-palette' },
        { key: 'fingerprint', label: 'مستشعر البصمة', placeholder: 'مثال: بصمة الوجه / بصمة تحت الشاشة', icon: 'bi-fingerprint' },
    ],

    // الكمبيوتر والتابليت
    'computers-tablets': [
        { key: 'device_type', label: 'نوع الجهاز', placeholder: 'مثال: لابتوب / تابليت / آيباد', icon: 'bi-laptop' },
        { key: 'screen_size', label: 'حجم الشاشة', placeholder: 'مثال: 15.6 بوصة / 11 بوصة', icon: 'bi-display' },
        { key: 'screen_type', label: 'نوع الشاشة', placeholder: 'مثال: IPS, OLED, Retina, Liquid Retina', icon: 'bi-display' },
        { key: 'resolution', label: 'دقة الشاشة', placeholder: 'مثال: 1920 x 1080 (Full HD) / 2388 x 1668', icon: 'bi-aspect-ratio' },
        { key: 'processor', label: 'المعالج', placeholder: 'مثال: Intel Core i7 / Apple M3 Pro / Snapdragon 8cx', icon: 'bi-cpu' },
        { key: 'ram', label: 'الذاكرة العشوائية (RAM)', placeholder: 'مثال: 16 جيجابايت DDR5', icon: 'bi-memory' },
        { key: 'storage', label: 'وحدة التخزين', placeholder: 'مثال: 512 جيجابايت SSD NVMe / 256 جيجابايت', icon: 'bi-device-hdd' },
        { key: 'gpu', label: 'كرت الشاشة (GPU)', placeholder: 'مثال: NVIDIA RTX 4060 8GB / مدمج', icon: 'bi-gpu-card' },
        { key: 'battery_life', label: 'عمر البطارية', placeholder: 'مثال: حتى 10 ساعات', icon: 'bi-battery-charging' },
        { key: 'os', label: 'نظام التشغيل', placeholder: 'مثال: Windows 11 / macOS Sonoma / iPadOS 17 / Android 14', icon: 'bi-windows' },
        { key: 'weight', label: 'الوزن', placeholder: 'مثال: 1.8 كجم / 478 جرام', icon: 'bi-speedometer' },
        { key: 'ports', label: 'المنافذ', placeholder: 'مثال: USB-C x2, USB-A x1, HDMI, سماعة', icon: 'bi-usb-plug' },
        { key: 'connectivity', label: 'الاتصال', placeholder: 'مثال: WiFi 6E, Bluetooth 5.3, 5G (اختياري)', icon: 'bi-wifi' },
        { key: 'keyboard', label: 'لوحة المفاتيح', placeholder: 'مثال: إضاءة خلفية / كيبورد Smart Connector', icon: 'bi-keyboard' },
        { key: 'stylus', label: 'دعم القلم', placeholder: 'مثال: Apple Pencil 2 / S Pen / لا يدعم', icon: 'bi-pencil' },
        { key: 'webcam', label: 'الكاميرا', placeholder: 'مثال: 1080p Full HD / 12 ميجابكسل', icon: 'bi-webcam' },
        { key: 'colors', label: 'الألوان المتاحة', placeholder: 'مثال: فضي, رمادي غامق, أزرق', icon: 'bi-palette' },
    ],

    // الساعات الذكية والأجهزة القابلة للإرتداء
    'smartwatches-wearables': [
        { key: 'screen_size', label: 'حجم الشاشة', placeholder: 'مثال: 1.9 بوصة / 45 مم', icon: 'bi-smartwatch' },
        { key: 'screen_type', label: 'نوع الشاشة', placeholder: 'مثال: AMOLED, Retina LTPO', icon: 'bi-display' },
        { key: 'processor', label: 'المعالج', placeholder: 'مثال: Apple S9 / Exynos W930', icon: 'bi-cpu' },
        { key: 'storage', label: 'سعة التخزين', placeholder: 'مثال: 32 جيجابايت', icon: 'bi-device-hdd' },
        { key: 'battery_life', label: 'عمر البطارية', placeholder: 'مثال: حتى 36 ساعة', icon: 'bi-battery-charging' },
        { key: 'water_resistance', label: 'مقاومة الماء', placeholder: 'مثال: 50 متر / IP68', icon: 'bi-droplet' },
        { key: 'sensors', label: 'المستشعرات', placeholder: 'مثال: نبض القلب، أكسجين الدم، تخطيط القلب', icon: 'bi-heart-pulse' },
        { key: 'gps', label: 'نظام الملاحة', placeholder: 'مثال: GPS مدمج + GLONASS', icon: 'bi-geo-alt' },
        { key: 'connectivity', label: 'الاتصال', placeholder: 'مثال: Bluetooth 5.3, WiFi, NFC', icon: 'bi-wifi' },
        { key: 'compatibility', label: 'التوافق', placeholder: 'مثال: iOS و Android / iOS فقط', icon: 'bi-phone' },
        { key: 'strap_material', label: 'مادة السوار', placeholder: 'مثال: سيليكون, ستانلس ستيل, جلد', icon: 'bi-watch' },
        { key: 'weight', label: 'الوزن', placeholder: 'مثال: 38.7 جرام (بدون السوار)', icon: 'bi-speedometer' },
        { key: 'colors', label: 'الألوان المتاحة', placeholder: 'مثال: أسود, فضي, ذهبي وردي', icon: 'bi-palette' },
    ],

    // ملحقات الهواتف الذكية
    'phone-accessories': [
        { key: 'type', label: 'النوع', placeholder: 'مثال: كفر / حامي شاشة / حامل / إكسسوار', icon: 'bi-box' },
        { key: 'compatible_device', label: 'الجهاز المتوافق', placeholder: 'مثال: iPhone 15 Pro Max / Samsung Galaxy S24', icon: 'bi-phone' },
        { key: 'material', label: 'المادة', placeholder: 'مثال: سيليكون, جلد, بلاستيك صلب, زجاج مقوى', icon: 'bi-shield' },
        { key: 'protection_level', label: 'مستوى الحماية', placeholder: 'مثال: حماية عسكرية / حماية خفيفة', icon: 'bi-shield-check' },
        { key: 'features', label: 'المميزات', placeholder: 'مثال: مسند, حامل بطاقات, شفاف, MagSafe', icon: 'bi-stars' },
        { key: 'connectivity', label: 'طريقة الاتصال', placeholder: 'مثال: بلوتوث / سلكي / USB / لا ينطبق', icon: 'bi-wifi' },
        { key: 'weight', label: 'الوزن', placeholder: 'مثال: 35 جرام', icon: 'bi-speedometer' },
        { key: 'colors', label: 'الألوان المتاحة', placeholder: 'مثال: أسود, شفاف, أزرق', icon: 'bi-palette' },
    ],

    // سماعات ومكبرات الصوت
    'headphones-speakers': [
        { key: 'type', label: 'النوع', placeholder: 'مثال: سماعات أذن / سماعات رأس / مكبر صوت', icon: 'bi-headphones' },
        { key: 'connectivity', label: 'طريقة الاتصال', placeholder: 'مثال: بلوتوث 5.3 / سلكية / WiFi', icon: 'bi-bluetooth' },
        { key: 'driver_size', label: 'حجم السماعة', placeholder: 'مثال: 40 مم', icon: 'bi-speaker' },
        { key: 'frequency', label: 'نطاق التردد', placeholder: 'مثال: 20Hz - 20kHz', icon: 'bi-soundwave' },
        { key: 'noise_cancellation', label: 'إلغاء الضوضاء', placeholder: 'مثال: ANC نشط / عزل سلبي', icon: 'bi-volume-mute' },
        { key: 'battery_life', label: 'عمر البطارية', placeholder: 'مثال: 30 ساعة / 6 ساعات + علبة شحن', icon: 'bi-battery-charging' },
        { key: 'charging', label: 'نوع الشحن', placeholder: 'مثال: USB-C, شحن لاسلكي', icon: 'bi-lightning-charge' },
        { key: 'microphone', label: 'الميكروفون', placeholder: 'مثال: ميكروفون مدمج مع تقليل الضوضاء', icon: 'bi-mic' },
        { key: 'water_resistance', label: 'مقاومة الماء/العرق', placeholder: 'مثال: IPX4 / IPX7', icon: 'bi-droplet' },
        { key: 'power_output', label: 'قوة الصوت (مكبرات)', placeholder: 'مثال: 20 واط / 40 واط', icon: 'bi-volume-up' },
        { key: 'weight', label: 'الوزن', placeholder: 'مثال: 5.4 جرام / 780 جرام', icon: 'bi-speedometer' },
        { key: 'colors', label: 'الألوان المتاحة', placeholder: 'مثال: أسود, أبيض, أزرق', icon: 'bi-palette' },
    ],

    // خزائن الطاقة والشواحن
    'power-banks-chargers': [
        { key: 'type', label: 'النوع', placeholder: 'مثال: شاحن حائط / كيبل / باور بانك / توصيلة', icon: 'bi-plug' },
        { key: 'wattage', label: 'القدرة (واط)', placeholder: 'مثال: 65 واط', icon: 'bi-lightning-charge' },
        { key: 'ports_count', label: 'عدد المنافذ', placeholder: 'مثال: 2 USB-C + 1 USB-A', icon: 'bi-usb-plug' },
        { key: 'cable_type', label: 'نوع الكيبل', placeholder: 'مثال: USB-C to Lightning / USB-C to USB-C', icon: 'bi-ethernet' },
        { key: 'cable_length', label: 'طول الكيبل', placeholder: 'مثال: 1.5 متر', icon: 'bi-rulers' },
        { key: 'fast_charging', label: 'الشحن السريع', placeholder: 'مثال: PD 3.0, QC 4.0', icon: 'bi-speedometer2' },
        { key: 'battery_capacity', label: 'سعة البطارية (باور بانك)', placeholder: 'مثال: 20,000 mAh', icon: 'bi-battery-full' },
        { key: 'compatibility', label: 'التوافق', placeholder: 'مثال: iPhone, Samsung, iPad', icon: 'bi-phone' },
        { key: 'weight', label: 'الوزن', placeholder: 'مثال: 120 جرام', icon: 'bi-speedometer' },
    ],

    // عروض مميزة
    'special-offers': [
        { key: 'offer_type', label: 'نوع العرض', placeholder: 'مثال: تخفيض / باقة / هدية', icon: 'bi-tag' },
        { key: 'original_price', label: 'السعر الأصلي', placeholder: 'مثال: 299.99$', icon: 'bi-cash' },
        { key: 'discount_percent', label: 'نسبة الخصم', placeholder: 'مثال: 25%', icon: 'bi-percent' },
        { key: 'offer_end', label: 'تاريخ انتهاء العرض', placeholder: 'مثال: 2026-03-01', icon: 'bi-calendar-event' },
        { key: 'warranty', label: 'الضمان', placeholder: 'مثال: سنة واحدة', icon: 'bi-shield-check' },
    ],
};

// توافق مع السلاقات القديمة (حتى يتم تحديث قاعدة البيانات)
categorySpecsMap['laptops'] = categorySpecsMap['computers-tablets'];
categorySpecsMap['tablets'] = categorySpecsMap['computers-tablets'];
categorySpecsMap['watches'] = categorySpecsMap['smartwatches-wearables'];
categorySpecsMap['headphones'] = categorySpecsMap['headphones-speakers'];
categorySpecsMap['chargers-cables'] = categorySpecsMap['power-banks-chargers'];
categorySpecsMap['chargers'] = categorySpecsMap['power-banks-chargers'];
categorySpecsMap['cases-covers'] = categorySpecsMap['phone-accessories'];
categorySpecsMap['accessories'] = categorySpecsMap['phone-accessories'];
categorySpecsMap['gaming'] = categorySpecsMap['video-games'];
categorySpecsMap['offers'] = categorySpecsMap['special-offers'];
categorySpecsMap['mobiles'] = categorySpecsMap['smartphones'];
categorySpecsMap['printers'] = categorySpecsMap['computers-tablets'];

// خريطة slug للتصنيفات
const categorySlugMap = @json($categories->pluck('slug', 'id'));

// ===== توليد حقول المواصفات =====
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
                <input type="text" class="form-control spec-field" 
                       name="specifications[${spec.key}]" 
                       value="${value}"
                       placeholder="${spec.placeholder}"
                       data-spec-key="${spec.key}">
            </div>
        `;
        if ((index + 1) % 2 === 0 && index < specs.length - 1) {
            html += '</div><div class="row">';
        }
    });
    html += '</div>';

    container.innerHTML = html;
}

// ===== مواصفات مخصصة =====
let customSpecCount = 0;
function addCustomSpec(key = '', value = '') {
    customSpecCount++;
    const container = document.getElementById('custom-specs-list');
    const div = document.createElement('div');
    div.className = 'row mb-2 custom-spec-row';
    div.id = `custom-spec-${customSpecCount}`;
    div.innerHTML = `
        <div class="col-5">
            <input type="text" class="form-control" placeholder="اسم المواصفة (مثال: الضمان)" 
                   name="custom_spec_keys[]" value="${key}">
        </div>
        <div class="col-5">
            <input type="text" class="form-control" placeholder="القيمة (مثال: سنتين)" 
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

// ===== الاستماع لتغيير التصنيفات =====
document.addEventListener('DOMContentLoaded', function() {
    const categoryCheckboxes = document.querySelectorAll('input[name="categories[]"]');
    
    function onCategoryChange() {
        const checkedCategories = Array.from(categoryCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => categorySlugMap[cb.value])
            .filter(slug => slug && categorySpecsMap[slug]);
        
        if (checkedCategories.length > 0) {
            // استخدم أول تصنيف مختار له مواصفات
            renderSpecsFields(checkedCategories[0]);
        } else {
            renderSpecsFields(null);
        }
    }

    categoryCheckboxes.forEach(cb => cb.addEventListener('change', onCategoryChange));
    
    // تهيئة أولية
    onCategoryChange();
});

// التبديل بين رابط URL ورفع ملف
const urlRadio = document.getElementById('image_type_url');
const uploadRadio = document.getElementById('image_type_upload');
const urlSection = document.getElementById('url-section');
const uploadSection = document.getElementById('upload-section');
const imageInput = document.getElementById('image');
const fileInput = document.getElementById('image_file');
const preview = document.getElementById('image-preview');
const previewImg = document.getElementById('preview-img');

urlRadio.addEventListener('change', function() {
    if (this.checked) {
        urlSection.style.display = 'block';
        uploadSection.style.display = 'none';
        fileInput.value = '';
        preview.style.display = 'none';
    }
});

uploadRadio.addEventListener('change', function() {
    if (this.checked) {
        urlSection.style.display = 'none';
        uploadSection.style.display = 'block';
        imageInput.value = '';
        preview.style.display = 'none';
    }
});

// معاينة الصورة عند إدخال رابط URL
imageInput.addEventListener('input', function() {
    const imageUrl = this.value.trim();
    
    if (imageUrl && imageUrl.startsWith('http')) {
        previewImg.src = imageUrl;
        preview.style.display = 'block';
        
        // إخفاء المعاينة إذا فشل تحميل الصورة
        previewImg.onerror = function() {
            preview.style.display = 'none';
        };
    } else {
        preview.style.display = 'none';
    }
});

// معاينة الصورة عند رفع ملف
fileInput.addEventListener('change', function() {
    const file = this.files[0];
    
    if (file) {
        // التحقق من نوع الملف
        if (!file.type.match('image.*')) {
            alert('يرجى اختيار ملف صورة صالح');
            this.value = '';
            return;
        }
        
        // التحقق من حجم الملف (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('حجم الصورة يجب أن يكون أقل من 2MB');
            this.value = '';
            return;
        }
        
        // عرض المعاينة
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

// توليد SKU تلقائياً من اسم المنتج
document.getElementById('name').addEventListener('input', function() {
    const skuField = document.getElementById('sku');
    if (!skuField.value) {
        const name = this.value.trim();
        const sku = name
            .split(' ')
            .map(word => word.substring(0, 3).toUpperCase())
            .join('-')
            .replace(/[^A-Z0-9-]/g, '');
        skuField.placeholder = sku || 'PRD-AUTO';
    }
});
</script>

<style>
.section-title h5 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.section-title hr {
    margin-top: 0.5rem;
    opacity: 0.1;
}

.form-label.fw-bold {
    font-size: 0.95rem;
    color: #2c3e50;
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

#specifications-container {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    border: 1px solid #e0e6ff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 1rem;
}

#specifications-container .spec-field {
    border: 1px solid #d1d9e6;
    transition: all 0.3s ease;
}

#specifications-container .spec-field:focus {
    border-color: #667eea;
    background-color: #fff;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
}

.custom-spec-row {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection

