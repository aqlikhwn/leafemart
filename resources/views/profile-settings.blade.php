@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="page-header">
    <h1 class="page-title">Profile Settings</h1>
    <a href="{{ route('profile') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Profile
    </a>
</div>

<div class="responsive-grid-2">
    <!-- Update Profile Form -->
    <div class="card">
        <h3 style="color: var(--primary-dark); margin-bottom: 20px;"><i class="fas fa-user-edit"></i> Update Profile</h3>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Profile Picture</label>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="position: relative; display: inline-block;">
                        <div style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; background: var(--gray-100); display: flex; align-items: center; justify-content: center;">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;" id="profilePic">
                            @else
                                <i class="fas fa-user" style="font-size: 24px; color: var(--gray-400);"></i>
                            @endif
                        </div>
                        @if($user->avatar)
                        <label style="position: absolute; top: -4px; right: -4px; width: 18px; height: 18px; background: var(--danger); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" title="Remove picture">
                            <input type="checkbox" name="remove_avatar" value="1" style="display: none;" onchange="document.getElementById('profilePic').style.opacity = this.checked ? '0.3' : '1'">
                            <i class="fas fa-times"></i>
                        </label>
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <input type="file" name="avatar" class="form-control" accept="image/jpeg,image/png,image/jpg">
                        <small style="color: var(--gray-400);">JPG or PNG. Max 2MB.</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">User Name</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" placeholder="e.g., 0123456789">
            </div>

            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3" placeholder="Enter your address">{{ $user->address }}</textarea>
                <small style="color: var(--gray-400);">This address will be used for delivery purposes.</small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Profile
            </button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="card" style="height: fit-content;">
        <h3 style="color: var(--primary-dark); margin-bottom: 20px;"><i class="fas fa-lock"></i> Change Password</h3>

        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-key"></i> Change Password
            </button>
        </form>
    </div>
</div>

<!-- Delete Account Section -->
<div class="card" style="margin-top: 30px; border: 2px solid var(--danger);">
    <h3 style="color: var(--danger); margin-bottom: 10px;"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h3>
    <p style="color: var(--gray-600); margin-bottom: 20px;">
        Once you delete your account, there is no going back. All your data, orders history, and notifications will be permanently removed.
    </p>
    
    <form action="{{ route('profile.delete') }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.');">
        @csrf
        @method('DELETE')
        
        <div class="form-group">
            <label class="form-label">Enter your password to confirm</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required style="max-width: 300px;">
            @error('password')
                <small style="color: var(--danger);">{{ $message }}</small>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> Delete My Account
        </button>
    </form>
</div>
@endsection
