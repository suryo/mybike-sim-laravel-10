@extends('layouts.admin')

@section('title', 'Edit Rider Profile')
@section('subtitle', 'Update performance and physical metrics for ' . $rider->name)

@section('actions')
<a href="{{ route('admin.riders.index') }}" class="btn btn-ghost">Back to List</a>
@endsection

@section('content')
<form action="{{ route('admin.riders.update', $rider) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="row">
        <div class="col-md-8">
            <div class="card p-4">
                <h3 class="section-title mb-4">General Information</h3>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Rider Full Name</label>
                        <input type="text" name="name" class="field" value="{{ old('name', $rider->name) }}" placeholder="e.g. John Doe" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" step="0.1" name="weight_kg" class="field" value="{{ old('weight_kg', $rider->weight_kg) }}" required>
                    </div>
                </div>

                <h3 class="section-title mt-5 mb-4">Performance Metrics</h3>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">FTP (Watts)</label>
                        <input type="number" name="ftp" class="field" value="{{ old('ftp', $rider->ftp) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Max Heart Rate (bpm)</label>
                        <input type="number" name="max_hr" class="field" value="{{ old('max_hr', $rider->max_hr) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Max Power (W)</label>
                        <input type="number" name="max_power_w" class="field" value="{{ old('max_power_w', $rider->max_power_w) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Aerobic Threshold (W)</label>
                        <input type="number" name="aerobic_threshold_w" class="field" value="{{ old('aerobic_threshold_w', $rider->aerobic_threshold_w) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Anaerobic Threshold (W)</label>
                        <input type="number" name="anaerobic_threshold_w" class="field" value="{{ old('anaerobic_threshold_w', $rider->anaerobic_threshold_w) }}">
                    </div>
                </div>

                <div class="mt-5 d-flex gap-3">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="{{ route('admin.riders.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </div>

            @if($rider->height_cm)
            <div class="card p-4 mt-4 bg-glass">
                <h3 class="section-title mb-4">Physical Dimensions (from Fitting Wizard)</h3>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Height</label>
                        <div class="text-accent h4">{{ $rider->height_cm }} cm</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Leg Length</label>
                        <div class="text-accent h4">{{ $rider->leg_length_cm }} cm</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Arm Length</label>
                        <div class="text-accent h4">{{ $rider->arm_length_cm }} cm</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Back Angle Pref.</label>
                        <div class="text-accent h4">{{ $rider->back_angle_preference }}°</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="col-md-4">
            <div class="card p-4 bg-glass text-center">
                <div class="icon-lg mb-3">🛠</div>
                <h4>Recalculate Fit?</h4>
                <p class="text-secondary small">Redo the Bike Fitting Wizard if your body dimensions or flexibility have changed.</p>
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
.text-accent { color: var(--accent); font-weight: 800; }
</style>
@endsection
