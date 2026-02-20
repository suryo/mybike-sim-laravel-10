<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulation_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('bicycle_id')->constrained()->onDelete('cascade');
            $table->string('route_name')->nullable();
            $table->float('total_distance_km')->default(0);
            $table->integer('total_time_seconds')->default(0);
            $table->float('avg_speed_kmh')->default(0);
            $table->float('avg_power_w')->default(0);
            $table->float('total_calories_burned')->default(0);
            $table->float('total_elevation_m')->default(0);
            $table->float('tss')->default(0);
            $table->json('segments')->nullable();           // Segment strategy configs
            $table->json('segment_results')->nullable();    // Per-segment outcome stats
            $table->json('route_elevation_profile')->nullable(); // Snapshot of route
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_sessions');
    }
};
