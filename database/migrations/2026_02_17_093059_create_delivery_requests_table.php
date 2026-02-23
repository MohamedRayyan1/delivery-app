<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained()->cascadeOnDelete();

            $table->decimal('offered_delivery_fee', 8, 2); // السعر المعروض عالسائق
            $table->string('required_vehicle_type')->nullable(); // motor, car

            // حالة الطلب بالنسبة لهاد السائق
            $table->string('status')->default('pending')->index(); // pending, accepted, rejected, ignored

            $table->timestamps();

            // Index مركب للبحث السريع (جيبلي الطلبات المعلقة لهذا السائق)
            $table->index(['driver_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_requests');
    }
};
