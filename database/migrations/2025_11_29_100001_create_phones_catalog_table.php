<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('phone_brands')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->json('images')->nullable();
            $table->string('chipset')->nullable();
            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->decimal('display_size', 4, 2)->nullable();
            $table->integer('battery_mah')->unsigned()->nullable();
            $table->string('os')->nullable();
            $table->year('release_year')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('views')->default(0);
            $table->timestamps();
            
            $table->index(['brand_id', 'is_active']);
            $table->index('slug');
            $table->index('release_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};
