{{-- قسم تسوق حسب الفئة مع أيقونات ملونة --}}
<div class="shop-by-category" id="categories">
    <div class="container">
        <div class="section-header-deal mb-2">
            <div class="deal-title-group">
                <i class="bi bi-grid-3x3-gap-fill" style="color: #FF6B00;"></i>
                <h2 class="section-title-deal">تسوق حسب الفئة</h2>
                <span class="deal-subtitle">اختر من بين الفئات المميزة</span>
            </div>
        </div>
        
        <div class="category-grid-compact">
            {{-- إلكترونيات --}}
            <a href="{{ route('products.index', ['category' => 'electronics']) }}" class="category-item" data-category="electronics">
                <div class="category-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="bi bi-laptop"></i>
                </div>
                <h3 class="category-name">إلكترونيات</h3>
                <p class="category-count">{{ $categoryCounts['electronics'] ?? 0 }} منتج</p>
                <div class="category-hover-effect"></div>
            </a>

            {{-- سوبر ماركت --}}
            <a href="{{ route('products.index', ['category' => 'supermarket']) }}" class="category-item" data-category="supermarket">
                <div class="category-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="bi bi-basket2-fill"></i>
                </div>
                <h3 class="category-name">سوبر ماركت</h3>
                <p class="category-count">{{ $categoryCounts['supermarket'] ?? 0 }} منتج</p>
                <div class="category-hover-effect"></div>
            </a>

            {{-- المنزل والمطبخ --}}
            <a href="{{ route('products.index', ['category' => 'home']) }}" class="category-item" data-category="home">
                <div class="category-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="bi bi-house-door-fill"></i>
                </div>
                <h3 class="category-name">المنزل والمطبخ</h3>
                <p class="category-count">{{ $categoryCounts['home'] ?? 0 }} منتج</p>
                <div class="category-hover-effect"></div>
            </a>

            {{-- الجمال والصحة --}}
            <a href="{{ route('products.index', ['category' => 'beauty']) }}" class="category-item" data-category="beauty">
                <div class="category-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <h3 class="category-name">الجمال والصحة</h3>
                <p class="category-count">{{ $categoryCounts['beauty'] ?? 0 }} منتج</p>
                <div class="category-hover-effect"></div>
            </a>

            {{-- الأزياء --}}
            <a href="{{ route('products.index', ['category' => 'fashion']) }}" class="category-item" data-category="fashion">
                <div class="category-icon" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
                    <i class="bi bi-bag-heart-fill"></i>
                </div>
                <h3 class="category-name">الأزياء</h3>
                <p class="category-count">{{ $categoryCounts['fashion'] ?? 0 }} منتج</p>
                <div class="category-hover-effect"></div>
            </a>

            {{-- الرياضة --}}
            <a href="{{ route('products.index', ['category' => 'sports']) }}" class="category-item" data-category="sports">
                <div class="category-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                    <i class="bi bi-bicycle"></i>
                </div>
                <h3 class="category-name">الرياضة</h3>
                <p class="category-count">{{ $categoryCounts['sports'] ?? 0 }} منتج</p>
                <div class="category-hover-effect"></div>
            </a>

            {{-- الألعاب --}}
            <a href="{{ route('products.index', ['category' => 'toys']) }}" class="category-item" data-category="toys">
                <div class="category-icon" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                    <i class="bi bi-controller"></i>
                </div>
                <h3 class="category-name">الألعاب</h3>
                <p class="category-count">{{ $categoryCounts['toys'] ?? 0 }} منتج</p>
                <div class="category-hover-effect"></div>
            </a>

            {{-- الكتب --}}
            <a href="{{ route('products.index', ['category' => 'books']) }}" class="category-item" data-category="books">
                <div class="category-icon" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                    <i class="bi bi-book-fill"></i>
                </div>
                <h3 class="category-name">الكتب</h3>
                <p class="category-count">{{ $categoryCounts['books'] ?? 0 }} منتج</p>
                <div class="category-hover-effect"></div>
            </a>
        </div>
    </div>
</div>

<style>
/* قسم الفئات المحسّن بدون مساحات */
.category-grid-compact {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.category-item {
    position: relative;
    background: #fff;
    border-radius: 12px;
    padding: 20px 15px;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
    overflow: hidden;
}

.category-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-color: #FF6B00;
}

.category-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 12px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.category-item:hover .category-icon {
    transform: scale(1.1) rotate(5deg);
}

.category-icon i {
    font-size: 32px;
    color: white;
}

.category-name {
    font-size: 15px;
    font-weight: 600;
    color: #2c3e50;
    margin: 0 0 5px 0;
    transition: color 0.3s ease;
}

.category-item:hover .category-name {
    color: #FF6B00;
}

.category-count {
    font-size: 12px;
    color: #7f8c8d;
    margin: 0;
}

.category-hover-effect {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: #FF6B00;
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.category-item:hover .category-hover-effect {
    transform: scaleX(1);
}

@media (max-width: 768px) {
    .category-grid-compact {
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
    
    .category-item {
        padding: 15px 10px;
    }
    
    .category-icon {
        width: 55px;
        height: 55px;
    }
    
    .category-icon i {
        font-size: 24px;
    }
    
    .category-name {
        font-size: 13px;
    }
    
    .category-count {
        font-size: 11px;
    }
}

@media (max-width: 480px) {
    .category-grid-compact {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>