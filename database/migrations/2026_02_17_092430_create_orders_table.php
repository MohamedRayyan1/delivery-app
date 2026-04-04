<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // العلاقات
            $table->foreignId('user_id')->constrained();
            $table->foreignId('restaurant_id')->constrained();
            $table->foreignId('driver_id')->nullable()->constrained();
            $table->foreignId('address_id')->constrained('user_addresses');
            $table->foreignId('extra_id')->nullable()->constrained('item_extras')->nullOnDelete();

            $table->integer('coupon_id')->nullable(); // مؤقتاً

            // الحالة
            $table->string('delivery_confirmation_code')->nullable();
            $table->enum('status', [
                'pending',          // قيد الانتظار (عند وصول الطلب)
                'preparing',        // قيد التحضير (عند قبول المطعم)
                'picked_up',        // قيد التوصيل (عندما يأخذ السائق الطلب)
                'delivered'         // تم التوصيل (عندما يستلمه الزبون)
            ])->default('pending')->index();
            // الدفع
            $table->string('payment_method');
            $table->string('payment_status')->default('unpaid')->index();
            $table->string('transaction_ref')->nullable();

            // الأرقام المالية (الفاتورة)
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);

            // النسب المستخدمة وقت الطلب (Snapshots) للحساب لاحقاً
            // ملاحظة: سنفترض أن هذه نسب مئوية (مثلاً 10.00%)
            $table->decimal('applied_restaurant_commission', 5, 2)->default(0);
            $table->decimal('applied_driver_share', 5, 2)->default(100); // 100% للسائق افتراضياً

            // التوقيت
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
