<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_flash_deal')) {
                $table->boolean('is_flash_deal')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('products', 'flash_deal_discount')) {
                $table->integer('flash_deal_discount')->nullable()->after('is_flash_deal');
            }
            if (!Schema::hasColumn('products', 'flash_deal_price')) {
                $table->decimal('flash_deal_price', 10, 2)->nullable()->after('flash_deal_discount');
            }
            if (!Schema::hasColumn('products', 'flash_deal_ends_at')) {
                $table->timestamp('flash_deal_ends_at')->nullable()->after('flash_deal_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_flash_deal', 'flash_deal_discount', 'flash_deal_price', 'flash_deal_ends_at']);
        });
    }
};
