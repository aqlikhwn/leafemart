@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="page-header">
    <h1 class="page-title">Activity Log</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<!-- Filter Pills -->
<div class="category-pills" style="margin-bottom: 20px;">
    <a href="{{ route('admin.activities.index') }}" class="category-pill {{ !$filterType ? 'active' : '' }}">
        All <span style="opacity: 0.7;">({{ $totalCounts['all'] ?? 0 }})</span>
    </a>
    <a href="{{ route('admin.activities.index', ['type' => 'admin_action']) }}" class="category-pill {{ $filterType === 'admin_action' ? 'active' : '' }}">
        <i class="fas fa-cog"></i> Admin Actions <span style="opacity: 0.7;">({{ $totalCounts['admin_action'] ?? 0 }})</span>
    </a>
    <a href="{{ route('admin.activities.index', ['type' => 'order']) }}" class="category-pill {{ $filterType === 'order' ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i> Orders <span style="opacity: 0.7;">({{ $totalCounts['order'] ?? 0 }})</span>
    </a>
    <a href="{{ route('admin.activities.index', ['type' => 'user']) }}" class="category-pill {{ $filterType === 'user' ? 'active' : '' }}">
        <i class="fas fa-user-plus"></i> Users <span style="opacity: 0.7;">({{ $totalCounts['user'] ?? 0 }})</span>
    </a>
    <a href="{{ route('admin.activities.index', ['type' => 'message']) }}" class="category-pill {{ $filterType === 'message' ? 'active' : '' }}">
        <i class="fas fa-envelope"></i> Messages <span style="opacity: 0.7;">({{ $totalCounts['message'] ?? 0 }})</span>
    </a>
</div>

<div class="card">
    @if($activities->count() > 0)
    <div style="display: flex; flex-direction: column; gap: 0;">
        @foreach($activities as $activity)
        <a href="{{ $activity['link'] ?? '#' }}" style="display: flex; align-items: flex-start; gap: 15px; padding: 15px; border-radius: 12px; text-decoration: none; transition: all 0.2s ease; border-bottom: 1px solid var(--gray-200);" class="activity-item">
            <div style="width: 40px; height: 40px; background: {{ $activity['color'] }}20; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas {{ $activity['icon'] }}" style="color: {{ $activity['color'] }}; font-size: 16px;"></i>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-weight: 600; color: var(--primary-dark); margin-bottom: 3px;">
                    {{ $activity['title'] }}
                </div>
                <div style="color: var(--gray-400); font-size: 13px;">
                    {{ $activity['description'] }}
                </div>
            </div>
            <div style="color: var(--gray-400); font-size: 12px; white-space: nowrap;">
                {{ $activity['time']->diffForHumans() }}
            </div>
        </a>
        @endforeach
    </div>
    
    <div style="margin-top: 20px;">
        {{ $activities->links() }}
    </div>
    @else
    <div style="text-align: center; padding: 40px; color: var(--gray-400);">
        <i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 15px;"></i>
        <p>No activities yet</p>
    </div>
    @endif
</div>

<style>
    .activity-item:hover {
        background: var(--primary-light);
    }
    .activity-item:last-child {
        border-bottom: none;
    }
</style>
@endsection
