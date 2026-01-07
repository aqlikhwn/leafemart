@extends('layouts.app')

@section('title', 'Manage Orders')

@section('content')
<div class="page-header">
    <h1 class="page-title">Manage Orders</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Dashboard
    </a>
</div>

<!-- Status Filter -->
<div class="category-pills" style="margin-bottom: 20px;">
    <a href="{{ route('admin.orders.index') }}" class="category-pill {{ !$status ? 'active' : '' }}">
        All Orders <span style="opacity: 0.7;">({{ array_sum($statusCounts) }})</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="category-pill {{ $status == 'pending' ? 'active' : '' }}">
        <i class="fas fa-clock"></i> Pending <span style="opacity: 0.7;">({{ $statusCounts['pending'] ?? 0 }})</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="category-pill {{ $status == 'processing' ? 'active' : '' }}">
        <i class="fas fa-cog"></i> Processing <span style="opacity: 0.7;">({{ $statusCounts['processing'] ?? 0 }})</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'ready']) }}" class="category-pill {{ $status == 'ready' ? 'active' : '' }}">
        <i class="fas fa-box"></i> Ready for Pickup <span style="opacity: 0.7;">({{ $statusCounts['ready'] ?? 0 }})</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'out_for_delivery']) }}" class="category-pill {{ $status == 'out_for_delivery' ? 'active' : '' }}">
        <i class="fas fa-truck"></i> Out for Delivery <span style="opacity: 0.7;">({{ $statusCounts['out_for_delivery'] ?? 0 }})</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="category-pill {{ $status == 'completed' ? 'active' : '' }}">
        <i class="fas fa-check-circle"></i> Completed <span style="opacity: 0.7;">({{ $statusCounts['completed'] ?? 0 }})</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="category-pill {{ $status == 'cancelled' ? 'active' : '' }}">
        <i class="fas fa-times-circle"></i> Cancelled <span style="opacity: 0.7;">({{ $statusCounts['cancelled'] ?? 0 }})</span>
    </a>
</div>

