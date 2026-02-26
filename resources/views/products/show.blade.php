@extends('layout')

@section('title', $product->name . ' - Update Aden')
@section('description', Str::limit($product->description, 150) . ' - اشتري الآن بسعر ' . number_format($product->price, 2) . ' $ مع التوصيل السريع')

@section('content')
@php
    // تجميع كل الصور (الرئيسية + الإضافية)
    $allImages = [];
    if ($product->image) $allImages[] = $product->image;
    foreach ($product->images ?? [] as $img) {
        if (!in_array($img->image_path, $allImages)) {
            $allImages[] = $img->image_path;
        }
    }
    
    // تحقق من نوع المنتج
    $categorySlugs = $product->categories->pluck('slug')->toArray();
    $isSmartphone = count(array_intersect($categorySlugs, ['smartphones', 'mobiles'])) > 0;
    $isAccessory = count(array_intersect($categorySlugs, ['phone-accessories', 'cases-covers', 'accessories'])) > 0;
    $isLaptop = count(array_intersect($categorySlugs, ['computers-tablets', 'laptops', 'tablets'])) > 0;
    $isWatch = count(array_intersect($categorySlugs, ['smartwatches-wearables', 'watches'])) > 0;
    $isHeadphone = count(array_intersect($categorySlugs, ['headphones-speakers', 'headphones'])) > 0;
    $isCharger = count(array_intersect($categorySlugs, ['power-banks-chargers', 'chargers-cables', 'chargers'])) > 0;
    
    // ترتيب المواصفات حسب التصنيف
    // ترتيب الهواتف الذكية
    $smartphoneOrder = ['network', 'sim', 'dimensions', 'weight', 'screen_type', 'resolution', 'screen_size', 'os', 'processor', 'storage', 'ram', 'rear_camera', 'front_camera', 'battery', 'charging', 'water_resistance', 'fingerprint', 'colors'];
    // ترتيب اللابتوبات والأجهزة اللوحية
    $laptopOrder = ['processor', 'ram', 'storage', 'gpu', 'screen_size', 'screen_type', 'resolution', 'os', 'battery_life', 'device_type', 'ports', 'connectivity', 'webcam', 'keyboard', 'stylus', 'weight', 'colors'];
    // ترتيب الساعات الذكية
    $watchOrder = ['screen_size', 'screen_type', 'battery_life', 'water_resistance', 'sensors', 'gps', 'processor', 'storage', 'connectivity', 'compatibility', 'strap_material', 'weight', 'colors'];
    // ترتيب السماعات والمكبرات
    $headphoneOrder = ['type', 'noise_cancellation', 'battery_life', 'connectivity', 'driver_size', 'frequency', 'charging', 'microphone', 'water_resistance', 'power_output', 'weight', 'colors'];
    // ترتيب الشواحن والباور بانك
    $chargerOrder = ['type', 'wattage', 'fast_charging', 'battery_capacity', 'ports_count', 'cable_type', 'cable_length', 'compatibility', 'weight'];
    // ترتيب ملحقات الهواتف
    $accessoryOrder = ['type', 'compatible_device', 'material', 'protection_level', 'features', 'magsafe', 'connectivity', 'weight', 'colors'];
    
    // اختيار الترتيب المناسب
    if ($isSmartphone) $specOrder = $smartphoneOrder;
    elseif ($isLaptop) $specOrder = $laptopOrder;
    elseif ($isWatch) $specOrder = $watchOrder;
    elseif ($isHeadphone) $specOrder = $headphoneOrder;
    elseif ($isCharger) $specOrder = $chargerOrder;
    elseif ($isAccessory) $specOrder = $accessoryOrder;
    else $specOrder = [];
    
    // ترتيب المواصفات
    $orderedSpecs = [];
    $specs = $product->specifications ?? [];
    if (!empty($specOrder) && !empty($specs)) {
        foreach ($specOrder as $key) {
            if (isset($specs[$key])) {
                $orderedSpecs[$key] = $specs[$key];
            }
        }
        // إضافة أي مواصفات غير موجودة في الترتيب
        foreach ($specs as $key => $value) {
            if (!isset($orderedSpecs[$key])) {
                $orderedSpecs[$key] = $value;
            }
        }
    } else {
        $orderedSpecs = $specs;
    }
    
    // تسميات المواصفات
    $specLabels = [
        'network' => ['دعم الشبكات', 'bi-wifi'],
        'sim' => ['نوع الشريحة', 'bi-sim'],
        'dimensions' => ['الأبعاد', 'bi-rulers'],
        'weight' => ['الوزن', 'bi-speedometer'],
        'screen_type' => ['نوع الشاشة', 'bi-display'],
        'resolution' => ['دقة الشاشة', 'bi-aspect-ratio'],
        'screen_size' => ['حجم الشاشة', 'bi-phone'],
        'os' => ['نظام التشغيل', 'bi-gear'],
        'processor' => ['المعالج', 'bi-cpu'],
        'storage' => ['سعة التخزين', 'bi-device-hdd'],
        'ram' => ['الذاكرة العشوائية', 'bi-memory'],
        'rear_camera' => ['الكاميرا الخلفية', 'bi-camera'],
        'front_camera' => ['الكاميرا الأمامية', 'bi-camera-video'],
        'battery' => ['البطارية', 'bi-battery-charging'],
        'battery_life' => ['عمر البطارية', 'bi-battery-charging'],
        'charging' => ['سرعة الشحن', 'bi-lightning-charge'],
        'water_resistance' => ['مقاومة الماء', 'bi-droplet'],
        'fingerprint' => ['مستشعر البصمة', 'bi-fingerprint'],
        'colors' => ['الألوان المتاحة', 'bi-palette'],
        'gpu' => ['كرت الشاشة', 'bi-gpu-card'],
        'ports' => ['المنافذ', 'bi-usb-plug'],
        'keyboard' => ['لوحة المفاتيح', 'bi-keyboard'],
        'wifi' => ['الواي فاي', 'bi-wifi'],
        'bluetooth' => ['البلوتوث', 'bi-bluetooth'],
        'webcam' => ['الكاميرا', 'bi-webcam'],
        'sensors' => ['المستشعرات', 'bi-heart-pulse'],
        'gps' => ['نظام الملاحة', 'bi-geo-alt'],
        'connectivity' => ['الاتصال', 'bi-wifi'],
        'compatibility' => ['التوافق', 'bi-phone'],
        'strap_material' => ['مادة السوار', 'bi-watch'],
        'type' => ['النوع', 'bi-box'],
        'device_type' => ['نوع الجهاز', 'bi-laptop'],
        'driver_size' => ['حجم السماعة', 'bi-speaker'],
        'frequency' => ['نطاق التردد', 'bi-soundwave'],
        'noise_cancellation' => ['إلغاء الضوضاء', 'bi-volume-mute'],
        'microphone' => ['الميكروفون', 'bi-mic'],
        'power_output' => ['قوة الصوت', 'bi-volume-up'],
        'wattage' => ['القدرة (واط)', 'bi-lightning-charge'],
        'ports_count' => ['عدد المنافذ', 'bi-usb-plug'],
        'cable_type' => ['نوع الكيبل', 'bi-ethernet'],
        'cable_length' => ['طول الكيبل', 'bi-rulers'],
        'fast_charging' => ['الشحن السريع', 'bi-speedometer2'],
        'battery_capacity' => ['سعة البطارية', 'bi-battery-full'],
        'material' => ['المادة', 'bi-shield'],
        'compatible_device' => ['الجهاز المتوافق', 'bi-phone'],
        'protection_level' => ['مستوى الحماية', 'bi-shield-check'],
        'features' => ['المميزات', 'bi-stars'],
        'magsafe' => ['دعم MagSafe', 'bi-magnet'],
        'stylus' => ['دعم القلم', 'bi-pencil'],
        'warranty' => ['الضمان', 'bi-shield-check'],
        'platform' => ['المنصة', 'bi-controller'],
    ];
