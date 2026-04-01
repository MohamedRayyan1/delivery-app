<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_menu_sections', function (Blueprint $table) {
            $table->foreignId('menu_section_id')
                  ->nullable()
                  ->after('restaurant_id')
                  ->constrained('menu_sections')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sub_menu_sections', function (Blueprint $table) {
            $table->dropForeign(['menu_section_id']);
            $table->dropColumn('menu_section_id');
        });
    }
};
