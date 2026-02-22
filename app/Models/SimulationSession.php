<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulationSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bicycle_id',
        'rider_id',
        'route_name',
        'total_distance_km',
        'total_time_seconds',
        'avg_speed_kmh',
        'avg_power_w',
        'total_calories_burned',
        'total_elevation_m',
        'tss',
        'segments',
        'segment_results',
        'route_elevation_profile',
    ];

    protected $casts = [
        'segments'                => 'array',
        'segment_results'         => 'array',
        'route_elevation_profile' => 'array',
        'total_distance_km'       => 'float',
        'avg_speed_kmh'           => 'float',
        'avg_power_w'             => 'float',
        'total_calories_burned'   => 'float',
        'total_elevation_m'       => 'float',
        'tss'                     => 'float',
    ];

    public function bicycle()
    {
        return $this->belongsTo(Bicycle::class);
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    /** Format total_time_seconds as H:MM:SS string */
    public function getFormattedTimeAttribute(): string
    {
        $s = $this->total_time_seconds;
        $h = intdiv($s, 3600);
        $m = intdiv($s % 3600, 60);
        $sec = $s % 60;
        return sprintf('%d:%02d:%02d', $h, $m, $sec);
    }
}
