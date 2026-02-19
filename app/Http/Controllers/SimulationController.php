<?php

namespace App\Http\Controllers;

use App\Models\Bicycle;
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    public function index()
    {
        $bicycles = Bicycle::all();
        return view('simulation.index', compact('bicycles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'bicycle_weight' => 'required|numeric|min:1',
            'rider_weight' => 'required|numeric|min:1',
            'max_hr' => 'required|integer|min:120|max:240',
            'ftp' => 'required|integer|min:50|max:1000',
            'efficiency' => 'required|numeric|min:0.5|max:1',
            'front_gears' => 'required|string',
            'rear_gears' => 'required|string',
            'initial_distance' => 'nullable|numeric|min:0',
            'initial_elevation' => 'nullable|integer|min:0',
            'initial_fuel' => 'nullable|integer|min:0',
        ]);

        // Convert comma-separated string to array and then to JSON
        $validated['front_gears'] = array_map('intval', explode(',', $request->front_gears));
        $validated['rear_gears'] = array_map('intval', explode(',', $request->rear_gears));
        $validated['wheel_diameter'] = 700; // Default
        $validated['efficiency'] = $request->efficiency;
        $validated['bicycle_weight'] = $request->bicycle_weight;
        $validated['rider_weight'] = $request->rider_weight;
        $validated['initial_distance'] = $request->initial_distance ?? 0;
        $validated['initial_elevation'] = $request->initial_elevation ?? 0;
        $validated['initial_fuel'] = $request->initial_fuel ?? 2500;

        Bicycle::create($validated);

        return redirect()->back()->with('success', 'Bicycle added successfully!');
    }

    public function update(Request $request, Bicycle $bicycle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'bicycle_weight' => 'required|numeric|min:1',
            'rider_weight' => 'required|numeric|min:1',
            'max_hr' => 'required|integer|min:120|max:240',
            'ftp' => 'required|integer|min:50|max:1000',
            'efficiency' => 'required|numeric|min:0.5|max:1',
            'front_gears' => 'required|string',
            'rear_gears' => 'required|string',
            'initial_distance' => 'nullable|numeric|min:0',
            'initial_elevation' => 'nullable|integer|min:0',
            'initial_fuel' => 'nullable|integer|min:0',
        ]);

        $validated['front_gears'] = array_map('intval', explode(',', $request->front_gears));
        $validated['rear_gears'] = array_map('intval', explode(',', $request->rear_gears));
        $validated['efficiency'] = $request->efficiency;
        $validated['bicycle_weight'] = $request->bicycle_weight;
        $validated['rider_weight'] = $request->rider_weight;
        $validated['initial_distance'] = $request->initial_distance ?? 0;
        $validated['initial_elevation'] = $request->initial_elevation ?? 0;
        $validated['initial_fuel'] = $request->initial_fuel ?? 2500;

        $bicycle->update($validated);

        return redirect()->back()->with('success', 'Bicycle updated successfully!');
    }

    public function destroy(Bicycle $bicycle)
    {
        $bicycle->delete();
        return redirect()->back()->with('success', 'Bicycle deleted successfully!');
    }
}
