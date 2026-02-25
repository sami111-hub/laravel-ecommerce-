@extends('admin.layout')

@section('title', 'تفاصيل المنتج')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-box-seam"></i> تفاصيل المنتج</h4>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> العودة للقائمة
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        {{-- معلومات المنتج الأساسية --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> {{ $product->name }}</h5>
            </div>
            <div class="card-body">
                @php
                    $allImages = [];
                    if ($product->image) $allImages[] = $product->image;
                    foreach ($product->images ?? [] as $img) {
                        if (!in_array($img->image_path, $allImages)) $allImages[] = $img->image_path;
                    }
                @endphp
                @if(count($allImages) > 0)
                    <div class="text-center mb-4">
                        <img src="{{ $allImages[0] }}" alt="{{ $product->name }}" class="img-fluid rounded shadow-sm" style="max-height: 350px;">
                    </div>
                    @if(count($allImages) > 1)
                    <div class="d-flex gap-2 justify-content-center mb-3 flex-wrap">
                        @foreach($allImages as $i => $imgPath)
                        <img src="{{ $imgPath }}" alt="صورة {{ $i + 1 }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid {{ $i === 0 ? '#0d6efd' : '#dee2e6' }};">
                        @endforeach
                    </div>
                    <small class="text-muted"><i class="bi bi-images"></i> {{ count($allImages) }} صورة</small>
                    @endif
                @endif
                
                <div class="mb-3">
                    <h6 class="text-muted fw-bold"><i class="bi bi-text-paragraph"></i> الوصف</h6>
                    <p class="lead">{{ $product->description ?? 'لا يوجد وصف' }}</p>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-cash-stack text-success fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted">السعر</small>
                                <x-multi-currency-price :price="$product->price" size="normal" />
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-boxes text-info fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted">المخزون</small>
                                <div>
                                    @if($product->stock > 10)
                                        <span class="badge bg-success fs-6">{{ $product->stock }} قطعة</span>
                                    @elseif($product->stock > 0)
                                        <span class="badge bg-warning fs-6">{{ $product->stock }} قطعة</span>
                                    @else
                                        <span class="badge bg-danger fs-6">نفد المخزون</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-upc text-secondary fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted">رمز المنتج (SKU)</small>
                                <p class="mb-0 fw-bold">{{ $product->sku ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-award text-primary fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted">العلامة التجارية</small>
                                <p class="mb-0 fw-bold">{{ $product->brand->name ?? '-' }}</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-tags text-warning fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted">التصنيفات</small>
                                <div>
                                    @forelse($product->categories as $cat)
                                        <span class="badge bg-primary me-1">{{ $cat->name }}</span>
                                    @empty
                                        <span class="text-muted">بدون تصنيف</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- المواصفات التقنية --}}
        @if($product->specifications && count($product->specifications) > 0)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-cpu"></i> المواصفات التقنية</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 specs-table">
                        <tbody>
                            @php
                                $specLabels = [
                                    'screen_size' => ['حجم الشاشة', 'bi-phone'],
                                    'screen_type' => ['نوع الشاشة', 'bi-display'],
                                    'resolution' => ['دقة الشاشة', 'bi-aspect-ratio'],
                                    'processor' => ['المعالج', 'bi-cpu'],
                                    'ram' => ['الذاكرة العشوائية', 'bi-memory'],
                                    'storage' => ['سعة التخزين', 'bi-device-hdd'],
                                    'rear_camera' => ['الكاميرا الخلفية', 'bi-camera'],
                                    'front_camera' => ['الكاميرا الأمامية', 'bi-camera-video'],
                                    'battery' => ['سعة البطارية', 'bi-battery-charging'],
                                    'battery_life' => ['عمر البطارية', 'bi-battery-charging'],
                                    'charging' => ['سرعة الشحن', 'bi-lightning-charge'],
                                    'os' => ['نظام التشغيل', 'bi-gear'],
                                    'sim' => ['نوع الشريحة', 'bi-sim'],
                                    'network' => ['دعم الشبكات', 'bi-wifi'],
                                    'water_resistance' => ['مقاومة الماء', 'bi-droplet'],
                                    'weight' => ['الوزن', 'bi-speedometer'],
                                    'dimensions' => ['الأبعاد', 'bi-rulers'],
                                    'colors' => ['الألوان المتاحة', 'bi-palette'],
                                    'fingerprint' => ['مستشعر البصمة', 'bi-fingerprint'],
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
                                    'print_type' => ['نوع الطباعة', 'bi-printer'],
                                    'print_color' => ['ألوان الطباعة', 'bi-palette'],
                                    'print_speed' => ['سرعة الطباعة', 'bi-speedometer2'],
                                    'paper_sizes' => ['أحجام الورق', 'bi-file-earmark'],
                                    'paper_tray' => ['سعة درج الورق', 'bi-inbox'],
                                    'duplex' => ['الطباعة على الوجهين', 'bi-arrow-left-right'],
                                    'scanner' => ['الماسح الضوئي', 'bi-upc-scan'],
                                    'monthly_duty' => ['الحمولة الشهرية', 'bi-graph-up'],
                                    'type' => ['النوع', 'bi-box'],
                                    'driver_size' => ['حجم السماعة', 'bi-speaker'],
                                    'frequency' => ['نطاق التردد', 'bi-soundwave'],
                                    'noise_cancellation' => ['إلغاء الضوضاء', 'bi-volume-mute'],
                                    'microphone' => ['الميكروفون', 'bi-mic'],
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
                                    'offer_type' => ['نوع العرض', 'bi-tag'],
                                    'original_price' => ['السعر الأصلي', 'bi-cash'],
                                    'discount_percent' => ['نسبة الخصم', 'bi-percent'],
                                    'offer_end' => ['تاريخ انتهاء العرض', 'bi-calendar-event'],
                                    'warranty' => ['الضمان', 'bi-shield-check'],
                                ];
                            @endphp
                            @foreach($product->specifications as $key => $value)
                                <tr>
                                    <td class="fw-bold text-nowrap" style="width: 35%; background: #f8f9fa;">
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
    </div>

    <div class="col-md-4">
        {{-- الإجراءات --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-gear"></i> الإجراءات</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil-square"></i> تعديل المنتج
                </a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100 mb-2">
                        <i class="bi bi-trash"></i> حذف المنتج
                    </button>
                </form>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100">
                    <i class="bi bi-arrow-right"></i> رجوع للقائمة
                </a>
            </div>
        </div>

        {{-- ملخص المنتج --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-bar-chart"></i> ملخص</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>تاريخ الإضافة</span>
                        <strong>{{ $product->created_at->format('Y/m/d') }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>آخر تحديث</span>
                        <strong>{{ $product->updated_at->diffForHumans() }}</strong>
                    </li>
                    @if($product->specifications)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>عدد المواصفات</span>
                        <span class="badge bg-primary">{{ count($product->specifications) }}</span>
                    </li>
                    @endif
                    <li class="list-group-item d-flex justify-content-between">
                        <span>عدد التقييمات</span>
                        <span class="badge bg-info">{{ $product->reviews->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>عدد الصور</span>
                        <span class="badge bg-secondary">{{ ($product->images ? $product->images->count() : 0) + ($product->image ? 1 : 0) }}</span>
                    </li>
                    @if($product->variants && $product->variants->count() > 0)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>عدد الموديلات</span>
                        <span class="badge bg-dark">{{ $product->variants->count() }}</span>
                    </li>
                    @endif
                </ul>

                @if($product->variants && $product->variants->count() > 0)
                <hr>
                <h6 class="mb-3"><i class="bi bi-phone"></i> الموديلات</h6>
                @foreach($product->variants as $variant)
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded {{ $variant->stock > 0 ? 'bg-success-subtle' : 'bg-danger-subtle' }}">
                    <span>{{ $variant->model_name }}</span>
                    <span class="badge {{ $variant->stock > 0 ? 'bg-success' : 'bg-danger' }}">{{ $variant->stock }} قطعة</span>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.specs-table td {
    padding: 12px 16px;
    vertical-align: middle;
    font-size: 0.95rem;
}
.specs-table tr:hover {
    background-color: #e8f0fe !important;
}
</style>
@endsection

