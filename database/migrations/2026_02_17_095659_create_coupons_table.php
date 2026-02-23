<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique()->index(); // الرمز (رمضان2026)
            $table->string('discount_type'); // percent, fixed
            $table->decimal('value', 8, 2); // 10% or 500 SYP

            // شروط الكوبون
            $table->decimal('min_order_price', 8, 2)->nullable();
            $table->timestamp('expiry_date');
            $table->integer('usage_limit')->nullable(); // كم مرة مسموح يستخدم بالمجمل

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
