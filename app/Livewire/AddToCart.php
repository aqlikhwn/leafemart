<?php

namespace App\Livewire;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddToCart extends Component
{
    public $product;
    public $quantity = 1;
    public $selectedVariation = null;
    public $message = '';
    public $messageType = '';

    public function mount($product)
    {
        $this->product = $product;
        
        // Set default variation if product has variations
        if ($product->variations && $product->variations->count() > 0) {
            $this->selectedVariation = $product->variations->first()->name;
        }
    }

    public function increment()
    {
        $maxStock = $this->getMaxStock();
        if ($this->quantity < $maxStock) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function selectVariation($variation)
    {
        $this->selectedVariation = $variation;
        $this->quantity = 1; // Reset quantity when variation changes
    }

    private function getMaxStock()
    {
        if ($this->selectedVariation && $this->product->variations) {
            $variation = $this->product->variations->where('name', $this->selectedVariation)->first();
            return $variation ? $variation->stock : $this->product->stock;
        }
        return $this->product->stock;
    }

    public function addToCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $maxStock = $this->getMaxStock();
        
        if ($this->quantity > $maxStock) {
            $this->message = 'Not enough stock available.';
            $this->messageType = 'error';
            return;
        }

        // Check if item already exists in cart
        $existingItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->where('variation', $this->selectedVariation)
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $this->quantity;
            if ($newQuantity > $maxStock) {
                $this->message = 'Cannot add more than available stock.';
                $this->messageType = 'error';
                return;
            }
            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            // Get price (variation or product price)
            $price = $this->product->price;
            if ($this->selectedVariation && $this->product->variations) {
                $variation = $this->product->variations->where('name', $this->selectedVariation)->first();
                if ($variation && $variation->price) {
                    $price = $variation->price;
                }
            }

            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $this->product->id,
                'variation' => $this->selectedVariation,
                'quantity' => $this->quantity,
                'price' => $price,
            ]);
        }

        $this->message = 'Added to cart successfully!';
        $this->messageType = 'success';
        $this->quantity = 1;

        // Dispatch event to update cart counter
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
