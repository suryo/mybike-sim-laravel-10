<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bicycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'weight_kg',
        'frame_material',
        'fork_material',
        'color',
        'bicycle_weight',
        'front_gears',
        'rear_gears',
        'wheel_diameter',
        'efficiency',
        'initial_distance',
        'initial_elevation',
        'initial_fuel',
        'initial_fuel_kcal',
        'drag_coefficient',
        'rolling_coefficient',
        'crank_length_mm',
        'bicycle_category_id',
        'stack',
        'reach',
        'seat_tube_length',
        'top_tube_length',
        'head_tube_angle',
        'seat_tube_angle',
        'chainstay_length',
        'wheelbase',
        'head_tube_length',
        'bb_drop',
        'fork_offset',
    ];

    public function category()
    {
        return $this->belongsTo(BicycleCategory::class, 'bicycle_category_id');
    }

    protected $casts = [
        'front_gears' => 'array',
        'rear_gears' => 'array',
    ];
}
