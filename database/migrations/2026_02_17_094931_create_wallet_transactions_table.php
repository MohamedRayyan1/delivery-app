<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // العملية مرتبطة بطلب؟ (ممكن لأ، ممكن تكون شحن رصيد)
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();

            // نوع العملية (إيداع، سحب، دفع طلب، استرجاع، عمولة)
            $table->string('type')->index();

            $table->decimal('amount', 10, 2); // المبلغ (سالب أو موجب)

            // الرصيد بعد العملية (Snapshots) عشان ما نضطر نعيد حساب كلشي من الصفر
            $table->decimal('balance_after', 10, 2);

            $table->string('description')->nullable(); // شرح (تم دفع الطلب رقم #50)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
