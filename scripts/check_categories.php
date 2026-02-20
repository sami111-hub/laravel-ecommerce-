<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$cats = App\Models\Category::select('id','name','slug','is_active')->orderBy('id')->get();
echo "=== التصنيفات في قاعدة البيانات ===\n";
foreach($cats as $c) {
    $status = $c->is_active ? 'نشط' : 'غير نشط';
    echo "ID:{$c->id} | slug:{$c->slug} | {$c->name} | {$status}\n";
}
echo "\nالمجموع: " . $cats->count() . " تصنيف\n";
