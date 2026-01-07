<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function checkAuth()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return null;
    }

    public function buyNow(Request $request)
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variation' => 'nullable|string',
        ]);
        
        $product = Product::with('category')->findOrFail($request->product_id);
        $variation = $request->variation;
        
        // Check stock
        if ($product->hasVariations() && $variation) {
            $productVariation = $product->variations()->where('name', $variation)->first();
            if (!$productVariation || $productVariation->stock < $request->quantity) {
                return back()->with('error', 'Not enough stock available for this variant.');
            }
            $unitPrice = $productVariation->price ?? $product->price;
        } else {
            if ($product->stock < $request->quantity) {
                return back()->with('error', 'Not enough stock available.');
            }
            $unitPrice = $product->price;
        }
        
        // Store buy now item in session (not in cart)
        $buyNowItem = [
            'product_id' => $product->id,
            'product' => $product,
            'variation' => $variation,
            'quantity' => $request->quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $unitPrice * $request->quantity,
        ];
        
        session(['buy_now_item' => $buyNowItem]);
        
        return redirect()->route('checkout', ['buy_now' => 1]);
    }

    public function checkout(Request $request)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        // Check if this is a Buy Now checkout
        if ($request->boolean('buy_now') && session()->has('buy_now_item')) {
            $buyNowItem = session('buy_now_item');
            $cartItems = collect([(object) $buyNowItem]);
            $total = $buyNowItem['subtotal'];
            $selectedItemIds = [];
            $isBuyNow = true;
            
            return view('checkout', compact('cartItems', 'total', 'selectedItemIds', 'isBuyNow'));
        }

        $query = Cart::with('product.category')
            ->where('user_id', Auth::id());
        
        // Filter by selected items if provided
        $selectedItems = $request->input('selected_items', []);
        
        // Handle if selected_items is passed as a comma-separated string
        if (is_string($selectedItems) && !empty($selectedItems)) {
            $selectedItems = explode(',', $selectedItems);
        }
        
        if (!empty($selectedItems) && is_array($selectedItems)) {
            $query->whereIn('id', $selectedItems);
        }

        
        $cartItems = $query->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Please select items to checkout.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });
        
        // Pass selected item IDs to the view for form submission
        $selectedItemIds = $cartItems->pluck('id')->toArray();
        $isBuyNow = false;

        return view('checkout', compact('cartItems', 'total', 'selectedItemIds', 'isBuyNow'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $rules = [
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'delivery_method' => 'required|in:pickup,delivery',
            'payment_method' => 'required|in:pay_at_store,online_banking',
        ];

        // Require delivery address for delivery method
        if ($request->delivery_method === 'delivery') {
            $rules['delivery_address'] = 'required|string|max:500';
        }

        // Require payment slip for online banking
        if ($request->payment_method === 'online_banking') {
            $rules['payment_slip'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        $request->validate($rules);

        // Check if this is a Buy Now order
        $isBuyNow = $request->boolean('buy_now') && session()->has('buy_now_item');
        
        if ($isBuyNow) {
            $buyNowItem = session('buy_now_item');
            $product = Product::findOrFail($buyNowItem['product_id']);
            
            // Create a virtual cart item object
            $cartItems = collect([(object) [
                'id' => null,
                'product_id' => $product->id,
                'product' => $product,
                'variation' => $buyNowItem['variation'],
                'quantity' => $buyNowItem['quantity'],
                'subtotal' => $buyNowItem['subtotal'],
            ]]);
        } else {
            $query = Cart::with('product')
                ->where('user_id', Auth::id());
            
            // Only process selected items
            $selectedItems = $request->input('selected_items', []);
            if (!empty($selectedItems)) {
                $query->whereIn('id', $selectedItems);
            }
            
            $cartItems = $query->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'No items selected for checkout.');
            }
        }

        // Verify stock availability
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', "Not enough stock for {$item->product->name}.");
            }
        }

        DB::beginTransaction();

        try {
            $subtotal = $cartItems->sum(function ($item) {
                return $item->subtotal;
            });

            // Add delivery fee for delivery orders
            $deliveryFee = $request->delivery_method === 'delivery' ? 3.00 : 0;
            $total = $subtotal + $deliveryFee;

            // Enforce online banking for delivery orders
            if ($request->delivery_method === 'delivery' && $request->payment_method !== 'online_banking') {
                return back()->with('error', 'Delivery orders require online banking payment.');
            }

            // Handle payment slip upload for online banking
            $paymentSlipPath = null;
            $paymentStatus = 'pending';
            
            if ($request->payment_method === 'online_banking' && $request->hasFile('payment_slip')) {
                $paymentSlipPath = $request->file('payment_slip')->store('payment_slips', 'public');
                $paymentStatus = 'uploaded';
            }

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'delivery_method' => $request->delivery_method,
                'delivery_address' => $request->delivery_method === 'delivery' ? $request->delivery_address : null,
                'payment_method' => $request->payment_method,
                'payment_slip' => $paymentSlipPath,
                'payment_status' => $paymentStatus,
                'status' => 'pending',
                'total' => $total,
            ]);

            // Create order items and update stock
            foreach ($cartItems as $item) {
                // Calculate unit price (use variation price if available)
                $unitPrice = $item->product->price;
                if ($item->variation) {
                    $productVariation = $item->product->variations()->where('name', $item->variation)->first();
                    if ($productVariation && $productVariation->price) {
                        $unitPrice = $productVariation->price;
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variation' => $item->variation,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                ]);

                // Reduce stock - either from variation or product
                if ($item->variation) {
                    $productVariation = $item->product->variations()->where('name', $item->variation)->first();
                    if ($productVariation) {
                        $productVariation->decrement('stock', $item->quantity);
                    }
                } else {
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            // Clear items - session for buy now, cart for regular orders
            if ($isBuyNow) {
                session()->forget('buy_now_item');
            } else {
                $cartItemIds = $cartItems->pluck('id')->filter()->toArray();
                if (!empty($cartItemIds)) {
                    Cart::whereIn('id', $cartItemIds)->delete();
                }
            }

            // Create notification for customer
            $notificationMessage = $request->payment_method === 'online_banking' 
                ? "Your order #{$order->id} has been placed. Payment slip uploaded and waiting for admin approval."
                : "Your order #{$order->id} has been placed. Total: RM " . number_format($total, 2);

            Notification::create([
                'user_id' => Auth::id(),
                'type' => 'order',
                'title' => 'Order Placed Successfully',
                'message' => $notificationMessage,
            ]);

            // Notify all admins about new order
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'new_order',
                    'title' => 'New Order Received',
                    'message' => "New order #{$order->id} from {$request->customer_name}. Total: RM " . number_format($total, 2),
                ]);
            }

            DB::commit();

            $successMessage = $request->payment_method === 'online_banking'
                ? 'Order placed successfully! Your payment slip is pending admin approval. Order ID: #' . $order->id
                : 'Order placed successfully! Order ID: #' . $order->id;

            return redirect()->route('orders.history')->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function history(Request $request)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $status = $request->get('status');
        
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        // Get status counts for filter badges
        $statusCounts = Order::where('user_id', Auth::id())
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('orders', compact('orders', 'status', 'statusCounts'));
    }

    public function show($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $order = Order::with('items.product.category')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('order-detail', compact('order'));
    }

    public function cancel($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Only pending orders can be cancelled
        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        // Restore stock for each item
        foreach ($order->items as $item) {
            // Restore stock - either to variation or product
            if ($item->variation) {
                $productVariation = $item->product->variations()->where('name', $item->variation)->first();
                if ($productVariation) {
                    $productVariation->increment('stock', $item->quantity);
                }
            } else {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => 'cancelled']);

        // Create notification
        Notification::create([
            'user_id' => Auth::id(),
            'type' => 'order',
            'title' => 'Order Cancelled',
            'message' => "Your order #{$order->id} has been cancelled. Stock has been restored.",
        ]);

        return redirect()->route('orders.history')->with('success', 'Order has been cancelled successfully.');
    }
}
