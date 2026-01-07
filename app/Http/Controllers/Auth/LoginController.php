<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $userName = $user->name;

            // Create login notification
            Notification::create([
                'user_id' => $user->id,
                'type' => 'login',
                'title' => 'Successful Login',
                'message' => "You logged in successfully on " . now()->format('d M Y, h:i A') . ". If this wasn't you, please change your password immediately.",
            ]);

            // Redirect admin to admin dashboard
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', "Welcome back, {$userName}! You're now logged in as admin.");
            }

            return redirect()->intended(route('home'))->with('success', "Welcome back, {$userName}! You've successfully logged in.");
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been successfully logged out. See you again soon!');
    }
}

