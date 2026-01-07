@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<style>
    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 20px;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .notification-item.unread {
        background: var(--primary-light);
    }
    .notification-item.read {
        background: transparent;
    }
    .notification-item:hover {
        background: var(--gray-100) !important;
        transform: translateX(5px);
    }
    .notification-icon {
        width: 45px;
        height: 45px;
        background: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }
    .notification-icon.read {
        background: var(--gray-200);
        color: var(--gray-400);
    }
    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-content {
        background: white;
        border-radius: 16px;
        max-width: 500px;
        width: 100%;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlide 0.3s ease;
    }
    @keyframes modalSlide {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .modal-header {
        padding: 20px 25px;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        align-items: center;
        gap: 15px;
        flex-shrink: 0;
    }
    .modal-body {
        padding: 25px;
        overflow-y: auto;
        flex: 1;
    }
    .modal-footer {
        padding: 15px 25px;
        border-top: 1px solid var(--gray-200);
        text-align: right;
        flex-shrink: 0;
    }
</style>
<div class="page-header">
    <h1 class="page-title">Notifications</h1>
    @if($unreadCount > 0)
    <form action="{{ route('notifications.markAllRead') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-secondary">
            <i class="fas fa-check-double"></i> Mark All as Read
        </button>
    </form>
    @endif
</div>

<!-- Filter Section -->
<div class="category-pills" style="justify-content: space-between; align-items: center;">
    <!-- Type Filters -->
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('notifications') }}?status={{ $status }}" 
           class="category-pill {{ $type === 'all' ? 'active' : '' }}">
            All <span style="opacity: 0.7;">({{ $allCount }})</span>
        </a>
        <a href="{{ route('notifications') }}?type=orders&status={{ $status }}" 
           class="category-pill {{ $type === 'orders' ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i> Orders <span style="opacity: 0.7;">({{ $typeCounts['orders'] ?? 0 }})</span>
        </a>
        <a href="{{ route('notifications') }}?type=announcements&status={{ $status }}" 
           class="category-pill {{ $type === 'announcements' ? 'active' : '' }}">
            <i class="fas fa-bullhorn"></i> Announcements <span style="opacity: 0.7;">({{ $typeCounts['announcements'] ?? 0 }})</span>
        </a>
        <a href="{{ route('notifications') }}?type=login&status={{ $status }}" 
           class="category-pill {{ $type === 'login' ? 'active' : '' }}">
            <i class="fas fa-shield-alt"></i> Security <span style="opacity: 0.7;">({{ $typeCounts['login'] ?? 0 }})</span>
        </a>
        <a href="{{ route('notifications') }}?type=messages&status={{ $status }}" 
           class="category-pill {{ $type === 'messages' ? 'active' : '' }}">
            <i class="fas fa-envelope"></i> Messages <span style="opacity: 0.7;">({{ $typeCounts['messages'] ?? 0 }})</span>
        </a>
    </div>

    
    <!-- Status Filters -->
    <div style="display: flex; gap: 8px; background: #E8F0FE; padding: 6px; border-radius: 50px;">
        <a href="{{ route('notifications') }}?type={{ $type }}" 
           class="category-pill {{ $status === 'all' ? 'active' : '' }}" style="border: none;">
            All
        </a>
        <a href="{{ route('notifications') }}?type={{ $type }}&status=unread" 
           class="category-pill {{ $status === 'unread' ? 'active' : '' }}" style="border: none;">
            Unread @if($unreadCount > 0)<span style="background: var(--danger); color: white; padding: 1px 6px; border-radius: 10px; font-size: 10px; margin-left: 3px;">{{ $unreadCount }}</span>@endif
        </a>
        <a href="{{ route('notifications') }}?type={{ $type }}&status=read" 
           class="category-pill {{ $status === 'read' ? 'active' : '' }}" style="border: none;">
            Read
        </a>
    </div>
</div>

