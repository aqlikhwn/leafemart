<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_categories' => Category::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock' => Product::where('stock', '<', 10)->count(),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::where('stock', '<', 10)
            ->take(5)
            ->get();

        // Recent Activities - combine various activities
        $recentActivities = collect();

        // Add activity logs from ActivityLog model
        $activityLogs = \App\Models\ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($log) {
                return [
                    'type' => 'admin_action',
                    'icon' => $log->icon,
                    'color' => $log->color,
                    'title' => ucfirst(str_replace('_', ' ', $log->action)),
                    'description' => $log->description . ($log->user ? ' by ' . $log->user->name : ''),
                    'time' => $log->created_at,
                    'link' => $log->model_type === 'App\\Models\\Order' && $log->model_id 
                        ? route('admin.orders.show', $log->model_id) 
                        : ($log->model_type === 'App\\Models\\Product' && $log->model_id 
                            ? route('admin.products.edit', $log->model_id) 
                            : route('admin.dashboard')),
                ];
            });
        $recentActivities = $recentActivities->merge($activityLogs);

        // Add recent orders as activities
        $recentOrdersForActivity = Order::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'order',
                    'icon' => 'fa-shopping-cart',
                    'color' => $order->status === 'completed' ? '#10B981' : ($order->status === 'pending' ? '#F59E0B' : '#4A90D9'),
                    'title' => 'New Order #' . $order->id,
                    'description' => $order->customer_name . ' placed an order for RM ' . number_format($order->total, 2),
                    'time' => $order->created_at,
                    'link' => route('admin.orders.show', $order->id),
                ];
            });
        $recentActivities = $recentActivities->merge($recentOrdersForActivity);

        // Add recent user registrations
        $recentUsers = User::where('role', 'customer')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'icon' => 'fa-user-plus',
                    'color' => '#A855F7',
                    'title' => 'New User Registration',
                    'description' => $user->name . ' joined the platform',
                    'time' => $user->created_at,
                    'link' => route('admin.users.index'),
                ];
            });
        $recentActivities = $recentActivities->merge($recentUsers);

        // Add recent messages if Message model exists
        if (class_exists(\App\Models\Message::class)) {
            $recentMessages = \App\Models\Message::latest()
                ->take(3)
                ->get()
                ->map(function ($message) {
                    return [
                        'type' => 'message',
                        'icon' => 'fa-envelope',
                        'color' => '#EF4444',
                        'title' => 'New Message',
                        'description' => 'Message from ' . $message->name . ': ' . \Illuminate\Support\Str::limit($message->subject, 30),
                        'time' => $message->created_at,
                        'link' => route('admin.messages.show', $message->id),
                    ];
                });
            $recentActivities = $recentActivities->merge($recentMessages);
        }

        // Sort by time and take latest 5
        $recentActivities = $recentActivities->sortByDesc('time')->take(5)->values();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts', 'recentActivities'));
    }

    public function activities()
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $filterType = request()->get('type');

        // Calculate counts for filter badges
        $totalCounts = [
            'admin_action' => \App\Models\ActivityLog::count(),
            'order' => Order::count(),
            'user' => User::where('role', 'customer')->count(),
            'message' => class_exists(\App\Models\Message::class) ? \App\Models\Message::count() : 0,
        ];
        $totalCounts['all'] = array_sum($totalCounts);

        // Combine all activities like dashboard
        $allActivities = collect();

        // Add activity logs from ActivityLog model
        if (!$filterType || $filterType === 'admin_action') {
            $activityLogs = \App\Models\ActivityLog::with('user')
                ->get()
                ->map(function ($log) {
                    return [
                        'type' => 'admin_action',
                        'icon' => $log->icon,
                        'color' => $log->color,
                        'title' => ucfirst(str_replace('_', ' ', $log->action)),
                        'description' => $log->description . ($log->user ? ' by ' . $log->user->name : ''),
                        'time' => $log->created_at,
                        'link' => $log->model_type === 'App\\Models\\Order' && $log->model_id 
                            ? route('admin.orders.show', $log->model_id) 
                            : ($log->model_type === 'App\\Models\\Product' && $log->model_id 
                                ? route('admin.products.edit', $log->model_id) 
                                : route('admin.dashboard')),
                    ];
                });
            $allActivities = $allActivities->merge($activityLogs);
        }

        // Add all orders as activities
        if (!$filterType || $filterType === 'order') {
            $orders = Order::with('user')
                ->get()
                ->map(function ($order) {
                    return [
                        'type' => 'order',
                        'icon' => 'fa-shopping-cart',
                        'color' => $order->status === 'completed' ? '#10B981' : ($order->status === 'pending' ? '#F59E0B' : '#4A90D9'),
                        'title' => 'New Order #' . $order->id,
                        'description' => $order->customer_name . ' placed an order for RM ' . number_format($order->total, 2),
                        'time' => $order->created_at,
                        'link' => route('admin.orders.show', $order->id),
                    ];
                });
            $allActivities = $allActivities->merge($orders);
        }

        // Add user registrations
        if (!$filterType || $filterType === 'user') {
            $users = User::where('role', 'customer')
                ->get()
                ->map(function ($user) {
                    return [
                        'type' => 'user',
                        'icon' => 'fa-user-plus',
                        'color' => '#A855F7',
                        'title' => 'New User Registration',
                        'description' => $user->name . ' joined the platform',
                        'time' => $user->created_at,
                        'link' => route('admin.users.index'),
                    ];
                });
            $allActivities = $allActivities->merge($users);
        }

        // Add messages if Message model exists
        if ((!$filterType || $filterType === 'message') && class_exists(\App\Models\Message::class)) {
            $messages = \App\Models\Message::all()
                ->map(function ($message) {
                    return [
                        'type' => 'message',
                        'icon' => 'fa-envelope',
                        'color' => '#EF4444',
                        'title' => 'New Message',
                        'description' => 'Message from ' . $message->name . ': ' . \Illuminate\Support\Str::limit($message->subject, 30),
                        'time' => $message->created_at,
                        'link' => route('admin.messages.show', $message->id),
                    ];
                });
            $allActivities = $allActivities->merge($messages);
        }

        // Sort by time descending
        $allActivities = $allActivities->sortByDesc('time')->values();

        // Manual pagination
        $page = request()->get('page', 1);
        $perPage = 20;
        $total = $allActivities->count();
        $activities = new \Illuminate\Pagination\LengthAwarePaginator(
            $allActivities->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => route('admin.activities.index'), 'query' => request()->only('type')]
        );

        return view('admin.activities.index', compact('activities', 'filterType', 'totalCounts'));
    }
}

