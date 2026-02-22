<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bicycle;
use App\Models\BicycleCategory;
use Illuminate\Support\Str;

class BicycleController extends Controller
{
    public function index()
    {
        $bicycles = Bicycle::with('category')->latest()->paginate(15);
        return view('admin.bicycles.index', compact('bicycles'));
    }

    public function create()
    {
        $categories = BicycleCategory::all();
        return view('admin.bicycles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // General
            'name'               => 'required|string|max:255',
            'type'               => 'required|string|max:100',
            'bicycle_category_id'=> 'required|exists:bicycle_categories,id',
            'weight_kg'          => 'required|numeric|min:1|max:50',
            'color'              => 'nullable|string|max:100',
            'frame_material'     => 'nullable|string|max:50',
            'fork_material'      => 'nullable|string|max:50',
            'tire_width'         => 'nullable|numeric|min:18|max:100',
            // Geometry
            'stack'              => 'nullable|numeric',
            'reach'              => 'nullable|numeric',
            'head_tube_angle'    => 'nullable|numeric|min:60|max:85',
            'seat_tube_angle'    => 'nullable|numeric|min:60|max:85',
            'head_tube_length'   => 'nullable|numeric',
            'seat_tube_length'   => 'nullable|numeric',
            'top_tube_length'    => 'nullable|numeric',
            'chainstay_length'   => 'nullable|numeric',
            'bb_drop'            => 'nullable|numeric',
            'wheelbase'          => 'nullable|numeric',
            'fork_offset'        => 'nullable|numeric',
            'wheel_diameter'     => 'nullable|numeric',
            // Drivetrain
            'front_gears_raw'    => 'nullable|string',
            'rear_gears_raw'     => 'nullable|string',
            'crank_length_mm'    => 'nullable|numeric',
            'efficiency'         => 'nullable|numeric|min:0.8|max:1.0',
            // Aero
            'drag_coefficient'   => 'nullable|numeric|min:0.1|max:2.0',
            'rolling_coefficient'=> 'nullable|numeric|min:0.001|max:0.1',
            // Simulation
            'bicycle_weight'     => 'nullable|numeric',
            'initial_distance'   => 'nullable|numeric',
            'initial_elevation'  => 'nullable|numeric',
        ]);

        $validated['slug']        = $this->uniqueSlug($validated['name']);
        $validated['front_gears'] = $this->parseGears($request->input('front_gears_raw'));
        $validated['rear_gears']  = $this->parseGears($request->input('rear_gears_raw'));

        // Use weight_kg as bicycle_weight if not provided
        if (empty($validated['bicycle_weight'])) {
            $validated['bicycle_weight'] = $validated['weight_kg'];
        }

        unset($validated['front_gears_raw'], $validated['rear_gears_raw']);

        Bicycle::create($validated);

        return redirect()->route('admin.bicycles.index')
                         ->with('success', 'Bicycle "' . $validated['name'] . '" created successfully.');
    }

    public function edit(Bicycle $bicycle)
    {
        $categories = BicycleCategory::all();
        return view('admin.bicycles.edit', compact('bicycle', 'categories'));
    }

    public function update(Request $request, Bicycle $bicycle)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'type'               => 'required|string|max:100',
            'bicycle_category_id'=> 'required|exists:bicycle_categories,id',
            'weight_kg'          => 'required|numeric|min:1|max:50',
            'color'              => 'nullable|string|max:100',
            'frame_material'     => 'nullable|string|max:50',
            'fork_material'      => 'nullable|string|max:50',
            'tire_width'         => 'nullable|numeric|min:18|max:100',
            'stack'              => 'nullable|numeric',
            'reach'              => 'nullable|numeric',
            'head_tube_angle'    => 'nullable|numeric|min:60|max:85',
            'seat_tube_angle'    => 'nullable|numeric|min:60|max:85',
            'head_tube_length'   => 'nullable|numeric',
            'seat_tube_length'   => 'nullable|numeric',
            'top_tube_length'    => 'nullable|numeric',
            'chainstay_length'   => 'nullable|numeric',
            'bb_drop'            => 'nullable|numeric',
            'wheelbase'          => 'nullable|numeric',
            'fork_offset'        => 'nullable|numeric',
            'wheel_diameter'     => 'nullable|numeric',
            'front_gears_raw'    => 'nullable|string',
            'rear_gears_raw'     => 'nullable|string',
            'crank_length_mm'    => 'nullable|numeric',
            'efficiency'         => 'nullable|numeric|min:0.8|max:1.0',
            'drag_coefficient'   => 'nullable|numeric|min:0.1|max:2.0',
            'rolling_coefficient'=> 'nullable|numeric|min:0.001|max:0.1',
            'bicycle_weight'     => 'nullable|numeric',
            'initial_distance'   => 'nullable|numeric',
            'initial_elevation'  => 'nullable|numeric',
        ]);

        $validated['front_gears'] = $this->parseGears($request->input('front_gears_raw'));
        $validated['rear_gears']  = $this->parseGears($request->input('rear_gears_raw'));

        if (empty($validated['bicycle_weight'])) {
            $validated['bicycle_weight'] = $validated['weight_kg'];
        }

        unset($validated['front_gears_raw'], $validated['rear_gears_raw']);

        $bicycle->update($validated);

        return redirect()->route('admin.bicycles.index')
                         ->with('success', 'Bicycle "' . $bicycle->name . '" updated successfully.');
    }

    public function destroy(Bicycle $bicycle)
    {
        $name = $bicycle->name;
        $bicycle->delete();
        return redirect()->route('admin.bicycles.index')
                         ->with('success', 'Bicycle "' . $name . '" deleted.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    /** Parse "11,13,15,17" string into [11, 13, 15, 17] int array. */
    private function parseGears(?string $raw): ?array
    {
        if (empty(trim($raw ?? ''))) return null;
        return array_values(array_filter(
            array_map('intval', array_map('trim', explode(',', $raw)))
        ));
    }

    /** Generate a unique slug (append -2, -3 if collision). */
    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 2;
        while (Bicycle::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }
}
