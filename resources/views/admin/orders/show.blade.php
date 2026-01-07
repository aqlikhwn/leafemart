@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">Order #{{ $order->id }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>

<div class="responsive-grid-2-1">
    <!-- Order Items -->
    <div>
        <div class="card" style="margin-bottom: 20px; overflow-x: auto;">
            <h3 style="color: var(--primary-dark); margin-bottom: 20px;">Order Items</h3>
            <table class="table">
                <thead>
                    <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            @php
                                $itemImage = null;
                                if ($item->product) {
                                    // Check if variation has an image
                                    if ($item->variation) {
                                        $variation = $item->product->variations()->where('name', $item->variation)->first();
                                        if ($variation && $variation->image) {
                                            $itemImage = $variation->image;
                                        }
                                    }
                                    // Fall back to product image
                                    if (!$itemImage && $item->product->image) {
                                        $itemImage = $item->product->image;
                                    }
                                }
                            @endphp
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; background: var(--primary-light); border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                    @if($itemImage)
                                    <img src="{{ asset('storage/' . $itemImage) }}" alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                    {{ $item->product->category->icon ?? '📦' }}
                                    @endif
                                </div>
                                <div>
                                    <span>{{ $item->product->name ?? 'Deleted Product' }}</span>
                                    @if($item->variation)
                                    <div style="color: var(--primary); font-size: 12px; font-weight: 500;">{{ $item->variation }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>RM {{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td><strong>RM {{ number_format($item->subtotal, 2) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: 700;">Total:</td>
                        <td style="font-size: 18px; font-weight: 700; color: var(--primary);">RM {{ number_format($order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Slip Section (for Online Banking orders) -->
        @if($order->payment_method === 'online_banking')
        <div class="card">
            <h3 style="color: var(--primary-dark); margin-bottom: 20px;">
                <i class="fas fa-receipt"></i> Payment Slip Verification
            </h3>
            
            <div style="display: flex; gap: 15px; margin-bottom: 20px;">
                <span class="badge badge-{{ $order->payment_status_badge }}" style="font-size: 14px; padding: 8px 16px;">
                    @switch($order->payment_status)
                        @case('pending') ⏳ Payment Pending @break
                        @case('uploaded') 📤 Slip Uploaded - Needs Review @break
                        @case('approved') ✅ Payment Approved @break
                        @case('rejected') ❌ Payment Rejected @break
                    @endswitch
                </span>
            </div>

            @if($order->payment_slip)
            <div style="background: var(--gray-100); border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                <p style="margin-bottom: 15px; font-weight: 500;">Payment Slip:</p>
                @php
                    $extension = pathinfo($order->payment_slip, PATHINFO_EXTENSION);
                @endphp
                @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                    <img src="{{ asset('storage/' . $order->payment_slip) }}" alt="Payment Slip" style="max-width: 100%; max-height: 400px; border-radius: 8px; cursor: pointer;" onclick="window.open('{{ asset('storage/' . $order->payment_slip) }}', '_blank')">
                @else
                    <a href="{{ asset('storage/' . $order->payment_slip) }}" target="_blank" class="btn btn-secondary">
                        <i class="fas fa-file-pdf"></i> View Payment Slip (PDF)
                    </a>
                @endif
            </div>

            @if($order->payment_status === 'uploaded')
            <div style="display: flex; gap: 10px;">
                <form action="{{ route('admin.orders.payment', $order->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="payment_status" value="approved">
                    <button type="submit" class="btn btn-success" style="width: 100%;">
                        <i class="fas fa-check"></i> Approve Payment
                    </button>
                </form>
                <form action="{{ route('admin.orders.payment', $order->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="payment_status" value="rejected">
                    <button type="submit" class="btn btn-danger" style="width: 100%;">
                        <i class="fas fa-times"></i> Reject Payment
                    </button>
                </form>
            </div>
            @endif
            @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> No payment slip uploaded yet.
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Order Info -->
    <div>
        <div class="card" style="margin-bottom: 20px;">
            <h3 style="color: var(--primary-dark); margin-bottom: 20px;">Customer Info</h3>
            <p><strong>Name:</strong> {{ $order->customer_name }}</p>
            <p><strong>Phone:</strong> {{ $order->phone }}</p>
            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <h3 style="color: var(--primary-dark); margin-bottom: 20px;">Order Info</h3>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
            <p><strong>Payment Method:</strong> 
                @if($order->payment_method == 'pay_at_store')
                    <i class="fas fa-store"></i> Pay at Store
                @else
                    <i class="fas fa-university"></i> Online Banking
                @endif
            </p>
            @if($order->payment_method === 'online_banking')
            <p><strong>Payment Status:</strong> <span class="badge badge-{{ $order->payment_status_badge }}">{{ ucfirst($order->payment_status) }}</span></p>
            @endif
            <p><strong>Order Status:</strong> <span class="badge badge-{{ $order->status_badge }}">{{ $order->status_label }}</span></p>
        </div>

        <!-- Delivery Info -->
        <div class="card" style="margin-bottom: 20px; {{ $order->delivery_method === 'delivery' ? 'border: 2px solid #F59E0B; background: #FFFBEB;' : '' }}">
            <h3 style="color: var(--primary-dark); margin-bottom: 20px;">
                @if($order->delivery_method === 'delivery')
                    <i class="fas fa-motorcycle" style="color: #F59E0B;"></i> Delivery Details
                @else
                    <i class="fas fa-store" style="color: var(--primary);"></i> Pickup Details
                @endif
            </h3>
            
            <p><strong>Method:</strong> 
                @if($order->delivery_method === 'delivery')
                    <span class="badge badge-warning">🚚 Delivery</span>
                @else
                    <span class="badge badge-primary">🏪 Pickup at Store</span>
                @endif
            </p>
            
            @if($order->delivery_method === 'delivery')
            <div style="margin-top: 15px; padding: 15px; background: white; border-radius: 10px; border-left: 4px solid #F59E0B;">
                <p style="margin-bottom: 5px;"><strong><i class="fas fa-map-marker-alt" style="color: #EF4444;"></i> Delivery Address:</strong></p>
                <p style="font-size: 15px; color: var(--primary-dark); white-space: pre-line;">{{ $order->delivery_address ?? 'No address provided' }}</p>
            </div>
            <div style="margin-top: 10px;">
                <small style="color: var(--gray-400);"><i class="fas fa-info-circle"></i> Delivery fee: RM 3.00 (included in total)</small>
            </div>
            @else
            <p style="color: var(--gray-600);"><i class="fas fa-info-circle"></i> Customer will collect at Leafé Mart, Mahallah Bilal</p>
            @endif
        </div>

        <div class="card">
            <h3 style="color: var(--primary-dark); margin-bottom: 15px;">Update Order Status</h3>
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <select name="status" class="form-control" style="margin-bottom: 15px;">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏰ Pending</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                    <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>📦 Ready for Pickup</option>
                    <option value="out_for_delivery" {{ $order->status == 'out_for_delivery' ? 'selected' : '' }}>🚚 Out for Delivery</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Update Status</button>
            </form>
        </div>
    </div>
</div>
@endsection
