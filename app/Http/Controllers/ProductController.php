<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function browse(Request $request)
    {
        $categories = Category::withCount(['products' => function($q) {
            $q->active()->inStock();
        }])->get();
        $categoryId = $request->get('category');

        $products = Product::with('category')
            ->active()
            ->inStock()
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(15);

        return view('browse', compact('products', 'categories', 'categoryId'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->take(4)
            ->get();

        return view('product', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $categories = Category::withCount(['products' => function($q) {
            $q->active()->inStock();
        }])->get();
        $categoryId = null; // No category filter for search results

        $products = Product::with('category')
            ->active()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->paginate(15);

        return view('browse', compact('products', 'categories', 'query', 'categoryId'));
    }
}
