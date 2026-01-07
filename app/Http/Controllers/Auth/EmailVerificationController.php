<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationCode as VerificationMail;
use App\Models\EmailVerificationCode;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    /**
     * Show the email verification notice
     */
    public function notice()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // If already verified, redirect to home
        if (Auth::user()->email_verified_at) {
            return redirect()->route('home')->with('info', 'Your email is already verified.');
        }

        return view('auth.verify-email');
    }

    /**
     * Send verification code to user's email
     */
    public function sendCode()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Generate 6-digit code
        $code = EmailVerificationCode::generateCode();

        // Store or update the code
        EmailVerificationCode::updateOrCreate(
            ['email' => $user->email],
            ['code' => $code, 'created_at' => now()]
        );

        // Send email
        Mail::to($user->email)->send(new VerificationMail($code));

        return redirect()->route('verification.verify.form')
            ->with('success', 'A verification code has been sent to your email.');
    }

    /**
     * Show the verification code form
     */
    public function showVerifyForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->email_verified_at) {
            return redirect()->route('home')->with('info', 'Your email is already verified.');
        }

        return view('auth.verify-email-code');
    }

    /**
     * Verify the code
     */
    public function verify(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        // Find the verification code
        $verificationCode = EmailVerificationCode::where('email', $user->email)->first();

        if (!$verificationCode) {
            return back()->withErrors(['code' => 'No verification code found. Please request a new one.']);
        }

        // Check if code is valid
        if (!$verificationCode->isValid()) {
            $verificationCode->delete();
            return back()->withErrors(['code' => 'The verification code has expired. Please request a new one.']);
        }

        // Check if code matches
        if ($verificationCode->code !== $request->code) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        // Update user's email_verified_at
        $user->update(['email_verified_at' => now()]);

        // Delete the code
        $verificationCode->delete();

        // Create success notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'verification_success',
            'title' => 'Email Verified Successfully! ✅',
            'message' => 'Your email address has been verified. Your account is now fully secured.',
        ]);

        return redirect()->route('home')
            ->with('success', 'Your email has been verified successfully! You can now place orders.');
    }

    /**
     * Resend the verification code
     */
    public function resendCode()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Generate new code
        $code = EmailVerificationCode::generateCode();

        // Store or update the code
        EmailVerificationCode::updateOrCreate(
            ['email' => $user->email],
            ['code' => $code, 'created_at' => now()]
        );

        // Send email
        Mail::to($user->email)->send(new VerificationMail($code));

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
