@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
@if($cartItems->count() > 0)
<div class="page-header">
    <h1 class="page-title">Shopping Cart</h1>
    <div style="display: flex; gap: 10px;">
        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear all items from your cart?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Clear All
            </button>
        </form>
        <a href="{{ route('browse') }}" class="btn btn-secondary">
            <i class="fas fa-plus"></i> Continue Shopping
        </a>
    </div>
</div>

<style>
    .cart-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
    }
    @media (max-width: 900px) {
        .cart-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="cart-grid">
    <!-- Cart Items -->
    <div class="card">
        <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="selectAll" style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    </th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr data-item-id="{{ $item->id }}" data-subtotal="{{ $item->subtotal }}">
                    <td>
                        <input type="checkbox" class="item-checkbox" value="{{ $item->id }}" style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;" checked>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            @php
                                $itemImage = null;
                                if ($item->variation) {
                                    $variation = $item->product->variations()->where('name', $item->variation)->first();
                                    if ($variation && $variation->image) {
                                        $itemImage = $variation->image;
                                    }
                                }
                                if (!$itemImage && $item->product->image) {
                                    $itemImage = $item->product->image;
                                }
                            @endphp
                            <div style="width: 60px; height: 60px; background: var(--primary-light); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; overflow: hidden;">
                                @if($itemImage)
                                <img src="{{ asset('storage/' . $itemImage) }}" alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                {{ $item->product->category->icon ?? '📦' }}
                                @endif
                            </div>
                            <div>
                                <strong>{{ $item->product->name }}</strong>
                                @if($item->variation)
                                <div style="color: var(--primary); font-size: 12px; font-weight: 500;">{{ $item->variation }}</div>
                                @endif
                                <div style="color: var(--gray-400); font-size: 12px;">{{ $item->product->category->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td>RM {{ number_format($item->subtotal / $item->quantity, 2) }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                <button type="submit" class="quantity-btn" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-minus"></i>
                                </button>
                            </form>
                            <span class="quantity-value">{{ $item->quantity }}</span>
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                @php
                                    $maxStock = $item->product->stock;
                                    if ($item->variation) {
                                        $variation = $item->product->variations()->where('name', $item->variation)->first();
                                        if ($variation) {
                                            $maxStock = $variation->stock;
                                        }
                                    }
                                @endphp
                                <button type="submit" class="quantity-btn" {{ $item->quantity >= $maxStock ? 'disabled' : '' }}>
                                    <i class="fas fa-plus"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    <td><strong>RM {{ number_format($item->subtotal, 2) }}</strong></td>
                    <td>
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 8px 12px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="card" style="height: fit-content;">
        <h3 style="color: var(--primary-dark); margin-bottom: 20px;">Order Summary</h3>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span>Subtotal (<span id="selectedCount">{{ $cartItems->count() }}</span> items)</span>
            <span id="subtotalDisplay">RM {{ number_format($total, 2) }}</span>
        </div>
        
        <hr style="border: none; border-top: 1px solid var(--gray-200); margin: 15px 0;">
        
        <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: 700; color: var(--primary-dark);">
            <span>Total</span>
            <span id="totalDisplay">RM {{ number_format($total, 2) }}</span>
        </div>

        <a href="#" id="checkoutBtn" class="btn btn-primary" style="width: 100%; margin-top: 20px; justify-content: center;">
            <i class="fas fa-credit-card"></i> Proceed to Checkout
        </a>
    </div>
</div>
@else
<div class="page-header">
    <h1 class="page-title">Shopping Cart</h1>
</div>
<div class="card">
    <div class="empty-state">
        <i class="fas fa-shopping-cart"></i>
        <h3>Your cart is empty</h3>
        <p>Looks like you haven't added any items yet.</p>
        <a href="{{ route('browse') }}" class="btn btn-primary" style="margin-top: 15px;">Start Shopping</a>
    </div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const selectedCountEl = document.getElementById('selectedCount');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    
    if (!selectAllCheckbox) return;
    
    function updateSummary() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        let total = 0;
        let selectedItems = [];
        
        checkedBoxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            total += parseFloat(row.dataset.subtotal);
            selectedItems.push(checkbox.value);
        });
        
        selectedCountEl.textContent = checkedBoxes.length;
        subtotalDisplay.textContent = 'RM ' + total.toFixed(2);
        totalDisplay.textContent = 'RM ' + total.toFixed(2);
        
        if (selectedItems.length === 0) {
            checkoutBtn.style.pointerEvents = 'none';
            checkoutBtn.style.opacity = '0.5';
            checkoutBtn.innerHTML = '<i class="fas fa-credit-card"></i> Select Items to Checkout';
        } else {
            checkoutBtn.style.pointerEvents = 'auto';
            checkoutBtn.style.opacity = '1';
            checkoutBtn.innerHTML = '<i class="fas fa-credit-card"></i> Proceed to Checkout';
            checkoutBtn.href = '{{ route("checkout") }}?selected_items=' + selectedItems.join(',');
        }
    }
    
    selectAllCheckbox.addEventListener('change', function() {
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSummary();
    });
    
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = document.querySelectorAll('.item-checkbox:checked').length === itemCheckboxes.length;
            selectAllCheckbox.checked = allChecked;
            updateSummary();
        });
    });
    
    // Initial update
    updateSummary();
});
</script>
@endpush
@endsection
