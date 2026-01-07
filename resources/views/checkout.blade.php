@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="page-header">
    <h1 class="page-title">Checkout</h1>
    @if($isBuyNow)
    <a href="{{ route('browse') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Continue Shopping
    </a>
    @else
    <a href="{{ route('cart.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Cart
    </a>
    @endif
</div>

<style>
    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    .mobile-confirm-btn {
        display: none;
    }
    @media (max-width: 768px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }
        .checkout-grid .checkout-form {
            order: 1;
        }
        .checkout-grid .checkout-summary {
            order: 2;
        }
        .desktop-confirm-btn {
            display: none;
        }
        .mobile-confirm-btn {
            display: block;
        }
    }
</style>
<div class="checkout-grid">
    <!-- Checkout Form -->
    <div class="card checkout-form">
        <h3 style="color: var(--primary-dark); margin-bottom: 20px;">Customer Information</h3>
        
        <form id="checkoutForm" action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if($isBuyNow)
            <input type="hidden" name="buy_now" value="1">
            @else
            @foreach($selectedItemIds as $itemId)
            <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
            @endforeach
            @endif
            
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="customer_name" class="form-control" value="{{ auth()->user()->name }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}" placeholder="e.g., 0123456789" required>
            </div>

            <h3 style="color: var(--primary-dark); margin: 30px 0 20px;"><i class="fas fa-truck"></i> Delivery Method</h3>

            <div style="display: flex; flex-direction: column; gap: 15px;">
                <label id="pickupOption" style="display: flex; align-items: center; gap: 15px; padding: 20px; background: var(--gray-100); border-radius: 12px; cursor: pointer; border: 2px solid var(--primary); transition: all 0.3s;" class="delivery-option">
                    <input type="radio" name="delivery_method" value="pickup" required checked style="width: 20px; height: 20px; accent-color: var(--primary);" onchange="toggleDeliveryDetails()">
                    <div>
                        <strong><i class="fas fa-store"></i> Pickup at Store</strong>
                        <p style="color: var(--gray-400); margin-top: 5px; font-size: 13px;">Collect your order at Leafé Mart, Mahallah Bilal</p>
                    </div>
                </label>

                <label id="deliveryOption" style="display: flex; align-items: center; gap: 15px; padding: 20px; background: var(--gray-100); border-radius: 12px; cursor: pointer; border: 2px solid transparent; transition: all 0.3s;" class="delivery-option">
                    <input type="radio" name="delivery_method" value="delivery" style="width: 20px; height: 20px; accent-color: var(--primary);" onchange="toggleDeliveryDetails()">
                    <div>
                        <strong><i class="fas fa-motorcycle"></i> Delivery</strong>
                        <p style="color: var(--gray-400); margin-top: 5px; font-size: 13px;">Delivery within IIUM Gombak only</p>
                    </div>
                </label>
            </div>

            <!-- Delivery Address (Hidden by default) -->
            <div id="deliveryAddressSection" style="display: none; margin-top: 20px; padding: 20px; background: #FEF3C7; border-radius: 12px; border: 2px solid #F59E0B;">
                <div class="alert alert-warning" style="margin-bottom: 15px; background: white;">
                    <i class="fas fa-info-circle"></i>
                    <span><strong>Note:</strong> Delivery is available only within <strong>IIUM Gombak campus</strong> (Mahallah areas only). A <strong>RM 3.00 delivery fee</strong> will be added.</span>
                </div>
                
                <div class="alert alert-info" style="margin-bottom: 15px; background: #DBEAFE;">
                    <i class="fas fa-credit-card"></i>
                    <span><strong>Payment:</strong> Delivery orders require <strong>online banking payment only</strong>.</span>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label"><i class="fas fa-map-marker-alt"></i> Delivery Address *</label>
                    <textarea name="delivery_address" id="deliveryAddressInput" class="form-control" rows="3" placeholder="e.g., Mahallah Aminah, Block A, Room 123">{{ auth()->user()->address }}</textarea>
                    <small style="color: var(--gray-600);">Please include your Mahallah name, block, and room number.</small>
                </div>
            </div>

            <h3 style="color: var(--primary-dark); margin: 30px 0 20px;"><i class="fas fa-credit-card"></i> Payment Method</h3>

            <div style="display: flex; flex-direction: column; gap: 15px;">
                <label id="payAtStoreOption" style="display: flex; align-items: center; gap: 15px; padding: 20px; background: var(--gray-100); border-radius: 12px; cursor: pointer; border: 2px solid transparent; transition: all 0.3s;" class="payment-option">
                    <input type="radio" name="payment_method" value="pay_at_store" required style="width: 20px; height: 20px; accent-color: var(--primary);" onchange="togglePaymentDetails()">
                    <div>
                        <strong><i class="fas fa-store"></i> Pay at Store</strong>
                        <p style="color: var(--gray-400); margin-top: 5px; font-size: 13px;">Pay when you collect or receive your order</p>
                    </div>
                </label>

                <label id="onlineBankingOption" style="display: flex; align-items: center; gap: 15px; padding: 20px; background: var(--gray-100); border-radius: 12px; cursor: pointer; border: 2px solid transparent; transition: all 0.3s;" class="payment-option">
                    <input type="radio" name="payment_method" value="online_banking" style="width: 20px; height: 20px; accent-color: var(--primary);" onchange="togglePaymentDetails()">
                    <div>
                        <strong><i class="fas fa-university"></i> Online Banking / QR Payment</strong>
                        <p style="color: var(--gray-400); margin-top: 5px; font-size: 13px;">Transfer to our bank account or scan QR code</p>
                    </div>
                </label>
            </div>

            <!-- Online Payment Details (Hidden by default) -->
            <div id="onlinePaymentDetails" style="display: none; margin-top: 20px; padding: 20px; background: #EEF2FF; border-radius: 12px; border: 2px solid var(--primary);">
                <h4 style="color: var(--primary-dark); margin-bottom: 15px;"><i class="fas fa-info-circle"></i> Transfer Payment Details</h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <!-- Bank Account Details -->
                    <div style="background: white; padding: 15px; border-radius: 10px;">
                        <h5 style="color: var(--primary); margin-bottom: 10px;"><i class="fas fa-university"></i> Bank Transfer</h5>
                        <p style="margin: 5px 0;"><strong>Bank:</strong> Maybank</p>
                        <p style="margin: 5px 0;"><strong>Account Name:</strong> Leafé Mart Sdn Bhd</p>
                        <p style="margin: 5px 0;"><strong>Account No:</strong> 1234-5678-9012</p>
                    </div>
                    
                    <!-- QR Code -->
                    <div style="background: white; padding: 15px; border-radius: 10px; text-align: center;">
                        <h5 style="color: var(--primary); margin-bottom: 10px;"><i class="fas fa-qrcode"></i> Scan QR Code</h5>
                        <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #1E3A5F, #4A90D9); border-radius: 10px; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-qrcode" style="font-size: 50px;"></i>
                        </div>
                        <p style="font-size: 12px; color: var(--gray-400); margin-top: 8px;">DuitNow QR</p>
                    </div>
                </div>

                <div class="alert alert-warning" style="margin-bottom: 15px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Transfer amount: <strong id="transferAmount">RM {{ number_format($total, 2) }}</strong> <span id="deliveryFeeNote" style="display: none;">(includes RM 3.00 delivery fee)</span> and upload your payment slip below.</span>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label"><i class="fas fa-upload"></i> Upload Payment Slip *</label>
                    <input type="file" name="payment_slip" id="paymentSlipInput" class="form-control" accept="image/*,.pdf">
                    <small style="color: var(--gray-400);">Accepted formats: JPG, PNG, PDF (Max 2MB)</small>
                </div>
            </div>

            <button type="submit" class="btn btn-primary desktop-confirm-btn" style="width: 100%; margin-top: 30px; padding: 16px;">
                <i class="fas fa-check"></i> Confirm Order
            </button>
        </form>
    </div>

    <!-- Order Summary -->
    <div class="card checkout-summary" style="height: fit-content;">
        <h3 style="color: var(--primary-dark); margin-bottom: 20px;">Order Summary</h3>
        
        @foreach($cartItems as $item)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--gray-200);">
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
                    <div style="font-weight: 500;">{{ $item->product->name }}</div>
                    @if($item->variation)
                    <div style="color: var(--primary); font-size: 12px; font-weight: 500;">{{ $item->variation }}</div>
                    @endif
                    <div style="color: var(--gray-400); font-size: 12px;">Qty: {{ $item->quantity }}</div>
                </div>
            </div>
            <strong>RM {{ number_format($item->subtotal, 2) }}</strong>
        </div>
        @endforeach

        <hr style="border: none; border-top: 2px solid var(--gray-200); margin: 20px 0;">
        
        <div style="display: flex; justify-content: space-between; padding: 8px 0; color: var(--gray-600);">
            <span>Subtotal</span>
            <span>RM {{ number_format($total, 2) }}</span>
        </div>
        
        <div id="deliveryFeeRow" style="display: none; justify-content: space-between; padding: 8px 0; color: var(--warning);">
            <span><i class="fas fa-motorcycle"></i> Delivery Fee</span>
            <span>RM 3.00</span>
        </div>
        
        <hr style="border: none; border-top: 1px solid var(--gray-200); margin: 10px 0;">
        
        <div style="display: flex; justify-content: space-between; font-size: 22px; font-weight: 700; color: var(--primary-dark);">
            <span>Total</span>
            <span id="orderTotal">RM {{ number_format($total, 2) }}</span>
        </div>
        
        <input type="hidden" name="delivery_fee" id="deliveryFeeInput" value="0">
    </div>
    
    <!-- Mobile Confirm Button -->
    <button type="button" class="btn btn-primary mobile-confirm-btn" onclick="document.getElementById('checkoutForm').submit()" style="width: 100%; padding: 16px; order: 3;">
        <i class="fas fa-check"></i> Confirm Order
    </button>
