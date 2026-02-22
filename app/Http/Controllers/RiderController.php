<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RiderController extends Controller
{
    /** Display a listing of riders. */
    public function index()
    {
        $riders = Rider::latest()->paginate(15);
        return view('admin.riders.index', compact('riders'));
    }

    /** Show the form for creating a new rider. */
    public function create()
    {
        return view('admin.riders.create');
    }

    /** Store a newly created rider. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'weight_kg'             => 'required|numeric|min:20',
            'ftp'                   => 'nullable|integer',
            'max_hr'                => 'nullable|integer',
            'max_power_w'           => 'nullable|integer',
            'aerobic_threshold_w'   => 'nullable|integer',
            'anaerobic_threshold_w' => 'nullable|integer',
        ]);

        $rider = Rider::create($validated);

        return redirect()->route('admin.riders.index')
                         ->with('success', 'Rider profile created successfully.');
    }

    /** Show the form for editing the specified rider. */
    public function edit(Rider $rider)
    {
        return view('admin.riders.edit', compact('rider'));
    }

    /** Update the specified rider. */
    public function update(Request $request, Rider $rider)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'weight_kg'             => 'required|numeric|min:20',
            'ftp'                   => 'nullable|integer',
            'max_hr'                => 'nullable|integer',
            'max_power_w'           => 'nullable|integer',
            'aerobic_threshold_w'   => 'nullable|integer',
            'anaerobic_threshold_w' => 'nullable|integer',
        ]);

        $rider->update($validated);

        return redirect()->route('admin.riders.index')
                         ->with('success', 'Rider profile updated.');
    }

    /** Remove the specified rider. */
    public function destroy(Rider $rider)
    {
        $rider->delete();
        return redirect()->route('admin.riders.index')
                         ->with('success', 'Rider profile deleted.');
    }

    /** ── BIKE FITTING WIZARD ─────────────────────────────────────────────── */

    /** Show the multi-step fitting wizard. */
    public function wizard()
    {
        return view('fitting.wizard');
    }

    /** AJAX save fitting measurements. */
    public function saveFitting(Request $request)
    {
        // For now, save to a temporary "Wizard Rider" or the current user's profile
        $data = $request->all();
        
        $rider = Rider::updateOrCreate(
            ['user_id' => auth()->id(), 'name' => $data['name'] ?? 'My Profile'],
            [
                'weight_kg'             => $data['weight_kg'] ?? 75,
                'height_cm'             => $data['height_cm'] ?? null,
                'sternal_notch_cm'      => $data['sternal_notch_cm'] ?? null,
                'arm_length_cm'         => $data['arm_length_cm'] ?? null,
                'leg_length_cm'         => $data['leg_length_cm'] ?? null,
                'shoe_size_eu'          => $data['shoe_size_eu'] ?? null,
                'lower_leg_cm'          => $data['lower_leg_cm'] ?? null,
                'thigh_cm'              => $data['thigh_cm'] ?? null,
                'torso_length_cm'       => $data['torso_length_cm'] ?? null,
                'back_angle_preference' => $data['back_angle_preference'] ?? 45,
                'riding_style'          => $data['riding_style'] ?? null,
                'flexibility_level'     => $data['flexibility_level'] ?? null,
            ]
        );

        return response()->json([
            'success' => true,
            'rider'   => $rider,
            'message' => 'Fitting data saved successfully.'
        ]);
    }
}
