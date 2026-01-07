<a href="{{ route('notifications') }}" class="sidebar-link {{ request()->routeIs('notifications') ? 'active' : '' }}" wire:key="notification-badge">
    <i class="fas fa-bell"></i>
    <span>Notifications</span>
    @if($count > 0)
    <span class="badge badge-danger">{{ $count > 99 ? '99+' : $count }}</span>
    @endif
</a>
