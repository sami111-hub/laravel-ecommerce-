/**
 * Update Aden Store - Enhanced JavaScript
 * التفاعلية والأنيميشن للواجهة
 */

// ===== 1. Hero Slider Management =====
const heroSlider = {
    currentSlide: 0,
    slides: [],
    dots: [],
    autoPlayInterval: null,
    autoPlayDelay: 5000,

    init() {
        this.slides = document.querySelectorAll('.hero-slide');
        this.dots = document.querySelectorAll('.hero-dot');
        
        if (this.slides.length === 0) return;

        // Start autoplay
        this.startAutoPlay();

        // Pause on hover
        const container = document.querySelector('.hero-slider-container');
        if (container) {
            container.addEventListener('mouseenter', () => this.stopAutoPlay());
            container.addEventListener('mouseleave', () => this.startAutoPlay());
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prev();
            if (e.key === 'ArrowRight') this.next();
        });

        // Touch swipe support
        this.addSwipeSupport();
    },

    goTo(index) {
        // Hide current slide
        this.slides[this.currentSlide].classList.remove('active');
        this.dots[this.currentSlide].classList.remove('active');

        // Show new slide
        this.currentSlide = index;
        this.slides[this.currentSlide].classList.add('active');
        this.dots[this.currentSlide].classList.add('active');

        // Reset autoplay
        this.stopAutoPlay();
        this.startAutoPlay();
    },

    next() {
        const nextIndex = (this.currentSlide + 1) % this.slides.length;
        this.goTo(nextIndex);
    },

    prev() {
        const prevIndex = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.goTo(prevIndex);
    },

    startAutoPlay() {
        this.autoPlayInterval = setInterval(() => this.next(), this.autoPlayDelay);
    },

    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    },

    addSwipeSupport() {
        const container = document.querySelector('.hero-slider-container');
        if (!container) return;

        let touchStartX = 0;
        let touchEndX = 0;

        container.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        container.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe();
        });

        this.handleSwipe = () => {
            if (touchEndX < touchStartX - 50) this.next();
            if (touchEndX > touchStartX + 50) this.prev();
        };
    }
};

// ===== 2. Product Card Interactions =====
function quickView(productId) {
    console.log('Quick view for product:', productId);
    // يمكن إضافة modal هنا
    alert('عرض سريع للمنتج رقم: ' + productId);
}

function addToWishlist(productId) {
    console.log('Add to wishlist:', productId);
    // إضافة للمفضلة
    const btn = event.target.closest('.quick-action-btn');
    if (btn) {
        btn.classList.add('active');
        btn.innerHTML = '<i class="bi bi-heart-fill" style="color: #ef4444;"></i>';
        
        // Show toast notification
        showToast('تمت الإضافة للمفضلة ❤️', 'success');
    }
}

function notifyWhenAvailable(productId) {
    console.log('Notify when available:', productId);
    showToast('سنخبرك عندما يتوفر المنتج 🔔', 'info');
}

// ===== 3. Toast Notifications =====
function showToast(message, type = 'success') {
    // Remove existing toasts
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) existingToast.remove();

    // Create toast
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'info-circle-fill'}"></i>
            <span>${message}</span>
        </div>
    `;

    // Add styles
    toast.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#3b82f6'};
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideInRight 0.4s ease, slideOutRight 0.4s ease 2.6s;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
    `;

    document.body.appendChild(toast);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Add toast animations
const toastStyles = document.createElement('style');
toastStyles.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    .toast-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .toast-content i {
        font-size: 20px;
    }
`;
document.head.appendChild(toastStyles);

// ===== 4. Smooth Scroll to Sections =====
function smoothScrollTo(targetId) {
    const target = document.querySelector(targetId);
    if (target) {
        target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// ===== 5. Add to Cart with Animation =====
document.addEventListener('DOMContentLoaded', function() {
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('.btn-add-to-cart');
            if (button) {
                // Add loading state
                button.innerHTML = '<i class="bi bi-arrow-repeat rotating"></i> <span>جاري الإضافة...</span>';
                button.disabled = true;

                // After form submits (you can customize this based on your backend response)
                setTimeout(() => {
                    button.innerHTML = '<i class="bi bi-check-circle-fill"></i> <span>تمت الإضافة!</span>';
                    
                    setTimeout(() => {
                        button.innerHTML = '<i class="bi bi-cart-plus-fill"></i> <span>أضف للسلة</span>';
                        button.disabled = false;
                    }, 2000);
                }, 1000);
            }
        });
    });
});

// Add rotating animation for loading
const loadingStyles = document.createElement('style');
loadingStyles.textContent = `
    @keyframes rotating {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .rotating {
        animation: rotating 1s linear infinite;
    }
`;
document.head.appendChild(loadingStyles);

// ===== 6. Category Items Hover Effect =====
document.addEventListener('DOMContentLoaded', function() {
    const categoryItems = document.querySelectorAll('.category-item');
    
    categoryItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});

// ===== 7. Promo Bar Pause on Hover =====
document.addEventListener('DOMContentLoaded', function() {
    const promoBar = document.querySelector('.promo-bar-content');
    
    if (promoBar) {
        promoBar.addEventListener('mouseenter', function() {
            this.style.animationPlayState = 'paused';
        });
        
        promoBar.addEventListener('mouseleave', function() {
            this.style.animationPlayState = 'running';
        });
    }
});

// ===== 8. Search Input Enhancement =====
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        searchInput.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    }
});

// ===== 9. Lazy Loading Images =====
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
});

// ===== 10. Scroll to Top Button =====
document.addEventListener('DOMContentLoaded', function() {
    // Create scroll to top button
    const scrollBtn = document.createElement('button');
    scrollBtn.className = 'scroll-to-top';
    scrollBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
    scrollBtn.style.cssText = `
        position: fixed;
        bottom: 100px;
        left: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2BB673 0%, #249e60 100%);
        color: white;
        border: none;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 999;
        box-shadow: 0 4px 12px rgba(43, 182, 115, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    `;
    
    document.body.appendChild(scrollBtn);
    
    // Show/hide on scroll
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollBtn.style.opacity = '1';
            scrollBtn.style.visibility = 'visible';
        } else {
            scrollBtn.style.opacity = '0';
            scrollBtn.style.visibility = 'hidden';
        }
    });
    
    // Scroll to top on click
    scrollBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    scrollBtn.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.1) translateY(-3px)';
    });
    
    scrollBtn.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1) translateY(0)';
    });
});

// ===== 11. Initialize Hero Slider =====
document.addEventListener('DOMContentLoaded', function() {
    heroSlider.init();
});

// ===== 12. Product Card Animation on Scroll =====
document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('.product-card-enhanced');
    
    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.animation = 'fadeIn 0.6s ease forwards';
                }, index * 100);
                cardObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });
    
    productCards.forEach(card => {
        card.style.opacity = '0';
        cardObserver.observe(card);
    });
});

// ===== 13. Header Scroll Effect =====
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.main-header');
    let lastScroll = 0;
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
        } else {
            header.style.boxShadow = '0 1px 3px rgba(0,0,0,0.06)';
        }
        
        lastScroll = currentScroll;
    });
});

console.log('🛍 Update Aden Store Enhanced JavaScript Loaded Successfully! ✨');
