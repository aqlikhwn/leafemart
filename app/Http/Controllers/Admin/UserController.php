<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdmin();
        
        $role = $request->get('role');
        $search = $request->get('search');

        $users = User::withCount('orders')
            ->when($role, function ($query) use ($role) {
                return $query->where('role', $role);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users', 'role', 'search'));
    }

    public function show($id)
    {
        $this->checkAdmin();
        
        $user = User::with(['orders' => function($q) {
            $q->latest()->take(10);
        }])->withCount('orders')->findOrFail($id);
        
        $totalSpent = Order::where('user_id', $id)
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        return view('admin.users.show', compact('user', 'totalSpent'));
    }

    public function edit($id)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:customer,admin',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'email_verified_at' => $request->has('email_verified') ? ($user->email_verified_at ?? now()) : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $this->checkAdmin();
        
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting the last admin
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot delete the last admin account.');
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }
}
