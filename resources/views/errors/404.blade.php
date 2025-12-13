@extends('layout')

@section('title', '404 - الصفحة غير موجودة')

@section('content')
<div class="error-page-wrapper" style="min-height: 70vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 text-center">
                <div class="error-content bg-white rounded-4 shadow-lg p-5">
                    <!-- رقم الخطأ -->
                    <h1 class="display-1 fw-bold text-primary mb-3" style="font-size: 8rem; line-height: 1;">
                        404
                    </h1>
                    
                    <!-- أيقونة -->
                    <div class="mb-4">
                        <i class="bi bi-emoji-frown text-warning" style="font-size: 4rem;"></i>
                    </div>
                    
                    <!-- العنوان -->
                    <h2 class="h3 fw-bold mb-3">عذراً! الصفحة غير موجودة</h2>
                    
                    <!-- الوصف -->
                    <p class="text-muted mb-4">
                        يبدو أن الصفحة التي تبحث عنها قد تم نقلها أو حذفها أو ربما لم تكن موجودة من الأساس.
                    </p>
                    
                    <!-- الأزرار -->
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-house-door"></i> العودة للرئيسية
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg px-4">
                            <i class="bi bi-grid-3x3-gap"></i> تصفح المنتجات
                        </a>
                    </div>
                    
                    <!-- روابط مفيدة -->
                    <div class="mt-5 pt-4 border-top">
                        <p class="text-muted small mb-3">أو جرب هذه الروابط:</p>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('categories.index') }}" class="text-decoration-none">
                                <i class="bi bi-tag"></i> التصنيفات
                            </a>
                            <a href="{{ route('offers') }}" class="text-decoration-none">
                                <i class="bi bi-gift"></i> العروض
                            </a>
                            <a href="{{ route('about') }}" class="text-decoration-none">
                                <i class="bi bi-info-circle"></i> من نحن
                            </a>
                            <a href="{{ route('contact') }}" class="text-decoration-none">
                                <i class="bi bi-envelope"></i> اتصل بنا
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page-wrapper {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.error-content {
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.error-content h1 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.error-content .btn {
    transition: all 0.3s ease;
}

.error-content .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>
@endsection
