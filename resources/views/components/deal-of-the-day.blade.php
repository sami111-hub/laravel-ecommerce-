{{-- قسم خصومات اليوم مع مؤقت عد تنازلي (مستوحى من Bazzarry) --}}
<div class="deal-of-the-day-section">
    <div class="container">
        <div class="section-header-deal">
            <div class="deal-title-group">
                <i class="bi bi-lightning-charge-fill"></i>
                <h2 class="section-title-deal">خصومات اليوم</h2>
                <span class="deal-subtitle">عروض لفترة محدودة</span>
            </div>
            
            {{-- مؤقت العد التنازلي --}}
            <div class="countdown-timer">
                <div class="timer-item">
                    <span class="timer-value" id="hours">00</span>
                    <span class="timer-label">ساعة</span>
                </div>
                <span class="timer-separator">:</span>
                <div class="timer-item">
                    <span class="timer-value" id="minutes">00</span>
                    <span class="timer-label">دقيقة</span>
                </div>
                <span class="timer-separator">:</span>
                <div class="timer-item">
                    <span class="timer-value" id="seconds">00</span>
                    <span class="timer-label">ثانية</span>
                </div>
            </div>

            <a href="{{ route('offers') }}" class="view-all-link">
                عرض الكل
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>

        {{-- قائمة المنتجات بتمرير أفقي --}}
        <div class="products-horizontal-scroll">
            <button class="scroll-btn scroll-left" onclick="scrollProducts('left', 'dealProducts')">
                <i class="bi bi-chevron-right"></i>
            </button>

            <div class="products-scroll-container" id="dealProducts">
                @php
                    try {
                        $dealProducts = \App\Models\Product::with(['category', 'brand'])
                            ->inRandomOrder()
                            ->take(12)
                            ->get()
                            ->map(function($product) {
                                $product->discount = rand(20, 60);
                                $product->old_price = $product->price * (1 + $product->discount / 100);
                                $product->delivery_days = rand(2, 4);
                                $product->stock = rand(5, 50);
                                return $product;
                            });
                    } catch (\Throwable $e) {
                        $dealProducts = collect();
                    }
                @endphp

                @forelse($dealProducts as $product)
                    @include('components.product-card-hybrid', ['product' => $product])
                @empty
                    <div class="no-products">
                        <i class="bi bi-inbox"></i>
                        <p>لا توجد عروض حالياً</p>
                    </div>
                @endforelse
            </div>

            <button class="scroll-btn scroll-right" onclick="scrollProducts('right', 'dealProducts')">
                <i class="bi bi-chevron-left"></i>
            </button>
        </div>
    </div>
</div>

<script>
// مؤقت العد التنازلي
function initCountdown() {
    // تعيين وقت نهاية (منتصف الليل القادم)
    const now = new Date();
    const tomorrow = new Date(now);
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(0, 0, 0, 0);
    const endTime = tomorrow.getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            document.getElementById('hours').textContent = '00';
            document.getElementById('minutes').textContent = '00';
            document.getElementById('seconds').textContent = '00';
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
        document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
}

// التمرير الأفقي للمنتجات
function scrollProducts(direction, containerId) {
    const container = document.getElementById(containerId);
    const scrollAmount = 300;
    
    if (direction === 'left') {
        container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else {
        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
}

// تفعيل المؤقت عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', initCountdown);
</script>