<div class="card">
    @if($orders->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Items</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td><strong>#{{ $order->id }}</strong><br><small style="color: var(--gray-400);">{{ $order->created_at->format('d/m/Y H:i') }}</small></td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->phone }}</td>
                <td>
                    @if($order->items->count() == 1)
                        @php
                            $item = $order->items->first();
                            $itemImage = null;
                            if ($item->product) {
                                if ($item->variation) {
                                    $variation = $item->product->variations()->where('name', $item->variation)->first();
                                    if ($variation && $variation->image) {
                                        $itemImage = $variation->image;
                                    }
                                }
                                if (!$itemImage && $item->product->image) {
                                    $itemImage = $item->product->image;
                                }
                            }
                        @endphp
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 32px; height: 32px; background: var(--primary-light); border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; font-size: 14px;">
                                @if($itemImage)
                                <img src="{{ asset('storage/' . $itemImage) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                {{ $item->product->category->icon ?? '📦' }}
                                @endif
                            </div>
                            <div style="min-width: 0;">
                                <div style="font-size: 12px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 120px;">{{ $item->product->name ?? 'Deleted' }}</div>
                                @if($item->variation)
                                <div style="font-size: 10px; color: var(--primary);">{{ $item->variation }}</div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div style="position: relative;">
                            <button type="button" onclick="toggleOrderItems({{ $order->id }})" style="display: flex; align-items: center; gap: 6px; background: var(--primary-light); border: 1px solid var(--gray-200); padding: 4px 10px; border-radius: 6px; cursor: pointer; font-size: 12px; color: var(--primary-dark);">
                                <div style="display: flex;">
                                    @foreach($order->items->take(3) as $item)
                                    @php
                                        $itemImage = null;
                                        if ($item->product) {
                                            if ($item->variation) {
                                                $variation = $item->product->variations()->where('name', $item->variation)->first();
                                                if ($variation && $variation->image) {
                                                    $itemImage = $variation->image;
                                                }
                                            }
                                            if (!$itemImage && $item->product->image) {
                                                $itemImage = $item->product->image;
                                            }
                                        }
                                    @endphp
                                    <div style="width: 24px; height: 24px; background: white; border-radius: 50%; border: 2px solid white; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-left: {{ $loop->first ? '0' : '-8px' }}; font-size: 10px;">
                                        @if($itemImage)
                                        <img src="{{ asset('storage/' . $itemImage) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                        {{ $item->product->category->icon ?? '📦' }}
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <span>{{ $order->items->count() }} items</span>
                                <i class="fas fa-chevron-down" id="chevron-{{ $order->id }}" style="font-size: 9px; transition: transform 0.3s;"></i>
                            </button>
                            
                            <div id="items-{{ $order->id }}" style="display: none; position: absolute; top: 100%; left: 0; z-index: 10; background: white; border: 1px solid var(--gray-200); border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); padding: 10px; min-width: 260px; margin-top: 6px;">
                                @foreach($order->items as $item)
                                @php
                                    $itemImage = null;
                                    if ($item->product) {
                                        if ($item->variation) {
                                            $variation = $item->product->variations()->where('name', $item->variation)->first();
                                            if ($variation && $variation->image) {
                                                $itemImage = $variation->image;
                                            }
                                        }
                                        if (!$itemImage && $item->product->image) {
                                            $itemImage = $item->product->image;
                                        }
                                    }
                                @endphp
                                <div style="display: flex; align-items: center; gap: 8px; padding: 6px 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--gray-100);' : '' }}">
                                    <div style="width: 36px; height: 36px; background: var(--primary-light); border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                        @if($itemImage)
                                        <img src="{{ asset('storage/' . $itemImage) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                        {{ $item->product->category->icon ?? '📦' }}
                                        @endif
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="font-size: 12px; font-weight: 500;">{{ $item->product->name ?? 'Deleted Product' }}</div>
                                        @if($item->variation)
                                        <div style="font-size: 10px; color: var(--primary);">{{ $item->variation }}</div>
                                        @endif
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-size: 11px; color: var(--gray-400);">x{{ $item->quantity }}</div>
                                        <div style="font-size: 12px; font-weight: 600; color: var(--primary);">RM {{ number_format($item->subtotal, 2) }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </td>
                <td><strong>RM {{ number_format($order->total, 2) }}</strong></td>
                <td>{{ $order->payment_method == 'pay_at_store' ? 'Store' : 'Online' }}</td>
                <td>
                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <select name="status" onchange="this.form.submit()" class="form-control" style="padding: 6px 10px; width: auto;">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏰ Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>📦 Ready for Pickup</option>
                            <option value="out_for_delivery" {{ $order->status == 'out_for_delivery' ? 'selected' : '' }}>🚚 Out for Delivery</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        </select>
                    </form>
                </td>
                <td>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary" style="padding: 8px 12px;">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 20px;">{{ $orders->links() }}</div>
    @else
    <div class="empty-state">
        <i class="fas fa-shopping-bag"></i>
        <h3>No Orders Found</h3>
    </div>
    @endif
</div>

@push('scripts')
<script>
function toggleOrderItems(orderId) {
    const dropdown = document.getElementById('items-' + orderId);
    const chevron = document.getElementById('chevron-' + orderId);
    
    // Close all other dropdowns first
    document.querySelectorAll('[id^="items-"]').forEach(el => {
        if (el.id !== 'items-' + orderId) {
            el.style.display = 'none';
        }
    });
    document.querySelectorAll('[id^="chevron-"]').forEach(el => {
        if (el.id !== 'chevron-' + orderId) {
            el.style.transform = 'rotate(0deg)';
        }
    });
    
    // Toggle current dropdown
    if (dropdown.style.display === 'none') {
        dropdown.style.display = 'block';
        chevron.style.transform = 'rotate(180deg)';
    } else {
        dropdown.style.display = 'none';
        chevron.style.transform = 'rotate(0deg)';
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('[id^="items-"]') && !e.target.closest('button[onclick^="toggleOrderItems"]')) {
        document.querySelectorAll('[id^="items-"]').forEach(el => {
            el.style.display = 'none';
        });
        document.querySelectorAll('[id^="chevron-"]').forEach(el => {
            el.style.transform = 'rotate(0deg)';
        });
    }
});
</script>
@endpush
@endsection
