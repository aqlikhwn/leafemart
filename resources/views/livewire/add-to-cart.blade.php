<div>
    {{-- Variation Selection --}}
    @if($product->variations && $product->variations->count() > 0)
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-weight: 600; margin-bottom: 10px; color: var(--primary-dark);">
            Select Variation
        </label>
        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
            @foreach($product->variations as $variation)
            <button 
                type="button"
                wire:click="selectVariation('{{ $variation->name }}')"
                style="
                    padding: 10px 20px;
                    border: 2px solid {{ $selectedVariation === $variation->name ? 'var(--primary)' : 'var(--gray-200)' }};
                    border-radius: 10px;
                    background: {{ $selectedVariation === $variation->name ? 'var(--primary-light)' : 'white' }};
                    color: {{ $selectedVariation === $variation->name ? 'var(--primary)' : 'var(--gray-600)' }};
                    font-weight: 500;
                    cursor: {{ $variation->stock > 0 ? 'pointer' : 'not-allowed' }};
                    opacity: {{ $variation->stock > 0 ? '1' : '0.5' }};
                    transition: all 0.2s ease;
                "
                {{ $variation->stock <= 0 ? 'disabled' : '' }}
            >
                {{ $variation->name }}
                @if($variation->stock <= 0)
                <span style="font-size: 11px; color: var(--danger);">(Out of Stock)</span>
                @elseif($variation->stock <= 5)
                <span style="font-size: 11px; color: var(--warning);">({{ $variation->stock }} left)</span>
                @endif
            </button>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Quantity Control --}}
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-weight: 600; margin-bottom: 10px; color: var(--primary-dark);">
            Quantity
        </label>
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="display: flex; align-items: center; border: 2px solid var(--gray-200); border-radius: 10px; overflow: hidden;">
                <button 
                    type="button"
                    wire:click="decrement"
                    style="padding: 12px 18px; background: var(--gray-100); border: none; cursor: pointer; font-size: 18px; color: var(--gray-600);"
                >
                    <i class="fas fa-minus"></i>
                </button>
                <span style="padding: 12px 25px; font-size: 18px; font-weight: 600; min-width: 60px; text-align: center;">
                    {{ $quantity }}
                </span>
                <button 
                    type="button"
                    wire:click="increment"
                    style="padding: 12px 18px; background: var(--gray-100); border: none; cursor: pointer; font-size: 18px; color: var(--gray-600);"
                >
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Message --}}
    @if($message)
    <div style="
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 15px;
        background: {{ $messageType === 'success' ? 'var(--success)' : 'var(--danger)' }};
        color: white;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    ">
        <i class="fas {{ $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i>
        {{ $message }}
    </div>
    @endif

    {{-- Add to Cart Button --}}
    <button 
        type="button"
        wire:click="addToCart"
        wire:loading.attr="disabled"
        style="
            width: 100%;
            padding: 15px 30px;
            background: linear-gradient(135deg, var(--primary), #5a9fd4);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(74, 144, 217, 0.3);
        "
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(74, 144, 217, 0.4)';"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(74, 144, 217, 0.3)';"
    >
        <span wire:loading.remove wire:target="addToCart">
            <i class="fas fa-cart-plus"></i> Add to Cart
        </span>
        <span wire:loading wire:target="addToCart">
            <i class="fas fa-spinner fa-spin"></i> Adding...
        </span>
    </button>
</div>
