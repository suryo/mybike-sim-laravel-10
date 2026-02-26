@extends('layouts.admin')

@section('title', 'Rider Profiles')
@section('subtitle', 'Manage different rider profiles and performance metrics.')

@section('actions')
<div class="header-btns">
    <a href="{{ route('admin.fitting.wizard') }}" class="btn btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.77 3.77z"></path></svg>
        <span>Fitting Wizard</span>
    </a>
    <a href="{{ route('admin.riders.create') }}" class="btn btn-ghost">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
        <span>Manual Profile</span>
    </a>
</div>
@endsection

@section('content')
<div class="rider-grid">
    <div class="card table-container">
        <table class="rider-table">
            <thead>
                <tr>
                    <th>Rider Info</th>
                    <th>Performance</th>
                    <th>Geometry</th>
                    <th>Status</th>
                    <th>Last Active</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riders as $rider)
                <tr class="rider-row">
                    <td>
                        <div class="rider-info">
                            <div class="rider-avatar">
                                {{ substr($rider->name, 0, 1) }}
                            </div>
                            <div class="rider-details">
                                <span class="rider-name">{{ $rider->name }}</span>
                                <span class="rider-slug">@/{{ $rider->slug }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="metrics">
                            <div class="metric-item">
                                <span class="metric-label">FTP</span>
                                <span class="metric-value">{{ $rider->ftp ?? '--' }}<small>W</small></span>
                            </div>
                            <div class="metric-item">
                                <span class="metric-label">WEIGHT</span>
                                <span class="metric-value">{{ $rider->weight_kg }}<small>kg</small></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="metrics">
                            <div class="metric-item">
                                <span class="metric-label">STACK</span>
                                <span class="metric-value">{{ $rider->stack_cm ? $rider->stack_cm * 10 : '--' }}<small>mm</small></span>
                            </div>
                            <div class="metric-item">
                                <span class="metric-label">REACH</span>
                                <span class="metric-value">{{ $rider->reach_cm ? $rider->reach_cm * 10 : '--' }}<small>mm</small></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($rider->height_cm)
                        <span class="status-badge success">
                            <span class="dot"></span> Optimized
                        </span>
                        @else
                        <span class="status-badge warning">
                            <span class="dot"></span> Pending Fit
                        </span>
                        @endif
                    </td>
                    <td class="timestamp">
                        {{ $rider->updated_at->diffForHumans() }}
                    </td>
                    <td class="text-end">
                        <div class="action-group">
                            <a href="{{ route('admin.riders.edit', $rider) }}" class="action-btn edit" title="Edit Profile">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>
                            <form action="{{ route('admin.riders.destroy', $rider) }}" method="POST" class="d-inline" onsubmit="return confirm('Archive this rider profile?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Delete Profile">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <div class="empty-icon">👤</div>
                        <h3>No Riders Found</h3>
                        <p>Start by creating a profile through the Fitting Wizard.</p>
                        <a href="{{ route('admin.fitting.wizard') }}" class="btn btn-primary mt-3">Start Fitting</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($riders->hasPages())
        <div class="pagination-footer">
            {{ $riders->links() }}
        </div>
        @endif
    </div>
</div>

<style>
.header-btns { display: flex; gap: 1rem; }
.card.table-container { padding: 0; overflow: hidden; background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255, 255, 255, 0.05); }

.rider-table { width: 100%; border-collapse: collapse; text-align: left; }
.rider-table th { padding: 1.25rem 2rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); background: rgba(15, 23, 42, 0.3); border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
.rider-table td { padding: 1.5rem 2rem; border-bottom: 1px solid rgba(255, 255, 255, 0.03); vertical-align: middle; }

.rider-row { transition: all 0.3s; }
.rider-row:hover { background: rgba(56, 189, 248, 0.03); }

.rider-info { display: flex; align-items: center; gap: 1.25rem; }
.rider-avatar { width: 45px; height: 45px; border-radius: 12px; background: var(--gradient); color: var(--bg-color); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; box-shadow: 0 4px 12px rgba(56, 189, 248, 0.2); }
.rider-details { display: flex; flex-direction: column; }
.rider-name { font-weight: 700; color: var(--text-primary); font-size: 1rem; }
.rider-slug { font-size: 0.8rem; color: var(--text-secondary); opacity: 0.7; }

.metrics { display: flex; gap: 2rem; }
.metric-item { display: flex; flex-direction: column; }
.metric-label { font-size: 0.65rem; font-weight: 800; color: var(--text-secondary); margin-bottom: 0.25rem; }
.metric-value { font-family: 'JetBrains Mono', monospace; font-weight: 700; color: var(--accent); font-size: 0.95rem; }
.metric-value small { font-size: 0.7rem; opacity: 0.6; margin-left: 2px; }

.status-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 30px; font-size: 0.8rem; font-weight: 700; }
.status-badge.success { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
.status-badge.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
.status-badge .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; box-shadow: 0 0 8px currentColor; }

.timestamp { font-size: 0.85rem; color: var(--text-secondary); }

.action-group { display: flex; gap: 0.75rem; justify-content: flex-end; }
.action-btn { width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border-radius: 10px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); color: var(--text-secondary); transition: all 0.3s; cursor: pointer; text-decoration: none; }
.action-btn:hover { color: var(--text-primary); transform: translateY(-3px); }
.action-btn.edit:hover { background: rgba(56, 189, 248, 0.1); border-color: var(--accent); color: var(--accent); }
.action-btn.delete:hover { background: rgba(239, 68, 68, 0.1); border-color: #ef4444; color: #ef4444; }

.empty-state { text-align: center; padding: 5rem 2rem; }
.empty-icon { font-size: 3rem; margin-bottom: 1.5rem; opacity: 0.3; }
.empty-state h3 { margin-bottom: 0.5rem; font-weight: 800; }
.empty-state p { color: var(--text-secondary); }

.pagination-footer { padding: 1.5rem 2rem; background: rgba(15, 23, 42, 0.2); border-top: 1px solid rgba(255, 255, 255, 0.05); }

@media (max-width: 1400px) {
    .metrics { gap: 1rem; }
    .rider-table th, .rider-table td { padding: 1rem 1.25rem; }
}
</style>
@endsection
