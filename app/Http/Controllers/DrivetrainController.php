<?php

namespace App\Http\Controllers;

use App\Models\Bicycle;
use Illuminate\Http\Request;

class DrivetrainController extends Controller
{
    public function index(Request $request)
    {
        $bicycles = Bicycle::all();
        
        $bikeId = $request->query('bicycle_id');
        $bicycle = null;

        if ($bikeId) {
            $bicycle = Bicycle::find($bikeId);
        }

        if (!$bicycle) {
            $bicycle = Bicycle::where('slug', 'marin-2025-four-corners-2-m')->first() 
                     ?? Bicycle::first();
        }

        return view('drivetrain.index', compact('bicycle', 'bicycles'));
    }
}
