<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        
        $messages = Message::with('user')
            ->latest()
            ->paginate(15);

        $unreadCount = Message::unread()->count();

        return view('admin.messages.index', compact('messages', 'unreadCount'));
    }

    public function show(Message $message)
    {
        $this->checkAdmin();
        
        // Mark as read
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function reply(Request $request, Message $message)
    {
        $this->checkAdmin();
        
        $request->validate([
            'admin_reply' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle multiple image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('message-replies', 'public');
            }
        }

        $message->update([
            'admin_reply' => $request->admin_reply,
            'reply_images' => !empty($imagePaths) ? json_encode($imagePaths) : null,
            'replied_at' => now(),
        ]);

        // Notify the user about the reply
        Notification::create([
            'user_id' => $message->user_id,
            'type' => 'message_reply',
            'title' => 'Message Reply Received',
            'message' => "Your message \"{$message->subject}\" has been replied to by admin.",
        ]);

        return redirect()->route('admin.messages.index')
            ->with('success', 'Reply sent successfully!');
    }
}
