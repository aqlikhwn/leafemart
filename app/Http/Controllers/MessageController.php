<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle multiple image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('messages', 'public');
            }
        }

        $message = Message::create([
            'user_id' => auth()->id() ?? 1, // Default to 1 if not logged in
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'images' => !empty($imagePaths) ? json_encode($imagePaths) : null,
        ]);

        // Notify all admins about new message
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'message',
                'title' => 'New Message Received',
                'message' => "New message from {$request->name}: {$request->subject}",
            ]);
        }

        return back()->with('success', 'Your message has been sent! We will get back to you soon.');
    }

    public function index()
    {
        $messages = Message::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        // Mark all unread replies as read when user visits this page
        Message::where('user_id', auth()->id())
            ->whereNotNull('admin_reply')
            ->where('reply_read', false)
            ->update(['reply_read' => true]);

        return view('messages.index', compact('messages'));
    }
}
