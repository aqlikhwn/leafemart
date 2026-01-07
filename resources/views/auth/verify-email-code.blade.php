@extends('layouts.auth')

@section('title', 'Enter Verification Code')

@section('content')
@if(session('success'))
<div style="background: #D1FAE5; color: #059669; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px;">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="error-message">
    @foreach($errors->all() as $error)
        {{ $error }}<br>
    @endforeach
</div>
@endif

<h2 style="text-align: center; color: #1E3A5F; margin-bottom: 10px; font-size: 20px;">Enter Verification Code</h2>
<p style="text-align: center; color: #94A3B8; margin-bottom: 25px; font-size: 14px;">
    We've sent a 6-digit verification code to <strong>{{ auth()->user()->email }}</strong>
</p>

<form method="POST" action="{{ route('verification.verify') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">Verification Code</label>
        <small style="display: block; color: #94A3B8; margin-bottom: 8px;">Enter the 6-digit code from your email</small>
        <input type="text" name="code" class="form-control" placeholder="------" maxlength="6" pattern="[0-9]{6}" style="text-align: center; letter-spacing: 6px; font-size: 24px; font-weight: 600;" required autofocus>
    </div>

    <button type="submit" class="btn-primary">Verify Email</button>
</form>

<div class="auth-footer" style="margin-top: 20px;">
    <form method="POST" action="{{ route('verification.resend') }}" style="display: inline;">
        @csrf
        Didn't receive the code? <button type="submit" style="background: none; border: none; color: #4A90D9; font-weight: 500; cursor: pointer; font-size: 14px;">Resend Code</button>
    </form>
</div>

<div class="auth-footer" style="margin-top: 10px;">
    <a href="{{ route('home') }}">← Continue browsing</a>
</div>
@endsection
