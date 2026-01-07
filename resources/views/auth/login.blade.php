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
@endsection

