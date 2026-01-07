@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="page-header">
    <h1 class="page-title">Manage Users</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Dashboard
    </a>
</div>

<!-- Search and Filter -->
<div class="card" style="margin-bottom: 20px;">
    <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label">Search</label>
            <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ $search }}">
        </div>
        <div>
            <label class="form-label">Role</label>
            <select name="role" class="form-control" style="min-width: 150px;">
                <option value="">All Roles</option>
                <option value="customer" {{ $role == 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Search
        </button>
        @if($search || $role)
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Clear
        </a>
        @endif
    </form>
</div>

<!-- Stats -->
<div class="grid grid-3" style="margin-bottom: 20px;">
    <div class="card" style="text-align: center; padding: 15px;">
        <div style="font-size: 28px; font-weight: 700; color: var(--success);">{{ \App\Models\User::count() }}</div>
        <div style="color: var(--gray-400);">Total Users</div>
    </div>
    <div class="card" style="text-align: center; padding: 15px;">
        <div style="font-size: 28px; font-weight: 700; color: var(--primary);">{{ \App\Models\User::where('role', 'customer')->count() }}</div>
        <div style="color: var(--gray-400);">Customers</div>
    </div>
    <div class="card" style="text-align: center; padding: 15px;">
        <div style="font-size: 28px; font-weight: 700; color: var(--warning);">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
        <div style="color: var(--gray-400);">Admins</div>
    </div>
</div>

<div class="card">
    @if($users->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Orders</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        @else
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        @endif
                        <strong>{{ $user->name }}</strong>
                    </div>
                </td>
                <td>
                    {{ $user->email }}
                    @if($user->email_verified_at)
                        <span style="color: var(--success); margin-left: 3px;" title="Verified"><i class="fas fa-check-circle"></i></span>
                    @else
                        <span style="color: var(--warning); margin-left: 3px;" title="Not Verified"><i class="fas fa-exclamation-circle"></i></span>
                    @endif
                </td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td>
                    @if($user->role == 'admin')
                    <span class="badge badge-warning"><i class="fas fa-crown"></i> Admin</span>
                    @else
                    <span class="badge badge-primary"><i class="fas fa-user"></i> Customer</span>
                    @endif
                </td>
                <td>{{ $user->orders_count }}</td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display: flex; gap: 5px;">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary" style="padding: 8px 12px;" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary" style="padding: 8px 12px;" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 8px 12px;" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 20px;">{{ $users->appends(request()->query())->links() }}</div>
    @else
    <div class="empty-state">
        <i class="fas fa-users"></i>
        <h3>No Users Found</h3>
        <p>No users match your search criteria.</p>
    </div>
    @endif
</div>
@endsection
