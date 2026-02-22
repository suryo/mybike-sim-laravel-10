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
        $bicycles = Bicycle::with('category')->latest()->paginate(10);
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
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'bicycle_category_id' => 'required|exists:bicycle_categories,id',
            'weight_kg' => 'required|numeric',
            'stack' => 'nullable|numeric',
            'reach' => 'nullable|numeric',
            'head_tube_angle' => 'nullable|numeric',
            'seat_tube_angle' => 'nullable|numeric',
            // ... more geometry fields if needed
        ]);

        $validated['slug'] = Str::slug($request->name);
        Bicycle::create($validated);

        return redirect()->route('admin.bicycles.index')->with('success', 'Bicycle created successfully.');
    }

    public function edit(Bicycle $bicycle)
    {
        $categories = BicycleCategory::all();
        return view('admin.bicycles.edit', compact('bicycle', 'categories'));
    }

    public function update(Request $request, Bicycle $bicycle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'bicycle_category_id' => 'required|exists:bicycle_categories,id',
            'weight_kg' => 'required|numeric',
            'stack' => 'nullable|numeric',
            'reach' => 'nullable|numeric',
        ]);

        $bicycle->update($validated);

        return redirect()->route('admin.bicycles.index')->with('success', 'Bicycle updated successfully.');
    }

    public function destroy(Bicycle $bicycle)
    {
        $bicycle->delete();
        return redirect()->route('admin.bicycles.index')->with('success', 'Bicycle deleted.');
    }
}
