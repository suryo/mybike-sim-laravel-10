@extends('layouts.admin')

@section('title', 'Rider Profiles')
@section('subtitle', 'Manage different rider profiles and performance metrics.')

@section('actions')
<a href="{{ route('admin.fitting.wizard') }}" class="btn btn-primary" style="background: var(--accent); color: #000;">+ Bike Fitting Wizard</a>
<a href="{{ route('admin.riders.create') }}" class="btn btn-ghost">+ Manual Profile</a>
@endsection

@section('content')
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Rider Name</th>
                <th>Weight</th>
                <th>FTP</th>
                <th>Max HR</th>
                <th>Measurements</th>
                <th>Last Update</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riders as $rider)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar" style="width: 40px; height: 40px; background: var(--glass); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; border: 1px solid var(--border);">
                            {{ substr($rider->name, 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight: 700;">{{ $rider->name }}</div>
                            <div class="text-secondary" style="font-size: 0.75rem;">{{ $rider->slug }}</div>
                        </div>
                    </div>
                </td>
                <td>{{ $rider->weight_kg }} kg</td>
                <td>{{ $rider->ftp ?? '--' }} W</td>
                <td>{{ $rider->max_hr ?? '--' }} bpm</td>
                <td>
                    @if($rider->height_cm)
                    <span class="badge" title="Has measurements">📏 Fit Set</span>
                    @else
                    <span class="text-secondary">Not Set</span>
                    @endif
                </td>
                <td>{{ $rider->updated_at->diffForHumans() }}</td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.riders.edit', $rider) }}" class="btn btn-icon" title="Edit">
                            ✎
                        </a>
                        <form action="{{ route('admin.riders.destroy', $rider) }}" method="POST" onsubmit="return confirm('Delete this rider?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-icon text-danger" title="Delete">
                                🗑
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <div class="text-secondary mb-3">No rider profiles found.</div>
                    <a href="{{ route('admin.fitting.wizard') }}" class="btn btn-primary">Start Bike Fitting</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="mt-4">
        {{ $riders->links() }}
    </div>
</div>

<style>
.badge { background: rgba(56,189,248,0.1); color: var(--accent); padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.7rem; font-weight: 700; border: 1px solid rgba(56,189,248,0.2); }
.btn-icon { width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid var(--border); background: var(--glass); color: var(--text-primary); text-decoration: none; cursor: pointer; transition: all 0.2s; }
.btn-icon:hover { border-color: var(--accent); background: rgba(56,189,248,0.1); }
.text-danger:hover { background: rgba(239, 68, 68, 0.1); border-color: #ef4444; }
</style>
@endsection
