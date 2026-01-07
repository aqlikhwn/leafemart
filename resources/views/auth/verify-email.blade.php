@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
@if(session('success'))
<div style="background: #D1FAE5; color: #059669; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px;">
    {{ session('success') }}
</div>
@endif

@if(session('info'))
<div style="background: #DBEAFE; color: #1D4ED8; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px;">
    {{ session('info') }}
</div>
@endif

<div style="text-align: center; margin-bottom: 25px;">
    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px;">
        <i class="fas fa-envelope" style="font-size: 36px; color: white;"></i>
        <span style="font-size: 36px;">✉️</span>
    </div>
    <h2 style="color: #1E3A5F; margin-bottom: 10px; font-size: 20px;">Verify Your Email</h2>
    <p style="color: #94A3B8; font-size: 14px;">
        To place orders, you need to verify your email address. Click the button below to receive a verification code.
    </p>
</div>

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit" class="btn-primary">Send Verification Code</button>
</form>

<div class="auth-footer" style="margin-top: 20px;">
    <a href="{{ route('home') }}">← Continue browsing</a>
</div>
@endsection
