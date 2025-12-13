// ============================================
// تحسينات JavaScript للتأثيرات البصرية
// Visual Enhancements JavaScript
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== Scroll Animations =====
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all elements with fade-in-on-scroll class
    document.querySelectorAll('.fade-in-on-scroll').forEach(el => {
        observer.observe(el);
    });

    // ===== Product Card Animations =====
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    // ===== Smooth Scroll for Links =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ===== Add to Cart Animation =====
    const addToCartButtons = document.querySelectorAll('.btn-add-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Add pulse animation
            this.style.animation = 'pulse 0.5s ease';
            
            // Create floating icon
            const icon = document.createElement('i');
            icon.className = 'bi bi-cart-check-fill';
            icon.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 3rem;
                color: #2BB673;
                z-index: 9999;
                pointer-events: none;
                animation: fadeInScale 0.5s ease;
            `;
            document.body.appendChild(icon);
            
            // Remove after animation
            setTimeout(() => {
                icon.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => icon.remove(), 300);
            }, 1000);
            
            // Reset button animation
            setTimeout(() => {
                this.style.animation = '';
            }, 500);
        });
    });

    // ===== Search Input Focus Effect =====
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
            this.parentElement.style.boxShadow = '0 8px 25px rgba(43, 182, 115, 0.2)';
        });
        
        searchInput.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
            this.parentElement.style.boxShadow = '';
        });
    }

    // ===== Category Card Hover Sound Effect (Visual) =====
    const categoryCards = document.querySelectorAll('.category-card .card');
    categoryCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1.2) rotate(360deg)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }
        });
    });

    // ===== Parallax Effect for Headers =====
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const headers = document.querySelectorAll('.neon-text');
        
        headers.forEach(header => {
            const speed = 0.5;
            header.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });

    // ===== Badge Glow Pulse =====
    const glowBadges = document.querySelectorAll('.badge-glow');
    glowBadges.forEach(badge => {
        setInterval(() => {
            badge.style.boxShadow = '0 0 ' + (Math.random() * 20 + 15) + 'px rgba(43, 182, 115, 0.5)';
        }, 1000);
    });

    // ===== Price Tag Animation on View =====
    const priceTags = document.querySelectorAll('.price-tag');
    const priceObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'pulse 1s ease';
                setTimeout(() => {
                    entry.target.style.animation = '';
                }, 1000);
            }
        });
    }, { threshold: 0.5 });

    priceTags.forEach(tag => priceObserver.observe(tag));

    // ===== Loading Skeleton Effect =====
    function createSkeleton(container) {
        const skeleton = document.createElement('div');
        skeleton.className = 'skeleton';
        skeleton.style.cssText = `
            width: 100%;
            height: 200px;
            margin-bottom: 10px;
            border-radius: 10px;
        `;
        container.appendChild(skeleton);
    }

    // ===== Image Lazy Loading with Fade Effect =====
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.style.opacity = '0';
                img.onload = () => {
                    img.style.transition = 'opacity 0.5s ease';
                    img.style.opacity = '1';
                };
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));

    // ===== Tooltip Enhancement =====
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(el => {
        el.classList.add('tooltip-custom');
    });

    // ===== Button Ripple Effect =====
    document.querySelectorAll('.btn-primary, .btn-gradient').forEach(button => {
        button.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                left: ${x}px;
                top: ${y}px;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: translate(-50%, -50%);
                animation: ripple 0.6s ease-out;
            `;
            
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // ===== Floating Action Button =====
    const scrollToTopBtn = document.createElement('button');
    scrollToTopBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
    scrollToTopBtn.className = 'scroll-to-top-btn';
    scrollToTopBtn.style.cssText = `
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2BB673 0%, #1ea05e 100%);
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(43, 182, 115, 0.4);
        opacity: 0;
        transform: scale(0);
        transition: all 0.3s ease;
        z-index: 1000;
    `;
    
    document.body.appendChild(scrollToTopBtn);

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.style.opacity = '1';
            scrollToTopBtn.style.transform = 'scale(1)';
        } else {
            scrollToTopBtn.style.opacity = '0';
            scrollToTopBtn.style.transform = 'scale(0)';
        }
    });

    scrollToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // ===== Add CSS Animations =====
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.5); }
        }
        
        @keyframes ripple {
            to {
                width: 300px;
                height: 300px;
                opacity: 0;
            }
        }
        
        .scroll-to-top-btn:hover {
            transform: scale(1.1) !important;
            box-shadow: 0 6px 20px rgba(43, 182, 115, 0.5) !important;
        }
        
        .scroll-to-top-btn:active {
            transform: scale(0.95) !important;
        }
    `;
    document.head.appendChild(style);

    // ===== Console Easter Egg =====
    console.log('%c🎨 موقع Update Aden - تصميم رائع!', 'font-size: 20px; color: #2BB673; font-weight: bold;');
    console.log('%c✨ تم تطوير هذا الموقع بأحدث التقنيات', 'font-size: 14px; color: #666;');
});
