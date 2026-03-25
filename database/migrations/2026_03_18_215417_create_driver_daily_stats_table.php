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
        Schema::create('driver_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->date('stat_date')->index(); // فهرس للبحث السريع حسب التاريخ
            $table->decimal('earnings', 12, 2)->default(0);
            $table->unsignedInteger('completed_orders')->default(0);
            $table->decimal('rating_sum', 10, 2)->default(0); // لتخزين مجموع التقييمات
            $table->unsignedInteger('rating_count')->default(0); // لتخزين عدد التقييمات
            $table->timestamps();
            
            // منع التكرار: سجل واحد لكل سائق في كل يوم
            $table->unique(['driver_id', 'stat_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_daily_stats');
    }
};
