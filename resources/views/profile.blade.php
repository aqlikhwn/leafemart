@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="page-header">
    <h1 class="page-title">My Profile</h1>
    <a href="{{ route('profile.settings') }}" class="btn btn-primary">
        <i class="fas fa-cog"></i> Edit Profile
    </a>
</div>

<!-- Profile Overview -->
<div class="card" style="margin-bottom: 30px;">
    <div style="display: flex; align-items: center; gap: 25px;">
        <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: 600; flex-shrink: 0;">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
        </div>
        <div style="flex: 1;">
            <h2 style="color: var(--primary-dark); margin-bottom: 5px; font-size: 28px;">{{ $user->name }}</h2>
            <p style="color: var(--gray-600); font-size: 16px; margin-bottom: 10px;">
                {{ $user->email }}
                @if($user->email_verified_at)
                    <span style="color: var(--success); margin-left: 5px;" title="Email Verified"><i class="fas fa-check-circle"></i></span>
                @else
                    <a href="{{ route('verification.notice') }}" style="color: var(--warning); margin-left: 5px; text-decoration: none;" title="Email not verified - Click to verify"><i class="fas fa-exclamation-circle"></i></a>
                @endif
            </p>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <span class="badge {{ $user->isAdmin() ? 'badge-warning' : 'badge-primary' }}" style="font-size: 13px; padding: 6px 12px;">
                    <i class="fas {{ $user->isAdmin() ? 'fa-crown' : 'fa-user' }}"></i> {{ ucfirst($user->role) }}
                </span>
                @if($user->phone)
                <span class="badge badge-secondary" style="font-size: 13px; padding: 6px 12px;">
                    <i class="fas fa-phone"></i> {{ $user->phone }}
                </span>
                @endif
                <span class="badge badge-secondary" style="font-size: 13px; padding: 6px 12px;">
                    <i class="fas fa-calendar"></i> Member since {{ $user->created_at->format('M Y') }}
                </span>
            </div>
        </div>
    </div>
    
    @if($user->address)
    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--gray-200);">
        <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">
            <i class="fas fa-map-marker-alt"></i> Address
        </div>
        <div style="color: var(--primary-dark);">{{ $user->address }}</div>
    </div>
    @endif
</div>

<!-- Order Stats -->
@php
    $totalOrders = $user->orders()->count();
    $completedOrders = $user->orders()->where('status', 'completed')->count();
    $totalSpent = $user->orders()->where('status', '!=', 'cancelled')->sum('total');
    $recentOrders = $user->orders()->with('items.product')->latest()->take(5)->get();
@endphp

<div class="grid grid-3" style="margin-bottom: 30px;">
    <div class="card" style="text-align: center; background: linear-gradient(135deg, #4A90D9, #1E3A5F); color: white;">
        <div style="font-size: 36px; font-weight: 700;">{{ $totalOrders }}</div>
        <div style="opacity: 0.8;">Total Orders</div>
    </div>
    <div class="card" style="text-align: center; background: linear-gradient(135deg, #10B981, #059669); color: white;">
        <div style="font-size: 36px; font-weight: 700;">{{ $completedOrders }}</div>
        <div style="opacity: 0.8;">Completed Orders</div>
    </div>
    <div class="card" style="text-align: center; background: linear-gradient(135deg, #F59E0B, #D97706); color: white;">
        <div style="font-size: 36px; font-weight: 700;">RM {{ number_format($totalSpent, 2) }}</div>
        <div style="opacity: 0.8;">Total Spent</div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card" style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="color: var(--primary-dark);"><i class="fas fa-history"></i> Recent Orders</h3>
        <a href="{{ route('orders.history') }}" class="btn btn-secondary" style="padding: 8px 16px;">
            View All Orders
        </a>
    </div>
    
    @if($recentOrders->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Date</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentOrders as $order)
            <tr>
                <td><strong>#{{ $order->id }}</strong></td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td>{{ $order->items->count() }} items</td>
                <td><strong>RM {{ number_format($order->total, 2) }}</strong></td>
                <td><span class="badge badge-{{ $order->status_badge }}">{{ $order->status_label }}</span></td>
                <td>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary" style="padding: 6px 10px;">
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
        <p>You haven't placed any orders yet.</p>
        <a href="{{ route('browse') }}" class="btn btn-primary" style="margin-top: 10px;">Start Shopping</a>
    </div>
    @endif
</div>
@endsection
