<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bicycle;

class BikeInsightSeeder extends Seeder
{
    public function run(): void
    {
        // Centurion 2023 Sweep FX (Size L)
        Bicycle::updateOrCreate(
            ['slug' => 'centurion-2023-sweep-fx'],
            [
                'name' => 'Centurion 2023 Sweep FX (L)',
                'type' => 'Hybrid',
                'bicycle_category_id' => 2, // Hybrid
                'weight_kg' => 10.5,
                'frame_material' => 'Aluminum',
                'fork_material' => 'Aluminum',
                'color' => 'Graphite Grey',
                'bicycle_weight' => 10.5,
                'front_gears' => [34, 50],
                'rear_gears' => [11, 12, 13, 14, 15, 17, 19, 21, 24, 28, 32],
                'wheel_diameter' => 700,
                'efficiency' => 0.95,
                'drag_coefficient' => 0.42,
                'rolling_coefficient' => 0.005,
                'crank_length_mm' => 170,
                
                // Geometry Data from Image
                'stack' => 593.2,
                'reach' => 396.2,
                'seat_tube_length' => 530,
                'top_tube_length' => 545.2,
                'head_tube_angle' => 70.5,
                'seat_tube_angle' => 75.0,
                'head_tube_length' => 150,
                'bb_drop' => 50,
                'chainstay_length' => 423.4,
                'wheelbase' => 1045.1,
                'fork_offset' => 50,
            ]
        );

        // Marin Bikes 2025 Four Corners 2 (Size S)
        Bicycle::updateOrCreate(
            ['slug' => 'marin-2025-four-corners-2-s'],
            [
                'name' => 'Marin 2025 Four Corners 2 (S)',
                'type' => 'Road',
                'bicycle_category_id' => 1, // Road
                'weight_kg' => 12.5,
                'frame_material' => 'Steel',
                'fork_material' => 'Steel',
                'color' => 'Deep Blue',
                'bicycle_weight' => 12.5,
                'front_gears' => [30, 46],
                'rear_gears' => [11, 13, 15, 17, 19, 21, 24, 28, 32, 36],
                'wheel_diameter' => 584, // 650B / 27.5"
                'efficiency' => 0.94,
                'drag_coefficient' => 0.45,
                'rolling_coefficient' => 0.006,
                'crank_length_mm' => 170,
                
                // Geometry Data from Image (Size S)
                'stack' => 593,
                'reach' => 391,
                'seat_tube_length' => 410,
                'top_tube_length' => 550,
                'head_tube_angle' => 71.0,
                'seat_tube_angle' => 73.5,
                'head_tube_length' => 160,
                'bb_drop' => 53.5,
                'chainstay_length' => 432,
                'wheelbase' => 1047,
                'fork_offset' => 49,
            ]
        );

        // Marin Bikes 2025 Four Corners 2 (Size M)
        Bicycle::updateOrCreate(
            ['slug' => 'marin-2025-four-corners-2-m'],
            [
                'name' => 'Marin 2025 Four Corners 2 (M)',
                'type' => 'Road',
                'bicycle_category_id' => 1, // Road
                'weight_kg' => 12.8,
                'frame_material' => 'Steel',
                'fork_material' => 'Steel',
                'color' => 'Deep Blue',
                'bicycle_weight' => 12.8,
                'front_gears' => [30, 46],
                'rear_gears' => [11, 13, 15, 17, 19, 21, 24, 28, 32, 36],
                'wheel_diameter' => 700,
                'efficiency' => 0.94,
                'drag_coefficient' => 0.45,
                'rolling_coefficient' => 0.006,
                'crank_length_mm' => 170,
                
                // Geometry Data from Image (Size M)
                'stack' => 615,
                'reach' => 403.9,
                'seat_tube_length' => 440,
                'top_tube_length' => 595,
                'head_tube_angle' => 72.0,
                'seat_tube_angle' => 73.5,
                'head_tube_length' => 200,
                'bb_drop' => 72,
                'chainstay_length' => 445.2,
                'wheelbase' => 1067,
                'fork_offset' => 49,
            ]
        );

        // Cleanup old generic slug if it exists
        Bicycle::where('slug', 'marin-2025-four-corners-2')->delete();
    }
}