</div>

@push('scripts')
<script>
const subtotal = {{ $total }};
const deliveryFee = 3.00;

function toggleDeliveryDetails() {
    const delivery = document.querySelector('input[value="delivery"]');
    const addressSection = document.getElementById('deliveryAddressSection');
    const addressInput = document.getElementById('deliveryAddressInput');
    const deliveryFeeRow = document.getElementById('deliveryFeeRow');
    const orderTotal = document.getElementById('orderTotal');
    const transferAmount = document.getElementById('transferAmount');
    const deliveryFeeNote = document.getElementById('deliveryFeeNote');
    const deliveryFeeInput = document.getElementById('deliveryFeeInput');
    const payAtStoreOption = document.getElementById('payAtStoreOption');
    const payAtStoreRadio = document.querySelector('input[value="pay_at_store"]');
    const onlineBankingRadio = document.querySelector('input[value="online_banking"]');
    
    // Update border styling for delivery options
    document.querySelectorAll('.delivery-option').forEach(el => {
        el.style.borderColor = 'transparent';
    });
    
    if (delivery.checked) {
        // Show delivery address section
        addressSection.style.display = 'block';
        addressInput.required = true;
        document.getElementById('deliveryOption').style.borderColor = 'var(--primary)';
        
        // Show delivery fee
        deliveryFeeRow.style.display = 'flex';
        const total = subtotal + deliveryFee;
        orderTotal.textContent = 'RM ' + total.toFixed(2);
        transferAmount.textContent = 'RM ' + total.toFixed(2);
        deliveryFeeNote.style.display = 'inline';
        deliveryFeeInput.value = deliveryFee;
        
        // Force online banking for delivery
        payAtStoreOption.style.opacity = '0.5';
        payAtStoreOption.style.pointerEvents = 'none';
        payAtStoreRadio.disabled = true;
        
        // Auto-select online banking
        onlineBankingRadio.checked = true;
        togglePaymentDetails();
    } else {
        // Hide delivery address section
        addressSection.style.display = 'none';
        addressInput.required = false;
        document.getElementById('pickupOption').style.borderColor = 'var(--primary)';
        
        // Hide delivery fee
        deliveryFeeRow.style.display = 'none';
        orderTotal.textContent = 'RM ' + subtotal.toFixed(2);
        transferAmount.textContent = 'RM ' + subtotal.toFixed(2);
        deliveryFeeNote.style.display = 'none';
        deliveryFeeInput.value = 0;
        
        // Re-enable pay at store option
        payAtStoreOption.style.opacity = '1';
        payAtStoreOption.style.pointerEvents = 'auto';
        payAtStoreRadio.disabled = false;
    }
}

function togglePaymentDetails() {
    const onlineBanking = document.querySelector('input[value="online_banking"]');
    const paymentDetails = document.getElementById('onlinePaymentDetails');
    const paymentSlipInput = document.getElementById('paymentSlipInput');
    
    // Update border styling for payment options
    document.querySelectorAll('.payment-option').forEach(el => {
        el.style.borderColor = 'transparent';
    });
    
    if (onlineBanking.checked) {
        paymentDetails.style.display = 'block';
        paymentSlipInput.required = true;
        document.getElementById('onlineBankingOption').style.borderColor = 'var(--primary)';
    } else {
        paymentDetails.style.display = 'none';
        paymentSlipInput.required = false;
        document.getElementById('payAtStoreOption').style.borderColor = 'var(--primary)';
    }
}
</script>
@endpush
@endsection
