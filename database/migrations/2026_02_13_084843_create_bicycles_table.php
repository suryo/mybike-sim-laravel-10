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
        Schema::create('bicycles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#3498db');
            $table->float('bicycle_weight')->default(8.5); // kg
            $table->float('rider_weight')->default(75.0); // kg
            $table->json('front_gears'); // e.g. [50, 34]
            $table->json('rear_gears'); // e.g. [11, 12, 13, 14, 15, 17, 19, 21, 23, 25, 28]
            $table->float('wheel_diameter')->default(700); // mm
            $table->float('efficiency')->default(0.98); // 0.95 - 1.0
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bicycles');
    }
};
