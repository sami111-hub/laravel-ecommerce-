<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-auth" content="{{ Auth::check() ? 'true' : 'false' }}">
    <title>@yield('title', 'متجر Update Aden - أفضل الأسعار والعروض')</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="@yield('description', 'متجر Update Aden الإلكتروني - تسوق أفضل المنتجات بأسعار مميزة والتوصيل إلى جميع أنحاء عدن')">
    <meta name="keywords" content="@yield('keywords', 'متجر إلكتروني, هواتف ذكية, لابتوبات, ساعات ذكية, إلكترونيات, عدن, اليمن, Update Aden')">
    <meta name="author" content="Update Aden">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', 'متجر Update Aden - أفضل الأسعار والعروض')">
    <meta property="og:description" content="@yield('description', 'متجر Update Aden الإلكتروني - تسوق أفضل المنتجات بأسعار مميزة والتوصيل إلى جميع أنحاء عدن')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:site_name" content="Update Aden">
    <meta property="og:locale" content="ar_AR">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'متجر Update Aden - أفضل الأسعار والعروض')">
    <meta name="twitter:description" content="@yield('description', 'متجر Update Aden الإلكتروني - تسوق أفضل المنتجات بأسعار مميزة والتوصيل إلى جميع أنحاء عدن')">
    <meta name="twitter:image" content="{{ asset('images/logo.png') }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Fonts and theme -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="{{ asset('css/color-system.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}?v={{ @filemtime(public_path('css/theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/smart-enhanced.css') }}?v={{ @filemtime(public_path('css/smart-enhanced.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/hybrid-design.css') }}?v={{ @filemtime(public_path('css/hybrid-design.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/enhancements.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/visual-enhancements.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/animated-backgrounds.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/professional-enhancements.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/premium-features.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/update-theme.css') }}?v={{ time() }}">
</head>
<body>
    @include('partials.top-nav-bar')
    @include('partials.promo-bar')
    @include('partials.topbar')
    @include('partials.header')
    @include('partials.delivery-bar')
    @include('partials.navbar')

    {{-- Fixed: Content wrapper without margins for clean spacing --}}
    @yield('content')

    @include('partials.support-footer')

    <footer class="bg-light py-3 mt-0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    <p class="mb-0 small text-muted">صُمم وطُور بـ <i class="bi bi-heart-fill text-danger"></i> في اليمن</p>
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/toast-notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/smart-enhanced.js') }}?v={{ @filemtime(public_path('js/smart-enhanced.js')) }}"></script>
    <script src="{{ asset('js/visual-enhancements.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/premium-features.js') }}?v={{ time() }}"></script>
    
    {{-- عرض رسائل Flash من Laravel --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showSuccess('{{ session('success') }}');
        });
    </script>
    @endif
    
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showError('{{ session('error') }}');
        });
    </script>
    @endif
    
    @include('partials.bottom-nav')
</body>
</html>
