@extends('layouts.app')

@section('title', 'View Message')

@section('content')
<div class="page-header">
    <a href="{{ route('admin.messages.index') }}" class="btn" style="background: var(--gray-200);">
        <i class="fas fa-arrow-left"></i> Back to Messages
    </a>
    <h1 class="page-title">View Message</h1>
</div>

<div class="responsive-grid-2-1">
    <!-- Message Content -->
    <div>
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
                <div>
                    <h3 style="color: var(--primary-dark); margin-bottom: 5px;">{{ $message->subject }}</h3>
                    <div style="color: var(--gray-400); font-size: 13px;">
                        Received on {{ $message->created_at->format('F d, Y at H:i') }}
                    </div>
                </div>
                @if($message->isReplied())
                    <span class="badge badge-success"><i class="fas fa-check"></i> Replied</span>
                @else
                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Awaiting Reply</span>
                @endif
            </div>
            
            <div style="background: var(--gray-100); padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                <p style="white-space: pre-wrap; line-height: 1.8;">{{ $message->message }}</p>
            </div>

            {{-- Display user attached images --}}
            @if($message->images)
            @php $userImages = json_decode($message->images, true); @endphp
            @if(is_array($userImages) && count($userImages) > 0)
            <div style="margin-bottom: 20px;">
                <h5 style="color: var(--primary-dark); margin-bottom: 10px;"><i class="fas fa-images"></i> Attached Images</h5>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @foreach($userImages as $img)
                    <a href="{{ asset('storage/' . $img) }}" target="_blank">
                        <img src="{{ asset('storage/' . $img) }}" alt="Attachment" style="height: 100px; border-radius: 8px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
            @endif
            
            @if($message->isReplied())
            <div style="border-top: 1px solid var(--gray-200); padding-top: 20px; margin-top: 20px;">
                <h4 style="color: var(--success); margin-bottom: 15px;">
                    <i class="fas fa-reply"></i> Your Reply
                    <span style="font-weight: normal; color: var(--gray-400); font-size: 12px;">
                        ({{ $message->replied_at->format('M d, Y H:i') }})
                    </span>
                </h4>
                <div style="background: #d4edda; padding: 15px; border-radius: 10px;">
                    <p style="white-space: pre-wrap; line-height: 1.6;">{{ $message->admin_reply }}</p>
                </div>

                {{-- Display reply attached images --}}
                @if($message->reply_images)
                @php $replyImages = json_decode($message->reply_images, true); @endphp
                @if(is_array($replyImages) && count($replyImages) > 0)
                <div style="margin-top: 15px;">
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        @foreach($replyImages as $img)
                        <a href="{{ asset('storage/' . $img) }}" target="_blank">
                            <img src="{{ asset('storage/' . $img) }}" alt="Reply Attachment" style="height: 80px; border-radius: 8px; cursor: pointer;">
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                @endif
            </div>
            @endif
        </div>
        
        @if(!$message->isReplied())
        <div class="card" style="margin-top: 20px;">
            <h4 style="color: var(--primary-dark); margin-bottom: 15px;"><i class="fas fa-reply"></i> Send Reply</h4>
            
            <form action="{{ route('admin.messages.reply', $message) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 20px;">
                    <textarea name="admin_reply" class="form-control" rows="5" placeholder="Type your reply here..." required>{{ old('admin_reply') }}</textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--primary-dark);">Attach Images (Optional)</label>
                    <input type="file" name="images[]" accept="image/*" multiple class="form-control" style="padding: 8px;">
                    <small style="color: var(--gray-400);">You can select multiple images. Max 2MB each.</small>
                </div>

                <button type="submit" class="btn btn-success" style="width: 100%;">
                    <i class="fas fa-paper-plane"></i> Send Reply
                </button>
            </form>
        </div>
        @endif
    </div>
    
    <!-- Sender Info -->
    <div>
        <div class="card">
            <h4 style="color: var(--primary-dark); margin-bottom: 20px;"><i class="fas fa-user"></i> Sender Info</h4>
            
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 20px;">
                    {{ strtoupper(substr($message->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight: 600;">{{ $message->name }}</div>
                    <div style="color: var(--gray-400); font-size: 13px;">{{ $message->email }}</div>
                </div>
            </div>
            
            @if($message->user)
            <div style="border-top: 1px solid var(--gray-200); padding-top: 15px;">
                <div style="font-size: 13px; color: var(--gray-400); margin-bottom: 10px;">Registered User</div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-user-check" style="color: var(--success);"></i>
                    <span>{{ $message->user->name }}</span>
                </div>
            </div>
            @endif
        </div>
        
        <div class="card" style="margin-top: 20px;">
            <h4 style="color: var(--primary-dark); margin-bottom: 15px;"><i class="fas fa-info-circle"></i> Quick Actions</h4>
            
            <a href="mailto:{{ $message->email }}" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">
                <i class="fas fa-envelope"></i> Email Directly
            </a>
        </div>
    </div>
</div>
@endsection
