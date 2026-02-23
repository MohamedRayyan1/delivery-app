<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_contacts', function (Blueprint $table) {
            $table->id(); 

            $table->string('governorate');
            $table->string('phone_number');
            $table->string('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_contacts');
    }
};