@endphp

<div class="container py-3">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">المنتجات</a></li>
            @if($product->categories->first())
                <li class="breadcrumb-item"><a href="{{ route('products.category', $product->categories->first()) }}">{{ $product->categories->first()->name }}</a></li>
            @endif
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 40) }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- === قسم الصور === --}}
        <div class="col-lg-6 mb-4">
            @if(count($allImages) > 1)
                {{-- معرض صور تفاعلي محسّن --}}
                <div class="product-gallery-enhanced">
                    {{-- الصورة الرئيسية --}}
                    <div class="main-image-container position-relative">
                        <div class="image-zoom-wrapper" id="zoomWrapper">
                            <img id="mainProductImage" src="{{ $allImages[0] }}" class="main-product-img" 
                                 alt="{{ $product->name }}" onclick="openFullscreen(this.src)">
                        </div>
                        {{-- عداد الصور --}}
                        <div class="image-counter">
                            <span id="currentImgNum">1</span> / {{ count($allImages) }}
                        </div>
                        {{-- زر الشاشة الكاملة --}}
                        <button class="fullscreen-btn" onclick="openFullscreen(document.getElementById('mainProductImage').src)" title="عرض بالحجم الكامل">
                            <i class="bi bi-arrows-fullscreen"></i>
                        </button>
                        {{-- أسهم التنقل --}}
                        <button class="gallery-nav-enhanced gallery-prev-enhanced" onclick="navigateGallery(-1)">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <button class="gallery-nav-enhanced gallery-next-enhanced" onclick="navigateGallery(1)">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </div>
                    {{-- صور مصغرة --}}
                    <div class="thumbnails-strip">
                        @foreach($allImages as $index => $img)
                        <div class="thumb-item {{ $index === 0 ? 'active' : '' }}" onclick="changeMainImage('{{ $img }}', this, {{ $index }})">
                            <img src="{{ $img }}" alt="صورة {{ $index + 1 }}" loading="lazy">
                        </div>
                        @endforeach
                    </div>
                </div>
            @elseif(count($allImages) === 1)
                <div class="product-gallery-enhanced">
                    <div class="main-image-container position-relative">
                        <div class="image-zoom-wrapper" id="zoomWrapper">
                            <img id="mainProductImage" src="{{ $allImages[0] }}" class="main-product-img" 
                                 alt="{{ $product->name }}" onclick="openFullscreen(this.src)">
                        </div>
                        <button class="fullscreen-btn" onclick="openFullscreen(document.getElementById('mainProductImage').src)">
                            <i class="bi bi-arrows-fullscreen"></i>
                        </button>
                    </div>
                </div>
            @else
                <div class="bg-light d-flex align-items-center justify-content-center rounded-4" style="height: 400px;">
                    <div class="text-center text-muted">
                        <i class="bi bi-image" style="font-size: 4rem;"></i>
                        <p class="mt-2">لا توجد صورة</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- === معلومات المنتج === --}}
        <div class="col-lg-6">
            @if($product->brand)
                <span class="badge bg-primary-subtle text-primary mb-2">{{ $product->brand->name }}</span>
            @endif
            @foreach($product->categories as $cat)
                <span class="badge bg-secondary-subtle text-secondary mb-2">{{ $cat->name }}</span>
            @endforeach
            
            <h1 class="fw-bold mb-3" style="font-size: 1.6rem;">{{ $product->name }}</h1>
            
            @if($product->description)
            <div class="product-description mb-3">
                {!! nl2br(e($product->description)) !!}
            </div>
            @endif
            
            <div class="mb-3">
                <x-multi-currency-price :price="$product->price" size="large" />
            </div>
            
            <div class="mb-3">
                @php
                    $hasVariants = $product->variants && $product->variants->where('is_active', true)->count() > 0;
                    $availableVariants = $hasVariants ? $product->variants->where('is_active', true)->where('stock', '>', 0) : collect();
                    $totalVariantStock = $availableVariants->sum('stock');
                @endphp

                @if($hasVariants)
                    {{-- المنتج فيه موديلات: حالة التوفر حسب الموديلات --}}
                    @if($availableVariants->count() > 0)
                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> متوفر ({{ $availableVariants->count() }} موديل)</span>
                    @else
                        <span class="badge bg-danger fs-6"><i class="bi bi-x-circle"></i> جميع الموديلات نفدت</span>
                    @endif
                @else
                    {{-- منتج عادي بدون موديلات --}}
                    @if($product->stock > 10)
                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> متوفر ({{ $product->stock }} قطعة)</span>
                    @elseif($product->stock > 0)
                        <span class="badge bg-warning text-dark fs-6"><i class="bi bi-exclamation-triangle"></i> كمية محدودة ({{ $product->stock }})</span>
                    @else
                        <span class="badge bg-danger fs-6"><i class="bi bi-x-circle"></i> غير متوفر</span>
                    @endif
                @endif
            </div>

            {{-- === موديلات المنتج === --}}
            @if($hasVariants && $availableVariants->count() > 0)
            <div class="card mb-3 border-primary">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0"><i class="bi bi-boxes"></i> اختر الموديل</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap gap-2" id="variants-list">
                        @foreach($availableVariants as $variant)
                            <label class="variant-option">
                                <input type="radio" name="selected_variant" value="{{ $variant->id }}" class="d-none variant-radio"
                                       data-stock="{{ $variant->stock }}" data-price="{{ $variant->price_adjustment }}"
                                       data-name="{{ $variant->model_name }}">
                                <span class="variant-badge badge bg-light text-dark border px-3 py-2" style="cursor: pointer; font-size: 0.9rem;">
                                    <strong><i class="bi bi-tag me-1"></i>{{ $variant->model_name }}</strong>
                                    @if($variant->color)
                                        <small class="d-block text-muted"><i class="bi bi-palette"></i> {{ $variant->color }}</small>
                                    @endif
                                    @if($variant->storage_size)
                                        <small class="d-block text-info"><i class="bi bi-device-ssd"></i> {{ $variant->storage_size }}</small>
                                    @endif
                                    @if($variant->ram)
                                        <small class="d-block text-warning"><i class="bi bi-memory"></i> RAM: {{ $variant->ram }}</small>
                                    @endif
                                    @if($variant->processor)
                                        <small class="d-block text-secondary"><i class="bi bi-cpu"></i> {{ $variant->processor }}</small>
                                    @endif
                                    <small class="text-success d-block mt-1">({{ $variant->stock }} قطعة)</small>
                                    @if($variant->price_adjustment != 0)
                                        <small class="d-block text-danger fw-bold">{{ $variant->price_adjustment > 0 ? '+' : '' }}{{ number_format($variant->price_adjustment, 2) }} $</small>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <div id="variant-error" class="text-danger small mt-2" style="display: none;">
                        <i class="bi bi-exclamation-circle"></i> يرجى اختيار الموديل المطلوب
                    </div>
                </div>
            </div>
            @endif

            {{-- أزرار --}}
            <div class="mb-3">
                <div class="d-flex gap-2 mb-2">
                    @auth
                        @if(\App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->exists())
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-heart-fill"></i> من المفضلة
                                </button>
                            </form>
                        @else
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-heart"></i> أضف للمفضلة
                                </button>
                            </form>
                        @endif
                    @endauth
                    
                    @php
                        $compareList = session('compare', []);
                        $inCompare = in_array($product->id, $compareList);
                    @endphp
                    @if($inCompare)
                        <form action="{{ route('compare.remove', $product) }}" method="POST" class="flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-arrow-left-right"></i> من المقارنة
                            </button>
                        </form>
                    @else
                        <form action="{{ route('compare.add', $product) }}" method="POST" class="flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-arrow-left-right"></i> أضف للمقارنة
                            </button>
                        </form>
                    @endif
                </div>
                
                @auth
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-3" id="add-to-cart-form" onsubmit="return validateCartForm()">
                    @csrf
                    <input type="hidden" name="variant_id" id="variant_id_input" value="">
                    @if($hasVariants && $availableVariants->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">الكمية</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="1" required disabled>
                            <small class="text-muted" id="stock-hint">اختر الموديل أولاً</small>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100" id="add-cart-btn" disabled>
                            <i class="bi bi-cart-plus"></i> اختر الموديل أولاً
                        </button>
                    @else
                        <div class="mb-3">
                            <label for="quantity" class="form-label">الكمية</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-cart-plus"></i> أضف إلى السلة
                        </button>
                    @endif
                </form>
                @else
                <div class="alert alert-info">
                    <a href="{{ route('login') }}" class="btn btn-primary">تسجيل الدخول لإضافة المنتج للسلة</a>
                </div>
                @endauth
            </div>
        </div>
    </div>

    {{-- === المواصفات التقنية === --}}
    @if(!empty($orderedSpecs))
    <div class="card mt-4 mb-4 specs-card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-cpu"></i> المواصفات التقنية</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <tbody>
                        @foreach($orderedSpecs as $key => $value)
                            <tr>
                                <td class="fw-bold text-nowrap" style="width: 40%; background: #f8f9fa;">
                                    @if(isset($specLabels[$key]))
                                        <i class="bi {{ $specLabels[$key][1] }} text-primary me-2"></i>{{ $specLabels[$key][0] }}
                                    @else
                                        <i class="bi bi-dot text-primary me-2"></i>{{ $key }}
                                    @endif
                                </td>
                                <td>{{ $value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- === التقييمات === --}}
    @php
        $reviews = $product->reviews->where('is_approved', true);
        $averageRating = $reviews->avg('rating') ?? 0;
        $reviewCount = $reviews->count();
    @endphp
    
    @if($reviewCount > 0)
    <div class="card mt-3 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-star-fill"></i> التقييمات ({{ $reviewCount }})</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4 text-center">
                    <div class="display-4 text-warning">{{ number_format($averageRating, 1) }}</div>
                    <div>
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($averageRating))
                                <i class="bi bi-star-fill text-warning"></i>
                            @elseif($i - $averageRating < 1 && $i - $averageRating > 0)
                                <i class="bi bi-star-half text-warning"></i>
                            @else
                                <i class="bi bi-star text-muted"></i>
                            @endif
                        @endfor
                    </div>
                    <small class="text-muted">من {{ $reviewCount }} تقييم</small>
                </div>
                <div class="col-md-8">
                    @for($rating = 5; $rating >= 1; $rating--)
                        @php
                            $count = $reviews->where('rating', $rating)->count();
                            $percentage = $reviewCount > 0 ? ($count / $reviewCount) * 100 : 0;
                        @endphp
                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <span class="me-2" style="width: 20px;">{{ $rating }}</span>
                                <i class="bi bi-star-fill text-warning me-2"></i>
                                <div class="progress flex-fill me-2" style="height: 20px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-muted small">{{ $count }}</span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            <hr>
            @auth
                @php $userReview = $reviews->where('user_id', Auth::id())->first(); @endphp
                @if(!$userReview)
                <div class="mb-4">
                    <h6>أضف تقييمك</h6>
                    <form action="{{ route('reviews.store', $product) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">التقييم</label>
                            <div class="rating-input">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" required>
                                    <label for="rating{{ $i }}" class="star-label"><i class="bi bi-star-fill"></i></label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="comment" rows="3" placeholder="شاركنا رأيك في هذا المنتج..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                    </form>
                </div>
                @endif
            @endauth
            <div class="reviews-list">
                @foreach($reviews->sortByDesc('created_at') as $review)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <strong>{{ $review->user->name }}</strong>
                            <div class="text-muted small">{{ $review->created_at->diffForHumans() }}</div>
                        </div>
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }}"></i>
                            @endfor
                        </div>
                    </div>
                    @if($review->comment)<p class="mb-0">{{ $review->comment }}</p>@endif
                    @auth
                        @if($review->user_id === Auth::id() || Auth::user()->isAdmin())
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline mt-2" onsubmit="return confirm('هل أنت متأكد؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                        </form>
                        @endif
                    @endauth
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    @auth
    <div class="card mt-3 mb-4">
        <div class="card-body">
            <h6>كن أول من يقيم هذا المنتج</h6>
            <form action="{{ route('reviews.store', $product) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <div class="rating-input">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" required>
                            <label for="rating{{ $i }}" class="star-label"><i class="bi bi-star-fill"></i></label>
                        @endfor
                    </div>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" name="comment" rows="3" placeholder="شاركنا رأيك..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">إرسال التقييم</button>
            </form>
        </div>
    </div>
    @endauth
    @endif

    {{-- === المنتجات المشابهة === --}}
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <div class="mt-4 mb-4">
        <h4 class="fw-bold mb-3">
            <i class="bi bi-grid-3x3-gap text-primary"></i> منتجات مشابهة
        </h4>
        <div class="row g-3">
            @foreach($relatedProducts as $related)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="card h-100 shadow-sm border-0 hover-shadow">
                    @php
                        $relImg = $related->image;
                        if (!$relImg && $related->images->count()) $relImg = $related->images->first()->image_path;
                    @endphp
                    @if($relImg)
                        <img src="{{ $relImg }}" class="card-img-top" alt="{{ $related->name }}" 
                             style="height: 180px; object-fit: contain; background: #f8f9fa;" loading="lazy">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="bi bi-image text-muted" style="font-size: 2.5rem;"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column p-2">
                        <h6 class="card-title mb-1" style="font-size: 0.85rem;">{{ Str::limit($related->name, 40) }}</h6>
                        @if($related->brand)
                            <small class="text-muted">{{ $related->brand->name }}</small>
                        @endif
                        <div class="mt-auto pt-2">
                            <x-multi-currency-price :price="$related->price" size="small" />
                            <a href="{{ route('products.show', $related) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                                <i class="bi bi-eye"></i> عرض
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-4">
        <i class="bi bi-arrow-right"></i> رجوع إلى المنتجات
    </a>
</div>

<style>
/* ===== معرض الصور المحسّن ===== */
.product-gallery-enhanced {
    position: sticky;
    top: 20px;
}
.main-image-container {
    border-radius: 16px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 12px;
}
.image-zoom-wrapper {
    position: relative;
    overflow: hidden;
    cursor: zoom-in;
}
.main-product-img {
    width: 100%;
    max-height: 500px;
    object-fit: contain;
    transition: transform 0.4s ease, opacity 0.2s ease;
    padding: 10px;
}
.image-zoom-wrapper:hover .main-product-img {
    transform: scale(1.05);
}
.image-counter {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: rgba(0,0,0,0.6);
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    backdrop-filter: blur(4px);
}
.fullscreen-btn {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(255,255,255,0.9);
    border: none;
    border-radius: 50%;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: all 0.3s;
    z-index: 3;
}
.fullscreen-btn:hover {
    background: #fff;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
}
.gallery-nav-enhanced {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.9);
    border: none;
    border-radius: 50%;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    transition: all 0.3s;
    z-index: 3;
    opacity: 0;
    font-size: 1.1rem;
}
.main-image-container:hover .gallery-nav-enhanced {
    opacity: 1;
}
.gallery-nav-enhanced:hover {
    background: #0d6efd;
    color: #fff;
    transform: translateY(-50%) scale(1.1);
}
.gallery-prev-enhanced { right: 12px; }
.gallery-next-enhanced { left: 12px; }

