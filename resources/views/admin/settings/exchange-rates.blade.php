@extends('admin.layout')

@section('title', 'إعدادات أسعار الصرف')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-1"><i class="bi bi-currency-exchange text-success"></i> أسعار الصرف</h2>
                    <p class="text-muted mb-0">تحديد سعر صرف الدولار مقابل العملات المحلية</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- معاينة الأسعار -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-eye me-2"></i>معاينة - سعر منتج 100 دولار</h5>
                </div>
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center flex-wrap gap-3">
                        <div class="border rounded p-3" style="min-width: 150px;">
                            <div class="text-muted small mb-1">دولار أمريكي</div>
                            <div class="fs-4 fw-bold text-success">
                                <i class="bi bi-currency-dollar"></i> 100.00
                            </div>
                        </div>
                        <div class="border rounded p-3" style="min-width: 150px;">
                            <div class="text-muted small mb-1">ريال سعودي</div>
                            <div class="fs-4 fw-bold text-primary" id="preview-sar">
                                {{ number_format(100 * floatval($sarRate), 2) }} ر.س
                            </div>
                        </div>
                        <div class="border rounded p-3" style="min-width: 150px;">
                            <div class="text-muted small mb-1">ريال يمني</div>
                            <div class="fs-4 fw-bold text-warning" id="preview-yer">
                                {{ number_format(100 * floatval($yerRate), 0) }} ر.ي
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- نموذج التحديث -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>تعديل أسعار الصرف</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.exchange-rates.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- سعر الريال السعودي -->
                        <div class="mb-4">
                            <label for="exchange_rate_sar" class="form-label fw-bold">
                                <i class="bi bi-flag text-primary me-1"></i> 
                                سعر الدولار مقابل الريال السعودي
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary text-white">1 $ =</span>
                                <input type="number" 
                                       class="form-control form-control-lg text-center fw-bold" 
                                       id="exchange_rate_sar" 
                                       name="exchange_rate_sar" 
                                       value="{{ old('exchange_rate_sar', $sarRate) }}"
                                       step="0.01" 
                                       min="0.01" 
                                       required
                                       oninput="updatePreview()">
                                <span class="input-group-text bg-primary text-white">ر.س</span>
                            </div>
                            <div class="form-text">مثال: 3.75 يعني 1 دولار = 3.75 ريال سعودي</div>
                        </div>

                        <!-- سعر الريال اليمني -->
                        <div class="mb-4">
                            <label for="exchange_rate_yer" class="form-label fw-bold">
                                <i class="bi bi-flag text-warning me-1"></i>
                                سعر الدولار مقابل الريال اليمني
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-warning text-dark">1 $ =</span>
                                <input type="number" 
                                       class="form-control form-control-lg text-center fw-bold" 
                                       id="exchange_rate_yer" 
                                       name="exchange_rate_yer" 
                                       value="{{ old('exchange_rate_yer', $yerRate) }}"
                                       step="1" 
                                       min="1" 
                                       required
                                       oninput="updatePreview()">
                                <span class="input-group-text bg-warning text-dark">ر.ي</span>
                            </div>
                            <div class="form-text">مثال: 535 يعني 1 دولار = 535 ريال يمني</div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="bi bi-check-circle me-2"></i>
                                حفظ أسعار الصرف
                            </button>
                            <button type="reset" class="btn btn-outline-secondary" onclick="setTimeout(updatePreview, 10)">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                إعادة تعيين
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ملاحظة -->
            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>ملاحظة:</strong> تغيير أسعار الصرف هنا سيؤثر على عرض الأسعار في <strong>جميع صفحات الموقع</strong> فوراً.
            </div>

        </div>
    </div>
</div>

<script>
function updatePreview() {
    const sar = parseFloat(document.getElementById('exchange_rate_sar').value) || 0;
    const yer = parseFloat(document.getElementById('exchange_rate_yer').value) || 0;
    
    document.getElementById('preview-sar').textContent = new Intl.NumberFormat('en', {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(100 * sar) + ' ر.س';
    document.getElementById('preview-yer').textContent = new Intl.NumberFormat('en', {minimumFractionDigits: 0, maximumFractionDigits: 0}).format(100 * yer) + ' ر.ي';
}
</script>
@endsection
