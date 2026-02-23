<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_create_users_table.php
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('phone')->unique()->index(); // مفهرس للبحث السريع
        $table->string('email')->nullable()->unique();
        $table->string('password');

        // الصلاحيات والبيانات الديموغرافية
        $table->string('role')->default('customer')->index(); // customer, driver, admin, restaurant_manager
        $table->string('city')->nullable()->index(); // لفلترة المحافظات

        // الإشعارات
        $table->string('fcm_token')->nullable();

        // نظام الحظر (الجديد)
        $table->boolean('is_banned')->default(false);

        $table->rememberToken();
        $table->timestamps();
        $table->softDeletes(); // للحفاظ على البيانات عند الحذف
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
