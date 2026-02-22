@extends('layouts.admin')

@section('title', 'Add New Bicycle')
@section('subtitle', 'Enter the technical specifications and geometry for a new bicycle.')

@section('actions')
<a href="{{ route('admin.bicycles.index') }}" class="btn btn-ghost">Back to List</a>
@endsection

@section('content')
<form action="{{ route('admin.bicycles.store') }}" method="POST">
    @csrf
    <div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        
        <!-- General Info -->
        <div class="card">
            <h3 style="margin-bottom: 1.5rem; color: var(--accent);">General Information</h3>
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Bicycle Name</label>
                    <input type="text" name="name" class="form-control" required style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 8px; color: white;">
                </div>
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Category</label>
                    <select name="bicycle_category_id" class="form-control" required style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 8px; color: white;">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Type (e.g. Race, Endurance)</label>
                    <input type="text" name="type" class="form-control" required style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 8px; color: white;">
                </div>
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Weight (kg)</label>
                    <input type="number" step="0.01" name="weight_kg" class="form-control" required style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 8px; color: white;">
                </div>
            </div>
        </div>

        <!-- Geometry -->
        <div class="card">
            <h3 style="margin-bottom: 1.5rem; color: var(--accent);">Frame Geometry (mm/deg)</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Stack</label>
                    <input type="number" step="0.1" name="stack" style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 8px; color: white;">
                </div>
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Reach</label>
                    <input type="number" step="0.1" name="reach" style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 8px; color: white;">
                </div>
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">HT Angle (deg)</label>
                    <input type="number" step="0.1" name="head_tube_angle" style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 8px; color: white;">
                </div>
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">ST Angle (deg)</label>
                    <input type="number" step="0.1" name="seat_tube_angle" style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 8px; color: white;">
                </div>
                <!-- Simplified for now, can add all 10+ fields if needed -->
            </div>
            <p style="margin-top: 1.5rem; font-size: 0.75rem; color: var(--text-secondary); font-style: italic;">
                Geometry data is used for visual comparisons and wireframe rendering.
            </p>
        </div>
    </div>

    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
        <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2.5rem;">Save Bicycle</button>
    </div>
</form>
@endsection
