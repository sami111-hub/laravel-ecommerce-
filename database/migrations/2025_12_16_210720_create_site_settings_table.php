<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, boolean, number
            $table->string('group')->nullable(); // promo, general, social, etc
            $table->timestamps();
        });

        // إدراج الإعدادات الافتراضية
        DB::table('site_settings')->insert([
            [
                'key' => 'promo_bar_text',
                'value' => '🎉 عرض خاص اليوم! خصم 20% على جميع الهواتف | 📱 شحن مجاني للطلبات فوق 100$ | 🎁 هدية مع كل طلب',
                'type' => 'textarea',
                'group' => 'promo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'promo_bar_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'promo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
