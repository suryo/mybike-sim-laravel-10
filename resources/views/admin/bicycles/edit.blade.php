@extends('layouts.admin')

@section('title', 'Edit Bicycle')
@section('subtitle', 'Update technical specifications and geometry for ' . $bicycle->name)

@section('actions')
<a href="{{ route('admin.bicycles.index') }}" class="btn btn-ghost">Back to List</a>
@endsection

@section('content')
<form action="{{ route('admin.bicycles.update', $bicycle) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        
        <!-- General Info -->
        <div class="card">
            <h3 style="margin-bottom: 1.5rem; color: var(--accent);">General Information</h3>
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Bicycle Name</label>
                    <input type="text" name="name" value="{{ $bicycle->name }}" class="form-control" required style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 8px; color: white;">
                </div>
                <!-- ... other fields ... -->
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Weight (kg)</label>
                    <input type="number" step="0.01" name="weight_kg" value="{{ $bicycle->weight_kg }}" class="form-control" required style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 8px; color: white;">
                </div>
            </div>
        </div>

        <!-- Geometry -->
        <div class="card">
            <h3 style="margin-bottom: 1.5rem; color: var(--accent);">Frame Geometry (mm/deg)</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Stack</label>
                    <input type="number" step="0.1" name="stack" value="{{ $bicycle->stack }}" style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 8px; color: white;">
                </div>
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Reach</label>
                    <input type="number" step="0.1" name="reach" value="{{ $bicycle->reach }}" style="width: 100%; padding: 0.75rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 8px; color: white;">
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
        <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2.5rem;">Update Bicycle</button>
    </div>
</form>
@endsection
