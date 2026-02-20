<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('category_slug')->nullable()->after('is_active');
            $table->json('specifications')->nullable()->after('category_slug');
            $table->json('custom_specifications')->nullable()->after('specifications');
            $table->decimal('original_price', 10, 2)->nullable()->after('custom_specifications');
            $table->decimal('offer_price', 10, 2)->nullable()->after('original_price');
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['category_slug', 'specifications', 'custom_specifications', 'original_price', 'offer_price']);
        });
    }
};
