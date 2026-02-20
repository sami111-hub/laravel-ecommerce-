@props(['price', 'size' => 'normal', 'showAll' => true])

@php
    $usdPrice = floatval($price);
    $sarRate = floatval(\App\Models\SiteSetting::get('exchange_rate_sar', '3.75'));
    $yerRate = floatval(\App\Models\SiteSetting::get('exchange_rate_yer', '535'));
    $sarPrice = $usdPrice * $sarRate;
    $yerPrice = $usdPrice * $yerRate;

    $sizeClasses = [
        'small' => ['main' => 'fs-6', 'sub' => 'small', 'gap' => '1'],
        'normal' => ['main' => 'fs-5', 'sub' => 'small', 'gap' => '2'],
        'large' => ['main' => 'fs-4', 'sub' => '', 'gap' => '2'],
    ];
    $s = $sizeClasses[$size] ?? $sizeClasses['normal'];
@endphp

<div class="multi-currency-price d-flex flex-wrap align-items-center gap-{{ $s['gap'] }}">
    <span class="badge bg-success {{ $s['main'] }} currency-badge" title="دولار أمريكي">
        <i class="bi bi-currency-dollar"></i> {{ number_format($usdPrice, 2) }}
    </span>
    @if($showAll)
    <span class="badge bg-primary {{ $s['sub'] }} currency-badge" title="ريال سعودي">
        {{ number_format($sarPrice, 2) }} ر.س
    </span>
    <span class="badge bg-warning text-dark {{ $s['sub'] }} currency-badge" title="ريال يمني">
        {{ number_format($yerPrice, 0) }} ر.ي
    </span>
    @endif
</div>
