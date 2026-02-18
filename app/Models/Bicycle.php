<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bicycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'bicycle_weight',
        'rider_weight',
        'max_hr',
        'ftp',
        'front_gears',
        'rear_gears',
        'wheel_diameter',
        'efficiency',
    ];

    protected $casts = [
        'front_gears' => 'array',
        'rear_gears' => 'array',
    ];
}
