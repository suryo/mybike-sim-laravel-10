<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Rider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'weight_kg',
        'ftp',
        'max_hr',
        'max_power_w',
        'aerobic_threshold_w',
        'anaerobic_threshold_w',
        'height_cm',
        'sternal_notch_cm',
        'arm_length_cm',
        'leg_length_cm',
        'shoe_size_eu',
        'lower_leg_cm',
        'thigh_cm',
        'torso_length_cm',
        'back_angle_preference',
        'riding_style',
        'flexibility_level',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($rider) {
            if (!$rider->slug) {
                $rider->slug = Str::slug($rider->name);
                // Simple collision check
                $count = static::where('slug', 'like', $rider->slug . '%')->count();
                if ($count > 0) $rider->slug .= '-' . ($count + 1);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function simulationSessions()
    {
        return $this->hasMany(SimulationSession::class);
    }
}
