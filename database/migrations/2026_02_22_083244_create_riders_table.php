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
        Schema::create('riders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            
            // Performance Metrics (moved from bicycles)
            $table->float('weight_kg')->default(75.0);
            $table->integer('ftp')->default(250);
            $table->integer('max_hr')->default(190);
            $table->integer('max_power_w')->default(350);
            $table->integer('aerobic_threshold_w')->default(180);
            $table->integer('anaerobic_threshold_w')->default(280);
            
            // Body Measurements (for Bike Fitting)
            $table->float('height_cm')->nullable();
            $table->float('sternal_notch_cm')->nullable();
            $table->float('arm_length_cm')->nullable();
            $table->float('leg_length_cm')->nullable();
            $table->float('shoe_size_eu')->nullable();
            $table->float('lower_leg_cm')->nullable();
            $table->float('thigh_cm')->nullable();
            $table->float('torso_length_cm')->nullable();
            
            // Preferences
            $table->integer('back_angle_preference')->default(45); // Degrees (slider from upright to aero)
            $table->string('riding_style')->nullable(); // e.g. "Good flat roads", "Hills and mountains"
            $table->string('flexibility_level')->nullable(); // e.g. "Very flexible", "Less flexible"
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riders');
    }
};
