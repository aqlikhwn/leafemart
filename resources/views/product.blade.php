@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="page-header">
    @if(request('from') == 'home')
    <a href="{{ route('home') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Home
    </a>
    @else
    <a href="{{ route('browse') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Browse
    </a>
    @endif
</div>

<div class="card product-detail-grid">
    <!-- Product Image -->
    <div>
        <div class="product-image" style="height: 400px; border-radius: 16px; margin: 0; width: 100%;">
            @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" id="mainProductImage">
            @else
            <span style="font-size: 120px;">{{ $product->category->icon ?? '📦' }}</span>
            @endif
        </div>

        {{-- Thumbnail Gallery --}}
        @php $additionalImages = $product->images ? json_decode($product->images, true) : []; @endphp
        @if($product->image)
        <div style="display: flex; gap: 10px; margin-top: 15px; flex-wrap: wrap;">
            {{-- Main image thumbnail --}}
            <div style="width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 3px solid var(--primary); cursor: pointer;" 
                 onclick="changeMainImage('{{ asset('storage/' . $product->image) }}', this)">
                <img src="{{ asset('storage/' . $product->image) }}" alt="Main" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            {{-- Additional image thumbnails --}}
            @if(is_array($additionalImages) && count($additionalImages) > 0)
            @foreach($additionalImages as $img)
            <div style="width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 2px solid var(--gray-200); cursor: pointer;"
                 onclick="changeMainImage('{{ asset('storage/' . $img) }}', this)">
                <img src="{{ asset('storage/' . $img) }}" alt="Additional" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            @endforeach
            @endif
        </div>
        @endif
    </div>

    <!-- Product Details -->
    <div>
        <span class="badge badge-primary" style="margin-bottom: 15px;">{{ $product->category->name }}</span>
        <h1 style="color: var(--primary-dark); font-size: 28px; margin-bottom: 10px;">{{ $product->name }}</h1>
        

        <div style="font-size: 32px; font-weight: 700; color: var(--primary); margin-bottom: 20px;" id="display-price">
            @if($product->price_range)
                {{ $product->price_range }}
            @else
                RM {{ number_format($product->price, 2) }}
            @endif
        </div>

        @if($product->isInStock())
        <p style="color: var(--success); margin-bottom: 25px;">
            <i class="fas fa-check-circle"></i> 
            @if($product->hasVariations())
                In Stock - Select a variation below ({{ $product->total_stock }} total available)
            @else
                In Stock ({{ $product->stock }} available)
            @endif
        </p>

        @auth
        <form action="{{ route('cart.add') }}" method="POST" id="add-to-cart-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <!-- Variation Selector -->
            @if($product->hasVariations())
            <div id="variation-section" style="margin-bottom: 20px; padding: 15px; border-radius: 12px; transition: all 0.3s ease;">
                <label class="form-label">Choose Variation:</label>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;" id="variation-options">
                    @foreach($product->variations()->active()->get() as $variation)
                    <label class="variation-option" style="display: inline-block;">
                        <input type="radio" name="variation" value="{{ $variation->name }}" 
                               data-stock="{{ $variation->stock }}" 
                               data-price="{{ $variation->price }}"
                               data-image="{{ $variation->image ? asset('storage/' . $variation->image) : '' }}"
                               style="display: none;" 
                               {{ $variation->stock == 0 ? 'disabled' : '' }}
                               onchange="updateVariation(this)">
                        <span class="variation-btn {{ $variation->stock == 0 ? 'out-of-stock' : '' }}" 
                              style="display: inline-flex; flex-direction: column; align-items: center; padding: 12px 20px; border: 1px solid var(--gray-200); border-radius: 10px; cursor: {{ $variation->stock > 0 ? 'pointer' : 'not-allowed' }}; transition: all 0.3s ease; font-weight: 500; min-width: 90px; position: relative; overflow: hidden; {{ $variation->stock == 0 ? 'opacity: 0.5;' : '' }}">
                            <span class="variation-tick" style="display: none; position: absolute; bottom: 0; right: 0; width: 22px; height: 22px; background: var(--primary); clip-path: polygon(100% 0, 0 100%, 100% 100%);">
                                <i class="fas fa-check" style="position: absolute; bottom: 3px; right: 3px; font-size: 9px; color: white;"></i>
                            </span>
                            <span style="font-size: 15px;">{{ $variation->name }}</span>
                            @if($variation->stock > 0)
                                <small style="color: var(--success); font-size: 11px; margin-top: 4px;">
                                    <i class="fas fa-check-circle"></i> {{ $variation->stock }} in stock
                                </small>
                            @else
                                <small style="color: var(--danger); font-size: 11px; margin-top: 4px;">
                                    <i class="fas fa-times-circle"></i> Out of stock
                                </small>
                            @endif
                        </span>
                    </label>
                    @endforeach
                </div>
                <p id="variation-error" style="display: none; color: var(--danger); margin-top: 10px; font-size: 14px;">
                    <i class="fas fa-exclamation-circle"></i> Please select a product variation first
                </p>
            </div>
            @endif

            <div style="display: flex; gap: 15px; align-items: center;">
                <div class="quantity-controls">
                    <button type="button" class="quantity-btn" onclick="decreaseQty()">-</button>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" 
                           max="{{ $product->hasVariations() ? $product->variations()->active()->first()->stock : $product->stock }}" 
                           class="quantity-value" style="width: 50px; text-align: center; border: none;">
                    <button type="button" class="quantity-btn" onclick="increaseQty()">+</button>
                </div>

                <button type="button" class="btn btn-secondary" style="flex: 1; border: 2px solid var(--primary);" id="add-to-cart-btn" onclick="addToCart()">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
                <button type="button" class="btn btn-primary" style="flex: 1;" id="buy-now-btn" onclick="buyNow()">
                    <i class="fas fa-bolt"></i> Buy Now
                </button>
            </div>
        </form>
        
        <!-- Hidden Buy Now Form -->
        <form id="buy-now-form" action="{{ route('buy.now') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" id="buy-now-quantity" value="1">
            <input type="hidden" name="variation" id="buy-now-variation" value="">
        </form>
        @else
        <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%;">
            <i class="fas fa-sign-in-alt"></i> Login to Add to Cart
        </a>
        @endauth
        @else
        <p style="color: var(--danger);">
            <i class="fas fa-times-circle"></i> Out of Stock
        </p>
        @endif
    </div>
