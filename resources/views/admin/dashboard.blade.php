@extends('layouts.admin')

@section('title', 'Dashboard Overview')
@section('subtitle', 'A summary of your platform\'s performance and content.')

@section('content')
<div class="stats-grid">
    <div class="card stat-card">
        <span class="stat-label">Total Bicycles</span>
        <span class="stat-value">{{ $stats['bikes'] }}</span>
    </div>
    <div class="card stat-card">
        <span class="stat-label">Published Articles</span>
        <span class="stat-value">{{ $stats['news'] }}</span>
    </div>
    <div class="card stat-card">
        <span class="stat-label">Upcoming Events</span>
        <span class="stat-value">{{ $stats['events'] }}</span>
    </div>
    <div class="card stat-card">
        <span class="stat-label">Planned Routes</span>
        <span class="stat-value">{{ $stats['routes'] }}</span>
    </div>
</div>

<div class="grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <div class="card">
        <h3 style="margin-bottom: 1.5rem;">Recent Bicycles</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem 0;">Name</th>
                    <th style="padding: 1rem 0;">Category</th>
                    <th style="padding: 1rem 0;">Weight</th>
                    <th style="padding: 1rem 0;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Bicycle::latest()->take(5)->get() as $bike)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem 0; font-weight: 600;">{{ $bike->name }}</td>
                    <td style="padding: 1rem 0; color: var(--text-secondary);">{{ $bike->category->name ?? 'N/A' }}</td>
                    <td style="padding: 1rem 0; font-family: 'JetBrains Mono', monospace;">{{ $bike->weight_kg }}kg</td>
                    <td style="padding: 1rem 0;">
                        <a href="{{ route('admin.bicycles.edit', $bike) }}" style="color: var(--accent); text-decoration: none; font-size: 0.85rem;">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 1.5rem;">Quick Actions</h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <a href="{{ route('admin.bicycles.create') }}" class="btn btn-primary" style="justify-content: center;">Add New Bicycle</a>
            <a href="#" class="btn btn-ghost" style="justify-content: center;">Write Article</a>
            <a href="#" class="btn btn-ghost" style="justify-content: center;">Create Event</a>
        </div>
    </div>
</div>
@endsection
