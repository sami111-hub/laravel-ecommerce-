<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phone_id')->constrained('phones')->onDelete('cascade');
            $table->string('region')->default('Aden');
            $table->string('currency', 3)->default('YER');
            $table->unsignedBigInteger('price');
            $table->string('source')->nullable();
            $table->date('effective_date');
            $table->boolean('is_current')->default(true);
            $table->timestamps();
            
            $table->index(['phone_id', 'region', 'effective_date']);
            $table->index(['phone_id', 'is_current']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_prices');
    }
};