<div class="card">
    @if($notifications->count() > 0)
    @foreach($notifications as $notification)
    @if($notification->type === 'announcement')
    {{-- Announcement: Show popup instead of redirect --}}
    @php
        $images = $notification->image ? json_decode($notification->image, true) : [];
        $imageUrls = array_map(fn($img) => asset('storage/' . $img), is_array($images) ? $images : []);
    @endphp
    <div class="notification-item {{ $notification->read ? 'read' : 'unread' }}" 
         onclick="showAnnouncementModal({{ $notification->id }}, '{{ addslashes($notification->title) }}', `{{ addslashes($notification->message) }}`, '{{ $notification->created_at->format('M d, Y h:i A') }}', {{ json_encode($imageUrls) }})">
        <div class="notification-icon {{ $notification->read ? 'read' : '' }}">
            <i class="fas fa-bullhorn"></i>
        </div>
        <div style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <strong style="color: var(--primary-dark);">{{ $notification->title }}</strong>
                <small style="color: var(--gray-400);">{{ $notification->created_at->diffForHumans() }}</small>
            </div>
            <p style="color: var(--gray-600); margin-top: 5px;">{{ Str::limit($notification->message, 100) }}</p>
            <small style="color: var(--primary); margin-top: 8px; display: inline-block;">
                <i class="fas fa-arrow-right"></i> Click to view details
            </small>
        </div>
        @if(!$notification->read)
        <div style="padding: 4px 8px; background: var(--primary); color: white; border-radius: 50px; font-size: 10px; font-weight: 600;">NEW</div>
        @else
        <div style="padding: 4px 8px; background: var(--gray-200); color: var(--gray-500); border-radius: 50px; font-size: 10px;"><i class="fas fa-check"></i></div>
        @endif
    </div>
    @else
    {{-- Other notifications: Redirect as usual --}}
    <a href="{{ route('notifications.click', $notification->id) }}" class="notification-item {{ $notification->read ? 'read' : 'unread' }}">
        <div class="notification-icon {{ $notification->read ? 'read' : '' }}">
            <i class="fas fa-bell"></i>
        </div>
        <div style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <strong style="color: var(--primary-dark);">{{ $notification->title }}</strong>
                <small style="color: var(--gray-400);">{{ $notification->created_at->diffForHumans() }}</small>
            </div>
            <p style="color: var(--gray-600); margin-top: 5px;">{{ $notification->message }}</p>
            <small style="color: var(--primary); margin-top: 8px; display: inline-block;">
                <i class="fas fa-arrow-right"></i> Click to view details
            </small>
        </div>
        @if(!$notification->read)
        <div style="padding: 4px 8px; background: var(--primary); color: white; border-radius: 50px; font-size: 10px; font-weight: 600;">NEW</div>
        @else
        <div style="padding: 4px 8px; background: var(--gray-200); color: var(--gray-500); border-radius: 50px; font-size: 10px;"><i class="fas fa-check"></i></div>
        @endif
    </a>
    @endif
    @if(!$loop->last)
    <hr style="border: none; border-top: 1px solid var(--gray-200); margin: 0;">
    @endif
    @endforeach

    <div style="margin-top: 20px;">
        {{ $notifications->links() }}
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-bell-slash"></i>
        <h3>No Notifications</h3>
        <p>You're all caught up!</p>
    </div>
    @endif
</div>

{{-- Announcement Modal --}}
<div class="modal-overlay" id="announcementModal" onclick="closeModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                <i class="fas fa-bullhorn" style="font-size: 20px;"></i>
            </div>
            <div>
                <h3 style="color: var(--primary-dark); margin: 0;" id="modalTitle">Announcement</h3>
                <small style="color: var(--gray-400);" id="modalDate"></small>
            </div>
        </div>
        <div class="modal-body">
            <div id="modalImageContainer" style="display: none; margin-bottom: 15px;"></div>
            <p style="color: var(--gray-600); line-height: 1.8; white-space: pre-wrap;" id="modalMessage"></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="closeModal()">
                <i class="fas fa-check"></i> Got it
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
var currentNotificationId = null;

function showAnnouncementModal(id, title, message, date, images) {
    currentNotificationId = id;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalMessage').textContent = message;
    document.getElementById('modalDate').textContent = date;
    
    // Handle multiple images
    const imageContainer = document.getElementById('modalImageContainer');
    imageContainer.innerHTML = '';
    
    if (images && images.length > 0) {
        images.forEach(function(src) {
            const img = document.createElement('img');
            img.src = src;
            img.alt = 'Announcement Image';
            img.style.cssText = 'max-width: 100%; border-radius: 10px; margin-bottom: 10px;';
            imageContainer.appendChild(img);
        });
        imageContainer.style.display = 'block';
    } else {
        imageContainer.style.display = 'none';
    }
    
    document.getElementById('announcementModal').classList.add('active');
    
    // Mark as read via AJAX (without reload)
    fetch('{{ url("/notifications") }}/' + id + '/read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
}

function closeModal(event) {
    if (!event || event.target === document.getElementById('announcementModal')) {
        document.getElementById('announcementModal').classList.remove('active');
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endpush
@endsection




