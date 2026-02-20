/* ============================================
   الميزات التفاعلية الاحترافية - متجر Update Aden
   Premium Interactive Features
   ============================================ */

// تهيئة الميزات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    initScrollProgress();
    initWhatsAppButton();
    // تم تعطيل إشعارات الشراء الحية
    // initSocialProof();
    initCountdownTimers();
    initProductComparison();
    initRecentlyViewed();
    initQuickView();
    initPriceRangeSlider();
    initStockProgress();
    initNewsletterForm();
});

// ===== شريط تقدم التمرير =====
function initScrollProgress() {
    const progressBar = document.createElement('div');
    progressBar.className = 'scroll-progress';
    document.body.appendChild(progressBar);

    window.addEventListener('scroll', () => {
        const windowHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (window.scrollY / windowHeight) * 100;
        progressBar.style.width = scrolled + '%';
    });
}

// ===== زر واتساب عائم =====
function initWhatsAppButton() {
    const whatsappBtn = document.createElement('div');
    whatsappBtn.className = 'whatsapp-float';
    whatsappBtn.innerHTML = `
        <i class="bi bi-whatsapp"></i>
        <span class="tooltip-text">تواصل معنا 0780 800 007</span>
    `;
    whatsappBtn.onclick = () => {
        window.open('https://wa.me/967780800007?text=مرحباً، أريد الاستفسار عن منتجاتكم', '_blank');
    };
    document.body.appendChild(whatsappBtn);
}

// ===== إشعارات الشراء الحية - معطلة =====
// تم تعطيل هذه الميزة بناءً على طلب المستخدم

// ===== عداد تنازلي للعروض =====
function initCountdownTimers() {
    const timers = document.querySelectorAll('[data-countdown]');
    timers.forEach(timer => {
        const endDate = new Date(timer.dataset.countdown).getTime();
        updateCountdown(timer, endDate);
        setInterval(() => updateCountdown(timer, endDate), 1000);
    });
}

function updateCountdown(element, endDate) {
    const now = new Date().getTime();
    const distance = endDate - now;

    if (distance < 0) {
        element.innerHTML = '<span class="text-danger">انتهى العرض!</span>';
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    element.innerHTML = `
        <div class="countdown-timer">
            ${days > 0 ? `<div class="countdown-item">
                <span class="countdown-number">${days}</span>
                <span class="countdown-label">يوم</span>
            </div>` : ''}
            <div class="countdown-item">
                <span class="countdown-number">${hours}</span>
                <span class="countdown-label">ساعة</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-number">${minutes}</span>
                <span class="countdown-label">دقيقة</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-number">${seconds}</span>
                <span class="countdown-label">ثانية</span>
            </div>
        </div>
    `;
}

// ===== مقارنة المنتجات =====
let comparisonProducts = [];

function initProductComparison() {
    const checkboxes = document.querySelectorAll('.compare-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleComparison);
    });

    updateComparisonBar();
}

function toggleComparison(e) {
    const productId = e.target.dataset.productId;
    
    if (e.target.checked) {
        if (comparisonProducts.length < 4) {
            comparisonProducts.push(productId);
        } else {
            e.target.checked = false;
            showToast('يمكنك مقارنة 4 منتجات كحد أقصى', 'warning');
        }
    } else {
        comparisonProducts = comparisonProducts.filter(id => id !== productId);
    }
    
    updateComparisonBar();
}

function updateComparisonBar() {
    let bar = document.querySelector('.compare-floating-bar');
    
    if (comparisonProducts.length > 0) {
        if (!bar) {
            bar = document.createElement('div');
            bar.className = 'compare-floating-bar';
            document.body.appendChild(bar);
        }
        
        bar.classList.add('active');
        bar.innerHTML = `
            <div class="container">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span>المنتجات المحددة للمقارنة</span>
                        <span class="compare-count">${comparisonProducts.length}</span>
                    </div>
                    <div>
                        <button class="btn btn-primary-modern" onclick="compareProducts()">
                            <i class="bi bi-arrow-left-right"></i> قارن الآن
                        </button>
                        <button class="btn btn-outline-modern ms-2" onclick="clearComparison()">
                            <i class="bi bi-x"></i> إلغاء
                        </button>
                    </div>
                </div>
            </div>
        `;
    } else if (bar) {
        bar.classList.remove('active');
    }
}

function compareProducts() {
    if (comparisonProducts.length < 2) {
        showToast('اختر منتجين على الأقل للمقارنة', 'warning');
        return;
    }
    window.location.href = `/products/compare?ids=${comparisonProducts.join(',')}`;
}

