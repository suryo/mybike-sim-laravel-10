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
        $road = BicycleCategory::create(['name' => 'Road', 'slug' => 'road']);
        $gravel = BicycleCategory::create(['name' => 'Gravel', 'slug' => 'gravel']);
        $mtb = BicycleCategory::create(['name' => 'Mountain', 'slug' => 'mtb']);

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
            'max_heart_rate' => 190,
            'max_power_output' => 1000,
            'aerobic_threshold' => 250,
            'anaerobic_threshold' => 350,
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
            'max_heart_rate' => 185,
            'max_power_output' => 800,
            'aerobic_threshold' => 200,
            'anaerobic_threshold' => 300,
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
            'max_heart_rate' => 195,
            'max_power_output' => 1200,
            'aerobic_threshold' => 280,
            'anaerobic_threshold' => 400,
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
        ]);
    }
}
