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
Schema::create('user_addresses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();

    $table->string('label')->nullable(); // البيت، العمل
    $table->string('street');
    $table->string('details')->nullable(); // مقابل الصيدلية...
    $table->string('floor')->nullable();
    $table->string('phone')->nullable(); // رقم احتياطي

    // دقة عالية للخرائط (10 أرقام، 8 منها بعد الفاصلة)
    $table->decimal('lat', 10, 8);
    $table->decimal('lng', 11, 8);

    $table->boolean('is_default')->default(false);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
