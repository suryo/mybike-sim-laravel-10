<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlannedRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'distance_km',
        'ascent_m',
        'waypoints',
        'coordinates',
        'elevation_profile',
    ];

    protected $casts = [
        'waypoints'         => 'array',
        'coordinates'       => 'array',
        'elevation_profile' => 'array',
    ];
}
