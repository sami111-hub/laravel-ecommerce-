<header class="main-header">
    <div class="container d-flex justify-content-between align-items-center py-3">
        <a href="/" class="logo-link">
            @include('partials.smart-logo')
        </a>
        <form class="search-form-enhanced" method="GET" action="{{ route('products.index') }}">
            <div class="search-input-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input type="text" 
                       name="search" 
                       placeholder="ابحث عن أي منتج تريده..." 
                       value="{{ request('search') }}" 
                       class="search-input"
                       oninput="liveSearch(this)"
                       autocomplete="off">
                <button type="submit" class="search-btn" aria-label="بحث">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
        </form>
        <div class="header-icons d-flex align-items-center gap-2 gap-md-3">
            @auth
                @php
                    $user = Auth::user()->load('role');
                    $userRole = $user->role;
                    $isAdmin = false;
                    if ($userRole) {
                        $isAdmin = in_array($userRole->slug, ['admin', 'super-admin']);
                    }
                    // Also check multiple roles
                    if (!$isAdmin) {
                        $isAdmin = $user->hasRole('admin') || $user->hasRole('super-admin') || $user->isAdmin();
                    }
                @endphp
                @if($isAdmin || $user->role_id)
                    <a href="{{ route('admin.dashboard') }}" class="header-btn btn-admin">
                        <i class="bi bi-speedometer2"></i>
                        <span class="d-none d-md-inline">لوحة الإدارة</span>
                    </a>
                @endif
                <a href="{{ route('dashboard.index') }}" class="header-btn btn-user">
                    <i class="bi bi-person-circle"></i>
                    <span class="d-none d-md-inline">{{ Str::limit(Auth::user()->name, 15) }}</span>
                </a>
                <a href="{{ route('offers') }}" class="header-btn btn-offers">
                    <i class="bi bi-tag-fill"></i>
                    <span class="d-none d-md-inline">العروض</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="header-btn btn-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-md-inline">خروج</span>
                    </button>
                </form>
            @else
                <button type="button" class="header-btn btn-login" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="bi bi-person-fill"></i>
                    <span>تسجيل الدخول</span>
                </button>
            @endauth
            <a href="{{ route('compare.index') }}" class="header-btn btn-compare">
                <i class="bi bi-arrow-left-right"></i>
                <span class="compare-badge">{{ count(session('compare', [])) }}</span>
                <span class="d-none d-md-inline">المقارنة</span>
            </a>
            <a href="/cart" class="header-btn btn-cart">
                <i class="bi bi-cart3"></i>
                <span class="cart-badge">{{ Auth::check() ? Auth::user()->carts()->sum('quantity') : 0 }}</span>
                <span class="d-none d-md-inline">السلة</span>
            </a>
        </div>
    </div>
</header>

<!-- Login Modal -->
@guest
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">تسجيل الدخول</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    <div class="mb-3">
                        <label for="modal_email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="modal_email" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="modal_password" class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="modal_password" name="password" required autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">تسجيل الدخول</button>
                </form>
                <div class="text-center mt-3">
                    <p class="mb-0">ليس لديك حساب؟ <a href="{{ route('register') }}" onclick="document.getElementById('loginModal').querySelector('.btn-close').click()">سجل الآن</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle login form errors - reopen modal if there are errors
    @if($errors->has('email') || $errors->has('password'))
        setTimeout(function() {
            var loginModalElement = document.getElementById('loginModal');
            if (loginModalElement) {
                var loginModal = new bootstrap.Modal(loginModalElement);
                loginModal.show();
            }
        }, 100);
    @endif
});
</script>
@endguest