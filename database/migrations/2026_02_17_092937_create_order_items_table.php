<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('menu_items'); // عشان نعرف شو الوجبة

            $table->integer('quantity');

            // بنسجل السعر وقت الطلب (Snapshot)
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
