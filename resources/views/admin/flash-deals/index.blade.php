@extends('admin.layout')
@section('title', 'إدارة عروض اليوم')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>إدارة عروض اليوم</h4>
            <p class="text-muted mb-0">تحكم يدوي كامل في المنتجات والأسعار والخصومات في قسم "عروض اليوم"</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary">
            <i class="bi bi-eye me-1"></i> معاينة الموقع
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- العمود الأيمن - الإعدادات --}}
        <div class="col-lg-4">
            {{-- إعدادات العرض --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning bg-opacity-10 border-0">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>إعدادات العرض</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.flash-deals.update-settings') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">عنوان القسم</label>
                            <input type="text" name="title" value="{{ $settings['title'] }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ $settings['is_active'] == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="isActive">تفعيل عروض اليوم</label>
                            </div>
                            <small class="text-muted">عند الإيقاف لن يظهر القسم في الصفحة الرئيسية</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-calendar-event me-1 text-primary"></i>تاريخ انتهاء العرض</label>
                            <input type="date" name="end_date" value="{{ $settings['end_date'] }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-clock me-1 text-info"></i>وقت الانتهاء</label>
                            <input type="time" name="end_time" value="{{ $settings['end_time'] }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-grid-3x3 me-1 text-success"></i>عدد المنتجات المعروضة</label>
                            <input type="number" name="max_products" value="{{ $settings['max_products'] }}" class="form-control" min="1" max="20" required>
                            <small class="text-muted">الحد الأقصى للمنتجات في الصفحة الرئيسية</small>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-bold">
                            <i class="bi bi-check-lg me-1"></i> حفظ الإعدادات
                        </button>
                    </form>
                </div>
            </div>

            {{-- إحصائيات --}}
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-primary bg-opacity-10 rounded-3 text-center">
                                <h3 class="mb-0 text-primary">{{ $flashProducts->count() }}</h3>
                                <small class="text-muted">منتج في العرض</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-success bg-opacity-10 rounded-3 text-center">
                                <h3 class="mb-0 text-success">{{ $flashProducts->count() > 0 ? round($flashProducts->avg('flash_deal_discount')) : 0 }}%</h3>
                                <small class="text-muted">متوسط الخصم</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-warning bg-opacity-10 rounded-3 text-center">
                                @php
                                    $totalSaved = $flashProducts->sum(function($p) {
                                        return $p->price - ($p->flash_deal_price ?? $p->price);
                                    });
                                @endphp
                                <h3 class="mb-0 text-warning">${{ number_format($totalSaved, 0) }}</h3>
                                <small class="text-muted">إجمالي التوفير</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 {{ $settings['is_active'] == '1' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 rounded-3 text-center">
                                <h3 class="mb-0 {{ $settings['is_active'] == '1' ? 'text-success' : 'text-danger' }}">
                                    <i class="bi bi-{{ $settings['is_active'] == '1' ? 'check-circle' : 'x-circle' }}"></i>
                                </h3>
                                <small class="text-muted">{{ $settings['is_active'] == '1' ? 'مفعّل' : 'متوقف' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- العمود الأيسر - المنتجات --}}
        <div class="col-lg-8">
            {{-- إضافة منتج --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success bg-opacity-10 border-0">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2 text-success"></i>إضافة منتج لعروض اليوم</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.flash-deals.add-product') }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-bold">اختر المنتج</label>
                                <select name="product_id" id="productSelect" class="form-select" required>
                                    <option value="">-- اختر منتج --</option>
                                    @foreach($availableProducts as $ap)
                                        <option value="{{ $ap->id }}" data-price="{{ $ap->price }}" data-image="{{ $ap->image }}">
                                            {{ $ap->name }} - ${{ number_format($ap->price, 2) }}{{ $ap->brand ? ' | ' . $ap->brand->name : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">نسبة الخصم</label>
                                <div class="input-group">
                                    <input type="number" name="discount" id="discountInput" class="form-control" min="1" max="99" value="20" required>
                                    <span class="input-group-text fw-bold">%</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div id="pricePreview" class="d-none mb-2">
                                    <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                        <span class="text-decoration-line-through text-muted" id="previewOldPrice"></span>
                                        <i class="bi bi-arrow-left text-danger"></i>
                                        <span class="fw-bold text-success" id="previewNewPrice"></span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success w-100 fw-bold" id="addBtn" disabled>
                                    <i class="bi bi-plus-lg me-1"></i> إضافة للعرض
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- قائمة المنتجات --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-stars me-2 text-warning"></i>منتجات العرض ({{ $flashProducts->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    @if($flashProducts->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 3.5rem;"></i>
                            <h5 class="text-muted mt-3">لا توجد منتجات في عروض اليوم</h5>
                            <p class="text-muted">ابحث عن منتج أعلاه وأضفه يدوياً للعرض مع تحديد نسبة الخصم</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 55px;"></th>
                                        <th>المنتج</th>
                                        <th class="text-center">السعر الأصلي</th>
                                        <th class="text-center">الخصم</th>
                                        <th class="text-center">سعر العرض</th>
                                        <th class="text-center">التوفير</th>
                                        <th class="text-center">ينتهي</th>
                                        <th class="text-center" style="width: 140px;">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($flashProducts as $product)
                                        @php
                                            $flashPrice = $product->flash_deal_price ?? round($product->price * (1 - ($product->flash_deal_discount / 100)), 2);
                                            $saved = $product->price - $flashPrice;
                                        @endphp
                                        <tr>
                                            <td>
                                                <img src="{{ $product->image ?? 'https://via.placeholder.com/50' }}" 
                                                     alt="{{ $product->name }}" class="rounded-2 border" 
                                                     style="width: 48px; height: 48px; object-fit: cover;">
                                            </td>
                                            <td>
                                                <a href="{{ route('products.show', $product) }}" target="_blank" class="text-decoration-none text-dark fw-bold">
                                                    {{ Str::limit($product->name, 35) }}
                                                </a>
                                                @if($product->category)
                                                    <br><small class="text-muted"><i class="bi bi-folder2 me-1"></i>{{ $product->category->name }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('admin.flash-deals.update-product', $product) }}" method="POST" class="d-inline-flex align-items-center gap-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="input-group input-group-sm" style="width: 90px;">
                                                        <input type="number" name="discount" value="{{ $product->flash_deal_discount }}" class="form-control form-control-sm text-center" min="1" max="99">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <button type="submit" class="btn btn-outline-primary btn-sm" title="تحديث الخصم">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <strong class="text-success fs-6">${{ number_format($flashPrice, 2) }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger bg-opacity-10 text-danger">${{ number_format($saved, 2) }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($product->flash_deal_ends_at)
                                                    @if($product->flash_deal_ends_at->isPast())
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>منتهي</span>
                                                    @else
                                                        <small class="text-muted">{{ $product->flash_deal_ends_at->format('m/d H:i') }}</small>
                                                    @endif
                                                @else
                                                    <small class="text-muted">—</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('admin.flash-deals.remove-product', $product) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('إزالة {{ Str::limit($product->name, 20) }} من العرض؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="bi bi-trash3 me-1"></i>إزالة
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">المجموع:</td>
                                        <td class="text-center"><strong>{{ round($flashProducts->avg('flash_deal_discount')) }}%</strong> <small class="text-muted">متوسط</small></td>
                                        <td class="text-center text-success fw-bold">${{ number_format($flashProducts->sum('flash_deal_price'), 2) }}</td>
                                        <td class="text-center text-danger fw-bold">${{ number_format($totalSaved, 2) }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var productSelect = document.getElementById('productSelect');
    var addBtn = document.getElementById('addBtn');
    var discountInput = document.getElementById('discountInput');
    var pricePreview = document.getElementById('pricePreview');
    var previewOldPrice = document.getElementById('previewOldPrice');
    var previewNewPrice = document.getElementById('previewNewPrice');
    var selectedPrice = 0;

    // عند اختيار منتج من القائمة
    productSelect.addEventListener('change', function() {
        var selected = this.options[this.selectedIndex];
        if (this.value) {
            selectedPrice = parseFloat(selected.getAttribute('data-price')) || 0;
            addBtn.disabled = false;
            updatePricePreview();
        } else {
            selectedPrice = 0;
            addBtn.disabled = true;
            pricePreview.classList.add('d-none');
        }
    });

    // معاينة السعر عند تغيير الخصم
    discountInput.addEventListener('input', updatePricePreview);

    function updatePricePreview() {
        if (selectedPrice > 0) {
            var disc = parseInt(discountInput.value) || 0;
            var newPrice = selectedPrice * (1 - disc / 100);
            previewOldPrice.textContent = '$' + selectedPrice.toFixed(2);
            previewNewPrice.textContent = '$' + newPrice.toFixed(2);
            pricePreview.classList.remove('d-none');
        }
    }
});
</script>
@endsection
