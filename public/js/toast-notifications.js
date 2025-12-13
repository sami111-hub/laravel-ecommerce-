/**
 * نظام إشعارات Toast - Update Aden Store
 * إشعارات جميلة وسلسة
 */

// إنشاء Toast Container
function createToastContainer() {
    if (!document.getElementById('toastContainer')) {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container-smart';
        document.body.appendChild(container);
    }
}

// عرض Toast
function showToast(message, type = 'success', duration = 3000) {
    createToastContainer();
    
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast-smart toast-${type}`;
    
    // الأيقونات حسب النوع
    const icons = {
        success: '<i class="bi bi-check-circle-fill"></i>',
        error: '<i class="bi bi-x-circle-fill"></i>',
        warning: '<i class="bi bi-exclamation-triangle-fill"></i>',
        info: '<i class="bi bi-info-circle-fill"></i>'
    };
    
    toast.innerHTML = `
        <div class="toast-icon">${icons[type] || icons.info}</div>
        <div class="toast-message">${message}</div>
        <button class="toast-close" onclick="closeToast(this)">
            <i class="bi bi-x"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Animation in
    setTimeout(() => toast.classList.add('show'), 10);
    
    // Auto remove
    setTimeout(() => {
        closeToast(toast.querySelector('.toast-close'));
    }, duration);
}

// إغلاق Toast
function closeToast(btn) {
    const toast = btn.closest('.toast-smart');
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 300);
}

// دوال مخصصة
window.showSuccess = (msg) => showToast(msg, 'success');
window.showError = (msg) => showToast(msg, 'error');
window.showWarning = (msg) => showToast(msg, 'warning');
window.showInfo = (msg) => showToast(msg, 'info');

// معالجة إضافة للسلة عبر AJAX
function addToCartAjax(productId, productName) {
    if (!isUserLoggedIn()) {
        showWarning('يجب تسجيل الدخول أولاً!');
        setTimeout(() => {
            window.location.href = '/login';
        }, 1500);
        return;
    }
    
    // عرض Loading
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> جاري الإضافة...';
    
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
        
        if (data.success) {
            showSuccess(`✓ تم إضافة ${productName} للسلة`);
            updateCartCount(data.cartCount);
            
            // تأثير على زر السلة
            const cartBtn = document.querySelector('.btn-cart');
            if (cartBtn) {
                cartBtn.classList.add('pulse-animation');
                setTimeout(() => cartBtn.classList.remove('pulse-animation'), 600);
            }
        } else {
            showError(data.message || 'حدث خطأ ما!');
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
        showError('فشل الاتصال بالخادم');
        console.error('Error:', error);
    });
}

// تحديث عداد السلة
function updateCartCount(count) {
    const badges = document.querySelectorAll('.cart-badge');
    badges.forEach(badge => {
        badge.textContent = count;
        badge.classList.add('bounce');
        setTimeout(() => badge.classList.remove('bounce'), 600);
    });
}

// التحقق من تسجيل الدخول
function isUserLoggedIn() {
    return document.querySelector('meta[name="user-auth"]')?.content === 'true' || 
           document.querySelector('.btn-logout') !== null;
}

// معالجة البحث الفوري
let searchTimeout;
function liveSearch(input) {
    clearTimeout(searchTimeout);
    
    const query = input.value.trim();
    if (query.length < 2) {
        hideSearchResults();
        return;
    }
    
    searchTimeout = setTimeout(() => {
        showSearchLoading();
        
        fetch(`/products/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data.products);
            })
            .catch(error => {
                console.error('Search error:', error);
                hideSearchResults();
            });
    }, 300);
}

function showSearchLoading() {
    const resultsDiv = getOrCreateSearchResults();
    resultsDiv.innerHTML = `
        <div class="search-loading">
            <div class="spinner-border spinner-border-sm"></div>
            <span>جاري البحث...</span>
        </div>
    `;
    resultsDiv.style.display = 'block';
}

function displaySearchResults(products) {
    const resultsDiv = getOrCreateSearchResults();
    
    if (products.length === 0) {
        resultsDiv.innerHTML = `
            <div class="no-results">
                <i class="bi bi-search"></i>
                <p>لا توجد نتائج</p>
            </div>
        `;
    } else {
        resultsDiv.innerHTML = products.map(product => `
            <a href="/products/${product.id}" class="search-result-item">
                <img src="${product.image_url || '/images/placeholder.png'}" alt="${product.name}">
                <div class="result-info">
                    <div class="result-name">${product.name}</div>
                    <div class="result-price">${product.price} ر.ي</div>
                </div>
            </a>
        `).join('');
    }
    
    resultsDiv.style.display = 'block';
}

function hideSearchResults() {
    const resultsDiv = document.getElementById('searchResults');
    if (resultsDiv) {
        resultsDiv.style.display = 'none';
    }
}

function getOrCreateSearchResults() {
    let resultsDiv = document.getElementById('searchResults');
    if (!resultsDiv) {
        resultsDiv = document.createElement('div');
        resultsDiv.id = 'searchResults';
        resultsDiv.className = 'search-results-dropdown';
        const searchForm = document.querySelector('.search-form-enhanced');
        if (searchForm) {
            searchForm.appendChild(resultsDiv);
        }
    }
    return resultsDiv;
}

// إغلاق نتائج البحث عند النقر خارجها
document.addEventListener('click', function(e) {
    if (!e.target.closest('.search-form-enhanced')) {
        hideSearchResults();
    }
});

// معالجة Wishlist
function addToWishlist(productId) {
    if (!isUserLoggedIn()) {
        showWarning('يجب تسجيل الدخول أولاً!');
        setTimeout(() => window.location.href = '/login', 1500);
        return;
    }
    
    const btn = event.target.closest('button');
    const heartIcon = btn.querySelector('i');
    
    fetch(`/wishlist/toggle/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.inWishlist) {
                heartIcon.classList.remove('bi-heart');
                heartIcon.classList.add('bi-heart-fill');
                btn.classList.add('active');
                showSuccess(data.message);
            } else {
                heartIcon.classList.remove('bi-heart-fill');
                heartIcon.classList.add('bi-heart');
                btn.classList.remove('active');
                showInfo(data.message);
            }
        } else {
            showError('حدث خطأ ما!');
        }
    })
    .catch(error => {
        showError('فشل الاتصال بالخادم');
        console.error('Error:', error);
    });
}

// Loading animation CSS class
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .spin { animation: spin 1s linear infinite; }
`;
document.head.appendChild(style);

console.log('✓ Toast Notifications System Loaded');
