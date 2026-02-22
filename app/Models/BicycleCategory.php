<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BicycleCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function bicycles()
    {
        return $this->hasMany(Bicycle::class);
    }
}