/* الصور المصغرة */
.thumbnails-strip {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding: 4px 0 8px;
    scroll-behavior: smooth;
}
.thumbnails-strip::-webkit-scrollbar { height: 3px; }
.thumbnails-strip::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
.thumb-item {
    flex-shrink: 0;
    width: 72px;
    height: 72px;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    border: 3px solid transparent;
    opacity: 0.5;
    transition: all 0.3s ease;
    background: #f8f9fa;
}
.thumb-item:hover {
    opacity: 0.8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.thumb-item.active {
    opacity: 1;
    border-color: #0d6efd;
    box-shadow: 0 2px 8px rgba(13,110,253,0.4);
}
.thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* المواصفات */
.specs-card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 12px; overflow: hidden; }
.specs-card .table td { padding: 12px 16px; vertical-align: middle; font-size: 0.95rem; }
.specs-card .table tr:hover { background-color: #e8f0fe !important; }

/* وصف المنتج */
.product-description {
    color: #555; font-size: 1rem; line-height: 1.8;
    border-right: 4px solid #2bb673;
    padding: 12px 16px; background: #f8fff8;
    border-radius: 8px 0 0 8px; white-space: pre-line;
}

/* الموديلات */
.variant-option .variant-radio:checked + .variant-badge {
    background: #0d6efd !important;
    color: white !important;
    border-color: #0d6efd !important;
}
.variant-badge:hover {
    background: #e3f0ff !important;
    border-color: #0d6efd !important;
}

/* المنتجات المشابهة */
.hover-shadow { transition: all 0.3s; }
.hover-shadow:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.12) !important; }

