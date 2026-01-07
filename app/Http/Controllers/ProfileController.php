<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    private function checkAuth()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function settings()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $user = Auth::user();
        return view('profile-settings', compact('user'));
    }

    public function update(Request $request)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Handle avatar removal
        if ($request->boolean('remove_avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = null;
        } elseif ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    public function notifications(Request $request)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $query = Notification::where('user_id', Auth::id());
        
        // Filter by type
        $type = $request->get('type', 'all');
        if ($type !== 'all') {
            $typeMap = [
                'orders' => ['order', 'order_status', 'order_cancelled', 'payment_approved', 'payment_rejected', 'new_order'],
                'announcements' => ['announcement'],
                'login' => ['login', 'welcome', 'verification', 'verification_success'],
                'messages' => ['message', 'message_reply'],
            ];
            
            if (isset($typeMap[$type])) {
                $query->whereIn('type', $typeMap[$type]);
            }
        }
        
        // Filter by read status
        $status = $request->get('status', 'all');
        if ($status === 'unread') {
            $query->where('read', false);
        } elseif ($status === 'read') {
            $query->where('read', true);
        }
        
        $notifications = $query->latest()->paginate(10)->appends($request->query());
        
        // Get counts for filter badges
        $allCount = Notification::where('user_id', Auth::id())->count();
        $unreadCount = Notification::where('user_id', Auth::id())->where('read', false)->count();
        
        // Get counts by type
        $typeMap = [
            'orders' => ['order', 'order_status', 'order_cancelled', 'payment_approved', 'payment_rejected', 'new_order'],
            'announcements' => ['announcement'],
            'login' => ['login', 'welcome', 'verification', 'verification_success'],
            'messages' => ['message', 'message_reply'],
        ];
        
        $typeCounts = [];
        foreach ($typeMap as $key => $types) {
            $typeCounts[$key] = Notification::where('user_id', Auth::id())->whereIn('type', $types)->count();
        }

        return view('notifications', compact('notifications', 'type', 'status', 'allCount', 'unreadCount', 'typeCounts'));

    }

    public function markNotificationRead($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->update(['read' => true]);

        return back();
    }

    public function markAllRead()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        Notification::where('user_id', Auth::id())
            ->update(['read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function clickNotification($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        // Mark as read
        $notification->update(['read' => true]);

        // Redirect to appropriate page
        return redirect($notification->getRedirectUrl());
    }

    public function deleteAccount(Request $request)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The password you entered is incorrect.']);
        }

        // Delete user's avatar if exists
        if ($user->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }

        // Delete user's notifications
        Notification::where('user_id', $user->id)->delete();

        // Delete user's cart items
        \App\Models\Cart::where('user_id', $user->id)->delete();

        // Logout and delete user
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Your account has been deleted successfully.');
    }
}
