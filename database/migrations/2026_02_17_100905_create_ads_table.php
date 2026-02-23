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
        Schema::create('ads', function (Blueprint $table) {
    $table->id();
    $table->foreignId('restaurant_id')->nullable()->constrained()->cascadeOnDelete();
    $table->string('image');
    $table->string('title')->nullable();
    $table->string('content')->nullable(); // رابط أو نص
    $table->string('status');
    $table->decimal('cost', 10, 2);
    // التحكم بالظهور
    $table->timestamp('start_date')->nullable();
    $table->timestamp('end_date')->nullable();
    $table->boolean('is_active')->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