function clearComparison() {
    comparisonProducts = [];
    document.querySelectorAll('.compare-checkbox:checked').forEach(cb => cb.checked = false);
    updateComparisonBar();
}

// ===== المنتجات المشاهدة مؤخراً =====
function initRecentlyViewed() {
    const productId = document.querySelector('[data-product-id]')?.dataset.productId;
    if (productId) {
        addToRecentlyViewed(productId);
    }
}

function addToRecentlyViewed(productId) {
    let recent = JSON.parse(localStorage.getItem('recentlyViewed') || '[]');
    recent = recent.filter(id => id !== productId);
    recent.unshift(productId);
    recent = recent.slice(0, 10); // الاحتفاظ بآخر 10 منتجات
    localStorage.setItem('recentlyViewed', JSON.stringify(recent));
}

// ===== عرض سريع للمنتج =====
function initQuickView() {
    const productCards = document.querySelectorAll('.product-card-hybrid');
    productCards.forEach(card => {
        const quickViewBtn = document.createElement('button');
        quickViewBtn.className = 'quick-view-btn';
        quickViewBtn.innerHTML = '<i class="bi bi-eye"></i> عرض سريع';
        quickViewBtn.onclick = (e) => {
            e.preventDefault();
            const productId = card.dataset.productId;
            if (productId) {
                showQuickView(productId);
            }
        };
        card.querySelector('.product-image-hybrid')?.appendChild(quickViewBtn);
    });
}

function showQuickView(productId) {
    // يمكن تطوير نافذة منبثقة للعرض السريع
    showToast('جاري تحميل العرض السريع...', 'info');
    // TODO: تحميل بيانات المنتج وعرضها في modal
}

// ===== شريط تمرير السعر =====
function initPriceRangeSlider() {
    const sliders = document.querySelectorAll('.price-range-slider input[type="range"]');
    sliders.forEach(slider => {
        slider.addEventListener('input', updatePriceValues);
    });
}

function updatePriceValues() {
    const minSlider = document.getElementById('priceMin');
    const maxSlider = document.getElementById('priceMax');
    if (minSlider && maxSlider) {
        const minValue = parseInt(minSlider.value);
        const maxValue = parseInt(maxSlider.value);
        
        document.getElementById('priceMinValue').textContent = '$' + minValue;
        document.getElementById('priceMaxValue').textContent = '$' + maxValue;
        
        // تطبيق التصفية
        filterProductsByPrice(minValue, maxValue);
    }
}

function filterProductsByPrice(min, max) {
    // TODO: تصفية المنتجات حسب السعر
    console.log(`تصفية من ${min} إلى ${max}`);
}

// ===== شريط تقدم المخزون =====
function initStockProgress() {
    const stockBars = document.querySelectorAll('[data-stock]');
    stockBars.forEach(bar => {
        const stock = parseInt(bar.dataset.stock);
        const maxStock = parseInt(bar.dataset.maxStock || 100);
        const percentage = (stock / maxStock) * 100;
        
        setTimeout(() => {
            bar.querySelector('.stock-progress-bar').style.width = percentage + '%';
        }, 100);
    });
}

// ===== نموذج النشرة البريدية =====
function initNewsletterForm() {
    const form = document.querySelector('.newsletter-form');
    if (form) {
        form.addEventListener('submit', handleNewsletterSubmit);
    }
}

function handleNewsletterSubmit(e) {
    e.preventDefault();
    const email = e.target.querySelector('input[type="email"]').value;
    
    if (!email) {
        showToast('الرجاء إدخال بريد إلكتروني صحيح', 'warning');
        return;
    }
    
    // TODO: إرسال البريد إلى السيرفر
    showToast('شكراً لاشتراكك! سنرسل لك أحدث العروض', 'success');
    e.target.reset();
}

// ===== إضافة تأثيرات إضافية =====
function showToast(message, type = 'info') {
    // استخدام نظام Toast الموجود
    if (typeof window.showToast === 'function') {
        window.showToast(message, type);
    } else {
        alert(message);
    }
}

// ===== تتبع تفاعل المستخدم =====
function trackUserInteraction(action, data) {
    console.log('User Action:', action, data);
    // TODO: إرسال البيانات للتحليلات
}

// تصدير الدوال للاستخدام العام
window.updateAdenPremium = {
    showQuickView,
    compareProducts,
    clearComparison,
    showToast,
    trackUserInteraction
};

console.log('✨ Update Aden - Premium Features Loaded Successfully!');
