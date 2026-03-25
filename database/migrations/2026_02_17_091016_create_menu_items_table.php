<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();

            // الربط مع القسم الفرعي
            $table->foreignId('sub_section_id')->constrained('sub_menu_sections')->cascadeOnDelete();

            $table->string('name')->index();
            $table->text('description')->nullable();

            $table->decimal('price', 8, 2);
            $table->decimal('discount_price', 8, 2)->nullable();

            $table->string('image')->nullable();

            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_available')->default(true)->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
