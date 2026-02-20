<?php

namespace App\Http\Controllers;

use App\Models\Bicycle;
use App\Models\SimulationSession;
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    public function index()
    {
        $bicycles = Bicycle::all();
        $sessions = SimulationSession::with('bicycle')
            ->latest()
            ->limit(50)
            ->get();
        return view('simulation.index', compact('bicycles', 'sessions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'color'              => 'required|string|max:7',
            'bicycle_weight'     => 'required|numeric|min:1',
            'rider_weight'       => 'required|numeric|min:1',
            'max_hr'             => 'required|integer|min:120|max:240',
            'ftp'                => 'required|integer|min:50|max:1000',
            'efficiency'         => 'required|numeric|min:0.5|max:1',
            'front_gears'        => 'required|string',
            'rear_gears'         => 'required|string',
            'initial_distance'   => 'nullable|numeric|min:0',
            'initial_elevation'  => 'nullable|integer|min:0',
            'initial_fuel'       => 'nullable|integer|min:0',
        ]);

        $validated['front_gears']       = array_map('intval', explode(',', $request->front_gears));
        $validated['rear_gears']        = array_map('intval', explode(',', $request->rear_gears));
        $validated['wheel_diameter']    = 700;
        $validated['efficiency']        = $request->efficiency;
        $validated['bicycle_weight']    = $request->bicycle_weight;
        $validated['rider_weight']      = $request->rider_weight;
        $validated['initial_distance']  = $request->initial_distance  ?? 0;
        $validated['initial_elevation'] = $request->initial_elevation ?? 0;
        $validated['initial_fuel']      = $request->initial_fuel      ?? 2500;

        Bicycle::create($validated);

        return redirect()->back()->with('success', 'Bicycle added successfully!');
    }

    public function update(Request $request, Bicycle $bicycle)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'color'              => 'required|string|max:7',
            'bicycle_weight'     => 'required|numeric|min:1',
            'rider_weight'       => 'required|numeric|min:1',
            'max_hr'             => 'required|integer|min:120|max:240',
            'ftp'                => 'required|integer|min:50|max:1000',
            'efficiency'         => 'required|numeric|min:0.5|max:1',
            'front_gears'        => 'required|string',
            'rear_gears'         => 'required|string',
            'initial_distance'   => 'nullable|numeric|min:0',
            'initial_elevation'  => 'nullable|integer|min:0',
            'initial_fuel'       => 'nullable|integer|min:0',
        ]);

        $validated['front_gears']       = array_map('intval', explode(',', $request->front_gears));
        $validated['rear_gears']        = array_map('intval', explode(',', $request->rear_gears));
        $validated['efficiency']        = $request->efficiency;
        $validated['bicycle_weight']    = $request->bicycle_weight;
        $validated['rider_weight']      = $request->rider_weight;
        $validated['initial_distance']  = $request->initial_distance  ?? 0;
        $validated['initial_elevation'] = $request->initial_elevation ?? 0;
        $validated['initial_fuel']      = $request->initial_fuel      ?? 2500;

        $bicycle->update($validated);

        return redirect()->back()->with('success', 'Bicycle updated successfully!');
    }

    public function destroy(Bicycle $bicycle)
    {
        $bicycle->delete();
        return redirect()->back()->with('success', 'Bicycle deleted successfully!');
    }

    // ── Simulation Sessions ────────────────────────────────────────────────

    public function saveSession(Request $request)
    {
        \Log::info('Save Session Attempt:', $request->all());
        
        try {
            $validated = $request->validate([
                'name'                     => 'required|string|max:255',
                'bicycle_id'               => 'required|exists:bicycles,id',
                'route_name'               => 'nullable|string|max:255',
                'total_distance_km'        => 'required|numeric|min:0',
                'total_time_seconds'       => 'required|integer|min:0',
                'avg_speed_kmh'            => 'required|numeric|min:0',
                'avg_power_w'              => 'required|numeric|min:0',
                'total_calories_burned'    => 'required|numeric|min:0',
                'total_elevation_m'        => 'nullable|numeric|min:0',
                'tss'                      => 'nullable|numeric|min:0',
                'segments'                 => 'nullable|array',
                'segment_results'          => 'nullable|array',
                'route_elevation_profile'  => 'nullable|array',
            ]);

            $session = SimulationSession::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Session saved!',
                'session' => $session->load('bicycle'),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Failed:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Save Session Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function listSessions()
    {
        $sessions = SimulationSession::with('bicycle')
            ->latest()
            ->limit(50)
            ->get()
            ->map(fn($s) => [
                'id'                   => $s->id,
                'name'                 => $s->name,
                'bicycle_name'         => $s->bicycle->name ?? '—',
                'bicycle_color'        => $s->bicycle->color ?? '#888',
                'route_name'           => $s->route_name,
                'total_distance_km'    => round($s->total_distance_km, 2),
                'total_time_seconds'   => $s->total_time_seconds,
                'formatted_time'       => $s->formatted_time,
                'avg_speed_kmh'        => round($s->avg_speed_kmh, 1),
                'avg_power_w'          => round($s->avg_power_w),
                'total_calories_burned'=> round($s->total_calories_burned),
                'total_elevation_m'    => round($s->total_elevation_m),
                'tss'                  => round($s->tss),
                'segments'             => $s->segments,
                'segment_results'      => $s->segment_results,
                'created_at'           => $s->created_at->format('Y-m-d H:i'),
            ]);

        return response()->json(['sessions' => $sessions]);
    }

    public function deleteSession(SimulationSession $session)
    {
        $session->delete();
        return response()->json(['success' => true, 'message' => 'Session deleted.']);
    }
}
