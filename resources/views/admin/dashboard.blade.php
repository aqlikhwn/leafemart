@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Admin Dashboard</h1>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
        <i class="fas fa-bullhorn"></i> Send Announcement
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-5" style="margin-bottom: 30px;">
    <div class="card" style="background: linear-gradient(135deg, #4A90D9, #1E3A5F); color: white;">
        <div style="font-size: 36px; font-weight: 700;">{{ $stats['total_products'] }}</div>
        <div style="opacity: 0.8;">Total Products</div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #A855F7, #7C3AED); color: white;">
        <div style="font-size: 36px; font-weight: 700;">{{ $stats['total_orders'] }}</div>
        <div style="opacity: 0.8;">Total Orders</div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #F59E0B, #D97706); color: white;">
        <div style="font-size: 36px; font-weight: 700;">{{ $stats['pending_orders'] }}</div>
        <div style="opacity: 0.8;">Pending Orders</div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #10B981, #059669); color: white;">
        <div style="font-size: 36px; font-weight: 700;">{{ \App\Models\Order::where('status', 'completed')->count() }}</div>
        <div style="opacity: 0.8;">Completed Orders</div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #EF4444, #DC2626); color: white;">
        <div style="font-size: 36px; font-weight: 700;">{{ $stats['low_stock'] }}</div>
        <div style="opacity: 0.8;">Low Stock Items</div>
    </div>
</div>

<!-- Quick Actions -->
<style>
    .action-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(30, 58, 95, 0.15);
        border-color: var(--primary);
    }
    .action-card:hover .action-icon {
        transform: scale(1.1);
        color: var(--primary-dark);
    }
    .action-card:hover h3 {
        color: var(--primary);
    }
    .action-icon {
        transition: all 0.3s ease;
    }
</style>
<div class="grid grid-4" style="margin-bottom: 30px;">
    <a href="{{ route('admin.products.index') }}" class="card action-card" style="text-decoration: none; text-align: center;">
        <i class="fas fa-box action-icon" style="font-size: 40px; color: var(--primary); margin-bottom: 10px;"></i>
        <h3 style="color: var(--primary-dark); transition: color 0.3s;">Manage Products</h3>
        <p style="color: var(--gray-400);">{{ $stats['total_products'] }} products</p>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="card action-card" style="text-decoration: none; text-align: center;">
        <i class="fas fa-tags action-icon" style="font-size: 40px; color: var(--primary); margin-bottom: 10px;"></i>
        <h3 style="color: var(--primary-dark); transition: color 0.3s;">Manage Categories</h3>
        <p style="color: var(--gray-400);">{{ $stats['total_categories'] }} categories</p>
    </a>
    <a href="{{ route('admin.orders.index') }}" class="card action-card" style="text-decoration: none; text-align: center;">
        <i class="fas fa-shopping-bag action-icon" style="font-size: 40px; color: var(--primary); margin-bottom: 10px;"></i>
        <h3 style="color: var(--primary-dark); transition: color 0.3s;">Manage Orders</h3>
        <p style="color: var(--gray-400);">{{ $stats['total_orders'] }} orders</p>
    </a>
    <a href="{{ route('admin.users.index') }}" class="card action-card" style="text-decoration: none; text-align: center;">
        <i class="fas fa-users action-icon" style="font-size: 40px; color: var(--primary); margin-bottom: 10px;"></i>
        <h3 style="color: var(--primary-dark); transition: color 0.3s;">Manage Users</h3>
        <p style="color: var(--gray-400);">{{ \App\Models\User::count() }} users</p>
    </a>
</div>

<style>
    .dashboard-bottom-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }
    @media (max-width: 768px) {
        .dashboard-bottom-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<div class="dashboard-bottom-grid">
    <!-- Recent Orders -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: var(--primary-dark);">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary" style="padding: 8px 16px;">View All</a>
        </div>
        @if($recentOrders->count() > 0)
        <table class="table">
            <thead>
                <tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td><strong>#{{ $order->id }}</strong></td>
                    <td>{{ $order->customer_name }}</td>
                    <td>RM {{ number_format($order->total, 2) }}</td>
                    <td><span class="badge badge-{{ $order->status_badge }}">{{ $order->status_label }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color: var(--gray-400); text-align: center; padding: 20px;">No orders yet.</p>
        @endif
    </div>

    <!-- Low Stock Alert -->
    <div class="card">
        <h3 style="color: var(--primary-dark); margin-bottom: 20px;"><i class="fas fa-exclamation-triangle" style="color: var(--warning);"></i> Low Stock Alert</h3>
        @if($lowStockProducts->count() > 0)
        @foreach($lowStockProducts as $product)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--gray-200);">
            <span>{{ $product->name }}</span>
            <span class="badge badge-danger">{{ $product->stock }} left</span>
        </div>
        @endforeach
        @else
        <p style="color: var(--gray-400); text-align: center; padding: 20px;">All products are well-stocked!</p>
        @endif
    </div>
</div>

<!-- Recent Activities -->
<div class="card" style="margin-top: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="color: var(--primary-dark);"><i class="fas fa-history"></i> Recent Activities</h3>
        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary" style="padding: 8px 16px;">View All</a>
    </div>
    
    @if($recentActivities->count() > 0)
    <div style="display: flex; flex-direction: column; gap: 0;">
        @foreach($recentActivities as $activity)
        <a href="{{ $activity['link'] }}" style="display: flex; align-items: flex-start; gap: 15px; padding: 15px; border-radius: 12px; text-decoration: none; transition: all 0.2s ease; border-bottom: 1px solid var(--gray-200);" class="activity-item">
            <div style="width: 40px; height: 40px; background: {{ $activity['color'] }}20; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas {{ $activity['icon'] }}" style="color: {{ $activity['color'] }}; font-size: 16px;"></i>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-weight: 600; color: var(--primary-dark); margin-bottom: 3px;">{{ $activity['title'] }}</div>
                <div style="color: var(--gray-400); font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $activity['description'] }}</div>
            </div>
            <div style="color: var(--gray-400); font-size: 12px; white-space: nowrap;">
                {{ $activity['time']->diffForHumans() }}
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div style="text-align: center; padding: 40px; color: var(--gray-400);">
        <i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 15px;"></i>
        <p>No recent activities</p>
    </div>
    @endif
</div>

<style>
    .activity-item:hover {
        background: var(--primary-light);
    }
    .activity-item:last-child {
        border-bottom: none;
    }
</style>
@endsection
