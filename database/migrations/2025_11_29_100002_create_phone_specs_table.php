<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_specs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phone_id')->constrained('phones')->onDelete('cascade');
            $table->string('group'); // الشاشة، المعالج، الذاكرة، الكاميرات، البطارية، الاتصال، النظام
            $table->string('key');
            $table->text('value');
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->unique(['phone_id', 'group', 'key']);
            $table->index(['phone_id', 'group']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_specs');
    }
};
