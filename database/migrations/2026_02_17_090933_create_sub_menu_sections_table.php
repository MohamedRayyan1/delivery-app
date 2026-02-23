<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_menu_sections', function (Blueprint $table) {
            $table->id();

            // الربط مع القسم الرئيسي
            $table->foreignId('section_id')->constrained('menu_sections')->cascadeOnDelete();

            $table->string('name');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_menu_sections');
    }
};