/* عرض الشاشة الكاملة المحسّن */
.fullscreen-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.95); z-index: 9999;
    display: flex; align-items: center; justify-content: center;
    cursor: zoom-out;
    animation: fadeIn 0.3s ease;
}
.fullscreen-overlay img {
    max-width: 95%; max-height: 90%;
    object-fit: contain;
    animation: zoomIn 0.3s ease;
}
.fullscreen-close {
    position: absolute; top: 20px; left: 20px;
    background: rgba(255,255,255,0.2); border: none;
    color: #fff; font-size: 1.5rem; border-radius: 50%;
    width: 48px; height: 48px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.3s;
}
.fullscreen-close:hover { background: rgba(255,255,255,0.4); }
.fullscreen-nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    background: rgba(255,255,255,0.2); border: none;
    color: #fff; font-size: 1.8rem; border-radius: 50%;
    width: 52px; height: 52px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.3s;
}
.fullscreen-nav:hover { background: rgba(255,255,255,0.4); }
.fullscreen-prev { right: 20px; }
.fullscreen-next { left: 20px; }
.fullscreen-counter {
    position: absolute; bottom: 20px; left: 50%;
    transform: translateX(-50%);
    color: #fff; font-size: 1rem;
    background: rgba(0,0,0,0.5); padding: 6px 16px;
    border-radius: 20px;
}

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes zoomIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }

