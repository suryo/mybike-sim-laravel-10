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
        Schema::create('planned_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('distance_km', 10, 2);
            $table->decimal('ascent_m', 10, 2)->default(0);
            $table->json('waypoints')->nullable();
            $table->json('coordinates')->nullable();
            $table->json('elevation_profile')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planned_routes');
    }
};
