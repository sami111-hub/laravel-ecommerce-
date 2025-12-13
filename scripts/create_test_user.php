<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$email = 'tester@example.com';
$exists = User::where('email', $email)->first();
if ($exists) {
    echo "User already exists: {$email}\n";
    exit(0);
}

$user = User::create([
    'name' => 'Automated Tester',
    'email' => $email,
    'password' => bcrypt('Test@1234')
]);

if ($user) {
    echo "Created user: {$email} with password Test@1234\n";
} else {
    echo "Failed to create user\n";
}
