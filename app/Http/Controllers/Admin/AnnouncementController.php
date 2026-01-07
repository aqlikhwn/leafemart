<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function create()
    {
        $this->checkAdmin();
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle multiple image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('announcements', 'public');
            }
        }

        // Store as JSON if there are images
        $imageJson = !empty($imagePaths) ? json_encode($imagePaths) : null;

        // Get all users (including admins)
        $users = User::all();

        // Create notification for each user
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => 'announcement',
                'image' => $imageJson,
                'read' => false,
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Announcement sent to ' . $users->count() . ' users!');
    }
}
