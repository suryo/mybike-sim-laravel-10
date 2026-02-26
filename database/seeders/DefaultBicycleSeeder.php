<?php

namespace Database\Seeders;

use App\Models\Bicycle;
use App\Models\BicycleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DefaultBicycleSeeder extends Seeder
{
    public function run(): void
    {
        $road = BicycleCategory::firstOrCreate(['slug' => 'road'], ['name' => 'Road']);
        $gravel = BicycleCategory::firstOrCreate(['slug' => 'gravel'], ['name' => 'Gravel']);
        $mtb = BicycleCategory::firstOrCreate(['slug' => 'mtb'], ['name' => 'Mountain']);

        // Specialized Tarmac SL8 (Simulation Data + Geometry)
        Bicycle::create([
            'name' => 'Specialized Tarmac SL8',
            'slug' => Str::slug('Specialized Tarmac SL8'),
            'bicycle_category_id' => $road->id,
            'type' => 'Road',
            'weight_kg' => 6.8,
            'frame_material' => 'Carbon',
            'color' => '#f8fafc',
            'drag_coefficient' => 0.30,
            'rolling_coefficient' => 0.003,
            'crank_length_mm' => 172.5,
            'initial_fuel_kcal' => 2000,
            // Geometry
            'stack' => 543,
            'reach' => 391,
            'seat_tube_length' => 481,
            'top_tube_length' => 548,
            'head_tube_angle' => 73.0,
            'seat_tube_angle' => 73.5,
            'chainstay_length' => 410,
            'wheelbase' => 991,
            'head_tube_length' => 143,
            'bb_drop' => 72,
            'front_gears' => [52, 36],
            'rear_gears' => [11, 12, 13, 14, 15, 17, 19, 21, 24, 28, 30, 34],
        ]);

        // Cannondale Topstone (Gravel)
        Bicycle::create([
            'name' => 'Cannondale Topstone Carbon',
            'slug' => Str::slug('Cannondale Topstone Carbon'),
            'bicycle_category_id' => $gravel->id,
            'type' => 'Gravel',
            'weight_kg' => 8.9,
            'frame_material' => 'Carbon',
            'color' => '#1e293b',
            'drag_coefficient' => 0.35,
            'rolling_coefficient' => 0.005,
            'crank_length_mm' => 170.0,
            'initial_fuel_kcal' => 2500,
            // Geometry
            'stack' => 579,
            'reach' => 385,
            'seat_tube_length' => 458,
            'top_tube_length' => 553,
            'head_tube_angle' => 71.2,
            'seat_tube_angle' => 73.1,
            'chainstay_length' => 420,
            'wheelbase' => 1030,
            'head_tube_length' => 165,
            'bb_drop' => 61,
            'front_gears' => [40],
            'rear_gears' => [11, 13, 15, 17, 19, 21, 24, 28, 32, 37, 42],
        ]);
        
        // Pinarello Dogma F
        Bicycle::create([
            'name' => 'Pinarello Dogma F',
            'slug' => Str::slug('Pinarello Dogma F'),
            'bicycle_category_id' => $road->id,
            'type' => 'Road',
            'weight_kg' => 6.9,
            'frame_material' => 'Carbon',
            'color' => '#ef4444',
            'drag_coefficient' => 0.29,
            'rolling_coefficient' => 0.003,
            'crank_length_mm' => 172.5,
            'initial_fuel_kcal' => 1800,
            // Geometry
            'stack' => 542,
            'reach' => 393,
            'seat_tube_length' => 530,
            'top_tube_length' => 550,
            'head_tube_angle' => 73.0,
            'seat_tube_angle' => 73.3,
            'chainstay_length' => 406,
            'wheelbase' => 988,
            'head_tube_length' => 139,
            'bb_drop' => 72,
            'front_gears' => [50, 34],
            'rear_gears' => [11, 12, 13, 14, 15, 17, 19, 21, 24, 28, 30, 34],
        ]);
    }
}
