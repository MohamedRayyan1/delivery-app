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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->boolean('is_online')->default(false)->index();
            $table->string('account_status')->default('pending')->index(); // pending, active, rejected
            $table->decimal('total_earnings', 12, 2)->default(0);

            $table->string('vehicle_type'); // motorcycle, car
            $table->string('vehicle_plate_number')->nullable();
            $table->string('license_image')->nullable();

            // موقع السائق الحالي (يحدث باستمرار)
            $table->decimal('current_lat', 10, 8)->nullable();
            $table->decimal('current_lng', 11, 8)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
