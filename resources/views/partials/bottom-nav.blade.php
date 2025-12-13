<nav class="bottom-nav" dir="rtl">
    <div class="items">
        <a href="/" class="{{ request()->is('/') || request()->routeIs('products.index') ? 'active' : '' }}">
            <span>🏠</span>
            <small>الرئيسية</small>
        </a>
        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
            <span>🛍️</span>
            <small>التصنيفات</small>
        </a>
        <a href="/offers" class="{{ request()->is('offers') ? 'active' : '' }}">
            <span>🔥</span>
            <small>العروض</small>
        </a>
        <a href="{{ route('cart.index') }}" class="{{ request()->routeIs('cart.index') ? 'active' : '' }}">
            <span>🛒</span>
            <small>
                السلة
                @if(session()->has('cart_count') && session('cart_count') > 0)
                    <span class="badge">{{ session('cart_count') }}</span>
                @endif
            </small>
        </a>
        @auth
        <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <span>👤</span>
            <small>حسابي</small>
        </a>
        @else
        <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">
            <span>👤</span>
            <small>الحساب</small>
        </a>
        @endauth
    </div>
</nav>