</div>

<!-- Product Description Section -->
@if($product->description || $product->description_image)
<div class="card" style="margin-top: 30px;" id="description">
    <h3 style="color: var(--primary-dark); margin-bottom: 15px; font-size: 20px;">
        <i class="fas fa-info-circle"></i> Product Description
    </h3>
    @if($product->description)
    <div class="product-description" style="color: var(--gray-600); line-height: 1.8; margin-bottom: 20px;">
        {!! nl2br(e($product->description)) !!}
    </div>
    @endif
    @if($product->description_image)
    <div style="text-align: center;">
        <img src="{{ asset('storage/' . $product->description_image) }}" alt="Product Description" style="max-width: 100%; border-radius: 12px;">
    </div>
    @endif
</div>
@endif

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<div style="margin-top: 40px;">
    <h2 style="color: var(--primary-dark); margin-bottom: 20px;">Related Products</h2>
    <div class="grid grid-4">
        @foreach($relatedProducts as $related)
        <a href="{{ route('product.show', $related->id) }}" class="product-card" style="text-decoration: none;">
            <div class="product-image">
                @if($related->image)
                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                {{ $related->category->icon ?? '📦' }}
                @endif
            </div>
            <div class="product-info">
                <div class="product-name">{{ $related->name }}</div>
                <div class="product-price">RM {{ number_format($related->price, 2) }}</div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

@push('styles')
<style>
.variation-btn {
    border: 2px solid #aaa !important;
}
.variation-option input:checked + .variation-btn {
    border: 2px solid var(--primary) !important;
}
.variation-btn.active {
    border: 2px solid var(--primary) !important;
}
.variation-btn:hover {
    border-color: var(--primary);
}
</style>
@endpush

@push('scripts')
<script>
const basePrice = {{ $product->price }};
const priceRange = '{{ $product->price_range ?? "RM " . number_format($product->price, 2) }}';
const hasVariations = {{ $product->hasVariations() ? 'true' : 'false' }};

function decreaseQty() {
    const input = document.getElementById('quantity');
    if (input.value > 1) input.value = parseInt(input.value) - 1;
}

function increaseQty() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.max);
    if (input.value < max) input.value = parseInt(input.value) + 1;
}

