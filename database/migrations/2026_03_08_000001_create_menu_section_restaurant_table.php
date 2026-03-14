<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_section_restaurant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_section_id')->constrained('menu_sections')->cascadeOnDelete();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('menu_section_restaurant');
    }
};

