<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            // قسم المطعم (Nullable)
            $table->foreignId('restaurant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->decimal('restaurant_rating', 2, 1)->nullable(); // 1-5

            // قسم السائق (Nullable)
            $table->foreignId('driver_id')->nullable()->constrained()->cascadeOnDelete();
            $table->decimal('driver_rating', 2, 1)->nullable(); // 1-5

            $table->text('comment')->nullable();

            $table->timestamps();

            // ضمان عدم تكرار التقييم لنفس الطلب
            $table->unique(['user_id', 'order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
