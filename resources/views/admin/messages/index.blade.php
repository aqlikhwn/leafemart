@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="page-header">
    <h1 class="page-title">Customer Messages</h1>
    @if($unreadCount > 0)
        <span class="badge badge-danger" style="font-size: 14px;">{{ $unreadCount }} Unread</span>
    @endif
</div>

<div class="card">
    @if($messages->count() > 0)
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Reply Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)
                <tr style="{{ !$message->is_read ? 'background: var(--primary-light);' : '' }}">
                    <td>
                        @if(!$message->is_read)
                            <span class="badge badge-warning"><i class="fas fa-envelope"></i> New</span>
                        @else
                            <span class="badge badge-secondary"><i class="fas fa-envelope-open"></i> Read</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $message->name }}</strong>
                        <div style="font-size: 12px; color: var(--gray-400);">{{ $message->email }}</div>
                    </td>
                    <td>{{ Str::limit($message->subject, 40) }}</td>
                    <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        @if($message->isReplied())
                            <span class="badge badge-success"><i class="fas fa-check"></i> Replied</span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-clock"></i> Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-primary" style="padding: 8px 12px; font-size: 13px;">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px;">
        {{ $messages->links() }}
    </div>
    @else
    <div style="text-align: center; padding: 40px; color: var(--gray-400);">
        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px;"></i>
        <p>No messages yet</p>
    </div>
    @endif
</div>
@endsection
