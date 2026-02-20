@extends('layout')

@section('title', $product->name . ' - Update Aden')
@section('description', Str::limit($product->description, 150) . ' - اشتري الآن بسعر ' . number_format($product->price, 2) . ' $ مع التوصيل السريع')

@section('content')
<div class="row">
    <div class="col-md-6 mb-3">
        @if($product->image)
            <img src="{{ $product->image }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
        @else
            <div class="bg-light d-flex align-items-center justify-content-center rounded shadow" style="height: 400px;">
                <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <h1>{{ $product->name }}</h1>
        @if($product->description)
        <div class="product-description mb-3">
            {!! nl2br(e($product->description)) !!}
        </div>
        @endif
        <div class="mb-3">
            <x-multi-currency-price :price="$product->price" size="large" />
        </div>
        
        <div class="mb-3">
            <strong>المخزون:</strong> {{ $product->stock }}
        </div>

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
            <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-3">
                @csrf
                <div class="mb-3">
                    <label for="quantity" class="form-label">الكمية</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" required>
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="bi bi-cart-plus"></i> أضف إلى السلة
                </button>
            </form>
            @else
            <div class="alert alert-info">
                <a href="{{ route('login') }}" class="btn btn-primary">تسجيل الدخول لإضافة المنتج للسلة</a>
            </div>
            @endauth
        </div>

        {{-- المواصفات التقنية --}}
        @if($product->specifications && count($product->specifications) > 0)
        <div class="card mt-3 mb-3 specs-card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-cpu"></i> المواصفات التقنية</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
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

        {{-- Rating Summary --}}
        @php
            $reviews = $product->reviews->where('is_approved', true);
            $averageRating = $reviews->avg('rating') ?? 0;
            $reviewCount = $reviews->count();
        @endphp
        
        @if($reviewCount > 0)
        <div class="card mt-3">
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
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-muted small">{{ $count }}</span>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                <hr>
                
                {{-- Add Review Form --}}
                @auth
                    @php
                        $userReview = $reviews->where('user_id', Auth::id())->first();
                    @endphp
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
                                        <label for="rating{{ $i }}" class="star-label">
                                            <i class="bi bi-star-fill"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">تعليقك (اختياري)</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" 
                                          placeholder="شاركنا رأيك في هذا المنتج..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                        </form>
                    </div>
                    @endif
                @endauth
                
                {{-- Reviews List --}}
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
                                    @if($i <= $review->rating)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                        <i class="bi bi-star text-muted"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="mb-0">{{ $review->comment }}</p>
                        @endif
                        @auth
                            @if($review->user_id === Auth::id() || Auth::user()->isAdmin())
                            <div class="mt-2">
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            </div>
                            @endif
                        @endauth
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        @auth
        <div class="card mt-3">
            <div class="card-body">
                <h6>كن أول من يقيم هذا المنتج</h6>
                <form action="{{ route('reviews.store', $product) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">التقييم</label>
                        <div class="rating-input">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" required>
                                <label for="rating{{ $i }}" class="star-label">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">تعليقك (اختياري)</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" 
                                  placeholder="شاركنا رأيك في هذا المنتج..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                </form>
            </div>
        </div>
        @endauth
        @endif

        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> رجوع إلى المنتجات
        </a>
    </div>
</div>

<style>
.specs-card .table td {
    padding: 12px 16px;
    vertical-align: middle;
    font-size: 0.95rem;
}
.specs-card .table tr:hover {
    background-color: #e8f0fe !important;
}
.specs-card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
}
.specs-card .card-header {
    border-radius: 0;
}

/* Product Description Styling */
.product-description {
    color: #555;
    font-size: 1rem;
    line-height: 1.8;
    border-left: 4px solid #2bb673;
    padding: 12px 16px;
    background: #f8fff8;
    border-radius: 0 8px 8px 0;
    white-space: pre-line;
    direction: ltr;
    text-align: left;
    font-family: 'Segoe UI', Arial, sans-serif;
}
/* If description contains Arabic, align right */
.product-description:lang(ar),
.product-description[dir="rtl"] {
    direction: rtl;
    text-align: right;
}
</style>
@endsection

