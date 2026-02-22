@extends('layouts.admin')

@section('title', 'Manage Bicycles')
@section('subtitle', 'View and manage the technical database of all available bicycles.')

@section('actions')
<a href="{{ route('admin.bicycles.create') }}" class="btn btn-primary">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    Add Bicycle
</a>
@endsection

@section('content')
<div class="card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 2px solid var(--border);">
                <th style="padding: 1.25rem 1rem;">Bicycle</th>
                <th style="padding: 1.25rem 1rem;">Category</th>
                <th style="padding: 1.25rem 1rem;">Weight</th>
                <th style="padding: 1.25rem 1rem;">Tech Specs</th>
                <th style="padding: 1.25rem 1rem; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bicycles as $bike)
            <tr style="border-bottom: 1px solid var(--border); transition: background 0.2s;">
                <td style="padding: 1.25rem 1rem;">
                    <div style="font-weight: 700; font-size: 1rem;">{{ $bike->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary); font-family: 'JetBrains Mono', monospace;">{{ $bike->slug }}</div>
                </td>
                <td style="padding: 1.25rem 1rem;">
                    <span style="background: rgba(56, 189, 248, 0.1); color: var(--accent); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                        {{ $bike->category->name ?? $bike->type }}
                    </span>
                </td>
                <td style="padding: 1.25rem 1rem; font-family: 'JetBrains Mono', monospace;">{{ $bike->weight_kg }}kg</td>
                <td style="padding: 1.25rem 1rem;">
                    <div style="font-size: 0.8rem;">Stack: <span style="color: var(--accent);">{{ $bike->stack ?? '-' }}</span></div>
                    <div style="font-size: 0.8rem;">Reach: <span style="color: var(--accent);">{{ $bike->reach ?? '-' }}</span></div>
                </td>
                <td style="padding: 1.25rem 1rem; text-align: right;">
                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <a href="{{ route('admin.bicycles.edit', $bike) }}" style="color: var(--text-secondary); text-decoration: none;" title="Edit">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>
                        <form action="{{ route('admin.bicycles.destroy', $bike) }}" method="POST" onsubmit="return confirm('Delete this bicycle?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; padding: 0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="padding: 1.5rem; border-top: 1px solid var(--border);">
        {{ $bicycles->links() }}
    </div>
</div>
@endsection