@media (max-width: 768px) {
    .product-gallery-enhanced { position: static; }
    .main-product-img { max-height: 350px; }
    .gallery-nav-enhanced { opacity: 1; width: 36px; height: 36px; }
    .thumb-item { width: 60px; height: 60px; }
}
</style>

<script>
// معرض الصور المحسّن
let currentImageIndex = 0;
const galleryImages = @json($allImages);

function changeMainImage(src, thumbEl, index) {
    const mainImg = document.getElementById('mainProductImage');
    mainImg.style.opacity = '0';
    setTimeout(() => {
        mainImg.src = src;
        mainImg.style.opacity = '1';
    }, 200);
    
    document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
    if (thumbEl) thumbEl.classList.add('active');
    if (typeof index !== 'undefined') {
        currentImageIndex = index;
    } else {
        currentImageIndex = galleryImages.indexOf(src);
    }
    updateImageCounter();
}

function navigateGallery(direction) {
    currentImageIndex += direction;
    if (currentImageIndex < 0) currentImageIndex = galleryImages.length - 1;
    if (currentImageIndex >= galleryImages.length) currentImageIndex = 0;
    
    const thumbs = document.querySelectorAll('.thumb-item');
    changeMainImage(galleryImages[currentImageIndex], thumbs[currentImageIndex], currentImageIndex);
    
    // scroll thumbnail into view
    if (thumbs[currentImageIndex]) {
        thumbs[currentImageIndex].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
}

function updateImageCounter() {
    const counter = document.getElementById('currentImgNum');
    if (counter) counter.textContent = currentImageIndex + 1;
}

function openFullscreen(src) {
    const overlay = document.createElement('div');
    overlay.className = 'fullscreen-overlay';
    overlay.innerHTML = `
        <button class="fullscreen-close" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        <button class="fullscreen-nav fullscreen-prev" onclick="event.stopPropagation(); navigateFullscreen(-1)"><i class="bi bi-chevron-right"></i></button>
        <img src="${src}" alt="عرض بالحجم الكامل" id="fullscreenImg">
        <button class="fullscreen-nav fullscreen-next" onclick="event.stopPropagation(); navigateFullscreen(1)"><i class="bi bi-chevron-left"></i></button>
        <div class="fullscreen-counter"><span id="fsCounter">${currentImageIndex + 1}</span> / ${galleryImages.length}</div>
    `;
    overlay.onclick = (e) => { if (e.target === overlay) overlay.remove(); };
    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
}

function navigateFullscreen(direction) {
    currentImageIndex += direction;
    if (currentImageIndex < 0) currentImageIndex = galleryImages.length - 1;
    if (currentImageIndex >= galleryImages.length) currentImageIndex = 0;
    
    const fsImg = document.getElementById('fullscreenImg');
    if (fsImg) {
        fsImg.style.opacity = '0';
        setTimeout(() => {
            fsImg.src = galleryImages[currentImageIndex];
            fsImg.style.opacity = '1';
        }, 200);
    }
    const fsCounter = document.getElementById('fsCounter');
    if (fsCounter) fsCounter.textContent = currentImageIndex + 1;
    
    // sync thumbnails
    const thumbs = document.querySelectorAll('.thumb-item');
    document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
    if (thumbs[currentImageIndex]) thumbs[currentImageIndex].classList.add('active');
    updateImageCounter();
}

// Swipe support for mobile
(function() {
    const wrapper = document.getElementById('zoomWrapper');
    if (!wrapper) return;
    let startX = 0, startY = 0;
    wrapper.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
    }, { passive: true });
    wrapper.addEventListener('touchend', (e) => {
        const diffX = e.changedTouches[0].clientX - startX;
        const diffY = e.changedTouches[0].clientY - startY;
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
            if (diffX > 0) navigateGallery(-1); // swipe right = prev (RTL)
            else navigateGallery(1); // swipe left = next (RTL)
        }
    }, { passive: true });
})();

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const overlay = document.querySelector('.fullscreen-overlay');
    if (e.key === 'ArrowRight') { overlay ? navigateFullscreen(-1) : navigateGallery(-1); }
    if (e.key === 'ArrowLeft') { overlay ? navigateFullscreen(1) : navigateGallery(1); }
    if (e.key === 'Escape') {
        if (overlay) {
            overlay.remove();
            document.body.style.overflow = '';
        }
    }
});

