<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class OrderController extends Controller
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
        $status = $request->get('status');

        $orders = Order::with(['user', 'items.product'])
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        // Get status counts for filter badges
        $statusCounts = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('admin.orders.index', compact('orders', 'status', 'statusCounts'));
    }

    public function show($id)
    {
        $this->checkAdmin();
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $this->checkAdmin();
        $request->validate([
            'status' => 'required|in:pending,processing,ready,out_for_delivery,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // If cancelled, restore stock
        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                // Restore stock - either to variation or product
                if ($item->variation) {
                    $productVariation = $item->product->variations()->where('name', $item->variation)->first();
                    if ($productVariation) {
                        $productVariation->increment('stock', $item->quantity);
                    }
                } else {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }

        // Create notification for user
        $statusMessages = [
            'processing' => 'Your order is now being processed.',
            'ready' => 'Your order is ready for pickup!',
            'out_for_delivery' => 'Your order is out for delivery! It will arrive soon.',
            'completed' => 'Your order has been completed. Thank you!',
            'cancelled' => 'Your order has been cancelled.',
        ];

        if (isset($statusMessages[$request->status])) {
            Notification::create([
                'user_id' => $order->user_id,
                'type' => 'order_status',
                'title' => "Order #{$order->id} Status Update",
                'message' => $statusMessages[$request->status],
            ]);
        }
        
        ActivityLog::log('status_changed', "Changed order #{$order->id} status from {$oldStatus} to {$request->status}", $order);

        return back()->with('success', 'Order status updated successfully!');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $this->checkAdmin();
        $request->validate([
            'payment_status' => 'required|in:approved,rejected',
        ]);

        $order = Order::findOrFail($id);

        // Only process if payment was uploaded
        if ($order->payment_status !== 'uploaded') {
            return back()->with('error', 'Cannot update payment status for this order.');
        }

        $order->update(['payment_status' => $request->payment_status]);

        // If approved, automatically set order to processing
        if ($request->payment_status === 'approved') {
            $order->update(['status' => 'processing']);
            
            Notification::create([
                'user_id' => $order->user_id,
                'type' => 'payment_approved',
                'title' => "Payment Approved - Order #{$order->id}",
                'message' => 'Your payment has been verified and approved! Your order is now being processed.',
            ]);
            
            ActivityLog::log('updated', "Approved payment for order #{$order->id}", $order);

            return back()->with('success', 'Payment approved! Order status updated to Processing.');
        }

        // If rejected, notify customer
        if ($request->payment_status === 'rejected') {
            Notification::create([
                'user_id' => $order->user_id,
                'type' => 'payment_rejected',
                'title' => "Payment Rejected - Order #{$order->id}",
                'message' => 'Your payment slip was rejected. Please contact us or upload a valid payment slip.',
            ]);
            
            ActivityLog::log('updated', "Rejected payment for order #{$order->id}", $order);

            return back()->with('success', 'Payment rejected. Customer has been notified.');
        }

        return back()->with('success', 'Payment status updated!');
    }
}
