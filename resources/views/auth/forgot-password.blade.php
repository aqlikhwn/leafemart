@extends('layouts.auth')

@section('title', 'Forgot Password')

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

<h2 style="text-align: center; color: #1E3A5F; margin-bottom: 10px; font-size: 20px;">Forgot Password?</h2>
<p style="text-align: center; color: #94A3B8; margin-bottom: 25px; font-size: 14px;">
    Enter your email address and we'll send you a verification code to reset your password.
</p>

<form method="POST" action="{{ route('password.send.code') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
    </div>

    <button type="submit" class="btn-primary">Send Verification Code</button>
</form>

<div class="auth-footer">
    Remember your password? <a href="{{ route('login') }}">Sign In</a>
</div>
@endsection