// ===== منطق اختيار الموديلات =====
document.addEventListener('DOMContentLoaded', function() {
    const variantRadios = document.querySelectorAll('.variant-radio');
    const variantIdInput = document.getElementById('variant_id_input');
    const quantityInput = document.getElementById('quantity');
    const addCartBtn = document.getElementById('add-cart-btn');
    const stockHint = document.getElementById('stock-hint');
    const variantError = document.getElementById('variant-error');

    if (variantRadios.length > 0) {
        variantRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const stock = parseInt(this.dataset.stock);
                const name = this.dataset.name;
                
                // تحديث الحقل المخفي
                variantIdInput.value = this.value;
                
                // تفعيل حقل الكمية
                quantityInput.disabled = false;
                quantityInput.max = stock;
                quantityInput.value = 1;
                
                // تحديث زر الإضافة
                addCartBtn.disabled = false;
                addCartBtn.innerHTML = '<i class="bi bi-cart-plus"></i> أضف إلى السلة (' + name + ')';
                addCartBtn.classList.remove('btn-secondary');
                addCartBtn.classList.add('btn-success');
                
                // تحديث رسالة المخزون
                stockHint.innerHTML = '<span class="text-success">المخزون المتاح: ' + stock + ' قطعة</span>';
                
                // إخفاء رسالة الخطأ
                if (variantError) variantError.style.display = 'none';
                
                // تحديث الشكل المرئي
                document.querySelectorAll('.variant-badge').forEach(badge => {
                    badge.classList.remove('bg-primary', 'text-white');
                    badge.classList.add('bg-light', 'text-dark');
                });
                this.nextElementSibling.classList.remove('bg-light', 'text-dark');
                this.nextElementSibling.classList.add('bg-primary', 'text-white');
            });
        });
    }
});

function validateCartForm() {
    const variantRadios = document.querySelectorAll('.variant-radio');
    if (variantRadios.length > 0) {
        const selected = document.querySelector('.variant-radio:checked');
        if (!selected) {
            const variantError = document.getElementById('variant-error');
            if (variantError) variantError.style.display = 'block';
            return false;
        }
    }
    return true;
}
</script>
@endsection

