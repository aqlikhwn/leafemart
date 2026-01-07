<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);

        // Create welcome notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'welcome',
            'title' => 'Welcome to Leafé Mart! 🎉',
            'message' => "Hi {$user->name}! Your account has been created successfully. Start exploring our products!",
        ]);

        // Create email verification reminder notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'verification',
            'title' => 'Verify Your Email Address',
            'message' => 'Please verify your email address to secure your account. Click here to verify.',
            'link' => route('verification.notice'),
        ]);

        return redirect()->route('home')->with('success', 'Welcome to Leafe Mart! Your account has been created.');
    }
}
