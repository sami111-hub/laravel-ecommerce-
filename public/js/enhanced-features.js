// Enhanced Features JavaScript

// Live Search Enhancement
function liveSearch(input) {
    const query = input.value.trim();
    const resultsContainer = document.getElementById('live-search-results');
    
    if (!resultsContainer) return;
    
    if (query.length < 2) {
        resultsContainer.innerHTML = '';
        resultsContainer.style.display = 'none';
        return;
    }
    
    fetch(`/products/search?q=${encodeURIComponent(query)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.products && data.products.length > 0) {
            let html = '<div class="live-search-results">';
            data.products.forEach(product => {
                html += `
                    <a href="/products/${product.id}" class="live-search-item">
                        ${product.image_url ? `<img src="${product.image_url}" alt="${product.name}">` : ''}
                        <div>
                            <strong>${product.name}</strong>
                            <div class="text-muted">$${product.price}</div>
                        </div>
                    </a>
                `;
            });
            html += '</div>';
            resultsContainer.innerHTML = html;
            resultsContainer.style.display = 'block';
        } else {
            resultsContainer.innerHTML = '<div class="live-search-no-results">لا توجد نتائج</div>';
            resultsContainer.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Search error:', error);
    });
}

// Compare Badge Update
function updateCompareBadge() {
    const compareCount = {{ count(session('compare', [])) }};
    const badge = document.querySelector('.compare-badge');
    if (badge) {
        badge.textContent = compareCount;
        badge.style.display = compareCount > 0 ? 'inline' : 'none';
    }
}

// Cart Badge Update
function updateCartBadge() {
    fetch('/cart/count', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const badge = document.querySelector('.cart-badge');
        if (badge) {
            badge.textContent = data.count || 0;
        }
    })
    .catch(error => console.error('Cart count error:', error));
}

// Product Image Zoom
function initImageZoom() {
    const productImages = document.querySelectorAll('.product-image-zoom');
    productImages.forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.transition = 'transform 0.3s ease';
        });
        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
}

// Smooth Scroll to Top
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCompareBadge();
    updateCartBadge();
    initImageZoom();
    
    // Add scroll to top button
    const scrollTopBtn = document.createElement('button');
    scrollTopBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
    scrollTopBtn.className = 'scroll-to-top';
    scrollTopBtn.onclick = scrollToTop;
    scrollTopBtn.style.cssText = `
        position: fixed;
        bottom: 80px;
        left: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #667eea;
        color: white;
        border: none;
        cursor: pointer;
        display: none;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    document.body.appendChild(scrollTopBtn);
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollTopBtn.style.display = 'block';
        } else {
            scrollTopBtn.style.display = 'none';
        }
    });
});


