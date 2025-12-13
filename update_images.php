<?php
// سكريبت مباشر لتحديث صور المنتجات

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

// مصفوفة الصور - صور حقيقية ومحددة لكل منتج
$imageMap = [
    // iPhone - صور حقيقية
    'iPhone 15 Pro Max' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-15-pro-max-blue-titanium-select.jpg',
    'iPhone 15 Pro' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-15-pro-blue-titanium-select.jpg',
    'iPhone 15' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-15-blue-select.jpg',
    'iPhone 14 Pro' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-14-pro-deep-purple-select.jpg',
    'iPhone 14' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-14-purple-select.jpg',
    'iPhone 13' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-13-pink-select-2021.jpg',
    'iPhone' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-15-blue-select.jpg',
    
    // Samsung Galaxy - صور من موقع Samsung
    'Galaxy S24 Ultra' => 'https://images.samsung.com/is/image/samsung/p6pim/ae/2401/gallery/ae-galaxy-s24-s928-sm-s928bzkgmea-thumb-539573208.png',
    'Galaxy S24' => 'https://images.samsung.com/is/image/samsung/p6pim/ae/2401/gallery/ae-galaxy-s24-s921-sm-s921blbgmea-thumb-539573129.png',
    'Galaxy S23 Ultra' => 'https://images.samsung.com/is/image/samsung/p6pim/levant/2302/gallery/levant-galaxy-s23-s918-sm-s918bzkemea-534859514.png',
    'Galaxy S23' => 'https://images.samsung.com/is/image/samsung/p6pim/levant/2302/gallery/levant-galaxy-s23-s911-sm-s911bzkgmea-534859446.png',
    'Galaxy A80' => 'https://images.samsung.com/is/image/samsung/p6pim/levant/sm-a805fzkdmid/gallery/levant-galaxy-a80-a805-sm-a805fzkdmid-frontblack-161934301.png',
    'Galaxy A54' => 'https://images.samsung.com/is/image/samsung/p6pim/ae/2303/gallery/ae-galaxy-a54-5g-a546-sm-a546elvgmea-534903069.png',
    'Galaxy Z Fold' => 'https://images.samsung.com/is/image/samsung/p6pim/ae/2308/gallery/ae-galaxy-z-fold5-f946-sm-f946bzkgmea-537404490.png',
    'Galaxy' => 'https://images.samsung.com/is/image/samsung/p6pim/ae/2401/gallery/ae-galaxy-s24-s921-sm-s921blbgmea-thumb-539573129.png',
    'Samsung' => 'https://images.samsung.com/is/image/samsung/p6pim/ae/2401/gallery/ae-galaxy-s24-s921-sm-s921blbgmea-thumb-539573129.png',
    
    // Xiaomi - صور من موقع Xiaomi
    'Xiaomi 14 Pro' => 'https://i02.appmifile.com/169_operator_sg/25/10/2023/c8f7be9d3d3f2c77e1e46e3d7aa32a20.png',
    'Xiaomi 14' => 'https://i02.appmifile.com/998_operator_sg/30/10/2023/52eb1eaa8aa92f8c09f7b153e5a7f85b.png',
    'Xiaomi 13' => 'https://i02.appmifile.com/mi-com-product/fly-birds/xiaomi-13/black.png',
    'Xiaomi Mi 11' => 'https://i02.appmifile.com/166_operator_sg/15/02/2021/5b79e36c4c18f2c8be9e9b3b7a2e2f98.png',
    'Redmi Note 12' => 'https://i02.appmifile.com/142_operator_sg/04/01/2023/4f99ab5b8c8d7d8b3e3e3b7a2e2f98.png',
    'Redmi' => 'https://i02.appmifile.com/142_operator_sg/04/01/2023/4f99ab5b8c8d7d8b3e3e3b7a2e2f98.png',
    'Xiaomi' => 'https://i02.appmifile.com/998_operator_sg/30/10/2023/52eb1eaa8aa92f8c09f7b153e5a7f85b.png',
    
    // Oppo
    'Oppo Find X7' => 'https://image.oppo.com/content/dam/oppo/product-asset-library/smartphone/find-x7/v1/assets/find-x7-purple-front.png',
    'Oppo Reno 8' => 'https://image.oppo.com/content/dam/oppo/product-asset-library/smartphone/reno8/v1/assets/reno8-black-front.png',
    'Oppo' => 'https://image.oppo.com/content/dam/oppo/product-asset-library/smartphone/reno8/v1/assets/reno8-black-front.png',
    
    // Realme
    'Realme GT 5' => 'https://image01.realme.net/general/20231027/1698393600358.png',
    'Realme' => 'https://image01.realme.net/general/20231027/1698393600358.png',
    
    // Huawei & Honor
    'Huawei' => 'https://consumer.huawei.com/content/dam/huawei-cbg-site/common/mkt/plp/phones-20230509/p60-pro-black.png',
    'Honor X9b' => 'https://www.hihonor.com/content/dam/honor/ae-en/product-asset-library/smartphone/honor-x9b-5g/specs/honor-x9b-5g-midnight-black.png',
    'Honor' => 'https://www.hihonor.com/content/dam/honor/ae-en/product-asset-library/smartphone/honor-x9b-5g/specs/honor-x9b-5g-midnight-black.png',
    
    // الهواتف العامة
    'تلفون' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-15-blue-select.jpg',
    'هاتف' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-15-blue-select.jpg',
    'مترو' => 'https://images.samsung.com/is/image/samsung/p6pim/ae/2401/gallery/ae-galaxy-s24-s921-sm-s921blbgmea-thumb-539573129.png',
    
    // MacBook - صور من Apple
    'MacBook Pro 16' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/mbp16-spacegray-select-202310.jpg',
    'MacBook Pro' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/mbp14-spacegray-select-202310.jpg',
    'MacBook Air M2' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/mba13-midnight-select-202402.jpg',
    'MacBook Air' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/mba13-midnight-select-202402.jpg',
    
    // Dell
    'Dell XPS 15' => 'https://i.dell.com/is/image/DellContent/content/dam/ss2/product-images/dell-client-products/notebooks/xps-notebooks/xps-15-9530/media-gallery/notebook-xps-15-9530-nt-blue-gallery-4.psd',
    'Dell Latitude' => 'https://i.dell.com/is/image/DellContent/content/dam/ss2/product-images/dell-client-products/notebooks/latitude-notebooks/latitude-14-5440/media-gallery/notebook-latitude-14-5440-gray-gallery-4.psd',
    'Dell' => 'https://i.dell.com/is/image/DellContent/content/dam/ss2/product-images/dell-client-products/notebooks/xps-notebooks/xps-15-9530/media-gallery/notebook-xps-15-9530-nt-blue-gallery-4.psd',
    
    // HP
    'HP Pavilion' => 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c08111257.png',
    'HP DeskJet' => 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c06561945.png',
    'HP LaserJet' => 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c08193408.png',
    'HP' => 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c08111257.png',
    
    // Lenovo
    'Lenovo ThinkPad' => 'https://p3-ofp.static.pub/fes/cms/2023/08/17/7m0s7c5t6q5h9r2w7p5e9h2w7p5e9h.png',
    'ThinkPad' => 'https://p3-ofp.static.pub/fes/cms/2023/08/17/7m0s7c5t6q5h9r2w7p5e9h2w7p5e9h.png',
    'Lenovo' => 'https://p3-ofp.static.pub/fes/cms/2023/08/17/7m0s7c5t6q5h9r2w7p5e9h2w7p5e9h.png',
    
    // Asus
    'Asus ROG' => 'https://dlcdnwebimgs.asus.com/gain/F2B1E5D8-C3A8-4E8E-8E8E-8E8E8E8E8E8E/w600/h600',
    'ROG' => 'https://dlcdnwebimgs.asus.com/gain/F2B1E5D8-C3A8-4E8E-8E8E-8E8E8E8E8E8E/w600/h600',
    'Asus' => 'https://dlcdnwebimgs.asus.com/gain/F2B1E5D8-C3A8-4E8E-8E8E-8E8E8E8E8E8E/w600/h600',
    
    // Apple Watch - صور من Apple
    'Apple Watch Ultra 2' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/watch-ultra-2-select-202309.jpg',
    'Apple Watch Series 9' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/watch-s9-aluminum-midnight-nc-45-front.jpg',
    'Apple Watch' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/watch-s9-aluminum-midnight-nc-45-front.jpg',
    
    // Samsung Watch
        'Galaxy Watch 6' => 'https://images.samsung.com/is/image/samsung/p6pim/ae/2307/gallery/ae-galaxy-watch6-r930-sm-r930nzsamea-thumb-537223318.png',
        'Galaxy Watch 5' => 'https://images.samsung.com/is/image/samsung/p6pim/levant/2208/gallery/levant-galaxy-watch5-r910-sm-r910nzaamea-thumb-533102239.png',
        'Galaxy Watch' => 'https://images.samsung.com/is/image/samsung/p6pim/ae/2307/gallery/ae-galaxy-watch6-r930-sm-r930nzsamea-thumb-537223318.png',
    
    // Xiaomi Watch
    'Xiaomi Smart Band 7' => 'https://i02.appmifile.com/821_operator_sg/26/05/2022/9e9e9b3b7a2e2f98.png',
    'Xiaomi Watch' => 'https://i02.appmifile.com/821_operator_sg/26/05/2022/9e9e9b3b7a2e2f98.png',
    'Watch' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/watch-s9-aluminum-midnight-nc-45-front.jpg',
    'ساعة' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/watch-s9-aluminum-midnight-nc-45-front.jpg',
    
    // الطابعات
    'ابسون' => 'https://media.epson.eu/s/e/products/ecotank-l3250/2.jpg',
    'Epson EcoTank' => 'https://media.epson.eu/s/e/products/ecotank-l3250/2.jpg',
    'Epson' => 'https://media.epson.eu/s/e/products/ecotank-l3250/2.jpg',
    'Canon Pixma' => 'https://in.canon/media/image/2023/06/14/a8f2c8c7e7e04c2a9e8e8e8e8e8e8e8e/pixma-g3730.png',
    'Canon' => 'https://in.canon/media/image/2023/06/14/a8f2c8c7e7e04c2a9e8e8e8e8e8e8e8e/pixma-g3730.png',
    'طابعة' => 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c06561945.png',
    
    // السماعات - صور حقيقية
    'AirPods Pro 2' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MQD83.jpg',
    'AirPods Pro' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MQD83.jpg',
    'AirPods' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MME73.jpg',
    'Sony WH-1000XM5' => 'https://www.sony.com/image/5d02da5df552836db894cabad685fb95.png',
    'Sony' => 'https://www.sony.com/image/5d02da5df552836db894cabad685fb95.png',
    'JBL Wave 200' => 'https://ae.jbl.com/dw/image/v2/AAUJ_PRD/on/demandware.static/-/Sites-masterCatalog_Harman/default/dw88e7e8e7/JBL_WAVE200TWS_ProductImage_Black_Front.png',
    'JBL' => 'https://ae.jbl.com/dw/image/v2/AAUJ_PRD/on/demandware.static/-/Sites-masterCatalog_Harman/default/dw88e7e8e7/JBL_WAVE200TWS_ProductImage_Black_Front.png',
    'Beats Studio Buds' => 'https://www.beatsbydre.com/content/dam/beats/web/product/earbuds/studio-buds/global/images/studio-buds-black.png',
    'Beats' => 'https://www.beatsbydre.com/content/dam/beats/web/product/earbuds/studio-buds/global/images/studio-buds-black.png',
    'سماعة' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MME73.jpg',
    'سماعات' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MME73.jpg',
    
    // الشواحن والإكسسوارات
    'Anker 65W' => 'https://d2j6dbq0eux0bg.cloudfront.net/images/66037618/2948388883.jpg',
    'شاحن Anker' => 'https://d2j6dbq0eux0bg.cloudfront.net/images/66037618/2948388883.jpg',
    'Anker' => 'https://d2j6dbq0eux0bg.cloudfront.net/images/66037618/2948388883.jpg',
    'Porodo' => 'https://porodo.net/cdn/shop/files/5_13ce2f9d-8e3e-4a3e-9e8e-8e8e8e8e8e8e.png',
    'شاحن' => 'https://d2j6dbq0eux0bg.cloudfront.net/images/66037618/2948388883.jpg',
    'Charger' => 'https://d2j6dbq0eux0bg.cloudfront.net/images/66037618/2948388883.jpg',
    
    // باور بانك
    'Powerology 20000mAh' => 'https://powerology.me/cdn/shop/files/PPBK20KBKAE_1.png',
    'Powerology' => 'https://powerology.me/cdn/shop/files/PPBK20KBKAE_1.png',
    'بطارية' => 'https://powerology.me/cdn/shop/files/PPBK20KBKAE_1.png',
    'باور بانك' => 'https://powerology.me/cdn/shop/files/PPBK20KBKAE_1.png',
    'Power Bank' => 'https://powerology.me/cdn/shop/files/PPBK20KBKAE_1.png',
    
    // الكوابل
    'كيبل USB-C' => 'https://d2j6dbq0eux0bg.cloudfront.net/images/66037618/3286719028.jpg',
    'كابل' => 'https://d2j6dbq0eux0bg.cloudfront.net/images/66037618/3286719028.jpg',
    'كيبل' => 'https://d2j6dbq0eux0bg.cloudfront.net/images/66037618/3286719028.jpg',
    'Cable' => 'https://d2j6dbq0eux0bg.cloudfront.net/images/66037618/3286719028.jpg',
    'USB' => 'https://d2j6dbq0eux0bg.cloudfront.net/images/66037618/3286719028.jpg',
    
    // الكاميرات
    'GoPro Hero 11' => 'https://gopro.com/content/dam/gopro/en-us/shop/cameras/hero11-black/pdp/hero11-black-front.png',
    'GoPro' => 'https://gopro.com/content/dam/gopro/en-us/shop/cameras/hero11-black/pdp/hero11-black-front.png',
    'Canon EOS' => 'https://i1.adis.ws/i/canon/eos-r6-mark-ii-body-front-on_01.png',
    'كاميرا' => 'https://gopro.com/content/dam/gopro/en-us/shop/cameras/hero11-black/pdp/hero11-black-front.png',
    
    // الميكروفون
    'Rode Wireless' => 'https://edge.rode.com/assets/product-images/rode-wireless-go-ii/rode_wirelessgoii_p01.png',
    'Rode' => 'https://edge.rode.com/assets/product-images/rode-wireless-go-ii/rode_wirelessgoii_p01.png',
    'ميكروفون' => 'https://edge.rode.com/assets/product-images/rode-wireless-go-ii/rode_wirelessgoii_p01.png',
    
    // الحافظات
    'حافظة Green Lion' => 'https://greenlionme.com/cdn/shop/products/Green-Lion-Magsafe-Foldable-Desktop-Stand-Black-1.jpg',
    'Green Lion' => 'https://greenlionme.com/cdn/shop/products/Green-Lion-Magsafe-Foldable-Desktop-Stand-Black-1.jpg',
    'حافظة Levelo' => 'https://levelome.com/cdn/shop/files/levelo-valero-plus-cover-for-iphone-15-pro-max-blue-1.jpg',
    'Levelo' => 'https://levelome.com/cdn/shop/files/levelo-valero-plus-cover-for-iphone-15-pro-max-blue-1.jpg',
    'حافظة' => 'https://greenlionme.com/cdn/shop/products/Green-Lion-Magsafe-Foldable-Desktop-Stand-Black-1.jpg',
    'جراب' => 'https://greenlionme.com/cdn/shop/products/Green-Lion-Magsafe-Foldable-Desktop-Stand-Black-1.jpg',
    'Case' => 'https://greenlionme.com/cdn/shop/products/Green-Lion-Magsafe-Foldable-Desktop-Stand-Black-1.jpg',
    
    // الذاكرة
    'Sandisk 128GB' => 'https://www.westerndigital.com/content/dam/store/en-us/assets/products/usb-flash-drives/sandisk-ultra-fit-usb-3-1/gallery/sandisk-ultra-fit-usb-3-1.png',
    'Sandisk' => 'https://www.westerndigital.com/content/dam/store/en-us/assets/products/usb-flash-drives/sandisk-ultra-fit-usb-3-1/gallery/sandisk-ultra-fit-usb-3-1.png',
    'ذاكرة' => 'https://www.westerndigital.com/content/dam/store/en-us/assets/products/usb-flash-drives/sandisk-ultra-fit-usb-3-1/gallery/sandisk-ultra-fit-usb-3-1.png',
    'Memory' => 'https://www.westerndigital.com/content/dam/store/en-us/assets/products/usb-flash-drives/sandisk-ultra-fit-usb-3-1/gallery/sandisk-ultra-fit-usb-3-1.png',
    
    // iPad
    'iPad' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-pro-11-select-wifi-spacegray-202210.jpg',
    'Tab' => 'https://images.samsung.com/is/image/samsung/p6pim/ae/sm-x210nzaemea/gallery/ae-galaxy-tab-a9-x210-sm-x210nzaemea-537860287.png',
];

$products = Product::all();
$updated = 0;
$skipped = 0;

foreach ($products as $product) {
    // تحديث جميع المنتجات بدون شرط
    // if (!empty($product->image) && str_starts_with($product->image, 'http')) {
    //     $skipped++;
    //     continue;
    // }
    
    $imageFound = false;
    
    // البحث عن تطابق
    foreach ($imageMap as $keyword => $imageUrl) {
        if (stripos($product->name, $keyword) !== false) {
            $product->image = $imageUrl;
            $product->save();
            $updated++;
            $imageFound = true;
            echo "✓ {$product->name}\n";
            break;
        }
    }
    
    // صورة افتراضية
    if (!$imageFound) {
        $product->image = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop';
        $product->save();
        $updated++;
        echo "⚠ {$product->name} (افتراضية)\n";
    }
}

echo "\n✅ تم تحديث: {$updated}\n";
echo "⏭ تم تخطي: {$skipped}\n";
echo "📊 الإجمالي: " . $products->count() . "\n";
