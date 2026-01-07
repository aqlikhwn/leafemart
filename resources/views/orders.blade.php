@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="page-header">
    <h1 class="page-title">My Orders</h1>
</div>

<!-- Status Filter Pills -->
<style>
    .filter-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
        padding: 15px 0;
    }
    .filter-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 500;
        color: var(--primary-dark);
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    .filter-pill:hover {
        border-color: var(--primary);
        color: var(--primary);
    }
    .filter-pill.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    .filter-pill i {
        font-size: 13px;
    }
    @media (max-width: 768px) {
        .filter-pills {
            padding: 12px 0;
            gap: 8px;
        }
        .filter-pill {
            padding: 8px 14px;
            font-size: 13px;
        }
    }
</style>
<div class="filter-pills">
    @php
        $totalOrders = array_sum($statusCounts);
    @endphp
    <a href="{{ route('orders.history') }}" class="filter-pill {{ !$status ? 'active' : '' }}">
        All ({{ $totalOrders }})
    </a>
    <a href="{{ route('orders.history', ['status' => 'pending']) }}" class="filter-pill {{ $status === 'pending' ? 'active' : '' }}">
        <i class="fas fa-clock"></i> Pending ({{ $statusCounts['pending'] ?? 0 }})
    </a>
    <a href="{{ route('orders.history', ['status' => 'processing']) }}" class="filter-pill {{ $status === 'processing' ? 'active' : '' }}">
        <i class="fas fa-cog"></i> Processing ({{ $statusCounts['processing'] ?? 0 }})
    </a>
    <a href="{{ route('orders.history', ['status' => 'ready']) }}" class="filter-pill {{ $status === 'ready' ? 'active' : '' }}">
        <i class="fas fa-box"></i> Ready for Pickup ({{ $statusCounts['ready'] ?? 0 }})
    </a>
    <a href="{{ route('orders.history', ['status' => 'out_for_delivery']) }}" class="filter-pill {{ $status === 'out_for_delivery' ? 'active' : '' }}">
        <i class="fas fa-truck"></i> Out for Delivery ({{ $statusCounts['out_for_delivery'] ?? 0 }})
    </a>
    <a href="{{ route('orders.history', ['status' => 'completed']) }}" class="filter-pill {{ $status === 'completed' ? 'active' : '' }}">
        <i class="fas fa-check-circle"></i> Completed ({{ $statusCounts['completed'] ?? 0 }})
    </a>
    <a href="{{ route('orders.history', ['status' => 'cancelled']) }}" class="filter-pill {{ $status === 'cancelled' ? 'active' : '' }}">
        <i class="fas fa-times-circle"></i> Cancelled ({{ $statusCounts['cancelled'] ?? 0 }})
    </a>


</div>

@if($orders->count() > 0)
<style>
    .items-dropdown {
        position: relative;
    }
    .items-dropdown-toggle {
        cursor: pointer;
        color: white;
        background: linear-gradient(135deg, var(--primary), #5a9fd4);
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        margin-top: 5px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(74, 144, 217, 0.3);
    }
    .items-dropdown-toggle:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(74, 144, 217, 0.4);
    }
    .items-dropdown-toggle i {
        transition: transform 0.3s ease;
    }
    .items-dropdown.open .items-dropdown-toggle i {
        transform: rotate(180deg);
    }
    .items-dropdown-content {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        padding: 12px;
        z-index: 100;
        min-width: 280px;
        max-height: 250px;
        overflow-y: auto;
        animation: dropdownSlide 0.2s ease;
    }
    @keyframes dropdownSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .items-dropdown.open .items-dropdown-content {
        display: block;
    }
    .dropdown-header {
        font-size: 11px;
        font-weight: 600;
        color: var(--gray-400);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-bottom: 8px;
        margin-bottom: 8px;
        border-bottom: 2px solid var(--primary-light);
    }
    .dropdown-item-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
        border-radius: 10px;
        transition: all 0.2s ease;
        margin-bottom: 4px;
    }
    .dropdown-item-row:hover {
        background: var(--primary-light);
    }
    .dropdown-item-row:last-child {
        margin-bottom: 0;
    }
    .dropdown-item-image {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-light), #e8f4fd);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .dropdown-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .dropdown-item-info {
        flex: 1;
    }
    .dropdown-item-name {
        font-size: 13px;
        font-weight: 500;
        color: var(--primary-dark);
    }
    .dropdown-item-variant {
        color: var(--primary);
        font-size: 11px;
    }
    .dropdown-item-qty {
        background: var(--gray-100);
        color: var(--gray-600);
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 10px;
    }
