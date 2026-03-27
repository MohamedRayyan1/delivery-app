<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('favorite_restaurants', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // إضافة index
    $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete(); // إضافة index
    $table->timestamp('created_at')->useCurrent();

    $table->unique(['user_id', 'restaurant_id']);
});

Schema::create('favorite_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('item_id')->constrained('menu_items')->cascadeOnDelete();
    $table->timestamp('created_at')->useCurrent();

    $table->unique(['user_id', 'item_id']);
});
    }

    public function down(): void
    {
        Schema::dropIfExists('favorite_items');
        Schema::dropIfExists('favorite_restaurants');
    }
};
