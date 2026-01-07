<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCode as PasswordResetMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    /**
     * Show the forgot password form
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset code to user's email
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        // Generate 6-digit code
        $code = PasswordResetToken::generateCode();

        // Store or update the token
        PasswordResetToken::updateOrCreate(
            ['email' => $request->email],
            ['token' => $code, 'created_at' => now()]
        );

        // Send email
        Mail::to($request->email)->send(new PasswordResetMail($code));

        return redirect()->route('password.reset.form', ['email' => $request->email])
            ->with('success', 'A verification code has been sent to your email.');
    }

    /**
     * Show the reset password form
     */
    public function showResetForm(Request $request)
    {
        $email = $request->query('email');
        
        if (!$email) {
            return redirect()->route('password.forgot');
        }

        return view('auth.reset-password', compact('email'));
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find the token
        $resetToken = PasswordResetToken::where('email', $request->email)->first();

        if (!$resetToken) {
            return back()->withErrors(['code' => 'No reset code found. Please request a new one.']);
        }

        // Check if token is valid
        if (!$resetToken->isValid()) {
            $resetToken->delete();
            return back()->withErrors(['code' => 'The reset code has expired. Please request a new one.']);
        }

        // Check if code matches
        if ($resetToken->token !== $request->code) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        // Update user's password
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        // Delete the token
        $resetToken->delete();

        return redirect()->route('login')
            ->with('success', 'Your password has been reset successfully! Please login with your new password.');
    }

    /**
     * Resend the reset code
     */
    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate new code
        $code = PasswordResetToken::generateCode();

        // Store or update the token
        PasswordResetToken::updateOrCreate(
            ['email' => $request->email],
            ['token' => $code, 'created_at' => now()]
        );

        // Send email
        Mail::to($request->email)->send(new PasswordResetMail($code));

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
