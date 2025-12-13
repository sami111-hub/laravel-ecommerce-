{{-- Top Navigation Bar مستوحى من Bazzarry --}}
<nav class="top-nav-bar">
    <div class="container">
        <ul class="top-nav-links">
            <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">الرئيسية</a></li>
            <li><a href="{{ route('products.index') }}" class="{{ request()->is('products*') ? 'active' : '' }}">الأقسام</a></li>
            <li><a href="{{ route('offers') }}" class="{{ request()->is('offers') ? 'active' : '' }}">أفضل العروض</a></li>
            <li>
                <a href="{{ route('products.index', ['sort' => 'best-selling']) }}" class="{{ request()->get('sort') == 'best-selling' ? 'active' : '' }}">
                    الأكثر مبيعاً
                </a>
            </li>
            <li><a href="{{ route('products.index', ['filter' => 'new']) }}" class="{{ request()->get('filter') == 'new' ? 'active' : '' }}">وصل حديثاً</a></li>
            @guest
            <li><a href="{{ route('register') }}">تسجيل جديد</a></li>
            @endguest
        </ul>
    </div>
</nav>
