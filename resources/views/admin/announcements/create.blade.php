@extends('layouts.app')

@section('title', 'Send Announcement')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-bullhorn" style="color: var(--primary);"></i> Send Announcement</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="card" style="max-width: 700px;">
    <p style="color: var(--gray-600); margin-bottom: 25px;">
        <i class="fas fa-info-circle" style="color: var(--info);"></i> 
        This announcement will be sent as a notification to <strong>all registered users</strong>.
    </p>

    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Announcement Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" 
                   placeholder="e.g., Store Hours Update, New Products Available" required>
            @error('title')
            <small style="color: var(--danger);">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="5" 
                      placeholder="Write your announcement message here..." required>{{ old('message') }}</textarea>
            @error('message')
            <small style="color: var(--danger);">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Attach Images (Optional)</label>
            <input type="file" name="images[]" accept="image/*" multiple class="form-control" style="padding: 8px;">
            <small style="color: var(--gray-400);">You can select multiple images. Max 2MB each.</small>
            @error('images.*')
            <small style="color: var(--danger);">{{ $message }}</small>
            @enderror
        </div>

        <div style="background: var(--gray-100); padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-users" style="font-size: 24px; color: var(--primary);"></i>
                <div>
                    <strong>Recipients:</strong> {{ \App\Models\User::count() }} users
                    <div style="font-size: 12px; color: var(--gray-400);">All registered users (including admins)</div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" onclick="return confirm('Send this announcement to all users?');">
            <i class="fas fa-paper-plane"></i> Send Announcement
        </button>
    </form>
</div>
@endsection
