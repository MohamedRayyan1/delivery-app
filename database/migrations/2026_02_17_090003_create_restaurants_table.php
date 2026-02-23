<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();

            // الربط مع المدير
            $table->foreignId('manager_user_id')->constrained('users')->cascadeOnDelete();

            $table->string('name');
            $table->string('governorate')->index();
            $table->string('city')->index();
            $table->string('status')->default('pending')->index();

            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();

            // التقييم والأرقام
            $table->decimal('rating', 3, 2)->default(0);
            $table->decimal('delivery_cost', 8, 2)->default(0);
            $table->decimal('min_order_price', 8, 2)->default(0);
            $table->string('delivery_time')->nullable();

            $table->boolean('is_featured')->default(false)->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
