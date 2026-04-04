<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_item_extras', function (Blueprint $table) {
            $table->id();

            // الربط مع عنصر الطلب الأساسي
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();

            // الربط مع الإضافة (nullOnDelete لكي لا ينحذف الطلب إذا المطعم حذف الإضافة مستقبلاً)
            $table->foreignId('extra_id')->nullable()->constrained('item_extras')->nullOnDelete();

            // الـ Snapshot (حفظ الاسم والسعر وقت الطلب)
            $table->string('extra_name');
            $table->decimal('extra_price', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_extras');
    }
};