</style>
<div class="card">
    <div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Items</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td><strong>#{{ $order->id }}</strong></td>
                <td>
                    @if($order->items->count() === 1)
                    {{-- Single Item: Show normally --}}
                    @php
                        $singleItem = $order->items->first();
                        $singleImage = null;
                        if ($singleItem->product) {
                            if ($singleItem->variation) {
                                $singleVar = $singleItem->product->variations()->where('name', $singleItem->variation)->first();
                                if ($singleVar && $singleVar->image) {
                                    $singleImage = $singleVar->image;
                                }
                            }
                            if (!$singleImage && $singleItem->product->image) {
                                $singleImage = $singleItem->product->image;
                            }
                        }
                    @endphp
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 40px; height: 40px; background: var(--primary-light); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; overflow: hidden; flex-shrink: 0;">
                            @if($singleImage)
                            <img src="{{ asset('storage/' . $singleImage) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            {{ $singleItem->product->category->icon ?? '📦' }}
                            @endif
                        </div>
                        <div style="font-size: 13px; font-weight: 500;">
                            {{ $singleItem->product->name ?? 'Deleted Product' }}
                            @if($singleItem->variation)
                            <span style="color: var(--primary);">({{ $singleItem->variation }})</span>
                            @endif
                            <span style="color: var(--gray-400);">x{{ $singleItem->quantity }}</span>
                        </div>
                    </div>
                    @else
                    {{-- Multiple Items: Show stacked images with dropdown --}}
                    <div class="items-dropdown" id="dropdown-{{ $order->id }}">
                        <div class="items-preview" onclick="toggleDropdown({{ $order->id }})" style="cursor: pointer;">
                            <div style="display: flex; align-items: center;">
                                <div style="display: flex; position: relative;">
                                    @foreach($order->items->take(3) as $index => $item)
                                    @php
                                        $previewImage = null;
                                        if ($item->product) {
                                            if ($item->variation) {
                                                $previewVar = $item->product->variations()->where('name', $item->variation)->first();
                                                if ($previewVar && $previewVar->image) {
                                                    $previewImage = $previewVar->image;
                                                }
                                            }
                                            if (!$previewImage && $item->product->image) {
                                                $previewImage = $item->product->image;
                                            }
                                        }
                                    @endphp
                                    <div style="width: 36px; height: 36px; background: var(--primary-light); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; overflow: hidden; border: 2px solid white; margin-left: {{ $index > 0 ? '-12px' : '0' }}; position: relative; z-index: {{ 10 - $index }}; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        @if($previewImage)
                                        <img src="{{ asset('storage/' . $previewImage) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                        {{ $item->product->category->icon ?? '📦' }}
                                        @endif
                                    </div>
                                    @endforeach
                                    @if($order->items->count() > 3)
                                    <div style="width: 36px; height: 36px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 600; color: white; border: 2px solid white; margin-left: -12px; position: relative; z-index: 5;">
                                        +{{ $order->items->count() - 3 }}
                                    </div>
                                    @endif
                                </div>
                                <div style="margin-left: 10px;">
                                    <span class="items-dropdown-toggle">
                                        {{ $order->items->count() }} items <i class="fas fa-chevron-down"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="items-dropdown-content">
                            <div class="dropdown-header">Order Items ({{ $order->items->count() }})</div>
                            @foreach($order->items as $item)
                            @php
                                $subItemImage = null;
                                if ($item->product) {
                                    if ($item->variation) {
                                        $subVariation = $item->product->variations()->where('name', $item->variation)->first();
                                        if ($subVariation && $subVariation->image) {
                                            $subItemImage = $subVariation->image;
                                        }
                                    }
                                    if (!$subItemImage && $item->product->image) {
                                        $subItemImage = $item->product->image;
                                    }
                                }
                            @endphp
                            <div class="dropdown-item-row">
                                <div class="dropdown-item-image">
                                    @if($subItemImage)
                                    <img src="{{ asset('storage/' . $subItemImage) }}" alt="">
                                    @else
                                    {{ $item->product->category->icon ?? '📦' }}
                                    @endif
                                </div>
                                <div class="dropdown-item-info">
                                    <div class="dropdown-item-name">
                                        {{ $item->product->name ?? 'Deleted Product' }}
                                    </div>
                                    @if($item->variation)
                                    <span class="dropdown-item-variant">{{ $item->variation }}</span>
                                    @endif
                                </div>
                                <span class="dropdown-item-qty">×{{ $item->quantity }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </td>



                <td><strong>RM {{ number_format($order->total, 2) }}</strong></td>
                <td>
                    @if($order->payment_method === 'online_banking')
                        <span class="badge badge-info"><i class="fas fa-university"></i> Online</span>
                    @else
                        <span class="badge badge-primary"><i class="fas fa-money-bill"></i> At Store</span>
                    @endif
                </td>
                <td>
                    @switch($order->status)
                        @case('pending')
                            <span class="badge badge-warning">Pending</span>
                            @break
                        @case('processing')
                            <span class="badge badge-info">Processing</span>
                            @break
                        @case('ready')
                            <span class="badge badge-primary">Ready</span>
                            @break
                        @case('out_for_delivery')
                            <span class="badge badge-info">Out for Delivery</span>
                            @break
                        @case('completed')
                            <span class="badge badge-success">Completed</span>
                            @break
                        @case('cancelled')
                            <span class="badge badge-danger">Cancelled</span>
                            @break
                        @default
                            <span class="badge">{{ ucfirst($order->status) }}</span>
                    @endswitch
                </td>
                <td style="color: var(--gray-400); font-size: 13px;">
                    {{ $order->created_at->format('d M Y') }}<br>
                    {{ $order->created_at->format('h:i A') }}
                </td>
                <td>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary" style="padding: 8px 12px; border-radius: 50%;" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

@push('scripts')
<script>
function toggleDropdown(orderId) {
    const dropdown = document.getElementById('dropdown-' + orderId);
    dropdown.classList.toggle('open');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.items-dropdown')) {
        document.querySelectorAll('.items-dropdown.open').forEach(d => d.classList.remove('open'));
    }
});
</script>
@endpush


<div style="margin-top: 20px;">
    {{ $orders->appends(request()->query())->links() }}
</div>
@else
<div class="card">
    <div class="empty-state">
        <i class="fas fa-receipt"></i>
        <h3>No Orders Found</h3>
        @if($status)
        <p>You don't have any {{ $status }} orders.</p>
        <a href="{{ route('orders.history') }}" class="btn btn-secondary" style="margin-top: 15px;">View All Orders</a>
        @else
        <p>You haven't placed any orders yet.</p>
        <a href="{{ route('browse') }}" class="btn btn-primary" style="margin-top: 15px;">Start Shopping</a>
        @endif
    </div>
</div>
@endif
@endsection
