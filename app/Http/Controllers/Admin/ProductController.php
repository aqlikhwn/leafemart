<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdmin();
        
        $query = Product::with('category')->withCount('variations');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('active', $request->status === 'active');
        }

        // Filter by stock
        if ($request->filled('stock')) {
            switch ($request->stock) {
                case 'low':
                    $query->where('stock', '<', 10)->where('stock', '>', 0);
                    break;
                case 'out':
                    $query->where('stock', 0);
                    break;
                case 'in':
                    $query->where('stock', '>', 0);
                    break;
            }
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $this->checkAdmin();
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'featured' => 'boolean',
            'active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'description_image' => 'nullable|image|max:2048',
            'variations' => 'nullable|string',
            'variation_stock' => 'nullable|integer|min:0',
            'variation_price' => 'nullable|numeric',
        ]);

        // Handle main image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle description image
        if ($request->hasFile('description_image')) {
            $validated['description_image'] = $request->file('description_image')->store('products', 'public');
        }

        // Handle multiple additional images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
        }
        $validated['images'] = !empty($imagePaths) ? json_encode($imagePaths) : null;

        $validated['featured'] = $request->boolean('featured');
        $validated['active'] = $request->boolean('active', true);

        // Remove variation fields before creating product
        unset($validated['variations'], $validated['variation_stock'], $validated['variation_price']);

        $product = Product::create($validated);

        // Handle initial variations
        if ($request->filled('variations')) {
            $variationNames = array_map('trim', explode(',', $request->variations));
            $variationStock = $request->input('variation_stock', 10);
            $variationPrice = $request->input('variation_price', 0);

            foreach ($variationNames as $name) {
                if (!empty($name)) {
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'name' => $name,
                        'stock' => $variationStock,
                        'price_adjustment' => $variationPrice,
                        'active' => true,
                    ]);
                }
            }

            $variationCount = count(array_filter($variationNames));
            
            ActivityLog::log('created', "Created product: {$product->name}", $product);
            
            return redirect()->route('admin.products.edit', $product->id)
                ->with('success', "Product created with {$variationCount} variations! You can customize each variation below.");
        }

        ActivityLog::log('created', "Created product: {$product->name}", $product);
        
        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Product created successfully! Add variations below if needed.');
    }

    public function edit($id)
    {
        $this->checkAdmin();
        $product = Product::with('variations')->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'variation' => 'nullable|string|max:255',
            'featured' => 'boolean',
            'active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'description_image' => 'nullable|image|max:2048',
        ]);

        // Handle description image removal
        if ($request->boolean('remove_description_image')) {
            if ($product->description_image) {
                Storage::disk('public')->delete($product->description_image);
            }
            $validated['description_image'] = null;
        } elseif ($request->hasFile('description_image')) {
            // Delete old description image
            if ($product->description_image) {
                Storage::disk('public')->delete($product->description_image);
            }
            $validated['description_image'] = $request->file('description_image')->store('products', 'public');
        }

        // Handle main image removal
        if ($request->boolean('remove_image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        } elseif ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle additional images - remove specific ones and/or add new ones
        $currentImages = $product->images ? json_decode($product->images, true) : [];
        
        // Remove selected images
        if ($request->has('remove_images')) {
            $removeIndices = $request->input('remove_images', []);
            foreach ($removeIndices as $index) {
                if (isset($currentImages[$index])) {
                    Storage::disk('public')->delete($currentImages[$index]);
                    unset($currentImages[$index]);
                }
            }
            $currentImages = array_values($currentImages); // Re-index array
        }
        
        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $currentImages[] = $image->store('products', 'public');
            }
        }
        
        $validated['images'] = !empty($currentImages) ? json_encode($currentImages) : null;

        $validated['featured'] = $request->boolean('featured');
        $validated['active'] = $request->boolean('active');

        $product->update($validated);
        
        ActivityLog::log('updated', "Updated product: {$product->name}", $product);

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $this->checkAdmin();
        $product = Product::findOrFail($id);

        $productName = $product->name;
        
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        
        ActivityLog::log('deleted', "Deleted product: {$productName}");

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
