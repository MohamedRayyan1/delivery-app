<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();

            // فهرسة رقم الهاتف لتسريع البحث عند التحقق
            $table->string('phone')->index();
            $table->string('code');
            $table->boolean('is_used')->default(false);

            // تاريخ انتهاء الصلاحية ضروري جداً للأمان
            $table->timestamp('expires_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
