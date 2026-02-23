<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // المفتاح (مثلاً: tax_percentage)
            $table->text('value'); // القيمة
            $table->string('description')->nullable(); // الوصف
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate(); // بهمنا تاريخ التعديل هون
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
