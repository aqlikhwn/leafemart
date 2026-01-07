@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">User Details</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit User
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
    <!-- User Info -->
    <div>
        <div class="card" style="text-align: center; margin-bottom: 20px;">
            @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin: 0 auto 15px;">
            @else
            <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-size: 36px; font-weight: 600; margin: 0 auto 15px;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            @endif
            <h2 style="color: var(--primary-dark); margin-bottom: 5px;">{{ $user->name }}</h2>
            @if($user->role == 'admin')
            <span class="badge badge-warning" style="font-size: 14px;"><i class="fas fa-crown"></i> Admin</span>
            @else
            <span class="badge badge-primary" style="font-size: 14px;"><i class="fas fa-user"></i> Customer</span>
            @endif
        </div>

        <div class="card">
            <h3 style="color: var(--primary-dark); margin-bottom: 20px;">Contact Information</h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase;">Email</div>
                    <div style="font-weight: 500;">{{ $user->email }}</div>
                </div>
                <div>
                    <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase;">Phone</div>
                    <div style="font-weight: 500;">{{ $user->phone ?? 'Not provided' }}</div>
                </div>
                <div>
                    <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase;">Address</div>
                    <div style="font-weight: 500;">{{ $user->address ?? 'Not provided' }}</div>
                </div>
                <div>
                    <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase;">Member Since</div>
                    <div style="font-weight: 500;">{{ $user->created_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders & Stats -->
    <div>
        <!-- Stats -->
        @php
            $completedOrders = $user->orders()->where('status', 'completed')->count();
        @endphp
        <div class="grid grid-3" style="margin-bottom: 20px;">
            <div class="card" style="text-align: center;">
                <div style="font-size: 32px; font-weight: 700; color: var(--primary);">{{ $user->orders_count }}</div>
                <div style="color: var(--gray-400);">Total Orders</div>
            </div>
            <div class="card" style="text-align: center;">
                <div style="font-size: 32px; font-weight: 700; color: var(--success);">{{ $completedOrders }}</div>
                <div style="color: var(--gray-400);">Completed Orders</div>
            </div>
            <div class="card" style="text-align: center;">
                <div style="font-size: 32px; font-weight: 700; color: var(--warning);">RM {{ number_format($totalSpent, 2) }}</div>
                <div style="color: var(--gray-400);">Total Spent</div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="color: var(--primary-dark);">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}?search={{ $user->name }}" class="btn btn-secondary" style="padding: 8px 16px;">
                    View All Orders
                </a>
            </div>
            @if($user->orders->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>RM {{ number_format($order->total, 2) }}</td>
                        <td><span class="badge badge-{{ $order->status_badge }}">{{ $order->status_label }}</span></td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary" style="padding: 6px 10px;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align: center; padding: 30px; color: var(--gray-400);">
                <i class="fas fa-shopping-bag" style="font-size: 40px; margin-bottom: 10px;"></i>
                <p>This user has no orders yet.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
