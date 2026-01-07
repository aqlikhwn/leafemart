@extends('layouts.app')

@section('title', 'My Messages')

@section('content')
<div class="page-header">
    <h1 class="page-title">My Messages</h1>
    <a href="{{ route('about') }}#contact-form" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Message
    </a>
</div>

@if($messages->count() > 0)
<div style="display: flex; flex-direction: column; gap: 20px;">
    @foreach($messages as $message)
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
            <div>
                <h4 style="color: var(--primary-dark); margin-bottom: 5px;">{{ $message->subject }}</h4>
                <div style="color: var(--gray-400); font-size: 13px;">
                    <i class="fas fa-clock"></i> Sent on {{ $message->created_at->format('F d, Y at H:i') }}
                </div>
            </div>
            @if($message->isReplied())
                <span class="badge badge-success"><i class="fas fa-check"></i> Replied</span>
            @else
                <span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Awaiting Reply</span>
            @endif
        </div>
        
        <!-- Original Message -->
        <div style="background: var(--gray-100); padding: 15px; border-radius: 10px; margin-bottom: 15px;">
            <div style="font-size: 12px; color: var(--gray-400); margin-bottom: 8px;">
                <i class="fas fa-user"></i> Your Message
            </div>
            <p style="white-space: pre-wrap; line-height: 1.6; margin: 0;">{{ $message->message }}</p>

            {{-- Display user attached images --}}
            @if($message->images)
            @php $userImages = json_decode($message->images, true); @endphp
            @if(is_array($userImages) && count($userImages) > 0)
            <div style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap;">
                @foreach($userImages as $img)
                <a href="{{ asset('storage/' . $img) }}" target="_blank">
                    <img src="{{ asset('storage/' . $img) }}" alt="Attachment" style="height: 60px; border-radius: 6px;">
                </a>
                @endforeach
            </div>
            @endif
            @endif
        </div>
        
        <!-- Admin Reply -->
        @if($message->isReplied())
        <div style="background: #d4edda; padding: 15px; border-radius: 10px; border-left: 4px solid var(--success);">
            <div style="font-size: 12px; color: var(--success); margin-bottom: 8px;">
                <i class="fas fa-reply"></i> Admin Reply 
                <span style="color: var(--gray-400);">• {{ $message->replied_at->format('M d, Y H:i') }}</span>
            </div>
            <p style="white-space: pre-wrap; line-height: 1.6; margin: 0;">{{ $message->admin_reply }}</p>

            {{-- Display admin reply images --}}
            @if($message->reply_images)
            @php $replyImages = json_decode($message->reply_images, true); @endphp
            @if(is_array($replyImages) && count($replyImages) > 0)
            <div style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap;">
                @foreach($replyImages as $img)
                <a href="{{ asset('storage/' . $img) }}" target="_blank">
                    <img src="{{ asset('storage/' . $img) }}" alt="Reply Attachment" style="height: 60px; border-radius: 6px;">
                </a>
                @endforeach
            </div>
            @endif
            @endif
        </div>
        @endif
    </div>
    @endforeach
</div>

<div style="margin-top: 20px;">
    {{ $messages->links() }}
</div>
@else
<div class="card" style="text-align: center; padding: 60px 40px;">
    <i class="fas fa-inbox" style="font-size: 60px; color: var(--gray-300); margin-bottom: 20px;"></i>
    <h3 style="color: var(--gray-400); margin-bottom: 10px;">No Messages Yet</h3>
    <p style="color: var(--gray-400); margin-bottom: 20px;">You haven't sent any messages to us yet.</p>
    <a href="{{ route('about') }}#contact-form" class="btn btn-primary">
        <i class="fas fa-envelope"></i> Send Your First Message
    </a>
</div>
@endif
@endsection
