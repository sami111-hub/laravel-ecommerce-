<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\Address;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        // التأكد من وجود مستخدمين ومنتجات
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد مستخدمين أو منتجات لإنشاء طلبات');
            return;
        }

        // إنشاء عناوين للمستخدمين
        foreach ($users as $user) {
            if (!$user->addresses()->exists()) {
                Address::create([
                    'user_id' => $user->id,
                    'label' => 'المنزل',
                    'phone' => '+967 777 ' . rand(100000, 999999),
                    'city' => 'عدن',
                    'area' => 'المعلا',
                    'street' => 'شارع الزبيري',
                    'building_number' => 'مبنى ' . rand(1, 50),
                    'floor' => (string)rand(1, 5),
                    'apartment' => (string)rand(1, 20),
                    'is_default' => true,
                ]);
            }
        }

        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $statusWeights = [40, 30, 25, 5]; // نسب الحالات

        // إنشاء 20 طلب تجريبي
        for ($i = 1; $i <= 20; $i++) {
            $user = $users->random();
            $address = $user->addresses()->first();
            
            if (!$address) {
                continue;
            }

            // اختيار حالة بناءً على الأوزان
            $random = rand(1, 100);
            $cumulative = 0;
            $status = 'pending';
            foreach ($statusWeights as $index => $weight) {
                $cumulative += $weight;
                if ($random <= $cumulative) {
                    $status = $statuses[$index];
                    break;
                }
            }

            // اختيار عدد عشوائي من المنتجات (1-5)
            $orderProducts = $products->random(rand(1, min(5, $products->count())));
            
            $subtotal = 0;
            $itemsData = [];
            
            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->price;
                $itemTotal = $price * $quantity;
                $subtotal += $itemTotal;
                
                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            $shipping = 5; // رسوم شحن ثابتة
            $total = $subtotal + $shipping;

            // إنشاء الطلب
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => $status,
                'phone' => $address->phone,
                'shipping_address' => json_encode([
                    'label' => $address->label,
                    'city' => $address->city,
                    'area' => $address->area,
                    'street' => $address->street,
                    'building_number' => $address->building_number,
                    'floor' => $address->floor,
                    'apartment' => $address->apartment,
                ]),
                'notes' => $status === 'cancelled' ? 'تم الإلغاء بطلب العميل' : null,
                'created_at' => now()->subDays(rand(0, 30)),
            ]);

            // إضافة منتجات الطلب
            foreach ($itemsData as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => Product::find($item['product_id'])->name,
                    'product_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }
        }

        $this->command->info('✅ تم إنشاء 20 طلب تجريبي');
        $this->command->info('📊 الإحصائيات:');
        $this->command->info('   - قيد الانتظار: ' . Order::where('status', 'pending')->count());
        $this->command->info('   - قيد المعالجة: ' . Order::where('status', 'processing')->count());
        $this->command->info('   - مكتملة: ' . Order::where('status', 'completed')->count());
        $this->command->info('   - ملغاة: ' . Order::where('status', 'cancelled')->count());
    }
}
