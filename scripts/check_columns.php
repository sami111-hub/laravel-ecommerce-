<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['products', 'categories', 'brands', 'roles', 'permissions', 'offers'];
foreach ($tables as $table) {
    $columns = Schema::getColumnListing($table);
    $hasIsActive = in_array('is_active', $columns) ? 'YES' : 'NO';
    echo "$table: is_active=$hasIsActive | columns: " . implode(', ', $columns) . "\n";
}
