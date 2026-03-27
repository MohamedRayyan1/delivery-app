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
            $table->foreignId('driver_id')->nullable()->constrained()->cascadeOnDelete();

            $table->decimal('offered_delivery_fee', 8, 2);
            $table->string('required_vehicle_type')->nullable();
            // pending: قيد الانتظار | accepted: مقبول | picked_up: تم الاستلام | delivered: تم التوصيل
            $table->enum('status', ['pending', 'accepted', 'picked_up', 'delivered'])->default('pending')->index();

            $table->timestamps();

            $table->index(['driver_id', 'status']);
            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_requests');
    }
};
