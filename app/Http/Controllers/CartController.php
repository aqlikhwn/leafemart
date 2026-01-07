<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cartItems = Cart::with('product.category')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        return view('cart', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Please login to add items to cart.'], 401);
            }
            return redirect()->route('login');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variation' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        // Use empty string instead of null for consistent unique constraint
        $variation = $request->variation ?: '';

        // Check stock based on variation or product
        if ($product->hasVariations() && $variation) {
            $productVariation = $product->variations()->where('name', $variation)->first();
            if (!$productVariation || $productVariation->stock < $request->quantity) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Not enough stock available for this variation.'], 400);
                }
                return back()->with('error', 'Not enough stock available for this variation.');
            }
            $stockToCheck = $productVariation->stock;
        } else {
            if ($product->stock < $request->quantity) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Not enough stock available.'], 400);
                }
                return back()->with('error', 'Not enough stock available.');
            }
            $stockToCheck = $product->stock;
        }

        // Check if item already in cart (same product AND same variation)
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('variation', $variation)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($stockToCheck < $newQuantity) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Not enough stock available.'], 400);
                }
                return back()->with('error', 'Not enough stock available.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'variation' => $variation,
                'quantity' => $request->quantity,
            ]);
        }

        $message = $variation ? "Added {$product->name} ({$variation}) to cart!" : 'Product added to cart!';
        
        // Get updated cart count
        $cartCount = Cart::where('user_id', Auth::id())->count();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cartCount' => $cartCount
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', $message);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($cartItem->product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Cart cleared successfully.');
    }
}