function updateVariation(radio) {
    // Clear any error highlight when variant is selected
    clearVariationError();
    
    // Update active styling and tick visibility
    document.querySelectorAll('.variation-btn').forEach(btn => {
        btn.classList.remove('active');
        const tick = btn.querySelector('.variation-tick');
        if (tick) tick.style.display = 'none';
    });
    radio.nextElementSibling.classList.add('active');
    const selectedTick = radio.nextElementSibling.querySelector('.variation-tick');
    if (selectedTick) selectedTick.style.display = 'block';
    
    // Update max quantity based on selected variation stock
    const stock = parseInt(radio.dataset.stock);
    const quantityInput = document.getElementById('quantity');
    quantityInput.max = stock;
    if (parseInt(quantityInput.value) > stock) {
        quantityInput.value = stock > 0 ? 1 : 0;
    }
    
    // Update price display with variation price
    const variationPrice = parseFloat(radio.dataset.price);
    if (variationPrice && variationPrice > 0) {
        document.getElementById('display-price').textContent = 'RM ' + variationPrice.toFixed(2);
    } else {
        document.getElementById('display-price').textContent = 'RM ' + basePrice.toFixed(2);
    }
    
    // Update out of stock display
    const addBtn = document.getElementById('add-to-cart-btn');
    const buyNowBtn = document.getElementById('buy-now-btn');
    if (stock === 0) {
        addBtn.disabled = true;
        addBtn.innerHTML = '<i class="fas fa-times-circle"></i> Out of Stock';
        if (buyNowBtn) buyNowBtn.disabled = true;
    } else {
        addBtn.disabled = false;
        addBtn.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
        if (buyNowBtn) buyNowBtn.disabled = false;
    }
    
    // Change main image if variation has an image
    const variationImage = radio.dataset.image;
    if (variationImage && variationImage.length > 0) {
        const mainImage = document.getElementById('mainProductImage');
        if (mainImage) {
            mainImage.src = variationImage;
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const firstRadio = document.querySelector('input[name="variation"]:checked');
    if (firstRadio) {
        updateVariation(firstRadio);
    }
});

// Change main product image
function changeMainImage(src, thumbnail) {
    const mainImage = document.getElementById('mainProductImage');
    if (mainImage) {
        mainImage.src = src;
    }
    
    // Update thumbnail borders
    const thumbnails = thumbnail.parentElement.children;
    for (let i = 0; i < thumbnails.length; i++) {
        thumbnails[i].style.border = '2px solid var(--gray-200)';
    }
    thumbnail.style.border = '3px solid var(--primary)';
    
    // Unselect all variants
    const variantRadios = document.querySelectorAll('input[name="variation"]');
    variantRadios.forEach(radio => {
        radio.checked = false;
    });
    
    // Remove active class and hide ticks from variant buttons
    document.querySelectorAll('.variation-btn').forEach(btn => {
        btn.classList.remove('active');
        const tick = btn.querySelector('.variation-tick');
        if (tick) tick.style.display = 'none';
    });
    
    // Reset price to price range
    document.getElementById('display-price').textContent = priceRange;
    
    // Clear any error state when image is clicked (variant deselected)
    clearVariationError();
}

// Show error highlight on variation section
function showVariationError() {
    const section = document.getElementById('variation-section');
    const error = document.getElementById('variation-error');
    
    if (section) {
        section.style.backgroundColor = 'rgba(220, 53, 69, 0.1)';
        section.style.border = '2px solid var(--danger)';
    }
    if (error) {
        error.style.display = 'block';
    }
    
    // Scroll to the variation section
    if (section) {
        section.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

// Clear error highlight
function clearVariationError() {
    const section = document.getElementById('variation-section');
    const error = document.getElementById('variation-error');
    
    if (section) {
        section.style.backgroundColor = 'transparent';
        section.style.border = 'none';
    }
    if (error) {
        error.style.display = 'none';
    }
}

// Check if variant is required and not selected
function checkVariantSelection() {
    if (hasVariations) {
        const selectedVariation = document.querySelector('input[name="variation"]:checked');
        if (!selectedVariation) {
            showVariationError();
            return false;
        }
        clearVariationError();
    }
    return true;
}

// Add to Cart function with AJAX
function addToCart() {
    if (!checkVariantSelection()) return;
    
    const form = document.getElementById('add-to-cart-form');
    const formData = new FormData(form);
    const addBtn = document.getElementById('add-to-cart-btn');
    
    // Disable button and show loading
    addBtn.disabled = true;
    addBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success toast
            showToast(data.message, 'success');
            
            // Update ALL cart count badges in header
            const cartBadges = document.querySelectorAll('.cart-count');
            cartBadges.forEach(badge => {
                badge.textContent = data.cartCount;
                badge.style.display = data.cartCount > 0 ? 'flex' : 'none';
            });
            
            // Dispatch Livewire event to update cart counter
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('cart-updated');
            }
        } else if (data.error) {
            showToast(data.error, 'error');
        }
    })

    .catch(error => {
        console.error('Error:', error);
        showToast('Something went wrong. Please try again.', 'error');
    })
    .finally(() => {
        // Re-enable button
        addBtn.disabled = false;
        addBtn.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
    });
}

// Toast notification function
function showToast(message, type = 'success') {
    // Remove existing toast
    const existingToast = document.getElementById('cart-toast');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.id = 'cart-toast';
    toast.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        animation: slideIn 0.3s ease;
        max-width: 350px;
    `;
    
    if (type === 'success') {
        toast.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
        toast.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
    } else {
        toast.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
        toast.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + message;
    }
    
    // Add animation style
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Buy Now function - submits to checkout directly
function buyNow() {
    if (!checkVariantSelection()) return;
    
    const quantity = document.getElementById('quantity').value;
    const selectedVariation = document.querySelector('input[name="variation"]:checked');
    
    document.getElementById('buy-now-quantity').value = quantity;
    document.getElementById('buy-now-variation').value = selectedVariation ? selectedVariation.value : '';
    
    document.getElementById('buy-now-form').submit();
}
</script>
@endpush
@endsection
