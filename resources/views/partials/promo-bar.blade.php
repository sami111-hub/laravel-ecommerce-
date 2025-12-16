{{-- شريط الترويجات المتحرك في أعلى الصفحة --}}
@php
    $promoText = \App\Models\SiteSetting::get('promo_bar_text', '🎉 عرض خاص اليوم! خصم 20% على جميع الهواتف | 📱 شحن مجاني للطلبات فوق 100$ | 🎁 هدية مع كل طلب');
    $promoEnabled = \App\Models\SiteSetting::get('promo_bar_enabled', '1');
@endphp

@if($promoEnabled == '1')
<div class="promo-bar">
    <div class="promo-bar-content">
        <div class="promo-text">
            @foreach(explode('|', $promoText) as $item)
                <span class="promo-item">{{ trim($item) }}</span>
                @if(!$loop->last)
                    <span class="promo-separator">•</span>
                @endif
            @endforeach
        </div>
        {{-- تكرار النص للحركة المستمرة --}}
        <div class="promo-text" aria-hidden="true">
            @foreach(explode('|', $promoText) as $item)
                <span class="promo-item">{{ trim($item) }}</span>
                @if(!$loop->last)
                    <span class="promo-separator">•</span>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endif
