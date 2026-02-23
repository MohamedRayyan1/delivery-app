<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // ربط السلة بالمستخدم
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // المطعم (عشان نمنع الطلب من مطعمين بنفس الوقت) - يقبل Null إذا السلة فارغة
            $table->foreignId('restaurant_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });

        // جدول عناصر السلة
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('menu_items')->cascadeOnDelete();

            $table->integer('quantity')->default(1);
            $table->string('notes')->nullable(); // (بدون مخلل، كتر ثوم...)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
