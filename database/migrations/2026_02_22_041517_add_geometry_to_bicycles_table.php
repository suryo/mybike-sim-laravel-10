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
        Schema::table('bicycles', function (Blueprint $table) {
            if (!Schema::hasColumn('bicycles', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
            if (!Schema::hasColumn('bicycles', 'type')) {
                $table->string('type')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('bicycles', 'frame_material')) {
                $table->string('frame_material')->nullable()->after('type');
            }
            if (!Schema::hasColumn('bicycles', 'fork_material')) {
                $table->string('fork_material')->nullable()->after('frame_material');
            }
            if (!Schema::hasColumn('bicycles', 'weight_kg')) {
                $table->float('weight_kg')->nullable()->after('frame_material');
            }
            if (!Schema::hasColumn('bicycles', 'drag_coefficient')) {
                $table->float('drag_coefficient')->nullable()->after('weight_kg');
            }
            if (!Schema::hasColumn('bicycles', 'rolling_coefficient')) {
                $table->float('rolling_coefficient')->nullable()->after('drag_coefficient');
            }
            if (!Schema::hasColumn('bicycles', 'crank_length_mm')) {
                $table->float('crank_length_mm')->nullable()->after('rolling_coefficient');
            }
            if (!Schema::hasColumn('bicycles', 'max_power_output')) {
                $table->integer('max_power_output')->nullable()->after('crank_length_mm');
            }
            if (!Schema::hasColumn('bicycles', 'aerobic_threshold')) {
                $table->integer('aerobic_threshold')->nullable()->after('max_power_output');
            }
            if (!Schema::hasColumn('bicycles', 'anaerobic_threshold')) {
                $table->integer('anaerobic_threshold')->nullable()->after('aerobic_threshold');
            }
            if (!Schema::hasColumn('bicycles', 'initial_fuel_kcal')) {
                $table->integer('initial_fuel_kcal')->nullable()->after('anaerobic_threshold');
            }
            
            if (!Schema::hasColumn('bicycles', 'bicycle_category_id')) {
                $table->foreignId('bicycle_category_id')->nullable()->constrained()->onDelete('set null');
            }
            
            // Geometry fields
            $geometryColumns = [
                'stack' => ['decimal', 8, 2],
                'reach' => ['decimal', 8, 2],
                'seat_tube_length' => ['decimal', 8, 2],
                'top_tube_length' => ['decimal', 8, 2],
                'head_tube_angle' => ['decimal', 5, 2],
                'seat_tube_angle' => ['decimal', 5, 2],
                'chainstay_length' => ['decimal', 8, 2],
                'wheelbase' => ['decimal', 8, 2],
                'head_tube_length' => ['decimal', 8, 2],
                'bb_drop' => ['decimal', 8, 2],
            ];

            foreach ($geometryColumns as $col => $typeInfo) {
                if (!Schema::hasColumn('bicycles', $col)) {
                    $type = $typeInfo[0];
                    if ($type === 'decimal') {
                        $table->decimal($col, $typeInfo[1], $typeInfo[2])->nullable();
                    } else {
                        $table->$type($col)->nullable();
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bicycles', function (Blueprint $table) {
            //
        });
    }
};
