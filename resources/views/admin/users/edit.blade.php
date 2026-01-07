@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit User</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            @error('name')
            <small style="color: var(--danger);">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email')
            <small style="color: var(--danger);">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="e.g., 0123456789">
            @error('phone')
            <small style="color: var(--danger);">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Role</label>
            <select name="role" class="form-control" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @if($user->id === auth()->id())
            <small style="color: var(--gray-400);">You cannot change your own role.</small>
            <input type="hidden" name="role" value="{{ $user->role }}">
            @endif
            @error('role')
            <small style="color: var(--danger);">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Email Verification</label>
            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" name="email_verified" id="email_verified" value="1" {{ $user->email_verified_at ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: var(--success);">
                <label for="email_verified" style="margin: 0; cursor: pointer;">
                    Email is verified
                    @if($user->email_verified_at)
                        <span style="color: var(--success);"><i class="fas fa-check-circle"></i></span>
                    @else
                        <span style="color: var(--warning);"><i class="fas fa-exclamation-circle"></i></span>
                    @endif
                </label>
            </div>
            <small style="color: var(--gray-400);">Toggle to manually verify or unverify this user's email.</small>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update User
            </button>
            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
