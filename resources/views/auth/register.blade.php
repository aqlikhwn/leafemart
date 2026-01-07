@extends('layouts.auth')

@section('title', 'Create Account')

@section('content')
@if($errors->any())
<div class="error-message">
    @foreach($errors->all() as $error)
        {{ $error }}<br>
    @endforeach
</div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">User Name</label>
        <input type="text" name="name" class="form-control" placeholder="Enter your user name" value="{{ old('name') }}" required autofocus>
    </div>

    <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required>
    </div>

    <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Create a password" required>
    </div>

    <div class="form-group">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
    </div>

    <button type="submit" class="btn-primary">Create Account</button>
</form>

<div class="auth-footer">
    Already have an account? <a href="{{ route('login') }}">Sign In</a>
</div>
@endsection
