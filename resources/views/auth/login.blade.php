@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
@if($errors->any())
<div class="error-message">
    @foreach($errors->all() as $error)
        {{ $error }}<br>
    @endforeach
</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
    </div>

    <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
    </div>

    <div class="forgot-password">
        <a href="{{ route('password.forgot') }}">Forgot Password?</a>
    </div>

    <div class="form-check">
        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <label for="remember">Remember me</label>
    </div>

    <button type="submit" class="btn-primary">Sign In</button>
</form>

<div class="auth-footer">
    Don't have an account? <a href="{{ route('register') }}">Create Account</a>
</div>

<!-- Demo Mode Section -->
<div class="demo-section" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
    <p style="text-align: center; color: #666; font-size: 14px; margin-bottom: 15px;">
        <i class="fas fa-flask"></i> Demo Mode - Quick Login
    </p>
    <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
        <button type="button" onclick="demoLogin('customer')" class="demo-btn" style="background: linear-gradient(135deg, #4A90D9, #1E3A5F); color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 13px; transition: all 0.3s ease;">
            <i class="fas fa-user"></i> Customer Demo
        </button>
        <button type="button" onclick="demoLogin('admin')" class="demo-btn" style="background: linear-gradient(135deg, #FF6B6B, #C44569); color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 13px; transition: all 0.3s ease;">
            <i class="fas fa-user-shield"></i> Admin Demo
        </button>
    </div>
</div>

<script>
function demoLogin(type) {
    const form = document.querySelector('form');
    const emailInput = form.querySelector('input[name="email"]');
    const passwordInput = form.querySelector('input[name="password"]');
    
    if (type === 'customer') {
        emailInput.value = 'aqilikhwan@gmail.com';
        passwordInput.value = 'qwertyuiop';
    } else if (type === 'admin') {
        emailInput.value = 'admin@leafemart.com';
        passwordInput.value = 'password';
    }
    
    // Auto-submit after a short delay
    setTimeout(() => {
        form.submit();
    }, 300);
}
</script>

<style>
.demo-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
</style>
@endsection
