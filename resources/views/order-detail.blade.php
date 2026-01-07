@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">Order #{{ $order->id }}</h1>
    <a href="{{ route('orders.history') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>

<div class="responsive-grid-2-1">
    <!-- Order Items -->
    <div class="card" style="overflow-x: auto;">
        <h3 style="color: var(--primary-dark); margin-bottom: 20px;">Order Items</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
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
                            <div style="width: 45px; height: 45px; background: var(--primary-light); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if($itemImage)
                                <img src="{{ asset('storage/' . $itemImage) }}" alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                {{ $item->product->category->icon ?? '📦' }}
                                @endif
                            </div>
                            <div>
                                <span>{{ $item->product->name ?? 'Product Deleted' }}</span>
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
                    <td colspan="3" style="text-align: right; font-weight: 700; font-size: 18px;">Total:</td>
                    <td style="font-size: 20px; font-weight: 700; color: var(--primary);">RM {{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Order Info -->
    <div>
        <div class="card" style="margin-bottom: 20px;">
            <h3 style="color: var(--primary-dark); margin-bottom: 20px;">Order Information</h3>
            
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase;">Order Date</div>
                    <div style="font-weight: 500; color: var(--primary-dark);">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                </div>
                
                <div>
                    <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase;">Status</div>
                    <span class="badge badge-{{ $order->status_badge }}" style="font-size: 14px;">
                        @switch($order->status)
                            @case('pending') ⏰ Pending @break
                            @case('processing') ⚙️ Processing @break
                            @case('ready') 📦 Ready for Pickup @break
                            @case('out_for_delivery') 🚚 Out for Delivery @break
                            @case('completed') ✅ Completed @break
                            @case('cancelled') ❌ Cancelled @break
                        @endswitch
                    </span>
                </div>

                <div>
                    <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase;">Payment Method</div>
                    <div style="font-weight: 500; color: var(--primary-dark);">
                        @if($order->payment_method == 'pay_at_store')
                            <i class="fas fa-store"></i> Pay at Store
                        @else
                            <i class="fas fa-university"></i> Online Banking
                        @endif
                    </div>
                </div>

                @if($order->payment_method == 'online_banking')
                <div>
                    <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase;">Payment Status</div>
                    <span class="badge badge-{{ $order->payment_status_badge }}" style="font-size: 12px;">
                        @switch($order->payment_status)
                            @case('pending') ⏳ Pending @break
                            @case('uploaded') 📤 Uploaded - Under Review @break
                            @case('approved') ✅ Approved @break
                            @case('rejected') ❌ Rejected @break
                        @endswitch
                    </span>
                </div>
                @endif
            </div>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <h3 style="color: var(--primary-dark); margin-bottom: 20px;">Customer Details</h3>
            
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase;">Name</div>
                    <div style="font-weight: 500; color: var(--primary-dark);">{{ $order->customer_name }}</div>
                </div>
                
                <div>
                    <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase;">Phone</div>
                    <div style="font-weight: 500; color: var(--primary-dark);">{{ $order->phone }}</div>
                </div>
            </div>

            @if($order->status === 'pending')
            <hr style="border: none; border-top: 1px solid var(--gray-200); margin: 20px 0;">
            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="width: 100%;">
                    <i class="fas fa-times"></i> Cancel Order
                </button>
            </form>
            @endif
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
            
            <div>
                <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase;">Method</div>
                <div style="margin-top: 5px;">
                    @if($order->delivery_method === 'delivery')
                        <span class="badge badge-warning" style="font-size: 13px;">🚚 Delivery</span>
                    @else
                        <span class="badge badge-primary" style="font-size: 13px;">🏪 Pickup at Store</span>
                    @endif
                </div>
            </div>
            
            @if($order->delivery_method === 'delivery')
            <div style="margin-top: 15px; padding: 15px; background: white; border-radius: 10px; border-left: 4px solid #F59E0B;">
                <div style="color: var(--gray-400); font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">
                    <i class="fas fa-map-marker-alt" style="color: #EF4444;"></i> Delivery Address
                </div>
                <div style="font-weight: 500; color: var(--primary-dark); white-space: pre-line;">{{ $order->delivery_address ?? 'No address provided' }}</div>
            </div>
            <div style="margin-top: 10px;">
                <small style="color: var(--gray-400);"><i class="fas fa-info-circle"></i> Delivery fee: RM 3.00 (included in total)</small>
            </div>
            @else
            <div style="margin-top: 15px;">
                <p style="color: var(--gray-600); font-size: 14px;"><i class="fas fa-info-circle"></i> Collect at Leafé Mart, Mahallah Bilal</p>
            </div>
            @endif
        </div>

        <!-- Order Status Timeline -->
        <div class="card" style="margin-top: 20px;">
            <h3 style="color: var(--primary-dark); margin-bottom: 20px;">Order Status</h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @php
                    // Different flow for pickup vs delivery
                    if ($order->delivery_method === 'delivery') {
                        $statuses = ['pending', 'processing', 'out_for_delivery', 'completed'];
                        $statusLabels = [
                            'pending' => 'Order Placed', 
                            'processing' => 'Processing', 
                            'out_for_delivery' => 'Out for Delivery', 
                            'completed' => 'Delivered'
                        ];
                    } else {
                        $statuses = ['pending', 'processing', 'ready', 'completed'];
                        $statusLabels = [
                            'pending' => 'Order Placed', 
                            'processing' => 'Processing', 
                            'ready' => 'Ready for Pickup', 
                            'completed' => 'Completed'
                        ];
                    }
                    $currentIndex = array_search($order->status, $statuses);
                    if ($order->status === 'cancelled') $currentIndex = -1;
                @endphp
                @foreach($statusLabels as $status => $label)
                    @php
                        $statusIndex = array_search($status, $statuses);
                        $isCompleted = $currentIndex !== false && $statusIndex <= $currentIndex;
                        $isCurrent = $order->status === $status;
                    @endphp
                    <div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: {{ $isCurrent ? 'var(--primary-light)' : ($isCompleted ? '#D1FAE5' : 'var(--gray-100)') }}; border-radius: 8px;">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background: {{ $isCompleted ? 'var(--success)' : 'var(--gray-200)' }}; display: flex; align-items: center; justify-content: center; color: white;">
                            @if($isCompleted)
                                <i class="fas fa-check"></i>
                            @endif
                        </div>
                        <span style="font-weight: {{ $isCurrent ? '600' : '400' }}; color: {{ $isCompleted ? 'var(--success)' : 'var(--gray-400)' }};">{{ $label }}</span>
                    </div>
                @endforeach

                @if($order->status === 'cancelled')
                <div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: #FEE2E2; border-radius: 8px;">
                    <div style="width: 30px; height: 30px; border-radius: 50%; background: var(--danger); display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-times"></i>
                    </div>
                    <span style="font-weight: 600; color: var(--danger);">Order Cancelled</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
