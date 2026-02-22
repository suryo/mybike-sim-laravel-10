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
        // 1. Mark simulation_sessions to allow multiple riders
        Schema::table('simulation_sessions', function (Blueprint $table) {
            $table->foreignId('rider_id')->nullable()->after('bicycle_id')->constrained()->onDelete('cascade');
        });

        // 2. Remove rider performance fields from bicycles
        Schema::table('bicycles', function (Blueprint $table) {
            $table->dropColumn([
                'rider_weight',
                'max_hr',
                'ftp',
                'max_power_output',
                'aerobic_threshold',
                'anaerobic_threshold',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simulation_sessions', function (Blueprint $table) {
            $table->dropForeign(['rider_id']);
            $table->dropColumn('rider_id');
        });

        Schema::table('bicycles', function (Blueprint $table) {
            $table->float('rider_weight')->default(75.0);
            $table->integer('max_hr')->default(190);
            $table->integer('ftp')->default(250);
            $table->integer('max_power_output')->default(350);
            $table->integer('aerobic_threshold')->default(180);
            $table->integer('anaerobic_threshold')->default(280);
        });
    }
};
