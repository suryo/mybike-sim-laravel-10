@extends('layouts.admin')

@section('title', 'Create Rider Profile')
@section('subtitle', 'Enter performance and physical metrics for a new rider.')

@section('actions')
<a href="{{ route('admin.riders.index') }}" class="btn btn-ghost">Back to List</a>
@endsection

@section('content')
<form action="{{ route('admin.riders.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card p-4">
                <h3 class="section-title mb-4">General Information</h3>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Rider Full Name</label>
                        <input type="text" name="name" class="field" value="{{ old('name') }}" placeholder="e.g. John Doe" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" step="0.1" name="weight_kg" class="field" value="{{ old('weight_kg', 75) }}" required>
                    </div>
                </div>

                <h3 class="section-title mt-5 mb-4">Performance Metrics</h3>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">FTP (Watts)</label>
                        <input type="number" name="ftp" class="field" value="{{ old('ftp', 250) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Max Heart Rate (bpm)</label>
                        <input type="number" name="max_hr" class="field" value="{{ old('max_hr', 190) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Max Power (W)</label>
                        <input type="number" name="max_power_w" class="field" value="{{ old('max_power_w', 350) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Aerobic Threshold (W)</label>
                        <input type="number" name="aerobic_threshold_w" class="field" value="{{ old('aerobic_threshold_w', 180) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Anaerobic Threshold (W)</label>
                        <input type="number" name="anaerobic_threshold_w" class="field" value="{{ old('anaerobic_threshold_w', 285) }}">
                    </div>
                </div>

                <div class="mt-5 d-flex gap-3">
                    <button type="submit" class="btn btn-primary">Create Profile</button>
                    <a href="{{ route('admin.riders.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 bg-glass text-center">
                <div class="icon-lg mb-3">🚲</div>
                <h4>Want precise results?</h4>
                <p class="text-secondary small">Use the Bike Fitting Wizard to get professional recommendations based on your body dimensions.</p>
                <a href="{{ route('admin.fitting.wizard') }}" class="btn btn-primary btn-sm mt-2">Open Fitting Wizard</a>
            </div>
        </div>
    </div>
</form>

<style>
.section-title { font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; color: var(--accent); border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; }
.form-label { font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem; display: block; }
.icon-lg { font-size: 3rem; }
.bg-glass { background: var(--glass); border: 1px solid var(--border); }
</style>
@endsection
