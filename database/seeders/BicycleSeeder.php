<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BicycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Bicycle::create([
            'name' => 'Suryo (Pro Racer)',
            'color' => '#3498db',
            'bicycle_weight' => 7.8,
            'front_gears' => [50, 34],
            'rear_gears' => [11, 12, 13, 14, 15, 17, 19, 21, 23, 25, 28, 30],
            'wheel_diameter' => 700,
            'efficiency' => 1.0, 
        ]);

        \App\Models\Bicycle::create([
            'name' => 'Tes (Heavy Hybrid)',
            'color' => '#e74c3c',
            'bicycle_weight' => 12.5,
            'front_gears' => [48, 36],
            'rear_gears' => [11, 13, 15, 17, 19, 21, 23, 26, 30, 34],
            'wheel_diameter' => 700,
            'efficiency' => 0.95,
        ]);
    }
}
