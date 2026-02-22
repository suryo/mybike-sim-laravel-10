<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bicycle;
use App\Models\BicycleCategory;
use Illuminate\Support\Str;

class RealWorldBikeSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure categories exist
        $road = BicycleCategory::firstOrCreate(['slug' => 'road'], ['name' => 'Road']);
        $gravel = BicycleCategory::firstOrCreate(['slug' => 'gravel'], ['name' => 'Gravel']);

        // 1. Polygon 2024 Tambora G4 (Size M)
        Bicycle::updateOrCreate(
            ['slug' => 'polygon-tambora-g4-2024-m'],
            [
                'name' => 'Polygon Tambora G4 (2024)',
                'type' => 'Gravel',
                'bicycle_category_id' => $gravel->id,
                'weight_kg' => 10.2, // Detailed weight for comparison
                'color' => 'Dark Teal',
                // Simulation defaults
                'bicycle_weight' => 10.2, 
                'rider_weight' => 75.0,
                'front_gears' => [40], // 1x drivetrain
                'rear_gears' => [11, 13, 15, 17, 19, 21, 24, 28, 32, 37, 42],
                'wheel_diameter' => 700,
                'efficiency' => 0.98,
                'max_hr' => 190,
                'ftp' => 250,
                'drag_coefficient' => 0.4,
                'rolling_coefficient' => 0.005,
                // Geometry data
                'stack' => 591,
                'reach' => 393,
                'seat_tube_length' => 440,
                'top_tube_length' => 583,
                'head_tube_angle' => 71.77,
                'seat_tube_angle' => 74.26,
                'head_tube_length' => 165,
                'bb_drop' => 70,
                'wheelbase' => 1050,
                'fork_offset' => 45.8,
                'chainstay_length' => 415,
                'tire_width' => 32,
                'frame_material' => 'Aluminum',
                'fork_material' => 'Carbon',
            ]
        );

        // 2. Polygon 2024 Strattos C Base (Size S)
        Bicycle::updateOrCreate(
            ['slug' => 'polygon-strattos-c-base-2024-s'],
            [
                'name' => 'Polygon Strattos C Base (2024)',
                'type' => 'Endurance Road',
                'bicycle_category_id' => $road->id,
                'weight_kg' => 8.9, 
                'color' => 'Deep Blue',
                // Simulation defaults
                'bicycle_weight' => 8.9,
                'rider_weight' => 75.0,
                'front_gears' => [50, 34],
                'rear_gears' => [11, 12, 13, 14, 15, 17, 19, 21, 24, 28, 32],
                'wheel_diameter' => 700,
                'efficiency' => 0.98,
                'max_hr' => 190,
                'ftp' => 250,
                'drag_coefficient' => 0.32,
                'rolling_coefficient' => 0.003,
                // Geometry data
                'stack' => 544.3,
                'reach' => 366,
                'seat_tube_length' => 473,
                'top_tube_length' => 528.2,
                'head_tube_angle' => 71.5,
                'seat_tube_angle' => 74,
                'head_tube_length' => 125,
                'bb_drop' => 72.5,
                'wheelbase' => 986.3,
                'fork_offset' => 57,
                'chainstay_length' => 415,
                'tire_width' => 28,
                'frame_material' => 'Carbon',
                'fork_material' => 'Carbon',
            ]
        );

        // 3. Polygon Bend R7 2023 (Size M) — Gravel, Aluminum
        Bicycle::updateOrCreate(
            ['slug' => 'polygon-bend-r7-2023-m'],
            [
                'name'                 => 'Polygon Bend R7 (2023)',
                'type'                 => 'All-Road Gravel',
                'bicycle_category_id'  => $gravel->id,
                'weight_kg'            => 10.4,
                'color'                => 'Khaki Green',
                'bicycle_weight'       => 10.4,
                'rider_weight'         => 75.0,
                'front_gears'          => [40],
                'rear_gears'           => [11, 13, 15, 17, 19, 21, 24, 28, 32, 37, 42],
                'wheel_diameter'       => 700,
                'efficiency'           => 0.98,
                'max_hr'               => 190,
                'ftp'                  => 250,
                'drag_coefficient'     => 0.42,
                'rolling_coefficient'  => 0.006,
                // Geometry
                'stack'                => 588.0,
                'reach'                => 389.0,
                'seat_tube_length'     => 440,
                'top_tube_length'      => 574.0,
                'head_tube_angle'      => 71.0,
                'seat_tube_angle'      => 72.5,
                'head_tube_length'     => 125,
                'bb_drop'              => 72,
                'wheelbase'            => 1041.0,
                'fork_offset'          => 48.0,
                'chainstay_length'     => 415,
                'tire_width'           => 40,
                'frame_material'       => 'Aluminum',
                'fork_material'        => 'Carbon',
            ]
        );

        // 4. Polygon Tambora G7 2024 (Size M) — Gravel, Full Carbon
        Bicycle::updateOrCreate(
            ['slug' => 'polygon-tambora-g7-2024-m'],
            [
                'name'                 => 'Polygon Tambora G7 (2024)',
                'type'                 => 'Gravel',
                'bicycle_category_id'  => $gravel->id,
                'weight_kg'            => 9.0,
                'color'                => 'Matte Black',
                'bicycle_weight'       => 9.0,
                'rider_weight'         => 75.0,
                'front_gears'          => [40],
                'rear_gears'           => [10, 12, 14, 16, 18, 21, 24, 28, 33, 39, 45],
                'wheel_diameter'       => 700,
                'efficiency'           => 0.99,
                'max_hr'               => 190,
                'ftp'                  => 250,
                'drag_coefficient'     => 0.38,
                'rolling_coefficient'  => 0.005,
                // Geometry — same frame as Tambora G4 (higher spec groupset/carbon frame)
                'stack'                => 591.0,
                'reach'                => 393.0,
                'seat_tube_length'     => 440,
                'top_tube_length'      => 583.0,
                'head_tube_angle'      => 71.77,
                'seat_tube_angle'      => 74.26,
                'head_tube_length'     => 165,
                'bb_drop'              => 70,
                'wheelbase'            => 1050.0,
                'fork_offset'          => 45.8,
                'chainstay_length'     => 415,
                'tire_width'           => 40,
                'frame_material'       => 'Carbon',
                'fork_material'        => 'Carbon',
            ]
        );
    }
}
