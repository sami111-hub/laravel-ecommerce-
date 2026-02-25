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
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->after('product_id')
                  ->constrained('product_variants')->nullOnDelete();
            
            // تحديث الـ unique constraint: منتج + موديل + مستخدم
            $table->unique(['user_id', 'product_id', 'variant_id'], 'carts_user_product_variant_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique('carts_user_product_variant_unique');
            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
        });
    }
};
